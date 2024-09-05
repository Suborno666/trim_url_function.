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
		global $wpdb;

		$table_name = $wpdb->prefix . 'updated_product_creds';
		$results = $wpdb->get_results("SELECT * FROM $table_name");

		if ($results) {
			foreach ($results as $row) { ?>
			<!-- Start Column -->
			<div class="col-12 col-md-4 col-lg-3 mb-5">
				<a class="product-item" href="#">
					<img src="<?php echo $row->post_image; ?>" class="img-fluid product-thumbnail">
					<h3 class="product-title"><a href="<?php the_permalink()?>"><?php echo  $row->post_name?></a></h3>
					<strong class="product-price"><?php echo "Rs. ".$row->post_price?></strong>
				</a>
			</div> 
			<!-- End Column -->
		<?php
			}
		}
		?>
		</div>
	</div>
</div>

<?php get_footer()?>