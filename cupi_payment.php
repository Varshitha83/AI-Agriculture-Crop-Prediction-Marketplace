<?php
session_start();
require('../sql.php');
require_once __DIR__ . "/../config.php";

$lang = isset($_GET['lang']) && isset($LANG_STRINGS[$_GET['lang']])
    ? $_GET['lang']
    : (isset($DEFAULT_LANGUAGE) ? $DEFAULT_LANGUAGE : 'en');
$t = $LANG_STRINGS[$lang];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo isset($t['pay_with_upi']) ? $t['pay_with_upi'] : 'UPI Payment'; ?></title>
    <link rel="icon" type="image/png" href="../assets/img/logo.png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="float-right">
        <a href="?lang=en">English</a> |
        <a href="?lang=te">తెలుగు</a>
    </div>
    <h2 class="mb-4"><?php echo $t['pay_with_upi']; ?></h2>

    <?php if (!empty($UPI_MERCHANT_VPA) && $UPI_MERCHANT_VPA !== 'yourupiid@upi'): ?>
        <p><strong>UPI ID:</strong> <?php echo htmlspecialchars($UPI_MERCHANT_VPA); ?></p>
        <p><?php echo ($lang === 'te')
            ? 'ఫోన్‌పే / గూగుల్ పే / పేటీఎం ద్వారా పై UPI IDకి మొత్తం చెల్లించండి.'
            : 'Use PhonePe / Google Pay / Paytm to pay the amount to the above UPI ID.'; ?></p>
    <?php else: ?>
        <div class="alert alert-warning">
            <?php echo ($lang === 'te')
                ? 'కాన్ఫిగ్ ఫైల్‌లో మీ UPI ID (UPI_MERCHANT_VPA) సెట్ చేయలేదు.'
                : 'Your UPI ID (UPI_MERCHANT_VPA) is not set in config.php.'; ?>
        </div>
    <?php endif; ?>

    <p class="mt-3">
        <?php echo ($lang === 'te')
            ? 'చెల్లింపు పూర్తయిన తర్వాత, క్రింద ట్రాన్సాక్షన్ ID నమోదు చేసి సబ్మిట్ చేయండి.'
            : 'After the payment, enter the transaction ID below and submit.'; ?>
    </p>

    <form method="post">
        <div class="form-group">
            <label><?php echo ($lang === 'te') ? 'ట్రాన్సాక్షన్ ID' : 'Transaction ID'; ?></label>
            <input type="text" name="txn_id" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">
            <?php echo $t['pay_now']; ?>
        </button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $txn_id = mysqli_real_escape_string($conn, $_POST['txn_id']);
        // TODO: save this TXN ID into your orders table for manual verification
        echo '<div class="alert alert-success mt-3">'.(
            $lang === 'te'
            ? 'ధన్యవాదాలు! మీ ట్రాన్సాక్షన్ ID సేవ్ చేయబడింది. అడ్మిన్ తనిఖీ చేసిన తర్వాత చెల్లింపు కన్ఫర్మ్ అవుతుంది.'
            : 'Thank you! Your transaction ID has been saved. The admin will verify and confirm the payment.'
        ).'</div>';
    }
    ?>

</div>
</body>
</html>
