<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include './includes/header.php';
include './includes/nav.php';
require_once('../database/dbrequire.php');
require_once('../database/common_function.php');

// Select products from the database
$query = "
    SELECT 
        pd.product_detail_id,
        pd.qty,
        pd.price,
        pd.img,
        p.product_name,
        c.category_name,
        b.brand_name
    FROM product_detail pd
    JOIN product p ON pd.product_id = p.product_id
    JOIN category c ON pd.category_id = c.category_id
    JOIN brand b ON pd.brand_id = b.brand_id
    ORDER BY pd.product_detail_id DESC
";
$res = $mysql->query($query);
?>

<!-- Store Header -->
<section class="store-header py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <h1 class="store-title">Our Product Collection</h1>
        <p class="store-subtitle">Discover premium laptops, accessories, and tech gadgets curated for excellence</p>
      </div>
      <div class="col-lg-4 text-lg-end">
        <div class="store-stats">
          <div class="stat-item">
            <span class="stat-number"><?php echo $res->num_rows; ?></span>
            <span class="stat-label">Products</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div style="height: 80px;"></div>

<!-- Store Content -->
<section class="store-content pb-5">
  <div class="container">
    <!-- Product Grid -->
    <div class="row g-4">
      <?php while ($show = $res->fetch_assoc()):
        $product_name = $show['product_name'];
        $price = $show['price'];
        $img_path = $show['img'];
        $category = $show['category_name'];
        $brand = $show['brand_name'];
      ?>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
          <div class="product-card">
            <div class="product-image">
              <?php if ($img_path) { ?>
                <img src="../admin/upload/<?= htmlspecialchars($img_path) ?>" alt="<?= htmlspecialchars($product_name) ?>" class="img-fluid">
              <?php } else { ?>
                <div class="no-image">
                  <i class="fas fa-image fa-3x"></i>
                  <span>No Image</span>
                </div>
              <?php } ?>
              <div class="product-overlay">
                <button type="button" class="btn btn-primary btn-sm cart-btn"
                  data-bs-toggle="modal"
                  data-bs-target="#exampleModal"
                  data-id="<?= $show['product_detail_id'] ?>"
                  data-name="<?= htmlspecialchars($product_name) ?>"
                  data-price="<?= htmlspecialchars($price) ?>"
                  data-img="<?= htmlspecialchars($img_path) ?>">
                  <i class="fas fa-shopping-cart me-1"></i>Add to Cart
                </button>
              </div>
            </div>
            <div class="product-info">
              <div class="product-meta">
                <span class="product-category"><?= htmlspecialchars($category) ?></span>
                <span class="product-brand"><?= htmlspecialchars($brand) ?></span>
              </div>
              <h5 class="product-title"><?= htmlspecialchars($product_name) ?></h5>
              <div class="product-price">
                <?php if (is_numeric($price)) { ?>
                  <span class="price-amount">$<?= number_format($price, 2) ?></span>
                <?php } else { ?>
                  <span class="price-unavailable">Price unavailable</span>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <!-- Empty State -->
    <?php if ($res->num_rows === 0): ?>
      <div class="text-center py-5">
        <div class="empty-state">
          <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
          <h3>No Products Found</h3>
          <p class="text-muted">We're currently updating our inventory. Please check back soon!</p>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Add to Cart Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          <i class="fas fa-shopping-cart me-2"></i>Add to Cart
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody id="cart-items">
              <!-- Cart items will be inserted here by JS -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="add_item" class="btn btn-primary">
          <i class="fas fa-plus me-1"></i>Add Item
        </button>
        <a href="cart.php" id="goto_cart" class="btn btn-success" style="display:none;">
          <i class="fas fa-shopping-cart me-1"></i>Go to Cart
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap 5 Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<style>
  .store-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-bottom: 1px solid var(--border-color);
  }

  .store-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--dark-color);
    margin-bottom: 1rem;
  }

  .store-subtitle {
    font-size: 1.125rem;
    color: var(--gray-color);
    margin-bottom: 0;
  }

  .store-stats {
    display: flex;
    justify-content: flex-end;
    align-items: center;
  }

  .stat-item {
    text-align: center;
    padding: 1rem;
    background: white;
    border-radius: 1rem;
    box-shadow: var(--shadow-sm);
  }

  .stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary-color);
  }

  .stat-label {
    font-size: 0.875rem;
    color: var(--gray-color);
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .product-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    height: 100%;
    border: 1px solid var(--border-color);
  }

  .product-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
  }

  .product-image {
    position: relative;
    overflow: hidden;
    background: #f8fafc;
    aspect-ratio: 4/3;
  }

  .product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }

  .product-card:hover .product-image img {
    transform: scale(1.05);
  }

  .no-image {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--gray-color);
    background: #f1f5f9;
  }

  .no-image span {
    margin-top: 0.5rem;
    font-size: 0.875rem;
  }

  .product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .product-card:hover .product-overlay {
    opacity: 1;
  }

  .cart-btn {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
  }

  .cart-btn:hover {
    transform: scale(1.05);
  }

  .product-info {
    padding: 1.5rem;
  }

  .product-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
  }

  .product-category,
  .product-brand {
    font-size: 0.75rem;
    color: var(--gray-color);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
  }

  .product-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 1rem;
    line-height: 1.4;
    height: 3rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
  }

  .product-price {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .price-amount {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--success-color);
  }

  .price-unavailable {
    font-size: 0.875rem;
    color: var(--gray-color);
    font-style: italic;
  }

  .empty-state {
    padding: 3rem 1rem;
  }

  .empty-state i {
    color: var(--gray-color);
    margin-bottom: 1rem;
  }

  .empty-state h3 {
    color: var(--dark-color);
    margin-bottom: 0.5rem;
  }

  /* Modal Styling */
  .modal-content {
    border: none;
    border-radius: 1rem;
    box-shadow: var(--shadow-xl);
  }

  .modal-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    border-radius: 1rem 1rem 0 0;
  }

  .modal-title {
    color: white;
  }

  .btn-close {
    filter: invert(1);
  }

  .table th {
    border: none;
    font-weight: 600;
    color: var(--dark-color);
    padding: 1rem;
  }

  .table td {
    border: none;
    padding: 1rem;
    vertical-align: middle;
  }

  .modal-footer {
    border-top: 1px solid var(--border-color);
    padding: 1.5rem;
  }

  .modal-footer .btn {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    border-radius: 0.75rem;
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .store-title {
      font-size: 2rem;
    }

    .store-subtitle {
      font-size: 1rem;
    }

    .stat-item {
      padding: 0.75rem;
    }

    .stat-number {
      font-size: 1.5rem;
    }

    .product-info {
      padding: 1rem;
    }

    .product-title {
      font-size: 1rem;
      height: auto;
    }
  }
</style>

<?php
include 'includes/footer.php';
?>

<script>
  (function() {
    var cart = JSON.parse(localStorage.getItem('cart') || '[]');

    function saveCart() {
      localStorage.setItem('cart', JSON.stringify(cart));
    }

    function cart_update() {
      var cart_table = "";
      var total_cost = 0;

      for (var i = 0; i < cart.length; i++) {
        var item = cart[i];
        var price = parseFloat(item.price) || 0;
        var qty = parseInt(item.qty) || 0;
        var itemTotal = price * qty;
        total_cost += itemTotal;

        cart_table += '<tr data-id="' + item.id + '">' +
          '<td><img src="../admin/upload/' + (item.img || '') + '" alt="' + (item.name || '') + '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">&nbsp; ' + (item.name || '') + '</td>' +
          '<td>$' + price.toFixed(2) + '</td>' +
          '<td style="white-space:nowrap;">' +
          '<button class="btn btn-sm btn-outline-secondary modal-qty" data-change="-1">-</button>' +
          ' <span class="mx-2 modal-qty-val">' + qty + '</span> ' +
          '<button class="btn btn-sm btn-outline-secondary modal-qty" data-change="1">+</button>' +
          '</td>' +
          '<td class="modal-line-total">$' + itemTotal.toFixed(2) + '</td>' +
          '</tr>';
      }

      // add grand total row
      cart_table += '<tr class="table-active">' +
        '<td colspan="3" class="text-end"><strong>Grand Total:</strong></td>' +
        '<td><strong>$<span id="modal-grand-total">' + total_cost.toFixed(2) + '</span></strong></td>' +
        '</tr>';

      document.getElementById('cart-items').innerHTML = cart_table;
    }

    // Bind add-to-cart buttons
    var buttons = document.querySelectorAll('.cart-btn');
    for (var b = 0; b < buttons.length; b++) {
      buttons[b].addEventListener('click', function(e) {
        e.preventDefault();
        var name = this.getAttribute('data-name');
        var id = parseInt(this.getAttribute('data-id'));
        var price = parseFloat(this.getAttribute('data-price'));
        var img = this.getAttribute('data-img');

        var existing = null;
        for (var i = 0; i < cart.length; i++) {
          if (parseInt(cart[i].id) === id) {
            existing = cart[i];
            break;
          }
        }
        if (existing) {
          existing.qty += 1;
        } else {
          cart.push({
            id: id,
            name: name,
            price: price,
            img: img,
            qty: 1
          });
        }

        saveCart();
        cart_update();

        // Show Bootstrap 5 modal
        var modal = new bootstrap.Modal(document.getElementById('exampleModal'));
        modal.show();

        // Reset footer buttons
        document.getElementById('goto_cart').style.display = 'none';
      });
    }

    // Handle +/- inside modal (event delegation)
    document.getElementById('cart-items').addEventListener('click', function(e) {
      var btn = e.target.closest('.modal-qty');
      if (!btn) return;
      var change = parseInt(btn.getAttribute('data-change'));
      var row = btn.closest('tr');
      var id = parseInt(row.getAttribute('data-id'));

      // find item
      for (var i = 0; i < cart.length; i++) {
        if (parseInt(cart[i].id) === id) {
          cart[i].qty = (parseInt(cart[i].qty) || 0) + change;
          if (cart[i].qty <= 0) {
            cart.splice(i, 1);
          }
          break;
        }
      }
      saveCart();
      cart_update();
    });

    // Persist to server session
    document.getElementById('add_item').addEventListener('click', function(e) {
      e.preventDefault();
      if (!cart || cart.length === 0) {
        showNotification('No items in cart to save.', 'warning');
        return;
      }
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'cart_api.php', true);
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
          try {
            var res = JSON.parse(xhr.responseText || '{}');
            if (res && res.success) {
              // Update nav cart count label
              var cartBadge = document.querySelector('.cart-badge');
              if (cartBadge && typeof res.count !== 'undefined') {
                cartBadge.textContent = res.count;
              }
              // Show Go to Cart button
              document.getElementById('goto_cart').style.display = 'inline-block';

              // Show success message
              showNotification('Items added to cart successfully!', 'success');
            } else {
              showNotification((res && res.message) ? res.message : 'Failed to add items', 'danger');
            }
          } catch (err) {
            console.error(err);
            showNotification('Unexpected response', 'danger');
          }
        }
      };
      xhr.send(JSON.stringify({
        cart: cart
      }));
    });

    // Initialize modal table if items exist
    if (cart && cart.length > 0) {
      cart_update();
    }

    // Notification function
    function showNotification(message, type = 'info') {
      // Create notification element
      const notification = document.createElement('div');
      notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
      notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;';
      notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      `;

      document.body.appendChild(notification);

      // Auto remove after 5 seconds
      setTimeout(() => {
        if (notification.parentNode) {
          notification.remove();
        }
      }, 5000);
    }
  })();
</script>