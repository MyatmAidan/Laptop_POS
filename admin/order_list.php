<?php
require '../database/dbrequire.php';
require './layouts/common.php';
require '../database/common_function.php';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Current page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // 5 orders per page
$offset = ($page - 1) * $limit;

// Count total orders (for total pages)
$countQuery = "SELECT COUNT(*) AS total FROM orders";
$countRes = $mysql->query($countQuery);
$totalOrders = $countRes->fetch_assoc()['total'];
$totalPages = ceil($totalOrders / $limit);

// Your query with LIMIT and OFFSET
$query = "
   SELECT 
    o.order_id,
    u.name AS user_name,
    p.product_name,
    b.brand_name,
    o.total_amount AS total_price,
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
    ON pd.brand_id = b.brand_id
LIMIT $limit OFFSET $offset;
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
        background: black;
        color: #fff;
        padding: 10px;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
    }

    .inv-section-title {
        background: #483C32;
        color: #fff;
        padding: 6px 8px;
        margin: 15px 0 10px 0;
        font-weight: bold;
        font-size: 14px;
    }

    table td .badge {
        font-size: 11px;
        padding: 5px;
        font-weight: 600;
        color: #fff;
    }

    .info {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        width: 100%;
    }

    .info .box {
        /* width: 100%; */
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
        background: #F5DEB3;
        color: black;
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
        background: black;
        color: #fff;
        font-size: 12px;
    }

    .status-selector {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
    }

    .status-option {
        flex: 1;
        padding: 15px 20px;
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        backdrop-filter: blur(8px);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .status-option.active {
        border-color: #007bff;
        background: #007bff;
        color: #fff;
    }

    .status-option i {
        display: block;
        font-size: 18px;
        margin-bottom: 4px;
        color: #000;
    }

    .status-option span {
        font-weight: 600;
        color: #000;
        font-size: 14px;
    }

    .status-option.active i {
        color: #0d6efd;
    }

    .status-option.active span {
        color: #0d6efd;
    }

    .status-option:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .status-option.active {
        background: rgba(13, 110, 253, 0.2);
        border-color: rgba(13, 110, 253, 0.5);
        box-shadow: 0 0 15px rgba(13, 110, 253, 0.3);
    }


    .glass-panel {
        background: rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .glass-table th,
    .glass-table td {
        background: rgba(255, 255, 255, 0.10) !important;
        border: 1px solid rgba(255, 255, 255, 0.18) !important;
    }

    .pagination {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(12px);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.5);
        padding: 12px 20px;
    }

    .page-link {
        background-color: rgba(255, 255, 255, 0.15);
        color: #0e0e0e;
        border: none;
        padding: 6px 14px;
        margin: 0 6px;
        border-radius: 10px;
        font-weight: 600;
        box-shadow: 0 0 6px rgba(255, 255, 255, 0.25);
        transition: 0.3s ease-in-out;
    }

    .page-item {
        background-color: transparent;
        color: #666;
        border: none;
        padding: 6px 8px;
        margin: 0 2px;
        font-weight: 600;
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
                    <div class="glass-panel card-body">
                        <table class="table table-hover table-sm table-bordered align-middle mb-0 glass-table">
                            <thead>
                                <tr>
                                    <th class="col-1">No.</th>
                                    <th class="col-2">Customer Name</th>
                                    <th class="col-2">Product Name</th>
                                    <th class="col-1">Total</th>
                                    <th class="col-2">Status</th>
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
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-info edit-btn"
                                                    data-val="<?= $row['order_id'] ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editOrderModal">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-warning receipt-btn"
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
                        <!-- Pagination -->
                        <div class="pagination mt-3">
                            <nav>
                                <ul class="pagination justify-content-center">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($page < $totalPages): ?>
                                        <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Order Status Modal -->
    <div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <div class="modal-body p-3 text-center">
                    <label for="order_status" class="form-label my-4">
                        <h4>Order Status</h4>
                    </label>
                    <div class="status-selector">
                        <div class="status-option  <?= $status == 'pending' ? 'active' : '' ?>" data-value="pending">
                            <i class="fas fa-clock"></i>
                            <span>Pending</span>
                        </div>
                        <div class="status-option  <?= $status == 'processing' ? 'active' : '' ?>" data-value="processing">
                            <i class="fas fa-cogs"></i>
                            <span>Processing</span>
                        </div>
                        <div class="status-option  <?= $status == 'shipped' ? 'active' : '' ?>" data-value="shipped">
                            <i class="fas fa-truck"></i>
                            <span>Shipped</span>
                        </div>
                        <div class="status-option  <?= $status == 'delivered' ? 'active' : '' ?>" data-value="delivered">
                            <i class="fas fa-check-circle"></i>
                            <span>Delivered</span>
                        </div>
                        <div class="status-option  <?= $status == 'cancelled' ? 'active' : '' ?>" data-value="cancelled">
                            <i class="fas fa-times-circle"></i>
                            <span>Cancelled</span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <input type="hidden" name="order_status" id="order_status" value="<?= $status ?>" required>
                    <button type="button" class="btn btn-primary btn-sm" id="saveStatusBtn">Save</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Invoice Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">INVOICE</h5>
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body p-2">
                    <div class="receipt" id="invoice">
                        <div class="inv-header">Laptop Store Receipt</div>

                        <div class="inv-section-title">Customer Information</div>
                        <div class="info">
                            <div class="box">
                                <p>Receipt #: <strong id="inv_receipt">00</strong></p>
                                <p>Name: <strong id="inv_name"></strong> </p>
                                <p>Email:<strong id="inv_email"></strong></p>
                            </div>
                            <div class="box">
                                <p>Date: <strong id="inv_date"></strong></p>
                                <p>Delivery:<strong id="inv_adderss"></strong></p>
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
                            <tbody id="invoice-items">
                                <!-- js will fill  -->
                            </tbody>
                        </table>
                        <div class="totals" id="invoice-summary">
                            <!-- js will fill -->
                        </div>

                        <div class="inv-footer mt-5">
                            No-137, Kyun Taw street, San Chaung | (+95) 9700070009 | info@laptopstore.com
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

        printWindow.document.write(`
        <html>
            <head>
                <title>Print Invoice</title>
                <style>
                    @media print {
                        @page {
                            size: A5;
                            margin: 10mm;
                        }
                        body {
                            font-family: Arial, sans-serif;
                            background: white;
                            color: black;
                            font-size: 12pt;
                            max-width: 100%;
                            margin: 0;
                            padding: 0;
                            display: flex;
                            justify-content: center; 
                            align-items: flex-start; 
                        }
                            .header {
                                height: 120px;
                                position: relative;
                                color: black;
                                padding: 10px 0px;
                            }

                            .header h1 {
                                font-size: 18px;
                            }
                        .receipt {
                            width: 100%;
                            max-width: 500px;
                                background: #fff;
                                margin: 0 auto;
                                padding: 15px 20px;
                                box-shadow: none;
                                font-size: 13px;
                            }

    .inv-header {
        background: #000;
        color: #fff;
        padding: 10px;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
    }

    .inv-section-title {
        background: #483C32;
        color: white;
        padding: 6px 8px;
        margin: 15px 0 10px 0;
        font-weight: bold;
        font-size: 14px;
    }

    .info {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        width: 100%;
    }

    .info .box {
        /* width: 100%; */
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
        background: #F5DEB3;
        color: #000;
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
        background: #000;
        color: #fff;
        font-size: 12px;
    }
                    }           
                </style>
            </head>
            <body>${invoice.outerHTML}</body>
        </html>
    `);

        printWindow.document.close();
        printWindow.focus();

        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 300);

    }

    $(document).on('click', '.status-option', function() {
        $('.status-option').removeClass('active');
        $(this).addClass('active');
        $('#order_status').val($(this).data('value'));
    });

    let currentOrderId = null;

    $(document).on('click', '.edit-btn', function() {
        currentOrderId = $(this).data('val'); // get order_id from button
        console.log('Editing order ID:', currentOrderId);
        $('#editOrderModal').modal('show');
    });

    $('#saveStatusBtn').on('click', function() {
        const status = $('#order_status').val();

        if (!currentOrderId || !status) {
            alert('No order selected or status not chosen');
            return;
        }

        $.ajax({
            url: 'update_status.php',
            type: 'POST',
            data: {
                order_id: currentOrderId,
                status: status
            },
            success: function(res) {
                console.log(res);

                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Order status updated successfully.',
                    showConfirmButton: true
                }).then(() => {
                    $('#editOrderModal').modal('hide');
                    location.reload(); // reload after alert
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Failed to update status!',
                });
            }
        });

    });


    $(document).ready(function() {
        $('.receipt-btn').click(function() {
            const orderId = $(this).data('val');
            const invoice_summary = $('#invoice-summary');
            // Fetch invoice data using AJAX
            $.ajax({
                url: 'get_invoice.php',
                type: 'GET',
                // dataType: 'json',
                data: {
                    order_id: orderId
                },
                success: function(response) {
                    console.log(response.order_info);

                    let responseData = response.order;
                    let responseItems = response.items;

                    // format created_at to "Month Day, Year"
                    const createdAt = new Date(responseData.created_at);
                    const options = {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    };
                    const formattedDate = createdAt.toLocaleDateString('en-US', options);

                    // format order id like 005
                    const receiptNo = String(responseData.order_id).padStart(3, '0');

                    const address = responseData.shipping_address + ',' + responseData.shipping_city;

                    $('#inv_receipt').text(receiptNo);
                    $('#inv_name').text(responseData.user_name);
                    $('#inv_email').text(responseData.email);
                    $('#inv_date').text(formattedDate);
                    $('#inv_adderss').text(address);
                    $('#invoice-items').html('');
                    $('#invoice-summary').html('');

                    $('#invoice-items').html('');
                    responseItems.forEach((item, index) => {
                        $('#invoice-items').append(`
                                <tr>
                                    <td>${index+1}</td>
                                    <td>${item.product_name}</td>
                                    <td>${item.quantity}</td>
                                    <td>${item.price}</td>
                                    <td>${item.quantity * item.price}</td>
                                </tr>
                            `);
                    });
                    $('#invoice-items').append(`
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    `);

                    $('#invoice-summary').append(`
                        <p><span>Discount :</span> <span>$0.00</span></p>
                        <p><span>Subtotal :</span> <span>$${responseData.total_amount}</span></p>
                        <p><span>Tax (5%):</span> <span>$${(responseData.total_amount * 0.05).toFixed(2)}</span></p>
                        <p><span>Grand Total :</span> <span>$${(responseData.total_amount * 1.10).toFixed(2)}</span></p>
                        <p><span>Paid By :</span> <span> Credit Card</span></p>
                    `);
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + error);
                }
            });
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php require './layouts/footer.php'; ?>