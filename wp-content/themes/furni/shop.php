<?php
/**
 * Template Name: Shop
 */
?>
<?php get_header()?>
<div class="untree_co-section product-section before-footer-section">
		    <div class="container">
		      	<div class="row">

				<?php
				$arg = [
					'post_type' => 'item',
					'post_status' => 'publish',
					'posts_per_page' => -1
				];
				$query = new WP_Query($arg);
				while($query->have_posts()):
					if($query->have_posts()):
						$query->the_post();
				?>
		      		<!-- Start Column 1 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5">
						<a class="product-item" href="#">
							<img src="images/product-3.png" class="img-fluid product-thumbnail">
							<h3 class="product-title"><a href="<?php the_permalink()?>"><?php echo get_the_title($query->ID)?></a></h3>
							<p></p>

							<span class="icon-cross">
								<img src="<?php get_template_directory_uri()?>/assets/images/cross.svg" class="img-fluid">
							</span>
						</a>
					</div> 
					<!-- End Column 1 -->
				<?php
					endif;
				endwhile;
				wp_reset_postdata();
				?>

		      	</div>
		    </div>
		</div>

<?php get_footer()?>