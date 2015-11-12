<?php
/**
 * Sorting
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     99.99
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $wp_query;

if ( 1 == $wp_query->found_posts )
	return;
?>
<form class="woocommerce_ordering woocommerce-ordering" method="get">
	<select name="orderby" class="orderby">
		<?php
			$catalog_orderby = apply_filters( 'woocommerce_catalog_orderby', array(
				'menu_order' => __( 'Default sorting', 'woocommerce' ),
				'date'       => __( 'Sort by newness', 'woocommerce' ),
				'title_asc'      => __( 'Sort by title: Alphabetically', 'woocommerce' ),
				'title_desc'      => __( 'Sort by title: Reverse-Alphabetically', 'woocommerce' ),
				'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
				'price_desc' => __( 'Sort by price: high to low', 'woocommerce' )
			) );

			if ( get_option( 'woocommerce_enable_review_rating' ) == 'no' )
				unset( $catalog_orderby['rating'] );

			foreach ( $catalog_orderby as $id => $name ){
				if(isset($_GET['orderby'])) $orderbyC = $_GET['orderby'];
				else $orderbyC = 0;
				echo '<option value="' . esc_attr( $id ) . '" ' . selected( $orderbyC , $id, false ) . '>' . esc_attr( $name ) . '</option>';
				}
		?>
	</select>
	<?php
		// Keep query string vars intact
		foreach ( $_GET as $key => $val ) {
			if ( 'orderby' == $key )
				continue;
			echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
		}
	?>
</form>
