<?php
include ('csession.php');
include ('../sql.php');

ini_set('memory_limit', '-1');

if (!isset($_SESSION['customer_login_user'])) {
    header("location: ../index.php"); // Redirecting To Home Page
    exit;
}

// Handle add to cart through GET (as requested)
if (isset($_GET['action']) && $_GET['action'] === 'add' && isset($_GET['crop'], $_GET['quantity'], $_GET['price'], $_GET['tradeid'])) {
    $crop = mysqli_real_escape_string($conn, trim($_GET['crop']));
    $quantity = (float)$_GET['quantity'];
    $price = (float)$_GET['price'];
    $tradeID = intval($_GET['tradeid']);

    if ($crop !== '' && $quantity > 0 && $price > 0 && $tradeID > 0) {
        $query4 = "INSERT INTO `cart`(`cropname`, `quantity`, `price`) VALUES ('$crop','$quantity','$price')";
        mysqli_query($conn, $query4);

        if (isset($_SESSION['shopping_cart'])) {
            $item_array_id = array_column($_SESSION['shopping_cart'], 'item_id');
            if (!in_array($tradeID, $item_array_id, true)) {
                $item_array = [
                    'item_id' => $tradeID,
                    'item_name' => $crop,
                    'item_price' => $price,
                    'item_quantity' => $quantity,
                ];
                $_SESSION['shopping_cart'][] = $item_array;
            }
        } else {
            $_SESSION['shopping_cart'][0] = [
                'item_id' => $tradeID,
                'item_name' => $crop,
                'item_price' => $price,
                'item_quantity' => $quantity,
            ];
        }
    }

    header('Location: cbuy_crops.php');
    exit;
}

$query4 = "SELECT * FROM custlogin WHERE email='$user_check'";
$ses_sq4 = mysqli_query($conn, $query4);
$row4 = mysqli_fetch_assoc($ses_sq4);
$para1 = $row4['cust_id'];
$para2 = $row4['cust_name'];
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
        <span class="badge badge-danger badge-pill mb-3">Shopping</span>
      </div>
    </div>

    <div class="row row-content">
      <div class="col-md-12 mb-3">
        <div class="card text-white bg-gradient-danger mb-3">
          <div class="card-header">
            <span class="text-danger display-4"> Buy Crops </span>
          </div>

          <div class="card-body">

            <!-- ===================== Buy Crops Form ===================== -->
            <table class="table table-striped table-bordered table-responsive-md btn-table">
              <thead class="text-white text-center">
              <tr>
                <th>Crop Name</th>
                <th>Quantity (in KG)</th>
                <th>Price (in Rs)</th>
                <th>Add Item</th>
              </tr>
              </thead>

              <tbody>
              <tr>
                <form method="GET" action="cbuy_crops.php">
                  <td>
                    <div class="form-group">
                      <?php
                      // query database table for crops with quantity greater than zero
                      $sql = "SELECT crop FROM production_approx WHERE quantity > 0";
                      $result = $conn->query($sql);

                      // populate dropdown menu options with the crop names
                      echo "<select id='crop' name='crop' class='form-control text-dark'>";
                      echo "<option value=''>Select Crop</option>";
                      while ($row = $result->fetch_assoc()) {
                          $cropVal = strtolower(trim($row["crop"]));
                          echo "<option value='" . $cropVal . "'>" . ucfirst($cropVal) . "</option>";
                      }
                      echo "</select>";
                      ?>
                    </div>
                  </td>

                  <input type="hidden" name="action" value="add">
                  <input type="hidden" name="tradeid" id="tradeid" value="">

                  <td>
                    <div class="form-group">
                      <input id="quantity" type="number" placeholder="Available Quantity" max="10"
                             name="quantity" required class="form-control text-dark">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <input id="price" type="text" value="0" name="price" readonly
                             class="form-control text-dark">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <button type="button" id="addBtn" class="btn btn-success form-control">ADD TO CART</button>
                    </div>
                  </td>
                </form>
              </tr>
              </tbody>
            </table>

            <!-- ===================== Order Details ===================== -->
            <h3 class="text-white">Order Details</h3>
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-responsive-md btn-table display" id="myTable">
                <tr class="bg-dange">
                  <th width="40%">Item Name</th>
                  <th width="10%">Quantity (in KG)</th>
                  <th width="20%">Price (in Rs.)</th>
                  <th width="5%">Action</th>
                </tr>

                <?php
                if (!empty($_SESSION["shopping_cart"])) {
                    $total = 0;
                    foreach ($_SESSION["shopping_cart"] as $keys => $values) {
                        ?>
                        <tr class="bg-white">
                          <td><?php echo ucfirst($values["item_name"]); ?></td>
                          <td><?php echo $values["item_quantity"]; ?></td>
                          <td>Rs. <?php echo $values["item_price"]; ?></td>
                          <td>
                            <a href="cbuy_crops.php?action=delete&id=<?php echo $values["item_id"]; ?>"
                               type="button" class="btn btn-warning btn-block">Remove</a>
                          </td>
                        </tr>
                        <?php
                        $total = $total + ($values["item_price"]);
                    }

                    // handle delete action
                    if (isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["id"])) {
                        foreach ($_SESSION["shopping_cart"] as $keys => $values) {
                            if ($values["item_id"] == $_GET["id"]) {
                                unset($_SESSION["shopping_cart"][$keys]);
                                $b = (int)$_GET["id"];

                                $query5 = "SELECT Trade_crop FROM farmer_crops_trade WHERE trade_id = $b";
                                $result5 = mysqli_query($conn, $query5);
                                if ($result5 && $row5 = $result5->fetch_assoc()) {
                                    $a = $row5["Trade_crop"];

                                    $query6 = "DELETE FROM cart WHERE cropname = '" . mysqli_real_escape_string($conn, $a) . "'";
                                    $result6 = mysqli_query($conn, $query6);
                                }

                                echo '<script>alert("Item Removed")</script>';
                                echo '<script>window.location="cbuy_crops.php"</script>';
                                exit;
                            }
                        }
                    }

                    // --------- TOTAL ROW + PROCEED TO PAYMENT BUTTON ---------
                    $_SESSION['Total_Cart_Price'] = $total;
                    ?>
                    <tr class="text-dark">
                      <td colspan="2" align="right">Total</td>
                      <td align="right">Rs. <?php echo round($total); ?></td>
                      <td>
                        <a href="cpayment_options.php?amount=<?php echo round($total); ?></a>" class="btn btn-info form-control">
                          Proceed to Payment
                        </a>
                      </td>
                    </tr>
                    <?php
                } // end if shopping_cart not empty
                ?>
              </table>
            </div><!-- /table-responsive -->

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require("footer.php"); ?>

<style>
  #addBtn {
    background-color: #28a745 !important;
    border-color: #28a745 !important;
    color: white !important;
  }
  #addBtn:hover {
    background-color: #218838 !important;
    border-color: #1e7e34 !important;
  }
  .remove {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    color: white !important;
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const prices = {
      maize: 30,
      bajra: 25,
      arhar: 35,
      barley: 20,
      rice: 40,
      soyabean: 45,
      wheat: 28
    };

    const crop = document.getElementById("crop");
    const qty = document.getElementById("quantity");
    const price = document.getElementById("price");
    const btn = document.getElementById("addBtn");
    const form = document.querySelector("form");
    const table = document.querySelector("#myTable tbody");

    function updatePrice() {
      const selected = crop.value.trim().toLowerCase();
      const quantity = parseFloat(qty.value) || 0;
      const total = (prices[selected] || 0) * quantity;
      price.value = total.toFixed(2);
    }


    if (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
      });
    }

    crop.addEventListener("change", updatePrice);
    qty.addEventListener("input", updatePrice);

    if (btn) {
      btn.addEventListener("click", function (e) {
        e.preventDefault();

        const selected = crop.value.trim();
        const quantity = qty.value.trim();
        const itemTotal = price.value.trim();

        if (!selected || !quantity || parseFloat(quantity) <= 0) {
          alert("Enter quantity");
          return;
        }

        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${selected}</td>
          <td>${quantity}</td>
          <td>Rs. ${itemTotal}</td>
          <td><button type="button" class="btn btn-danger remove">REMOVE</button></td>
        `;

        table.appendChild(row);

// ✅ Show temporary total + button (UI only)
let total = 0;
document.querySelectorAll("#myTable tbody tr").forEach(r => {
    let priceCell = r.children[2];

    if (priceCell) {
        let txt = priceCell.innerText.replace("Rs.", "").trim();
        let val = parseFloat(txt);

        if (!isNaN(val)) {
            total += val;
        }
    }
});

// Remove old total row if exists
let old = document.getElementById("totalRow");
if (old) old.remove();

// Add new total row
let newRow = document.createElement("tr");
newRow.id = "totalRow";

newRow.innerHTML = `
  <td colspan="2"><b>Total</b></td>
  <td><b>Rs. ${Math.round(total)}</b></td>
  <td>
    <a href="cpayment_options.php?amount=${Math.round(total)}" class="btn btn-info form-control">
      Proceed to Payment
    </a>
  </td>
`;

document.querySelector("#myTable tbody").appendChild(newRow);
      });
    }

    document.addEventListener("click", function (e) {
      if (e.target && e.target.classList.contains("remove")) {
        const targetRow = e.target.closest("tr");
        if (targetRow) {
          targetRow.remove();
          // Recalculate UI total
let total = 0;

document.querySelectorAll("#myTable tbody tr").forEach(r => {
  let txt = r.children[2]?.innerText || "";
  let val = parseFloat(txt.replace(/[^\d.]/g, "")) || 0;
  total += val;
});

let old = document.getElementById("totalRow");
if (old) old.remove();

if (total > 0) {
  let newRow = document.createElement("tr");
  newRow.id = "totalRow";

  newRow.innerHTML = `
    <td colspan="2"><b>Total</b></td>
    <td><b>Rs. ${Math.round(total)}</b></td>
    <td>
      <a href="cpayment_options.php?amount=${Math.round(total)}" class="btn btn-info form-control">
        Proceed to Payment
      </a>
    </td>
  `;

  document.querySelector("#myTable tbody").appendChild(newRow);
}
        }
      }
    });

    updatePrice();
  });
</script>

</body>
</html>
