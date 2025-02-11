<footer class="htlfndr-footer">
    <button class="htlfndr-button-to-top" role="button"><span>Back to top</span></button><!-- Button "To top" -->

    <div class="widget-wrapper">
        <div class="container">
            <div class="row">
                <aside class="col-xs-12 col-sm-6 col-md-3 htlfndr-widget-column">
                    <div class="widget">
                        <a class="htlfndr-logo navbar-brand" href="<?php echo esc_url(home_url()); ?>">
                            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/footer-logo.png" alt="Logo" />
                        </a>
                        <hr style="margin-top:100px;">
                        <p>Yes Quote It’s mission is to make a market place where customers and providers connect directly making it cheaper and better for everyone.</p>
                        <br />
                        <p>Our platform helps Customers save money by receiving quotes directly from Providers (who often provide unadvertised or below market rates).</p>
                        <br />
                        <p>Providers using our site save on 3rd party booking fees, and can deal directly with customers to create customised experiences.</p>
                    </div><!-- .widget -->
                </aside>

                <aside class="col-xs-12 col-sm-6 col-md-3 htlfndr-widget-column">
                    <div class="widget">
                        <h3 class="widget-title">Contact Info</h3>
                        <h5>Address</h5>
                        <p>Yes Quote It<br>120 CA 15th Avenue-Suite 214, USA</p>
                        <hr />
                        <h5>Phone Number</h5>
                        <p>1-555-5555-5555</p>
                        <hr />
                        <h5>Email Address</h5>
                        <p><a href="mailto:info@yesquoteit.com">info@yesquoteit.com</a></p>
                    </div><!-- .widget -->
                </aside>

                <aside class="col-xs-12 col-sm-6 col-md-3 htlfndr-widget-column">
                    <div class="widget">
                        <h3 class="widget-title">Pages</h3>
                        <?php 
                                wp_nav_menu(array(
                                    'theme_location' => 'footer-menu',  // Use the correct theme location
                                    'menu_class'     => 'menu',     // Bootstrap's navbar nav class
                                    'container'      => false,            // Don't wrap in a container div
                                    'depth'           => 1,                // Only 1 level deep (top-level items)
                                    'fallback_cb'     => false,           // Prevent fallback to wp_page_menu
                                ));
                                ?>
                    </div><!-- .widget -->
                </aside>

                <aside class="col-xs-12 col-sm-6 col-md-3 htlfndr-widget-column">
                    <div class="widget">
                        <h3 class="widget-title">Follow Us</h3>
                        <div class="htlfndr-follow-plugin">
                            <a href="https://www.facebook.com/yesquoteit" class="htlfndr-follow-button button-facebook"></a>
                            <a href="http://x.com/yesquoteit" class="htlfndr-follow-button button-twitter"></a>
                            <a href="https://www.instagram.com/yesquoteit" class="htlfndr-follow-button button-instagram">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        </div><!-- .htlfndr-follow-plugin -->
                        <hr />
                        <h3 class="widget-title">Mailing List</h3>
                        <p>Sign up for our mailing list to get the latest updates and offers</p>
                        <form>
                            <input type="email" placeholder="Your E-mail" />
                            <input type="submit" />
                        </form>
                        <hr/>
                        <h3 class="widget-title">contact info</h3>
                        <h5>address</h5>
                        <p>Yes Quoteit  <br>P.O Box 2, Jindabyne, NSW 2627</p>
                        <hr>
                        <h5>email address</h5>
                        <p> info@yesquoteit.com</p>
                    </div><!-- .widget -->
                </aside>
            </div><!-- .row -->
        </div><!-- .container -->
    </div><!-- .widget-wrapper -->

    <div class="htlfndr-copyright text-center">
        <div class="container" role="contentinfo">
            <p>© Copyright 2025 <a href="https://yesquoteit.com/">YES Quote It Pty Ltd</a></p>
        </div><!-- .container -->
    </div>
</footer>

<!-- Add Sign Up & Sign In Forms -->
<div id="htlfndr-sing-up">
    <div class="htlfndr-content-card">
        <div class="htlfndr-card-title clearfix">
            <h2 class="pull-left">Sign up</h2>
        </div>
        <form id="htlfndr-sing-up-form" method="post">
            <div class="row">
                <div class="col-md-6">
                    <h4>First Name</h4>
                    <input type="text" class="htlfndr-input" name="first_name" required>
                </div>
                <div class="col-md-6">
                    <h4>Last Name</h4>
                    <input type="text" class="htlfndr-input" name="last_name" required>
                </div>
            </div>
            <h4>Email Address</h4>
            <input type="email" class="htlfndr-input" name="email" required>
            <h4>Password</h4>
            <input type="password" class="htlfndr-input" name="password" required>
            <h4>Confirm Password</h4>
            <input type="password" class="htlfndr-input" name="confirm_password" required>
            <div class="clearfix">
                <span>Have an account? <a href="#">Sign in</a></span>
                <input type="submit" value="Sign Up" class="pull-right">
            </div>
        </form>
    </div>
</div>

<!-- Sign In -->
<div id="htlfndr-sing-in">
    <div class="htlfndr-content-card">
        <div class="htlfndr-card-title clearfix">
            <h2 class="pull-left">Sign in</h2>
        </div>
        <form id="htlfndr-sing-in-form" method="post">
            <h4>Email Address</h4>
            <input type="email" class="htlfndr-input" name="email" required>
            <h4>Password</h4>
            <input type="password" class="htlfndr-input" name="password" required>
            <div class="clearfix">
                <span>Don't have an account? <a href="#">Sign up</a></span>
                <input type="submit" value="Sign In" class="pull-right">
            </div>
        </form>
    </div>
</div>

<!-- jQuery and Scripts -->
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/jquery-1.11.3.min.js"></script>
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/bootstrap.min.js"></script>
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/owl.carousel.min.js"></script>
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/script.js"></script>

<?php wp_footer(); ?>
</body>
</html>
