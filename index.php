<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AquaManager - Smart Water Refilling Station Management</title>
    <link rel="stylesheet" href="frontend/assets/css/landing.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="landing-nav">
        <div class="container">
            <a href="#" class="nav-logo">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" fill="currentColor"/>
                    <path d="M12 6v6l4 2" stroke="white" stroke-width="2" stroke-linecap="round"/>
                </svg>
                AquaManager
            </a>
            <ul class="nav-menu">
                <li><a href="#features">Features</a></li>
                <li><a href="#how-it-works">How It Works</a></li>
                <li><a href="#testimonials">Testimonials</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="login.php" class="btn btn-primary">Get Started</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1>Water Refilling Station Management System</h1>
                <p>Streamline operations, increase efficiency, and grow your water refilling business with our comprehensive management system.</p>
                <div class="hero-buttons">
                    <a href="login.php" class="btn btn-primary">Get Started →</a>
                    <a href="#how-it-works" class="btn btn-secondary">Book a Demo</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="frontend/assets/images/hero-image.png" alt="Water Refilling Station">
                <div class="hero-badge">Trusted by 500+ businesses</div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Powerful Features to Grow Your Business</h2>
            </div>
            <p class="section-subtitle">Our comprehensive suite of tools helps you manage every aspect of your water refilling station efficiently.</p>
            
            <div class="features-grid">
                <!-- Real-time Analytics -->
                <div class="feature-card">
                    <svg class="feature-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 3v18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M7 14l4-4 4 4 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h3>Real-time Analytics</h3>
                    <p>Monitor sales, inventory, and customer data in real-time with intuitive dashboards.</p>
                </div>

                <!-- Inventory Management -->
                <div class="feature-card">
                    <svg class="feature-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="5" y="4" width="6" height="7" stroke="currentColor" stroke-width="2"/>
                        <rect x="13" y="4" width="6" height="7" stroke="currentColor" stroke-width="2"/>
                        <rect x="5" y="13" width="6" height="7" stroke="currentColor" stroke-width="2"/>
                        <rect x="13" y="13" width="6" height="7" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <h3>Inventory Management</h3>
                    <p>Track water levels, bottles, and supplies with automated alerts when stocks run low.</p>
                </div>

                <!-- Delivery Tracking -->
                <div class="feature-card">
                    <svg class="feature-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 6h15l3 6v5h-3m-3 0H7m-3 0H1V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="6" cy="17" r="2" stroke="currentColor" stroke-width="2"/>
                        <circle cx="16" cy="17" r="2" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <h3>Delivery Tracking</h3>
                    <p>Manage delivery routes, track orders, and optimize your distribution network.</p>
                </div>

                <!-- Customer Management -->
                <div class="feature-card">
                    <svg class="feature-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <h3>Customer Management</h3>
                    <p>Build customer profiles, track purchase history, and implement loyalty programs.</p>
                </div>

                <!-- Payment Processing -->
                <div class="feature-card">
                    <svg class="feature-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" y="5" width="20" height="14" rx="2" stroke="currentColor" stroke-width="2"/>
                        <path d="M2 10h20" stroke="currentColor" stroke-width="2"/>
                        <path d="M6 15h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <h3>Payment Processing</h3>
                    <p>Accept multiple payment methods and automate billing for subscription customers.</p>
                </div>

                <!-- Maintenance Alerts -->
                <div class="feature-card">
                    <svg class="feature-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M13.73 21a2 2 0 01-3.46 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <h3>Maintenance Alerts</h3>
                    <p>Get notifications for scheduled maintenance and filter replacements.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works-section" id="how-it-works">
        <div class="container">
            <div class="section-title">
                <h2>How It Works</h2>
            </div>
            <p class="section-subtitle">Getting started with AquaManager is simple. Follow these steps to transform your water refilling station business.</p>
            
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">01</div>
                    <h3>Sign Up & Setup</h3>
                    <p>Create your account and configure your business profile with station details and product offerings.</p>
                    <div class="step-arrow">→</div>
                </div>

                <div class="step-card">
                    <div class="step-number">02</div>
                    <h3>Import Data</h3>
                    <p>Import your existing customer database, inventory, and historical sales data or start fresh.</p>
                    <div class="step-arrow">→</div>
                </div>

                <div class="step-card">
                    <div class="step-number">03</div>
                    <h3>Daily Operations</h3>
                    <p>Track sales, manage inventory, process orders, and monitor deliveries all from one dashboard.</p>
                    <div class="step-arrow">→</div>
                </div>

                <div class="step-card">
                    <div class="step-number">04</div>
                    <h3>Analyze & Grow</h3>
                    <p>Use insights from detailed reports to optimize operations and expand your business.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section" id="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>What Our Customers Say</h2>
            </div>
            <p class="section-subtitle">Join hundreds of water refilling station owners who have transformed their businesses with AquaManager.</p>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="stars">★★★★★</div>
                    <p class="testimonial-text">"Since implementing AquaManager, we've increased our daily output by 30% and reduced delivery delays by 45%. The customer management features have helped us retain more clients."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">MS</div>
                        <div class="author-info">
                            <h4>Maria Santos</h4>
                            <p>Owner, Aqua Pure Refilling</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="stars">★★★★★</div>
                    <p class="testimonial-text">"The analytics dashboard gives me insights I never had before. I can now make data-driven decisions about inventory, staffing, and marketing that have dramatically improved our profitability."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">JR</div>
                        <div class="author-info">
                            <h4>James Rivera</h4>
                            <p>Manager, H2O Express</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="stars">★★★★★</div>
                    <p class="testimonial-text">"Managing our three locations used to be a nightmare before AquaManager. Now I can oversee all operations from one interface and have cut administrative work by 80%."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">ER</div>
                        <div class="author-info">
                            <h4>Elena Reyes</h4>
                            <p>CEO, Crystal Waters Co.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing-section" id="pricing">
        <div class="container">
            <div class="section-title">
                <h2>Simple, Transparent Pricing</h2>
            </div>
            <p class="section-subtitle">Choose the plan that fits your business needs. All plans include our core features.</p>
            
            <div class="pricing-grid">
                <!-- Starter Plan -->
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3>Starter</h3>
                        <p>Perfect for small stations just getting started</p>
                    </div>
                    <div class="pricing-price">
                        <span class="price">$49</span>
                        <span class="price-period">/month</span>
                    </div>
                    <ul class="pricing-features">
                        <li>Single station management</li>
                        <li>Basic inventory tracking</li>
                        <li>Customer database</li>
                        <li>Sales reporting</li>
                        <li>Email support</li>
                    </ul>
                    <a href="login.php" class="btn btn-outline">Get Started</a>
                </div>

                <!-- Professional Plan (Featured) -->
                <div class="pricing-card featured">
                    <div class="pricing-header">
                        <h3>Professional</h3>
                        <p>Ideal for growing businesses with advanced needs</p>
                    </div>
                    <div class="pricing-price">
                        <span class="price">$99</span>
                        <span class="price-period">/month</span>
                    </div>
                    <ul class="pricing-features">
                        <li>Up to 3 stations</li>
                        <li>Advanced inventory management</li>
                        <li>Customer loyalty program</li>
                        <li>Delivery route optimization</li>
                        <li>Online payment processing</li>
                        <li>24/7 priority support</li>
                    </ul>
                    <a href="login.php" class="btn btn-primary">Get Started</a>
                </div>

                <!-- Enterprise Plan -->
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3>Enterprise</h3>
                        <p>For large operations with multiple locations</p>
                    </div>
                    <div class="pricing-price">
                        <span class="price">$199</span>
                        <span class="price-period">/month</span>
                    </div>
                    <ul class="pricing-features">
                        <li>Unlimited stations</li>
                        <li>Full enterprise features</li>
                        <li>Custom reporting</li>
                        <li>API access</li>
                        <li>Dedicated account manager</li>
                        <li>White-label options</li>
                    </ul>
                    <a href="login.php" class="btn btn-outline">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer CTA -->
    <section class="footer-cta">
        <div class="container">
            <h2>Ready to Transform Your Water Refilling Business?</h2>
            <p>Join over 500 water refilling stations that have increased efficiency, reduced costs, and grown their customer base with AquaManager.</p>
            <div class="hero-buttons">
                <a href="login.php" class="btn btn-outline">Start Free Trial</a>
                <a href="#" class="btn btn-outline">Schedule Demo</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h3>
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="currentColor"/>
                            <path d="M12 6v6l4 2" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        AquaManager
                    </h3>
                    <p>Comprehensive management solution for water refilling stations. Streamline operations and grow your business.</p>
                    <div class="social-links">
                        <a href="#"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                        <a href="#"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a>
                        <a href="#"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/></svg></a>
                        <a href="#"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg></a>
                    </div>
                </div>

                <div class="footer-links">
                    <h4>Product</h4>
                    <ul>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                        <li><a href="#">Integrations</a></li>
                        <li><a href="#">Updates</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Tutorials</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Support Center</a></li>
                    </ul>
                </div>

                <div class="footer-contact">
                    <h4>Contact</h4>
                    <p>📧 contact@aquamanager.com</p>
                    <p>📞 +1 (555) 123-4567</p>
                    <p>📍 123 Water Street, Suite 401, New York, NY 10001</p>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© 2025 AquaManager. All rights reserved.</p>
                <div class="footer-bottom-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
