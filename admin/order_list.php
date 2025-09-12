<?php
require '../database/dbrequire.php';
require './layouts/common.php';
require '../database/common_function.php';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// all-in-one query
$query = "
   SELECT 
    o.order_id,
    u.name AS user_name,
    p.product_name,
    b.brand_name,
    (oi.quantity * oi.price) AS total_price,
    o.shipping_address,
    o.shipping_city,
    o.shipping_state,
    o.shipping_zip,
    o.shipping_country,
    o.status
FROM orders o
JOIN user u 
    ON o.user_id = u.id
JOIN order_items oi 
    ON o.order_id = oi.order_id
JOIN product_detail pd 
    ON oi.product_detail_id = pd.product_detail_id
JOIN product p 
    ON pd.product_id = p.product_id
JOIN brand b 
    ON pd.brand_id = b.brand_id;
";
$res = $mysql->query($query);

require './layouts/header.php';
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #f5f5f5;
    }

    .receipt {
        max-width: 100%;
        width: 100%;
        background: #fff;
        margin: 0 auto;
        padding: 15px 20px;
        box-shadow: none;
        font-size: 13px;
    }

    .inv-header {
        background: #f15b5b;
        color: #fff;
        padding: 10px;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
    }

    .inv-section-title {
        background: #f15b5b;
        color: #fff;
        padding: 6px 8px;
        margin: 15px 0 10px 0;
        font-weight: bold;
        font-size: 14px;
    }

    .info {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .info .box {
        width: 48%;
        margin-bottom: 10px;
    }

    .info p {
        margin: 3px 0;
    }

    .info strong {
        display: inline-block;
        width: 100px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 13px;
    }

    table thead {
        background: #1f77d0;
        color: #fff;
    }

    table th,
    table td {
        border: 1px solid #ddd;
        padding: 6px;
        text-align: center;
    }

    table tbody tr:nth-child(even) {
        background: #f9f9f9;
    }

    .totals {
        margin-top: 15px;
        float: right;
        width: 240px;
        font-size: 13px;
    }

    .totals p {
        display: flex;
        justify-content: space-between;
        margin: 3px 0;
    }

    .inv-footer {
        clear: both;
        text-align: center;
        margin-top: 25px;
        padding: 10px;
        background: #f15b5b;
        color: #fff;
        font-size: 12px;
    }
</style>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>Order List</h1>
            <div class="">
                <a href="javascript:history.back()" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 offset-md-8 col-sm-6 offset-sm-6">
                <?php if ($success !== '') { ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php } ?>
                <?php if ($error !== '') { ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php } ?>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th class="col-1">No.</th>
                                    <th class="col-2">Customer Name</th>
                                    <th class="col-2">Product Name</th>
                                    <th class="col-1">Brand</th>
                                    <th class="col-1">Total</th>
                                    <th class="col-1">Status</th>
                                    <th class="col-2">Address</th>
                                    <th class="col-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $row['order_id'] ?></td>
                                            <td><?= $row['user_name'] ?></td>
                                            <td><?= $row['product_name'] ?></td>
                                            <td><?= $row['brand_name'] ?></td>
                                            <td><?= $row['total_price'] ?></td>
                                            <td>
                                                <?php if ($row['status'] === 'pending'): ?>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                <?php elseif ($row['status'] === 'delivered'): ?>
                                                    <span class="badge bg-success">Delivered</span>
                                                <?php elseif ($row['status'] === 'processing'): ?>
                                                    <span class="badge bg-info text-dark">Processing</span>
                                                <?php elseif ($row['status'] === 'shipped'): ?>
                                                    <span class="badge bg-primary">Shipped</span>
                                                <?php elseif ($row['status'] === 'cancelled'): ?>
                                                    <span class="badge bg-danger">Cancelled</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?= htmlspecialchars($row['status']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $row['shipping_zip'] . ', ' . $row['shipping_address'] . ', ' . $row['shipping_state'] . ', ' . $row['shipping_city'] . ', ' . $row['shipping_country'] ?></td>
                                            <td>
                                                <a href="<?= $admin_base_url . 'product_detail_edit.php?id=' . $row['product_detail_id'] ?>" class="btn btn-sm btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <button type="button" class="btn btn-sm btn-info receipt-btn"
                                                    data-val="<?= $row['order_id'] ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#exampleModalCenter">
                                                    <i class="fa-regular fa-file-lines"></i>
                                                </button>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">INVOICE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-2">
                    <div class="receipt">
                        <div class="inv-header">Laptop Store Receipt</div>

                        <div class="section-title">Customer Information</div>
                        <div class="info">
                            <div class="box">
                                <p><strong>Name:</strong> John Doe</p>
                                <p><strong>Email:</strong> johndoe@email.com</p>
                                <p><strong>Phone:</strong> (123) 123-4567</p>
                            </div>
                            <div class="box">
                                <p><strong>Receipt #:</strong> 002</p>
                                <p><strong>Date:</strong> April 10, 2019</p>
                                <p><strong>Delivery:</strong> 3383 Public Works Drive, Chattanooga, TN</p>
                            </div>
                        </div>

                        <div class="inv-section-title">Order Details</div>
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Unit</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Product A</td>
                                    <td>10</td>
                                    <td>$50</td>
                                    <td>$500</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Product B</td>
                                    <td>10</td>
                                    <td>$30</td>
                                    <td>$300</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Product C</td>
                                    <td>20</td>
                                    <td>$20</td>
                                    <td>$400</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Product D</td>
                                    <td>15</td>
                                    <td>$30</td>
                                    <td>$450</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="totals">
                            <p><strong>Subtotal:</strong> <span>$1,650</span></p>
                            <p><strong>Delivery:</strong> <span>$50</span></p>
                            <p><strong>Total:</strong> <span>$1,700</span></p>
                        </div>
                        <br>
                        <div class="inv-footer">
                            2142 Cuffman Alley, Bowling Green, KY | (123)123-4567 | info@abcdbox.com
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="printInvoice()">Print</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printInvoice() {
        var content = document.querySelector('.receipt').innerHTML;
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Invoice</title></head><body>');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>

<?php require './layouts/footer.php'; ?>