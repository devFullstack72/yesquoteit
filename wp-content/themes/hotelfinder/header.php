<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php wp_title( '|', true, 'right' ); ?> <?php echo get_bloginfo('name') ?></title>

    <?php wp_head(); ?> <!-- WordPress Hook -->

</head>
<body>
    <!-- Overlay preloader-->
    <div class="htlfndr-loader-overlay"></div>

    <div class="htlfndr-wrapper">
        <header>
            <div class="htlfndr-top-header">
                <div class="navbar navbar-default htlfndr-blue-hover-nav">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <!-- Brand and toggle get grouped for better mobile display -->
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle collapsed hide" data-toggle="collapse" data-target="#htlfndr-first-nav">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                    <a class="htlfndr-logo navbar-brand" href="<?php echo home_url(); ?>"> <!-- Home link -->
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/header-logo.png" alt="Logo" style="margin-top:5px;" />
                                        <!-- <p class="htlfndr-logo-text">Yes<span>QuoteIt</span></p> -->
                                    </a>
                                </div><!-- .navbar-header -->
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 text-right">
                                <?php do_shortcode('[partner_loggedin_info]'); ?>
                            </div>
                        </div>
                    </div><!-- .container -->
                </div><!-- .navbar.navbar-default.htlfndr-blue-hover-nav-->
            </div><!-- .htlfndr-top-header -->

            <!-- Main Navigation -->
            <div class="htlfndr-under-header">
                    <nav class="navbar navbar-default">
                        <div class="container">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#htlfndr-main-nav">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div><!-- .navbar-header -->
                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <div class="collapse navbar-collapse" id="htlfndr-main-nav">
                                <?php 
                                wp_nav_menu(array(
                                    'theme_location' => 'primary-menu',  // Use the correct theme location
                                    'menu_class'     => 'nav navbar-nav',     // Bootstrap's navbar nav class
                                    'container'      => false,            // Don't wrap in a container div
                                    'depth'           => 1,                // Only 1 level deep (top-level items)
                                    'fallback_cb'     => false,           // Prevent fallback to wp_page_menu
                                ));
                                ?>
                            </div>
                        </div><!-- .container -->
                    </nav><!-- .navbar.navbar-default.htlfndr-blue-hover-nav -->
                </div><!-- .htlfndr-under-header -->
    <noscript><h2>You have JavaScript disabled!</h2></noscript>
        </header>

    <!-- End of slider section -->