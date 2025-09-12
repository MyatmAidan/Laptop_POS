<?php
session_start();
require_once '../auth.php';
require_once('../database/dbrequire.php');
require_once('../database/common_function.php');
include './includes/header.php';
include 'includes/nav.php';


// Require login
if (!isset($_SESSION['SESS_USER_ID']) && !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Require cart
$cart = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart)) {
    header("Location: cart.php");
    exit;
}


$product_sql = "
    SELECT 
        pd.product_detail_id,
        pd.product_id,
        pd.qty,
        pd.price,
        pd.img,
        p.product_name AS product_name,
        b.brand_name
    FROM product_detail pd
    INNER JOIN product p ON pd.product_id = p.product_id
    INNER JOIN brand b ON pd.brand_id = b.brand_id
    WHERE pd.product_detail_id IN (" . implode(',', array_map('intval', array_column($cart, 'id'))) . ")
";


$product_res = $mysql->query($product_sql);
$row = $product_res->fetch_assoc();
// var_dump($row);
// die;


// Calculate subtotal
$subtotal = 0.0;
foreach ($cart as $it) {
    $subtotal += ((float)$it['price']) * ((int)$it['qty']);
}
$grandTotal = $subtotal;

?>

<div class="chk-wrapper">
    <!-- Left: Shipping form -->
    <div class="chk-form">
        <h4>Shipping Information</h4>
        <form id="checkoutForm" action="checkout_process.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="shipping_address" class="form-control" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="shipping_city" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">State</label>
                    <input type="text" name="shipping_state" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Zip Code</label>
                    <input type="text" name="shipping_zip" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Country</label>
                    <input type="text" name="shipping_country" class="form-control" required>
                </div>
            </div>

            <br>
            <!-- Proceed to Order -->
            <button type="button" id="proceedBtn" class="btn btn-primary my-3 w-100">
                Proceed to Order
            </button>

        </form>

    </div>


    <!-- Right: Order summary -->
    <div class="chk-summary">
        <h4>Order Summary</h4>

        <?php
        $subtotal = 0;

        if ($product_res && $product_res->num_rows > 0) {
            // Store DB products in array keyed by id
            $products = [];
            while ($row = $product_res->fetch_assoc()) {
                $products[$row['product_detail_id']] = $row;
            }

            // Render each cart item
            foreach ($cart as $item) {
                $pid = (int)$item['id'];
                $pname = $item['name'];
                $qty = (int)$item['qty'];
                $pimg = $item['img'];
                $price = (float)$item['price']; // use session price
                $lineTotal = $price * $qty;
                $subtotal += $lineTotal;

                // $pname = isset($products[$pid]['product_name']) ? $products[$pid]['product_name'] : 'Unknown Product';
        ?>
                <?php if (!empty($pimg)) { ?>
                    <div class="chk-item">
                        <img src="../admin/<?= htmlspecialchars($pimg) ?>"
                            style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px; margin:auto;"
                            alt="<?= htmlspecialchars($pname) ?>">
                    </div>
                <?php } ?>

                <div class="chk-item">
                    <span><?= htmlspecialchars($pname) ?> (x<?= $qty ?>)</span>
                    <span>$<?= number_format($lineTotal, 2) ?></span>
                </div>
        <?php
            }
        }
        ?>

        <div class="chk-item">
            <span>Subtotal</span>
            <span>$<?= number_format($subtotal, 2) ?></span>
        </div>
        <div class="chk-item">
            <span>Shipping</span>
            <span>—</span>
        </div>
        <div class="chk-total d-flex justify-content-between">
            <span>Total</span>
            <span>$<?= number_format($subtotal, 2) ?></span>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Continue Payments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- Add id to the payment form -->
                    <div class="card shadow-sm border rounded p-4">
                        <h5 class="mb-3"><i class="fas fa-credit-card"></i> Payment Information</h5>

                        <!-- Cardholder Name -->
                        <div class="form-group mb-3">
                            <label class="fw-bold">Cardholder Name</label>
                            <input type="text" id="card_name"
                                class="form-control"
                                placeholder="<?= htmlspecialchars($_SESSION['SESS_USERNAME'] ?? 'John Doe') ?>"
                                required>
                        </div>

                        <!-- Card Number with Visa Icon -->
                        <div class="form-group mb-3 position-relative">
                            <label class="fw-bold">Card Number</label>
                            <input type="text" id="card_number"
                                class="form-control"
                                placeholder="4111 1111 1111 1111"
                                maxlength="19" required>
                            <div class="position-absolute" style="top: 30px; right: 15px;">
                                <i class="fab fa-cc-visa fa-2x text-primary"></i>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Expiry -->
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Expiry Date</label>
                                <input type="text" id="expiry"
                                    class="form-control"
                                    placeholder="MM/YY" maxlength="5" required>
                            </div>
                            <!-- CVV -->
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">CVV</label>
                                <input type="password" id="cvv"
                                    class="form-control"
                                    placeholder="123" maxlength="4" required>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="confirmOrderBtn">Yes, Proceed</button>
                </div>


            </div>
        </div>
    </div>

    <script>
        document.getElementById("proceedBtn").addEventListener("click", function() {
            let form = document.getElementById("checkoutForm");

            // Validate shipping form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Show payment modal
            let orderModal = new bootstrap.Modal(document.getElementById("orderModal"));
            orderModal.show();
        });

        document.getElementById("confirmOrderBtn").addEventListener("click", function() {
            let shippingForm = document.getElementById("checkoutForm");

            // validate shipping first
            if (!shippingForm.checkValidity()) {
                shippingForm.reportValidity();
                return;
            }

            // validate payment fields manually
            let cardName = document.getElementById("card_name").value.trim();
            let cardNumber = document.getElementById("card_number").value.trim();
            let expiry = document.getElementById("expiry").value.trim();
            let cvv = document.getElementById("cvv").value.trim();

            if (!cardName || !cardNumber || !expiry || !cvv) {
                alert("Please fill in all payment details.");
                return;
            }

            // add payment fields as hidden inputs into checkoutForm
            const paymentData = {
                card_name: cardName,
                card_number: cardNumber,
                expiry: expiry,
                cvv: cvv
            };

            for (let key in paymentData) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = key;
                hidden.value = paymentData[key];
                shippingForm.appendChild(hidden);
            }

            // now submit shippingForm with payment data
            shippingForm.submit();
        });
    </script>



</div>