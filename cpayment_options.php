<?php
include('csession.php');
include('../sql.php');

ini_set('memory_limit', '-1');

// make sure user is logged in
if (!isset($_SESSION['customer_login_user'])) {
    header("location: ../index.php");
    exit;
}

// fetch customer details so $para2 is available for cnav.php
$query4 = "SELECT * FROM custlogin WHERE email='$user_check'";
$ses_sq4 = mysqli_query($conn, $query4);
$row4 = mysqli_fetch_assoc($ses_sq4);
$para1 = $row4['cust_id'];
$para2 = $row4['cust_name'];

// amount from query string or cart
$amount = isset($_GET['amount']) ? (float)$_GET['amount'] : (isset($_SESSION['Total_Cart_Price']) ? (float)$_SESSION['Total_Cart_Price'] : 0);
if ($amount <= 0) {
    echo "<script>alert('Invalid payment amount'); window.location='cbuy_crops.php';</script>";
    exit;
}

$stripe_error = "";

// ---------- CARD PAYMENT (STRIPE) BRANCH ----------
if (isset($_GET['pay_mode']) && $_GET['pay_mode'] === 'card') {
    require_once "StripePayment/config.php";

    $TotalCartPrice = $amount * 100; // in paise

    try {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'inr',
                    'unit_amount' => $TotalCartPrice,
                    'product_data' => [
                        'name' => 'Crops Payment',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://localhost/agriculture-portal-main/agriculture-portal-main/customer/cupdatedb.php?mode=card',
            'cancel_url'  => 'http://localhost/agriculture-portal-main/agriculture-portal-main/customer/cpayment_options.php',
        ]);

        // redirect straight to Stripe Checkout
        header("Location: " . $session->url);
        exit;
    } catch (\Stripe\Exception\ApiErrorException $e) {
        $stripe_error = $e->getMessage();
    }
}

// ---------- UPI INTENT LINK ----------
$merchant_vpa   = "9391260913@ibl"; // TODO: change to your real UPI ID
$merchant_name  = "Agriculture Portal";
$upi_note       = "Crops Payment";

$upi_url = "upi://pay?pa=" . urlencode($merchant_vpa)
    . "&pn=" . urlencode($merchant_name)
    . "&am=" . urlencode($amount)
    . "&cu=INR"
    . "&tn=" . urlencode($upi_note);
?>
<!DOCTYPE html>
<html>
<?php include('cheader.php'); ?>

<body class="bg-white" id="top">
<?php include('cnav.php'); ?>

<section class="section section-shaped section-lg">
  <div class="shape shape-style-1 shape-primary">
    <span></span><span></span><span></span><span></span><span></span>
    <span></span><span></span><span></span><span></span><span></span>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-md-8 mx-auto text-center">
        <span class="badge badge-danger badge-pill mb-3">PAYMENT</span>
      </div>
    </div>

    <div class="row row-content">
      <div class="col-md-8 mx-auto">
        <div class="card shadow">
          <div class="card-header bg-gradient-danger text-white text-center">
            <h3>Crops Payment</h3>
            <h2 class="mt-2">₹<?php echo number_format($amount, 2); ?></h2>
          </div>

          <div class="card-body">

            <?php if (!empty($stripe_error)): ?>
              <div class="alert alert-danger">
                Stripe error: <?php echo htmlspecialchars($stripe_error); ?>
              </div>
            <?php endif; ?>

            <!-- CARD / STRIPE SECTION -->
            <div class="mb-4">
              <h5 class="mb-2">Credit / Debit / ATM Card (Stripe)</h5>
              <p class="text-muted small mb-2">
                Pay securely using your card. You will be redirected to Stripe’s card payment page.
              </p>
              <a href="cpayment_options.php?pay_mode=card&amount=<?php echo $amount; ?>"
                 class="btn btn-outline-dark btn-block">
                PAY WITH CARD (POWERED BY STRIPE)
              </a>
            </div>

            <!-- PAY BY ANY UPI APP -->
            <div class="mb-4">
              <h5 class="mb-2">Pay by any UPI App</h5>
              <div class="d-flex flex-wrap mb-2">
                <button type="button" class="btn btn-outline-secondary mr-2 mb-2 upi-app"
                        data-app="PhonePe">PHONEPE</button>
                <button type="button" class="btn btn-outline-secondary mr-2 mb-2 upi-app"
                        data-app="GPay">GPAY</button>
                <button type="button" class="btn btn-outline-secondary mr-2 mb-2 upi-app"
                        data-app="Paytm">PAYTM</button>
              </div>

              <label>Enter UPI ID manually</label>
              <input type="text" class="form-control mb-2" id="payer_upi"
                     placeholder="yourname@upi">

              <small class="text-muted">
                Pay to merchant UPI ID:
                <strong><?php echo htmlspecialchars($merchant_vpa); ?></strong>
              </small>
            </div>

            <!-- CONFIRM AFTER PAYMENT -->
            <form method="POST" action="cupdatedb.php">
              <input type="hidden" name="amount" value="<?php echo htmlspecialchars($amount); ?>">
              <input type="hidden" name="payment_method" value="UPI">

              <label>Transaction / UTR Number</label>
              <input type="text" name="utr" class="form-control" required>

              <button type="submit" class="btn btn-success btn-block">
                I HAVE PAID, PLACE ORDER
              </button>
            </form>

            <p class="mt-3 text-muted small">
              UPI payment in this project is for demonstration only. The system
              does not verify the transaction with the bank; details are saved
              when you click “I have paid, place order”.
            </p>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require('footer.php'); ?>

<script>
  // UPI link from PHP
  const upiPayUrl = "<?php echo $upi_url; ?>";

  // highlight chosen UPI app and try to open UPI link
  document.querySelectorAll('.upi-app').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('selected_app').value = btn.dataset.app;

      document.querySelectorAll('.upi-app').forEach(b => b.classList.remove('btn-primary'));
      btn.classList.add('btn-primary');

      // attempt to open UPI payment link (works mainly on mobile)
      window.location.href = upiPayUrl;
    });
  });

  // copy entered UPI ID into hidden field for form
  document.getElementById('payer_upi').addEventListener('input', function () {
    document.getElementById('form_payer_upi').value = this.value;
  });

  // explicit button to open UPI app
  document.getElementById('open_upi_link').addEventListener('click', () => {
    window.location.href = upiPayUrl;
  });
</script>

</body>
</html>
