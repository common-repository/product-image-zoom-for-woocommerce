<?php
/**
 * Remove default supports
 */
remove_theme_support( 'wc-product-gallery-zoom' );
remove_theme_support( 'wc-product-gallery-lightbox' );
remove_theme_support( 'wc-product-gallery-slider' );

/**
 * Disable default lightbox trigger
 */
function pizfwc_single_product_image_thumbnail_html_modify( $html, $post_thumbnail_id ){
	$html = str_replace('<a', '<span', $html);
	$html = str_replace('</a', '</span', $html);
	$html = str_replace('href', 'data-href', $html);

	return $html;
}
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'pizfwc_single_product_image_thumbnail_html_modify', 15, 2);

/**
 * Modify gallery html
 */
remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

function pizfwc_show_product_thumbnails(){
	global $woocommerce, $product;
	$attachments = $product->get_gallery_image_ids();
	?>
	<ol class="flex-control-thumbs pizfwc-control-thumbs">
		<?php foreach($attachments as $attachment_id):
			$thumbnail_src = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
			$large_src = wp_get_attachment_image_src( $attachment_id, 'full' );
		?>
			<li><img src="<?php echo $thumbnail_src[0]; ?>" data-large-image="<?php echo $large_src[0]; ?>" class=""></li>
		<?php endforeach; ?>
	</ol>
	<?php
}
add_action( 'woocommerce_product_thumbnails', 'pizfwc_show_product_thumbnails', 20 );


/**
 * Modify single product main image attr parameters
 * to fix, back to main image when variation cleared
 */
function pizfwc_gallery_image_html_attachment_image_params_modify($image_attr_arr, $attachment_id){
	$main_image_src = wp_get_attachment_image_src( $attachment_id, 'full' )[0];
	$image_attr_arr['data-main_image'] = $main_image_src;

	return $image_attr_arr;
}
add_filter( 'woocommerce_gallery_image_html_attachment_image_params', 'pizfwc_gallery_image_html_attachment_image_params_modify', 10, 4 );