<?php
session_start();
include 'includes/header.php';
include 'includes/nav.php';
$orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
?>
<div class="container" style="padding:30px 0;">
    <div class="alert alert-success">
        <strong>Success!</strong> Your order #<?= $orderId ?> has been placed.
    </div>
    <p>
        <a class="btn btn-primary" href="store.php">Continue Shopping</a>
        <a class="btn btn-default" href="order.php">View My Orders</a>
    </p>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (!empty($_SESSION['ERR_MSGS'])): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Checkout Failed',
            html: '<?= implode("<br>", array_map("htmlspecialchars", $_SESSION['ERR_MSGS'])) ?>',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Try Again'
        });
    </script>
<?php unset($_SESSION['ERR_MSGS']);
endif; ?>

<?php if (!empty($_SESSION['MSGS'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            html: '<?= implode("<br>", array_map("htmlspecialchars", $_SESSION['MSGS'])) ?>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>
<?php unset($_SESSION['MSGS']);
endif; ?>