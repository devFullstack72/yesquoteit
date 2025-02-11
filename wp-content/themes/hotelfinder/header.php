<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php wp_title( '|', true, 'right' ); ?> Yes Quote It</title>

    <!-- Bootstrap -->
    <link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Main styles -->
    <link href="<?php echo get_template_directory_uri(); ?>/css/style.css" rel="stylesheet" />
    <!-- IE styles -->
    <link href="<?php echo get_template_directory_uri(); ?>/css/ie.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.min.css" rel="stylesheet" />
    <!-- OWL Carousel -->
    <link href="<?php echo get_template_directory_uri(); ?>/css/owl.carousel.css" rel="stylesheet" />
    <!-- Jquery UI -->
    <link href="<?php echo get_template_directory_uri(); ?>/css/jquery-ui.css" rel="stylesheet" />

    <?php wp_head(); ?> <!-- WordPress Hook -->

    <style type="text/css">
    /* Apply underline effect to the current active menu item */
        .current-menu-item>a:after {
            background: #23def7;  /* The color for the underline */
            content: '';  /* Creates the underline */
        }

        .htlfndr-top-header .htlfndr-logo.navbar-brand>img {
            top: 9px;
        }

    </style>
</head>
<body>
    <!-- Overlay preloader-->
    <div class="htlfndr-loader-overlay"></div>

    <div class="htlfndr-wrapper">
        <header>
            <div class="htlfndr-top-header">
                <div class="navbar navbar-default htlfndr-blue-hover-nav">
                    <div class="container">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed hide" data-toggle="collapse" data-target="#htlfndr-first-nav">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="htlfndr-logo navbar-brand" href="<?php echo home_url(); ?>"> <!-- Home link -->
                                <img src="<?php echo get_template_directory_uri(); ?>/images/header-logo.jpg" alt="Logo" />
                                <!-- <p class="htlfndr-logo-text">Yes<span>QuoteIt</span></p> -->
                            </a>
                        </div><!-- .navbar-header -->
                      
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