<!-- Footer -->
<footer class="footer-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="footer-widget">
                    <div class="footer-logo mb-3">
                        <i class="fas fa-laptop-code fa-2x text-primary me-2"></i>
                        <span class="footer-brand">Laptop Store</span>
                    </div>
                    <p class="footer-description">
                        Your trusted destination for premium laptops, accessories, and cutting-edge technology.
                        We're committed to providing quality products and exceptional service.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <div class="footer-widget">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="store.php">Store</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="about.php">About Us</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h5 class="footer-title">Categories</h5>
                    <ul class="footer-links">
                        <li><a href="store.php?category=1">Mac Laptops</a></li>
                        <li><a href="store.php?category=11">Windows Laptops</a></li>
                        <li><a href="store.php?category=7">Gaming Laptops</a></li>
                        <li><a href="store.php?category=1">Accessories</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h5 class="footer-title">Contact Info</h5>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Tech Street, Digital City, DC 12345</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+1 (555) 123-4567</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>info@laptopstore.com</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-clock"></i>
                            <span>Mon - Fri: 9:00 AM - 6:00 PM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="footer-divider">

        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="copyright">
                    <p>&copy; <?php echo date('Y'); ?> Laptop Store. All rights reserved.</p>
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="footer-bottom-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Cookie Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer-section {
        background: linear-gradient(135deg, var(--dark-color) 0%, #111827 100%);
        color: white;
        padding: 4rem 0 2rem;
        margin-top: 4rem;
        width: 100%;
        height: auto;
    }

    .footer-widget {
        margin-bottom: 2rem;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .footer-brand {
        color: white;
    }

    .footer-description {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .social-links {
        display: flex;
        gap: 1rem;
    }

    .social-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 50%;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .social-link:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-3px);
    }

    .footer-title {
        color: white;
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .footer-title::after {
        content: '';
        position: absolute;
        bottom: -0.5rem;
        left: 0;
        width: 30px;
        height: 2px;
        background: var(--primary-color);
        border-radius: 1px;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 0.75rem;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .footer-links a:hover {
        color: var(--primary-color);
        transform: translateX(5px);
    }

    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        color: rgba(255, 255, 255, 0.8);
    }

    .contact-item i {
        color: var(--primary-color);
        margin-top: 0.25rem;
        flex-shrink: 0;
    }

    .footer-divider {
        border-color: rgba(255, 255, 255, 0.1);
        margin: 2rem 0 1rem;
    }

    .copyright {
        color: rgba(255, 255, 255, 0.6);
    }

    .footer-bottom-links {
        display: flex;
        gap: 1.5rem;
        justify-content: flex-end;
    }

    .footer-bottom-links a {
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        font-size: 0.875rem;
        transition: color 0.3s ease;
    }

    .footer-bottom-links a:hover {
        color: var(--primary-color);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .footer-section {
            padding: 3rem 0 1.5rem;
            margin-top: 2rem;
        }

        .footer-bottom-links {
            justify-content: flex-start;
            margin-top: 1rem;
        }

        .social-links {
            justify-content: center;
        }
    }
</style>