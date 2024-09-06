<!-- /*
* Bootstrap 5
* Template Name: Furni
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->

<?php
	global $current_user;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Untree.co">
  <link rel="shortcut icon" href="favicon.png">

  <meta name="description" content="" />
  <meta name="keywords" content="bootstrap, bootstrap4" />

		<!-- Bootstrap CSS -->
		<!-- <link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
		<link href="css/tiny-slider.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet"> -->
		
		
		<?php wp_head();?>
		<title><?php the_title();?> Free Bootstrap 5 Template for Furniture and Interior Design Websites by Untree.co </title>
	</head>

	<body>
		<!-- Start Header/Navigation -->
		<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

			<div class="container">
				<a class="navbar-brand" href="index.html"><?php echo get_bloginfo();?><span>.</span></a>

				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarsFurni">
					<ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
						<li class="nav-item" <?php (is_front_page())?'active':''?>>
							<a class="nav-link" href="http://localhost/furni/">Home</a>
						</li>
						<li class="nav-item" <?php (is_page(15))?'active':''?>>
							 <a class="nav-link" href="http://localhost/furni/shops/">Shop</a></li>
						<li><a class="nav-link" href="http://localhost/furni/user_login/">Login</a></li>
						<li><a class="nav-link" href="about.html">About us</a></li>
						<li><a class="nav-link" href="services.html">Services</a></li>
						<li><a class="nav-link" href="blog.html">Blog</a></li>
						<li><a class="nav-link" href="contact.html">Contact us</a></li>
					</ul>
					<div class="nav-item dropdown">
						<a href="#" class="nav-link dropdown-toggle" style="color:white" data-bs-toggle="dropdown">
							<i class="fa fa-user fa-2x" style="color:white"></i>
						</a>

						<div class="dropdown-menu m-0 bg-light rounded-0" >
							<?php
							// $current_user = null;
							if(!is_user_logged_in()){
								$current_user = wp_get_current_user();
							?>
								<a href="<?php echo get_the_permalink(253);?>" class="dropdown-item">Register</a>
							<?php } else {?>
								<a class="dropdown-item"><?php echo $current_user->display_name;?></a>
								<a href="<?php echo get_the_permalink(255);?>" class="dropdown-item"><?php echo "Open Chatroom"?></a>
								<a href="<?php echo get_the_permalink(31);?>" class="dropdown-item">Update User</a>
							<?php }?>
							<?php
							if ( is_user_logged_in() ) {
							?>
								<a href="<?php echo wp_logout_url( home_url()); ?>" class="dropdown-item">Logout</a>
							<?php } else {?>
								<a href="<?php echo get_the_permalink(183);?>" class="dropdown-item">Login</a>
							<?php } ?>
						</div>
					</div>

					<ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
						<li><a class="nav-link" href="#"><img src="images/user.svg"></a></li>
						<li><a class="nav-link" href="cart.html"><img src="images/cart.svg"></a></li>
					</ul>
				</div>
			</div>
				
		</nav>
		<!-- End Header/Navigation -->

		<?php if(is_front_page()):?>
			<!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1>Modern Interior <span clsas="d-block">Design Studio</span></h1>
								<p class="mb-4">Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique.</p>
								<p><a href="" class="btn btn-secondary me-2">Shop Now</a><a href="#" class="btn btn-white-outline">Explore</a></p>
							</div>
						</div>
						<div class="col-lg-7">
							<div class="hero-img-wrap">
								<img src="images/couch.png" class="img-fluid">
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Hero Section -->
		<?php else:?>
			<!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1><?php the_title()?></h1>
							</div>
						</div>
						<div class="col-lg-7">
							
						</div>
					</div>
				</div>
			</div>
			<!-- End Hero Section -->
		<?php endif?>