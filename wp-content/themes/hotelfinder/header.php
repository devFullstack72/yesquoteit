<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Home Page - Hotel Finder</title>

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

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
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
								<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#htlfndr-first-nav">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="htlfndr-logo navbar-brand" href="index.html">
									<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
									<p class="htlfndr-logo-text">hotel <span>finder</span></p>
								</a>
							</div><!-- .navbar-header -->
							<!-- Collect the nav links, forms, and other content for toggling -->
							<div class="collapse navbar-collapse navbar-right" id="htlfndr-first-nav">
								<!-- List with sing up/sing in links -->
								<ul class="nav navbar-nav htlfndr-singup-block">
									<li id="htlfndr-singup-link">
								<a href="#" data-toggle="modal" data-target="#htlfndr-sing-up"><span>sing up</span></a>
							</li>
							<li id="htlfndr-singin-link">
								<a href="#" data-toggle="modal" data-target="#htlfndr-sing-in"><span>sing in</span></a>
							</li>
								</ul><!-- .htlfndr-singup-block -->
								<!-- List with currency and language dropdons -->
								<ul class="nav navbar-nav htlfndr-custom-select htlfndr-currency">
									<li>
										<label for="htlfndr-currency" class="sr-only">Change currency</label>
										<select name="htlfndr-currency" id="htlfndr-currency" tabindex="-1">
											<option value="eur">eur</option>
											<option value="gbp">gbp</option>
											<option value="jpy">jpy</option>
											<option value="usd" selected="selected">usd</option>
										</select>
									</li>
								</ul>
								<ul class="nav navbar-nav htlfndr-custom-select htlfndr-language">
									<li id="htlfndr-dropdown-language">
										<label for="htlfndr-language" class="sr-only">Change language</label>
										<select name="htlfndr-language" id="htlfndr-language" tabindex="-1">
											<option value="eng" selected="selected">eng</option>
											<option value="ger">ger</option>
											<option value="jap">jap</option>
											<option value="ita">ita</option>
											<option value="fre">fre</option>
											<option value="rus" >rus</option>
										</select>
									</li>	
								</ul><!-- .htlfndr-top-menu-dropdowns -->
							</div><!-- .collapse.navbar-collapse -->
						</div><!-- .container -->
					</div><!-- .navbar.navbar-default.htlfndr-blue-hover-nav-->
				</div><!-- .htlfndr-top-header -->
				<!-- Start of main navigation -->
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
								<ul class="nav navbar-nav">
									<li class="active">
										<a href="index.html">home</a>
									</li>
									<li><a href="elements.html">elements</a></li>
									<li>
										<a href="blog-index.html">blog</a>
									</li>
									<li>
										<a href="about-us.html">about</a>
									</li>
									<li>
										<a href="user-page.html">user profile</a>
									</li>
									<li  class="dropdown">
										<a href="#" onclick="return false;">Pages</a>
										<ul class="dropdown-menu">
											<li  class="dropdown-submenu"><a href="search-result.html">Search result</a>
												<ul class="dropdown-menu">
													<li><a href="search-result.html">Search result 1</a>
													<li><a href="search-result-v-2.html">Search result 2</a></li>
													<li><a href="search-result-v-3.html">Search result 3</a></li>
												</ul>
											</li>
											<li  class="dropdown-submenu"><a href="hotel-page-v1.html">Hotel page</a>
												<ul class="dropdown-menu">
													<li><a href="hotel-page-v1.html">Hotel page 1</a>
													<li><a href="hotel-page-v2.html">Hotel page 1 Special offer</a></li>
													<li><a href="hotel-page-v3.html">Hotel page 2</a></li>
													<li><a href="hotel-page-v4.html">Hotel page 2 Special offer</a></li>
												</ul>
											</li>	
											<li class="dropdown-submenu"><a href="hotel-room-page.html">Hotel Room Page</a>
												<ul class="dropdown-menu">
													<li><a href="hotel-room-page.html">Hotel Room Page</a>
													<li><a href="hotel-room-page-special-offer.html">Hotel Room Page Special offer</a></li>
												</ul>
											</li>
											<li><a href="payment.html">Payment</a></li>
											<li><a href="search-rooms.html">Search Rooms</a></li>
											<li><a href="contact-us.html">Contact Us</a></li>
											<li><a href="thanks-page.html">Thanks Page</a></li>											
											<li><a href="404-page.html">404 Page</a></li>
										</ul>
									</li>
								</ul>
							</div><!-- .collapse.navbar-collapse -->
						</div><!-- .container -->
					</nav><!-- .navbar.navbar-default.htlfndr-blue-hover-nav -->
				</div><!-- .htlfndr-under-header -->
				<!-- End of main navigation -->
				
				
				<!-- End of slider section -->
				<noscript><h2>You have JavaScript disabled!</h2></noscript>
		</header>
