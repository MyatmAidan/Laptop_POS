<?php
session_start();
require_once '../auth.php';
include './includes/header.php';
include 'includes/nav.php';
?>

<div id="main">
    <header class="container">
        <h3 class="page-header">Cart</h3>
    </header>
    <div class="container">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="cart-body">
                <?php if (!empty($_SESSION['cart'])): ?>
                    <?php
                    $no = 1;
                    $grand_total = 0;
                    foreach ($_SESSION['cart'] as $item):
                        $item_total = $item['price'] * $item['qty'];
                        $grand_total += $item_total;
                    ?>
                        <tr data-id="<?= (int)$item['id'] ?>">
                            <td><?= $no++; ?></td>
                            <td>
                                <img src="../admin/<?= htmlspecialchars($item['img']) ?>"
                                    style="width:50px; height:50px; object-fit:cover; border-radius:5px;">
                                <?= htmlspecialchars($item['name']) ?>
                            </td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td style="white-space: nowrap;">
                                <button class="btn btn-xs btn-default qty-btn" data-change="-1">-</button>
                                <span class="mx-2 qty-val"><?= (int)$item['qty'] ?></span>
                                <button class="btn btn-xs btn-default qty-btn" data-change="1">+</button>
                            </td>
                            <td>$<span class="line-total"><?= number_format($item_total, 2) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Total</strong></td>
                        <td><strong>$<span id="grand-total"><?= number_format($grand_total, 2) ?></span></strong></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No items in cart</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-between">
            <div class="text-end mt-3">
                <a href="store.php" class="btn btn-primary">Continue Shopping</a>
            </div>
            <div class="text-end mt-3">
                <a href="checkout.php" class="btn btn-primary">Check Out</a>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        function post(url, data, cb) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    cb(xhr.status, xhr.responseText);
                }
            };
            var body = Object.keys(data).map(function(k) {
                return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]);
            }).join('&');
            xhr.send(body);
        }

        var table = document.getElementById('cart-body');
        if (!table) return;

        table.addEventListener('click', function(e) {
            var btn = e.target.closest('.qty-btn');
            if (!btn) return;
            var row = btn.closest('tr');
            var id = parseInt(row.getAttribute('data-id'));
            var change = parseInt(btn.getAttribute('data-change'));
            post('cart_update.php', {
                id: id,
                change: change
            }, function(status, resp) {
                try {
                    var res = JSON.parse(resp || '{}');
                    if (res && res.success) {
                        if (res.qty <= 0) {
                            row.parentNode.removeChild(row);
                        } else {
                            row.querySelector('.qty-val').textContent = res.qty;
                            row.querySelector('.line-total').textContent = parseFloat(res.line_total).toFixed(2);
                        }
                        var gt = document.getElementById('grand-total');
                        if (gt) gt.textContent = parseFloat(res.grand_total).toFixed(2);
                        var labels = document.querySelectorAll('.navbar-nav .label.label-info');
                        if (labels && labels.length > 0) {
                            labels[labels.length - 1].textContent = res.count;
                        }
                        if (!table.querySelector('tr[data-id]')) {
                            table.innerHTML = '<tr><td colspan="5" class="text-center">No items in cart</td></tr>';
                            if (gt) gt.textContent = '0.00';
                        }
                    }
                } catch (err) {
                    console.error(err);
                }
            });
        });
    })();
</script>

<?php include 'includes/footer.php';
?>

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