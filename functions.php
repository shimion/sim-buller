<?php

add_action( 'after_setup_theme', 'pmc_buler_theme_setup' );

function pmc_buler_theme_setup() {
	add_theme_support( 'post-formats', array( 'link', 'gallery', 'video' , 'audio') );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' ); // this enable thumbnails and stuffs
	add_image_size( 'gallery', 95,95, true );
	add_image_size( 'port2',230,150, true );
	add_image_size( 'advertise', 235,130, true );
	add_image_size( 'homeProduct', 280 ,240, true );
	add_image_size( 'homePort', 380 ,340, true );
	add_image_size( 'shop', 280 ,240, true );
	add_image_size( 'shop-sidebar', 272 ,240, true );
	add_image_size( 'widget', 255,140, true );
	add_image_size( 'postBlock', 375,170, true );
	add_image_size( 'productBig', 720,600, true );
	add_image_size( 'productSmall', 132,113, true );
	add_image_size( 'blog', 800, 390, true );
	add_image_size( 'port3', 380,340, true );
	add_image_size( 'port4', 260,160, true );
	add_image_size( 'related', 180,90, true );
	add_image_size( 'homepost', 1180,490, true );
	add_theme_support('woocommerce'); // so they don't advertise their themes :)
	
    register_nav_menus(array(

        'main-menu' => 'Main Menu',

        'footer-menu' => 'Footer Menu',
		
		'top_menu' => 'Top Menu',	

		'resp_menu' => 'Responsive Menu',		
		
		'scroll_menu' => 'Scroll Menu'		

    ));
	
	class Walker_Responsive_Menu extends Walker_Nav_Menu {
		function start_lvl(&$output, $depth){
		  $indent = str_repeat("\t", $depth); // don't output children opening tag (`<ul>`)
		}

		function end_lvl(&$output, $depth){
		  $indent = str_repeat("\t", $depth); // don't output children closing tag
		}
		
		function start_el(&$output, $item, $depth, $args) {
			global $wp_query;		
			$item_output = $attributes = $prepend ='';
			// Create a visual indent in the list if we have a child item.
			$visual_indent = ( $depth ) ? str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $depth) : '';

			// Load the item URL
			$attributes .= ! empty( $item->url ) ? ' value="'   . esc_attr( $item->url ) .'"' : '';

			// If we have hierarchy for the item, add the indent, if not, leave it out.
			// Loop through and output each menu item as this.
			if($depth != 0) {
				$item_output .= '<option ' . $attributes .'>'. $visual_indent . $item->title. '</option>';
			} else {
				$item_output .= '<option ' . $attributes .'>'.$prepend.$item->title.'</option>';
			}


			// Make the output happen.
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}


	// Main walker menu	
	class description_walker extends Walker_Nav_Menu
	{
		  function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			   global $wp_query;
			   $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			   $class_names = $value = '';
			   $classes = empty( $item->classes ) ? array() : (array) $item->classes;
			   $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
			   $class_names = ' class="'. esc_attr( $class_names ) . '"';
			   $output .= $indent . '<li id="menu-item-'.rand(0,9999).'-'. $item->ID . '"' . $value . $class_names .'>';
			   $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
			   $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
			   $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
			   $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
			   $prepend = '<strong>';
			   $append = '</strong>';
			   if($depth != 0)
			   {
					$append = $prepend = "";
			   }
				$item_output = $args->before;
				$item_output .= '<a'. $attributes .'>';
				$item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
				$item_output .= $args->link_after;
				$item_output .= '</a>';	
				$item_output .= $args->after;
				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}
		
	
}


/*-----------------------------------------------------------------------------------*/
// Options Framework
/*-----------------------------------------------------------------------------------*/


// Paths to admin functions
define('MY_TEXTDOMAIN', 'wp-buler');
load_theme_textdomain( 'wp-buler', get_template_directory() . '/languages' );
load_theme_textdomain( 'woocommerce', get_template_directory() . '/languages' );
define('ADMIN_PATH', get_stylesheet_directory() . '/admin/');
define('BOX_PATH', get_stylesheet_directory() . '/includes/boxes/');
define('ADMIN_DIR', get_template_directory_uri() . '/admin/');
define('LAYOUT_PATH', ADMIN_PATH . '/layouts/');

// You can mess with these 2 if you wish.
$themedata = wp_get_theme(get_stylesheet_directory() . '/style.css');
define('THEMENAME', $themedata['Name']);
define('OPTIONS', 'of_options_buler_pmc'); // Name of the database row where your options are stored

if (is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
	//Call action that sets
	add_action('admin_head','pmc_options');
}

/* import theme options */
function pmc_options()	{


		
	if (!get_option('of_options_buler_pmc')){
	
		$pmc_data = 'YToxMTc6e3M6MTQ6InNob3dyZXNwb25zaXZlIjtzOjE6IjEiO3M6OToicG9ydF9zbHVnIjtzOjk6InBvcnRmb2xpbyI7czoxODoiaG9tZV9yZWNlbnRfbnVtYmVyIjtzOjI6IjEyIjtzOjI2OiJob21lX3JlY2VudF9udW1iZXJfZGlzcGxheSI7czoxNjoiU2VsZWN0IGEgbnVtYmVyOiI7czozMToiaG9tZV9yZWNlbnRfbnVtYmVyX2Rpc3BsYXlfcG9zdCI7czoxOiI2IjtzOjIzOiJob21lX3JlY2VudF9udW1iZXJfcG9zdCI7czoxOiI2IjtzOjEwOiJjYXR3b290eXBlIjtzOjE6IjEiO3M6MTE6ImFkZF90b19jYXJ0IjtzOjE6IjEiO3M6MjE6InJlY2VudF9zdGF0dXNfcHJvZHVjdCI7czoxOiIxIjtzOjI3OiJob21lX3JlY2VudF9wcm9kdWN0c19udW1iZXIiO3M6MjoiMTIiO3M6MzQ6ImhvbWVfcmVjZW50X251bWJlcl9kaXNwbGF5X3Byb2R1Y3QiO3M6MToiNCI7czoyMjoicmVjZW50X3N0YXR1c19wcm9kdWN0RiI7czoxOiIxIjtzOjI4OiJob21lX3JlY2VudF9wcm9kdWN0c0ZfbnVtYmVyIjtzOjI6IjEyIjtzOjM1OiJob21lX3JlY2VudF9udW1iZXJfZGlzcGxheV9mcHJvZHVjdCI7czoxOiI0IjtzOjE2OiJ0cmFuc2xhdGlvbl9jYXJ0IjtzOjU6Ikl0ZW1zIjtzOjI1OiJ0cmFuc2xhdGlvbl9zaGFyZV9wcm9kdWN0IjtzOjI0OiJTaGFyZSB0aGlzIHByb2R1Y3QgdGl0bGUiO3M6MTg6InRyYW5zbGF0aW9uX3NhbXBsZSI7czoxMToiTGl2ZSBTYW1wbGUiO3M6MjE6InRyYW5zbGF0aW9uX2Fsc29fbGlrZSI7czoxNzoiQWxzbyBsaWtlIHByb2R1Y3QiO3M6MjY6InRyYW5zbGF0aW9uX2xvZ2luX3JlZ2lzdGVyIjtzOjE2OiJMb2dpbiAvIFJlZ2lzdGVyIjtzOjIwOiJ0cmFuc2xhdGlvbl9mZWF0dXJlZCI7czoyMDoiT3VyIEZ1dHVyZWQgUHJvZHVjdHMiO3M6MzI6InRyYW5zbGF0aW9uX3JlY2VudF9wcnVkdWN0X3RpdGxlIjtzOjE1OiJSZWNlbnQgUHJvZHVjdHMiO3M6MjA6InRyYW5zbGF0aW9uX21vcmVsaW5rIjtzOjIxOiJSZWFkIG1vcmUgZm9yIHByb2R1Y3QiO3M6MTY6InByb2R1Y3RfY2F0X3BhZ2UiO3M6MjoiMTIiO3M6MTg6ImNhdGFsb2dfaW1nX2hlaWdodCI7czozOiIyMDAiO3M6OToicXVvdGVfYmlnIjtzOjc1OiJDSEVDSyBPVVIgTEFURVNUIDxiPldPUkRQUkVTUyBUSEVNRTwvYj4gVEhBVCBJTVBMRU1FTlRTIDxiPlBBR0UgQlVJTERFUjwvYj4iO3M6MTE6InF1b3RlX3NtYWxsIjtzOjc5OiItIGxlYXJuIGhvdyB0byBidWlsZCBXb3JkcHJlc3MgVGhlbWVzIHdpdGggZWFzZSB3aXRoIGEgcHJlbWl1bSA8c3Bhbj48YSBocmVmID0gIjtzOjI1OiJ0b3Bfbm90aWZpY2F0aW9uX2ljb25fdGVsIjtzOjE4OiIgaWNvbi1tb2JpbGUtcGhvbmUiO3M6MjA6InRvcF9ub3RpZmljYXRpb25fdGVsIjtzOjI3OiI8c3Bhbj4rMzg2IDEyMyA0NSA2OTwvc3Bhbj4iO3M6MjY6InRvcF9ub3RpZmljYXRpb25faWNvbl9tYWlsIjtzOjE3OiJpY29uLWVudmVsb3BlLWFsdCI7czoyMToidG9wX25vdGlmaWNhdGlvbl9tYWlsIjtzOjM1OiI8c3Bhbj5tYWlsQHByZW1pdW1jb2RpbmcuY29tPC9zcGFuPiI7czoxNDoiYWR2ZXJ0aXNlaW1hZ2UiO2E6Njp7aToxO2E6NDp7czo1OiJvcmRlciI7czoxOiIxIjtzOjU6InRpdGxlIjtzOjk6IlNwb25zb3IgMSI7czozOiJ1cmwiO3M6ODI6Imh0dHA6Ly9idWxsc3kucHJlbWl1bWNvZGluZy5jb20vd3AtY29udGVudC91cGxvYWRzLzIwMTMvMDgvc3BvbnNvci1wbGFjZWhvbGRlci5wbmciO3M6NDoibGluayI7czoyNDoiaHR0cDovL3ByZW1pdW1jb2RpbmcuY29tIjt9aToyO2E6NDp7czo1OiJvcmRlciI7czoxOiIyIjtzOjU6InRpdGxlIjtzOjk6IlNwb25zb3IgNiI7czozOiJ1cmwiO3M6ODI6Imh0dHA6Ly9idWxsc3kucHJlbWl1bWNvZGluZy5jb20vd3AtY29udGVudC91cGxvYWRzLzIwMTMvMDgvc3BvbnNvci1wbGFjZWhvbGRlci5wbmciO3M6NDoibGluayI7czoyNDoiaHR0cDovL3ByZW1pdW1jb2RpbmcuY29tIjt9aTozO2E6NDp7czo1OiJvcmRlciI7czoxOiIzIjtzOjU6InRpdGxlIjtzOjk6IlNwb25zb3IgNCI7czozOiJ1cmwiO3M6ODI6Imh0dHA6Ly9idWxsc3kucHJlbWl1bWNvZGluZy5jb20vd3AtY29udGVudC91cGxvYWRzLzIwMTMvMDgvc3BvbnNvci1wbGFjZWhvbGRlci5wbmciO3M6NDoibGluayI7czoyNDoiaHR0cDovL3ByZW1pdW1jb2RpbmcuY29tIjt9aTo0O2E6NDp7czo1OiJvcmRlciI7czoxOiI0IjtzOjU6InRpdGxlIjtzOjk6IlNwb25zb3IgMiI7czozOiJ1cmwiO3M6ODI6Imh0dHA6Ly9idWxsc3kucHJlbWl1bWNvZGluZy5jb20vd3AtY29udGVudC91cGxvYWRzLzIwMTMvMDgvc3BvbnNvci1wbGFjZWhvbGRlci5wbmciO3M6NDoibGluayI7czoyNDoiaHR0cDovL3ByZW1pdW1jb2RpbmcuY29tIjt9aTo1O2E6NDp7czo1OiJvcmRlciI7czoxOiI1IjtzOjU6InRpdGxlIjtzOjk6IlNwb25zb3IgMyI7czozOiJ1cmwiO3M6ODI6Imh0dHA6Ly9idWxsc3kucHJlbWl1bWNvZGluZy5jb20vd3AtY29udGVudC91cGxvYWRzLzIwMTMvMDgvc3BvbnNvci1wbGFjZWhvbGRlci5wbmciO3M6NDoibGluayI7czoyNDoiaHR0cDovL3ByZW1pdW1jb2RpbmcuY29tIjt9aTo2O2E6NDp7czo1OiJvcmRlciI7czoxOiI2IjtzOjU6InRpdGxlIjtzOjk6IlNwb25zb3IgNSI7czozOiJ1cmwiO3M6ODI6Imh0dHA6Ly9idWxsc3kucHJlbWl1bWNvZGluZy5jb20vd3AtY29udGVudC91cGxvYWRzLzIwMTMvMDgvc3BvbnNvci1wbGFjZWhvbGRlci5wbmciO3M6NDoibGluayI7czoyNDoiaHR0cDovL3ByZW1pdW1jb2RpbmcuY29tIjt9fXM6NDoibG9nbyI7czo3MzoiaHR0cDovL2J1bGVyLnByZW1pdW1jb2RpbmcuY29tL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDEzLzA5L2J1bGxlci1sb2dvLnBuZyI7czo3OiJmYXZpY29uIjtzOjc3OiJodHRwOi8vYnVsbHN5LnByZW1pdW1jb2RpbmcuY29tL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDEzLzA4L2Zhdmljb24tYnVsbHN5LnBuZyI7czoxNjoiZ29vZ2xlX2FuYWx5dGljcyI7czowOiIiO3M6OToibWFpbkNvbG9yIjtzOjc6IiNGNTU4NTYiO3M6MTQ6ImdyYWRpZW50X2NvbG9yIjtzOjc6IiNGNUY2RjEiO3M6MjE6ImdyYWRpZW50X2JvcmRlcl9jb2xvciI7czo3OiIjRjJGMkYwIjtzOjg6ImJveENvbG9yIjtzOjc6IiNmZmZmZmYiO3M6MTU6IlNoYWRvd0NvbG9yRm9udCI7czo3OiIjMDAwMDAwIjtzOjIzOiJTaGFkb3dPcGFjaXR0eUNvbG9yRm9udCI7czoxOiIwIjtzOjIxOiJib2R5X2JhY2tncm91bmRfY29sb3IiO3M6NzoiI2ZkZmRmZCI7czoxODoiYm9keV9iZ19wcm9wZXJ0aWVzIjtzOjEwOiJyZXBlYXQgMCAwIjtzOjIzOiJiYWNrZ3JvdW5kX2ltYWdlX2hlYWRlciI7czoxOiIxIjtzOjIzOiJoZWFkZXJfYmFja2dyb3VuZF9jb2xvciI7czo3OiIjZmZmZmZmIjtzOjIwOiJoZWFkZXJfYmdfcHJvcGVydGllcyI7czoxMDoicmVwZWF0IDAgMCI7czoxMjoiY3VzdG9tX3N0eWxlIjtzOjA6IiI7czo5OiJpb3NzbGlkZXIiO2E6Mzp7aToxO2E6NTp7czo1OiJvcmRlciI7czoxOiIxIjtzOjU6InRpdGxlIjtzOjA6IiI7czozOiJ1cmwiO3M6ODA6Imh0dHA6Ly9idWxsc3kucHJlbWl1bWNvZGluZy5jb20vd3AtY29udGVudC91cGxvYWRzLzIwMTMvMDgvc2xpZGVzaG93LWltYWdlLTMuanBnIjtzOjQ6ImxpbmsiO3M6MDoiIjtzOjExOiJkZXNjcmlwdGlvbiI7czozOTA6IjxoMSBzdHlsZSA9IFwiZm9udC1zaXplOjUwcHggIWltcG9ydGFudDtjb2xvcjojZmZmICFpbXBvcnRhbnQ7XCI+VE9QIFBIT1RPR1JBUEhFUlM8L2gxPg0KTG9yZW0gaXBzdW0gZG9sb3Igc2l0IGFtZXQsIGNvbnNlY3RlPGJyPg0KYWRpcGlzY2luZyBlbGl0LiBGdXNjZSBhdCBqdXN0byBlZ2V0IGxvcmVtIDxicj4NCnBvcnQgdGl0b3IgdGluY2lkdW50Ljxicj4NCjxicj4NCjxhIGhyZWYgPSBcImh0dHA6Ly9wcmVtaXVtY29kaW5nLmNvbS9cIj48c3BhbiBzdHlsZSA9IFwiY29sb3I6Izk4QjgyNztmb250LXdlaWdodDpib2xkO1wiPlNlZSB0aGVtIGluIEFjdGlvbjwvc3Bhbj4gPHNwYW4gc3R5bGUgPSBcImNvbG9yOiNmZmY7Zm9udC13ZWlnaHQ6Ym9sZDtcIj7Cuzwvc3Bhbj4gPC9hPiI7fWk6MjthOjU6e3M6NToib3JkZXIiO3M6MToiMiI7czo1OiJ0aXRsZSI7czowOiIiO3M6MzoidXJsIjtzOjc5OiJodHRwOi8vYnVsbHN5LnByZW1pdW1jb2RpbmcuY29tL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDEzLzA4L3NsaWRlc2hvdy1pbWFnZTEucG5nIjtzOjQ6ImxpbmsiO3M6MjQ6Imh0dHA6Ly9wcmVtaXVtY29kaW5nLmNvbSI7czoxMToiZGVzY3JpcHRpb24iO3M6MDoiIjt9aTozO2E6NTp7czo1OiJvcmRlciI7czoxOiIzIjtzOjU6InRpdGxlIjtzOjA6IiI7czozOiJ1cmwiO3M6ODA6Imh0dHA6Ly9idWxsc3kucHJlbWl1bWNvZGluZy5jb20vd3AtY29udGVudC91cGxvYWRzLzIwMTMvMDgvc2xpZGVzaG93LWltYWdlLTQuanBnIjtzOjQ6ImxpbmsiO3M6MDoiIjtzOjExOiJkZXNjcmlwdGlvbiI7czo0NDc6IjxkaXYgc3R5bGUgPVwibWFyZ2luLWxlZnQ6NDAwcHg7XCI+DQo8aDEgc3R5bGUgPSBcImZvbnQtc2l6ZTo1MHB4ICFpbXBvcnRhbnQ7Y29sb3I6I2ZmZiAhaW1wb3J0YW50O1wiPldFIERPIFBIT1RPU0VTU0lPTlM8L2gxPg0KTG9yZW0gaXBzdW0gZG9sb3Igc2l0IGFtZXQsIGNvbnNlY3RlPGJyPg0KYWRpcGlzY2luZyBlbGl0LiBGdXNjZSBhdCBqdXN0byBlZ2V0IGxvcmVtIDxicj4NCnBvcnQgdGl0b3IgdGluY2lkdW50Ljxicj4NCjxicj4NCjxhIGhyZWYgPSBcImh0dHA6Ly9wcmVtaXVtY29kaW5nLmNvbS9cIj48c3BhbiBzdHlsZSA9IFwiY29sb3I6Izk4QjgyNztmb250LXdlaWdodDpib2xkO1wiPkNoZWNrIG1vcmUgYWJvdXQgdGhlIFNlcnZpY2U8L3NwYW4+IDxzcGFuIHN0eWxlID0gXCJjb2xvcjojZmZmO2ZvbnQtd2VpZ2h0OmJvbGQ7XCI+wrs8L3NwYW4+IDwvYT4NCjwvZGl2PiI7fX1zOjExOiJuaXZvX3NsaWRlciI7YTozOntpOjE7YTo1OntzOjU6Im9yZGVyIjtzOjE6IjEiO3M6NToidGl0bGUiO3M6MzQ6IlBob3RvZ3JhcGhlciBvbiBhIGJsYWNrIGJhY2tncm91bmQiO3M6MzoidXJsIjtzOjgwOiJodHRwOi8vYnVsbHN5LnByZW1pdW1jb2RpbmcuY29tL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDEzLzA4L3NsaWRlc2hvdy1pbWFnZS0zLmpwZyI7czo0OiJsaW5rIjtzOjI0OiJodHRwOi8vcHJlbWl1bWNvZGluZy5jb20iO3M6MTE6ImRlc2NyaXB0aW9uIjtzOjEyOiJSVUdHRUQgU0hPRVMiO31pOjI7YTo1OntzOjU6Im9yZGVyIjtzOjE6IjIiO3M6NToidGl0bGUiO3M6NDU6Ik1hbiBwaG90b2dyYXBoaW5nIGEgeW91bmcgd29tYW4gaW4gdGhlIHN0dWRpbyI7czozOiJ1cmwiO3M6Nzk6Imh0dHA6Ly9idWxsc3kucHJlbWl1bWNvZGluZy5jb20vd3AtY29udGVudC91cGxvYWRzLzIwMTMvMDgvc2xpZGVzaG93LWltYWdlMS5wbmciO3M6NDoibGluayI7czoyOToiaHR0cDovL2NhbXkucHJlbWl1bWNvZGluZy5jb20iO3M6MTE6ImRlc2NyaXB0aW9uIjtzOjI1OiJTTklDS0VSIFJFVklFVyBJTiBERVRBSUxTIjt9aTozO2E6NTp7czo1OiJvcmRlciI7czoxOiIzIjtzOjU6InRpdGxlIjtzOjI4OiJGZW1hbGUgcGhvdG9ncmFwaGVyIHNob290aW5nIjtzOjM6InVybCI7czo4MDoiaHR0cDovL2J1bGxzeS5wcmVtaXVtY29kaW5nLmNvbS93cC1jb250ZW50L3VwbG9hZHMvMjAxMy8wOC9zbGlkZXNob3ctaW1hZ2UtNC5qcGciO3M6NDoibGluayI7czoyOToiaHR0cDovL2NhbXkucHJlbWl1bWNvZGluZy5jb20iO3M6MTE6ImRlc2NyaXB0aW9uIjtzOjI3OiJXQVRFUlBST09GIFJVTk5JTkcgU05JQ0tFUlMiO319czoxNDoic2xpZGVyX29wYWNpdHkiO3M6MzoiMC43IjtzOjI1OiJzbGlkZXJfZm9udFNpemVfY29sb3JOaXZvIjthOjI6e3M6NDoic2l6ZSI7czo0OiI0M3B4IjtzOjU6ImNvbG9yIjtzOjc6IiNmZmZmZmYiO31zOjIwOiJzbGlkZXJfYmFja0NvbG9yTml2byI7czo3OiIjRjU1ODU2IjtzOjIyOiJzbGlkZXJfYm9yZGVyQ29sb3JOaXZvIjtzOjc6IiNGNTU4NTYiO3M6OToiYm9keV9mb250IjthOjM6e3M6NDoic2l6ZSI7czo0OiIxNnB4IjtzOjU6ImNvbG9yIjtzOjc6IiM0NjNGM0EiO3M6NDoiZmFjZSI7czoxMToiT3BlbiUyMFNhbnMiO31zOjEyOiJoZWFkaW5nX2ZvbnQiO2E6Mjp7czo0OiJmYWNlIjtzOjY6Ik9zd2FsZCI7czo1OiJzdHlsZSI7czo2OiJub3JtYWwiO31zOjk6Im1lbnVfZm9udCI7czoxMToiT3BlbiUyMFNhbnMiO3M6MTQ6ImJvZHlfYm94X2NvbGVyIjtzOjc6IiNmZmZmZmYiO3M6MTU6ImJvZHlfbGlua19jb2xlciI7czo3OiIjNDYzRjNBIjtzOjE1OiJoZWFkaW5nX2ZvbnRfaDEiO2E6Mjp7czo0OiJzaXplIjtzOjQ6IjMwcHgiO3M6NToiY29sb3IiO3M6NzoiIzQ2M0YzQSI7fXM6MTU6ImhlYWRpbmdfZm9udF9oMiI7YToyOntzOjQ6InNpemUiO3M6NDoiMjRweCI7czo1OiJjb2xvciI7czo3OiIjNDYzRjNBIjt9czoxNToiaGVhZGluZ19mb250X2gzIjthOjI6e3M6NDoic2l6ZSI7czo0OiIyMHB4IjtzOjU6ImNvbG9yIjtzOjc6IiM0NjNGM0EiO31zOjE1OiJoZWFkaW5nX2ZvbnRfaDQiO2E6Mjp7czo0OiJzaXplIjtzOjQ6IjE4cHgiO3M6NToiY29sb3IiO3M6NzoiIzQ2M0YzQSI7fXM6MTU6ImhlYWRpbmdfZm9udF9oNSI7YToyOntzOjQ6InNpemUiO3M6NDoiMTdweCI7czo1OiJjb2xvciI7czo3OiIjNDYzRjNBIjt9czoxNToiaGVhZGluZ19mb250X2g2IjthOjI6e3M6NDoic2l6ZSI7czo0OiIxNnB4IjtzOjU6ImNvbG9yIjtzOjc6IiM0NjNGM0EiO31zOjEzOiJmYWNlYm9va19zaG93IjtzOjE6IjEiO3M6ODoiZmFjZWJvb2siO3M6Mzg6Imh0dHBzOi8vd3d3LmZhY2Vib29rLmNvbS9QcmVtaXVtQ29kaW5nIjtzOjEyOiJ0d2l0dGVyX3Nob3ciO3M6MToiMSI7czo3OiJ0d2l0dGVyIjtzOjMzOiJodHRwczovL3R3aXR0ZXIuY29tL3ByZW1pdW1jb2RpbmciO3M6MTA6InZpbWVvX3Nob3ciO3M6MToiMSI7czo1OiJ2aW1lbyI7czoyOToiaHR0cDovL3BpbnRlcmVzdC5jb20vZ2xqaXZlYy8iO3M6MTI6InlvdXR1YmVfc2hvdyI7czoxOiIxIjtzOjc6InlvdXR1YmUiO3M6Mjc6Imh0dHA6Ly9kcmliYmJsZS5jb20vZ2xqaXZlYyI7czoxMDoiZW1haWxfc2hvdyI7czoxOiIxIjtzOjU6ImVtYWlsIjtzOjIyOiJpbmZvQHByZW1pdW1jb2RpbmcuY29tIjtzOjE0OiJlcnJvcnBhZ2V0aXRsZSI7czoxMDoiT09PUFMhIDQwNCI7czoxNzoiZXJyb3JwYWdlc3VidGl0bGUiO3M6NjU6IlNlZW1zIGxpa2UgeW91IHN0dW1ibGVkIGF0IHNvbWV0aGluZyB0aGF0IGRvZXNuXFxcJ3QgcmVhbGx5IGV4aXN0IjtzOjk6ImVycm9ycGFnZSI7czozMjY6IlNvcnJ5LCBidXQgdGhlIHBhZ2UgeW91IGFyZSBsb29raW5nIGZvciBoYXMgbm90IGJlZW4gZm91bmQuPGJyLz5UcnkgY2hlY2tpbmcgdGhlIFVSTCBmb3IgZXJyb3JzLCB0aGVuIGhpdCByZWZyZXNoLjwvYnI+T3IgeW91IGNhbiBzaW1wbHkgY2xpY2sgdGhlIGljb24gYmVsb3cgYW5kIGdvIGhvbWU6KQ0KPGJyPjxicj4NCjxhIGhyZWYgPSBcImh0dHA6Ly9idWxsc3kucHJlbWl1bWNvZGluZy5jb20vXCI+PGltZyBzcmMgPSBcImh0dHA6Ly9idWxsc3kucHJlbWl1bWNvZGluZy5jb20vd3AtY29udGVudC91cGxvYWRzLzIwMTMvMDgvaG9tZUhvdXNlSWNvbi5wbmdcIj48L2E+IjtzOjk6ImNvcHlyaWdodCI7czoxMDQ6IkJ1bGxzeSBAMjAxMyBEZXNpZ25lZCBieSA8YSBocmVmID0gXCJodHRwOi8vcHJlbWl1bWNvZGluZy5jb20vXCI+UHJlbWl1bUNvZGluZzwvYT4gfCBBbGwgUmlnaHRzIFJlc2VydmVkIjtzOjIzOiJ0cmFuc2xhdGlvbl9zb2NpYWx0aXRsZSI7czoxNzoiU29jaWFsaXplIHdpdGggdXMiO3M6MjA6InRyYW5zbGF0aW9uX2ZhY2Vib29rIjtzOjg6IkZhY2Vib29rIjtzOjE5OiJ0cmFuc2xhdGlvbl90d2l0dGVyIjtzOjc6IlR3aXR0ZXIiO3M6MTc6InRyYW5zbGF0aW9uX3ZpbWVvIjtzOjk6IlBpbnRlcmVzdCI7czoxOToidHJhbnNsYXRpb25fZHJpYmJsZSI7czo3OiJEcmliYmxlIjtzOjE3OiJ0cmFuc2xhdGlvbl9lbWFpbCI7czoxMzoiU2VuZCB1cyBFbWFpbCI7czoxNjoidHJhbnNsYXRpb25fcG9zdCI7czoxNjoiT3VyIGxhdGVzdCBwb3N0cyI7czoyNDoidHJhbnNsYXRpb25fZW50ZXJfc2VhcmNoIjtzOjk6IlNlYXJjaC4uLiI7czoxNjoidHJhbnNsYXRpb25fcG9ydCI7czoxNjoiUmVjZW50IHBvcnRmb2xpbyI7czoyMzoidHJhbnNsYXRpb25fcmVsYXRlZHBvc3QiO3M6MTM6IlJlbGF0ZWQgUG9zdHMiO3M6Mjc6InRyYW5zbGF0aW9uX2FkdmVydGlzZV90aXRsZSI7czoxNjoiT3VyIE1ham9yIEJyYW5kcyI7czoyNDoidHJhbnNsYXRpb25fbW9yZWxpbmtibG9nIjtzOjk6IlZpZXcgbW9yZSI7czoyNDoidHJhbnNsYXRpb25fbW9yZWxpbmtwb3J0IjtzOjE1OiJSZWFkIG1vcmUgYWJvdXQiO3M6MjQ6InBvcnRfcHJvamVjdF9kZXNjcmlwdGlvbiI7czoyMDoiUHJvamVjdCBEZXNjcmlwdGlvbjoiO3M6MjA6InBvcnRfcHJvamVjdF9kZXRhaWxzIjtzOjE2OiJQcm9qZWN0IGRldGFpbHM6IjtzOjE2OiJwb3J0X3Byb2plY3RfdXJsIjtzOjEyOiJQcm9qZWN0IFVSTDoiO3M6MjE6InBvcnRfcHJvamVjdF9kZXNpZ25lciI7czoxNzoiUHJvamVjdCBkZXNpZ25lcjoiO3M6MTc6InBvcnRfcHJvamVjdF9kYXRlIjtzOjI3OiJQcm9qZWN0IERhdGUgb2YgY29tcGxldGlvbjoiO3M6MTk6InBvcnRfcHJvamVjdF9jbGllbnQiO3M6MTU6IlByb2plY3QgQ2xpZW50OiI7czoxODoicG9ydF9wcm9qZWN0X3NoYXJlIjtzOjE3OiJTaGFyZSB0aGUgcHJvamVjdCI7czoyMDoicG9ydF9wcm9qZWN0X3JlbGF0ZWQiO3M6MTY6IlJlbGF0ZWQgUHJvamVjdHMiO3M6MTU6InRyYW5zbGF0aW9uX2FsbCI7czo4OiJTaG93IGFsbCI7czoxNDoidHJhbnNsYXRpb25fYnkiO3M6MjoiYnkiO3M6MjU6InRyYW5zbGF0aW9uX2xlYXZlX2NvbW1lbnQiO3M6MTc6IkxlYXZlIHRoZSBjb21tZW50IjtzOjIyOiJ0cmFuc2xhdGlvbl9zaGFyZV9wb3N0IjtzOjE1OiJTaGFyZSB0aGlzIFBvc3QiO3M6Mjc6InRyYW5zbGF0aW9uX3JlY2VudF9jb21tZW50cyI7czoxNToiUmVjZW50IENvbW1lbnRzIjtzOjMyOiJ0cmFuc2xhdGlvbl9jb21tZW50X2xlYXZlX3JlcGxheSI7czoxNToiTGVhdmUgYSBDb21tZW50IjtzOjM1OiJ0cmFuc2xhdGlvbl9jb21tZW50X2xlYXZlX3JlcGxheV90byI7czoxNjoiTGVhdmUgYSBSZXBseSB0byI7czozOToidHJhbnNsYXRpb25fY29tbWVudF9sZWF2ZV9yZXBsYXlfY2FuY2xlIjtzOjEyOiJDYW5jZWwgUmVwbHkiO3M6MjQ6InRyYW5zbGF0aW9uX2NvbW1lbnRfbmFtZSI7czo0OiJOYW1lIjtzOjI0OiJ0cmFuc2xhdGlvbl9jb21tZW50X21haWwiO3M6NDoiTWFpbCI7czoyNzoidHJhbnNsYXRpb25fY29tbWVudF93ZWJzaXRlIjtzOjc6IldlYnNpdGUiO3M6Mjg6InRyYW5zbGF0aW9uX2NvbW1lbnRfcmVxdWlyZWQiO3M6ODoicmVxdWlyZWQiO3M6MjY6InRyYW5zbGF0aW9uX2NvbW1lbnRfY2xvc2VkIjtzOjIwOiJDb21tZW50cyBhcmUgY2xvc2VkLiI7czozMToidHJhbnNsYXRpb25fY29tbWVudF9ub19yZXNwb25jZSI7czoxMDoiTm8gUmVwbGllcyI7czozMToidHJhbnNsYXRpb25fY29tbWVudF9vbmVfY29tbWVudCI7czo5OiJPbmUgUmVwbHkiO3M6MzE6InRyYW5zbGF0aW9uX2NvbW1lbnRfbWF4X2NvbW1lbnQiO3M6NzoiUmVwbGllcyI7czoxNjoiYmFja2dyb3VuZF9pbWFnZSI7czowOiIiO3M6NzoiYm9keV9iZyI7czowOiIiO3M6OToiaGVhZGVyX2JnIjtzOjA6IiI7fQ==';
		$pmc_data = unserialize(base64_decode($pmc_data)); //100% safe - ignore theme check nag
		update_option('of_options_buler_pmc', $pmc_data);
		
	}
	//delete_option(OPTIONS);
	
}

// Build Options
$root =  get_template_directory() .'/';
$admin =  get_template_directory() . '/admin/';

require_once ($admin . 'theme-options.php');   // Options panel settings and custom settings
require_once ($admin . 'admin-interface.php');  // Admin Interfaces
require_once ($admin . 'admin-functions.php');  // Theme actions based on options settings
require_once ($admin . 'medialibrary-uploader.php'); // Media Library Uploader


$includes =  get_template_directory() . '/includes/';
$widget_includes =  get_template_directory() . '/includes/widgets/';

/* include custom widgets */
require_once ($widget_includes . 'recent_post_widget.php'); 
require_once ($widget_includes . 'popular_post_widget.php');



/* include scripts */
function pmc_scripts() {
	global $pmc_data;
	/*scripts*/
	wp_enqueue_script('pmc_customjs', get_template_directory_uri() . '/js/custom.js', array('jquery'),true,true);  	      
	wp_enqueue_script('pmc_prettyphoto', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', array('jquery'),true,true);
	wp_enqueue_script('pmc_easing', get_template_directory_uri() . '/js/jquery.easing.1.3.js', array('jquery'),true,true);
	wp_enqueue_script('pmc_cycle', get_template_directory_uri() . '/js/jquery.cycle.all.min.js', array('jquery'),true,true);
	wp_register_script('pmc_nivo', get_template_directory_uri() . '/js/jquery.nivo.slider.pack.js', array('jquery'),true,true);
	wp_register_script('pmc_any', get_template_directory_uri() . '/js/jquery.anythingslider.js', array('jquery'),true,true);
	wp_register_script('pmc_isotope', get_template_directory_uri() . '/js/jquery.isotope.min.js', array('jquery'),true,true);  		
	wp_register_script('pmc_ba-bbq', get_template_directory_uri() . '/js/jquery.ba-bbq.js', array('jquery'),true,true);  
	wp_register_script('pmc_news', get_template_directory_uri() . '/js/jquery.li-scroller.1.0.js', array('jquery'),true,true);  
	wp_enqueue_script('pmc_gistfile', get_template_directory_uri() . '/js/gistfile_pmc.js', array('jquery') ,true,true);  
	wp_register_script('pmc_bxSlider', get_template_directory_uri() . '/js/jquery.bxslider.js', array('jquery') ,true,true);  			
	wp_register_script('pmc_iosslider', get_template_directory_uri() . '/js/jquery.iosslider.min.js', array('jquery') ,true,true);  	
	wp_register_script('googlemap', 'http://maps.google.com/maps/api/js?sensor=false', array('jquery'), '', true);		
	/*style*/
	wp_enqueue_style( 'main', get_stylesheet_uri(), 'style');
	wp_enqueue_style( 'prettyp', get_template_directory_uri() . '/css/prettyPhoto.css', 'style');
	if(isset($pmc_data['heading_font'])){			
	if($pmc_data['heading_font']['face'] != 'verdana' and $pmc_data['heading_font']['face'] != 'trebuchet' and $pmc_data['heading_font']['face'] != 'georgia' and $pmc_data['heading_font']['face'] != 'Helvetica Neue' and $pmc_data['heading_font']['face'] != 'times,tahoma') {				
	wp_enqueue_style('googleFont', 'http://fonts.googleapis.com/css?family='.$pmc_data['heading_font']['face'] ,'',NULL);				}				}
	if(isset($pmc_data['body_font'])){			
	if(($pmc_data['body_font']['face'] != 'verdana') and ($pmc_data['body_font']['face'] != 'trebuchet') and ($pmc_data['body_font']['face'] != 'georgia') and ($pmc_data['body_font']['face'] != 'Helvetica Neue') and ($pmc_data['body_font']['face'] != 'times,tahoma')) {	
	wp_enqueue_style('googleFontbody', 'http://fonts.googleapis.com/css?family='.$pmc_data['body_font']['face'] ,'',NULL);			}						}		
	wp_enqueue_style('font-awesome_pms', get_template_directory_uri() . '/css/font-awesome.css' ,'',NULL);
	wp_enqueue_style('options',  get_stylesheet_directory_uri() . '/css/options.css', 'style');		
}

add_action( 'wp_enqueue_scripts', 'pmc_scripts' ); 

// Other theme options
require_once ($includes . 'sidebars.php');
require_once ($includes . 'custom_functions.php');


	
	
if (function_exists( 'is_woocommerce' ) ) { 
	

	
	
	
	function pmc_cartShow(){
		global $woocommerce, $pmc_data, $sitepress,$product; ?>
		<div class="cartWrapper">
			<?php 
			$check_out = '';
			if($woocommerce->cart->get_cart_url() != ''){ 
			if (function_exists('icl_object_id')) {
				$cart= get_permalink(icl_object_id(woocommerce_get_page_id( 'cart' ), 'page', false));
				$check_out = get_permalink(icl_object_id(woocommerce_get_page_id( 'checkout' ), 'page', false));
				}
			else {
				$cart=$woocommerce->cart->get_cart_url();
				$check_out = $woocommerce->cart->get_checkout_url(); 
			}
			}
			else {$cart = home_url().'cart/';};
			?>
			<div class="header-cart-left">
				<div class="header-cart-items">
					<a href="<?php echo $cart; ?>" class="cart-top"><?php if (!function_exists('icl_object_id') or (ICL_LANGUAGE_CODE == $sitepress->get_default_language()) ) { echo pmc_stripText($pmc_data['translation_cart']); } else {  _e('Cart','buller'); } ?></a>
					<a class="cart-bubble cart-contents">(<?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?>)</a>
				</div>
				<div class="header-cart-total">
					<a class="cart-total"><?php echo $woocommerce->cart->get_cart_total(); ?></a>
				</div>
			</div>
			<div class="header-cart-icon"></div>
			<div class="cartTopDetails">
				<div class="cart_list product_list_widget">
					<div class="widget_shopping_cart_top widget_shopping_cart_content">	

						<?php get_template_part('woocommerce/cart/mini-cart') ?>

					</div>	
				</div>	
			</div>
		</div>	
	<?php
	}
	
	function woocommerce_header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		ob_start();
		?>
			<div class="header-cart-left">
				<div class="header-cart-items">
					<a href="<?php echo $cart; ?>" class="cart-top"><?php   _e('Items', 'pmc-themes');  ?></a>
					<a class="cart-bubble cart-contents">(<?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'pmc-themes'), $woocommerce->cart->cart_contents_count);?>)</a>
				</div>
				<div class="header-cart-total">
					<a class="cart-total"><?php echo $woocommerce->cart->get_cart_total(); ?></a>
				</div>
			</div>
		<?php

		$fragments['.header-cart-left'] = ob_get_clean();
		return $fragments;
	}
	
	add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');


	/*== WOO CUSTOMIZATION ==*/
	$average = '';

	
	if(isset($pmc_data['product_cat_page']) && $pmc_data['product_cat_page'] != 'Select a number:'){

	add_filter('loop_shop_per_page', create_function('$cols', 'return '.$pmc_data['product_cat_page'].';'));
	
	}




	add_filter('woocommerce_get_catalog_ordering_args', 'custom_woocommerce_get_catalog_ordering_args');
	
	function custom_woocommerce_get_catalog_ordering_args( $args ) {
			if (isset($_GET['orderby'])) {
				switch ($_GET['orderby']) :
					case 'date' :
						$args['orderby'] = 'date';
						$args['order'] = 'asc';
						$args['meta_key'] = '';
					break;
					case 'price_desc' :
						$args['orderby'] = 'meta_value_num';
						$args['order'] = 'desc';
						$args['meta_key'] = '_price';
					break;
					case 'title_desc' :
						$args['orderby'] = 'title';
						$args['order'] = 'desc';
						$args['meta_key'] = '';
					break;
					case 'menu_order' :
						$args['orderby'] = '';
						$args['order'] = '';
						$args['meta_key'] = '';
					break;
					case 'price' :
						$args['orderby'] = 'meta_value_num';
						$args['order'] = 'asc';
						$args['meta_key'] = '_price';
					break;
					case 'title_asc' :
						$args['orderby'] = 'title';
						$args['order'] = 'asc';
						$args['meta_key'] = '';
					break;	
					case 'select' :
						$args['orderby'] = '';
						$args['order'] = '';
						$args['meta_key'] = '';
					break;					
				endswitch;
			}
		return $args;
	}

	function pmc_woocommerce_image_dimensions() {
		global $pagenow;
	 
		if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
			return;
		}

		$catalog = array(
			'width' 	=> '280',	// px
			'height'	=> '380',	// px
			'crop'		=> 1 		// true
		);

		$single = array(
			'width' 	=> '700',	// px
			'height'	=> '600',	// px
			'crop'		=> 1 		// true
		);

		$thumbnail = array(
			'width' 	=> '134',	// px
			'height'	=> '115',	// px
			'crop'		=> 1 		// false
		);

		// Image sizes
		update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
		update_option( 'shop_single_image_size', $single ); 		// Single product image
		update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
	}

	add_action( 'after_switch_theme', 'pmc_woocommerce_image_dimensions', 1 );



	
	
	//define('WOOCOMMERCE_USE_CSS', false);
	function pmc_remove_styles() {
		if ( class_exists( 'woocommerce' ) ) {
		wp_dequeue_style( 'woocommerce' );
		wp_deregister_style( 'woocommerce' );
		}
	}
	add_action( 'wp_enqueue_scripts', 'pmc_remove_styles', 20 );
	add_filter( 'woocommerce_enqueue_styles', '__return_false' );
	
	
	add_action('woocommerce_before_main_content', create_function('', 'echo "";'), 10);
	add_action('woocommerce_after_main_content', create_function('', 'echo "";'), 10);
	
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
	
	// change add to cart location
	
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 10);
	
	// remove ordering
	
	remove_action( 'woocommerce_pagination', 'woocommerce_catalog_ordering', 20 );
	
	// excerpt on top
	
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 5);
	
	// remove categories
	
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
	
	// remove title from single
	
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
	
	// remove default related
	
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

	//remove add to cart
	
	//update_option( 'woocommerce_enable_ajax_add_to_cart' , 'no' );	
	
}

/* top bar function */
function pmc_showTop() {
	
	global $pmc_data, $sitepress;
	?>
	<div class="top-nav">
		<div class="topNotification">
			<div class="top-telephone">
				<?php if(isset($pmc_data['top_notification_icon_tel']))  echo '<i class="'.$pmc_data['top_notification_icon_tel'].'"></i>' ?>
				<?php if(isset($pmc_data['top_notification_tel']))  echo pmc_translation('top_notification_tel', 'Premiumcoding Telephone')  ?>
			</div>
			<div class="top-mail">
				<?php if(isset($pmc_data['top_notification_icon_mail']))  echo '<i class="'.$pmc_data['top_notification_icon_mail'].'"></i>' ?>
				<?php if(isset($pmc_data['top_notification_mail']))  echo pmc_translation('top_notification_mail', 'Premiumcoding Mail')  ?>
			</div>
			<div class="top-time">
				<?php if(isset($pmc_data['top_notification_icon_time']))  echo '<i class="'.$pmc_data['top_notification_icon_time'].'"></i>' ?>
				<?php if(isset($pmc_data['top_notification_time']))  echo pmc_translation('top_notification_time', 'Premiumcoding working hours')  ?>
			</div>
		</div>		
		<ul>
		
		<?php 
		if ( has_nav_menu( 'top_menu' ) ) {
			wp_nav_menu( array('theme_location' => 'top_menu', 'container' => 'false', 'menu_class' => 'top-nav', 'echo' => true, 'items_wrap' => '%3$s' )); 
		}
		?>
		</ul>
	</div>
	<?php
}
	

/* custom short content */
function shortcontent($start, $end, $new, $source, $lenght){
	$countopen = $countclose = 0;
	$text = strip_tags(preg_replace('/<h(.*)>(.*)<\/h(.*)>.*/iU', '', $source), '<b><strong>');
	$text = str_replace( '<strong>' , '<b>', $text );
	$text = str_replace( '</strong>' , '</b>', $text );
	$text = preg_replace('#\[video\](.*)\[\/video\]#si', '', $text);
	$text = preg_replace('#\[pmc_link\](.*)\[\/pmc_link\]#si', '', $text);
	$text = preg_replace('/\[[^\]]*\]/', $new, $text); 
	$text = substr(preg_replace('/\s[\s]+/','',$text),0,$lenght);
	$countopen = substr_count($text, '<b>');
	$countclose = substr_count($text, '</b>');
	if ($countopen > $countclose)
		return $text.'</b>';
	else
		return $text;
}


/* custom breadcrumb */
function pmc_breadcrumb() {
	global $pmc_data;
	$breadcrumb = '';
	if (!is_home()) {
		$breadcrumb .= '<a href="';
		$breadcrumb .=  home_url();
		$breadcrumb .=  '">';
		$breadcrumb .= get_bloginfo('name');
		$breadcrumb .=  "</a> » ";
		if (is_single()) {
			if (is_single()) {
				$name = '';
				if(!get_query_var($pmc_data['port_slug'])){
					$category = get_the_category(); +
					$category_id = get_cat_ID($category[0]->cat_name);
					$category_link = get_category_link($category_id);					
					$name = '<a href="'. esc_url( $category_link ).'">'.$category[0]->cat_name .'</a>';
				}
				else{
					$taxonomy = 'portfoliocategory';
					$entrycategory = get_the_term_list( get_the_ID(), $taxonomy, '', ',', '' );
					$catstring = $entrycategory;
					$catidlist = explode(",", $catstring);	
					$name = $catidlist[0];
				}
				
				$breadcrumb .= $name .' » '. get_the_title();
			}	
		} elseif (is_page()) {
			$breadcrumb .=  get_the_title();
		}
		elseif(get_query_var('portfoliocategory')){
			$term = get_term_by('slug', get_query_var('portfoliocategory'), 'portfoliocategory'); $name = $term->name; 
			$breadcrumb .=  $name;
		}	
		else if(get_query_var('tag')){
			$tag = get_query_var('tag');
			$tag = str_replace('-',' ',$tag);
			$breadcrumb .=  $tag;
		}
		else if(get_query_var('s')){
			the_search_query();				
		} 
		else if(get_query_var('cat')){
			$cat = get_query_var('cat');
			$cat = get_category($cat);
			$breadcrumb .=  $cat->name;
		}
		else if(get_query_var('m')){
			$breadcrumb .=  __('Archive','wp-bullsy');
		}	
	
		else{
			$breadcrumb .=  'Home';
		}
	}
	return $breadcrumb ;
}
/* social share links */
function pmc_socialLinkSingle() {
	$social = '';
	$social ='<div class="addthis_toolbox"><div class="custom_images">';
	global $pmc_data; 
	if($pmc_data['facebook_show'] == 1)
	$social .= '<a class="addthis_button_facebook" href="'.$pmc_data['facebook'].'" title="'.pmc_translation('translation_facebook', 'Facebook').'"></a>';            
	if($pmc_data['twitter_show'] == 1)
	$social .= '<a class="addthis_button_twitter" href="'.$pmc_data['twitter'].'" title="'.pmc_translation('translation_twitter', 'Twitter').'"></a>';  
	$social .='<a class="addthis_button_more"></a></div><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f3049381724ac5b"></script>';	
	if($pmc_data['email_show'] == 1) 
	$social .= '<a class="emaillink" href="mailto:'.$pmc_data['email'].'" title="'.pmc_translation('translation_email', 'Send us Email').'"></a>'; 
	$social .= '</div>'; 
	echo $social;
}

/* links to social profile */
function pmc_socialLink() {
	$social = '';
	global $pmc_data; 
	if($pmc_data['facebook_show'] == 1)
	$social .= '<a target="_blank" class="facebooklink top" href="'.$pmc_data['facebook'].'" title="'.pmc_translation('translation_facebook', 'Facebook').'"></a>'; 
	if($pmc_data['youtube_show'] == 1)
	$social .= '<a target="_blank" class="dribble top" href="'.$pmc_data['youtube'].'" title="'.pmc_translation('translation_dribble', 'Dribble').'"></a>';  
	if($pmc_data['twitter_show'] == 1)
	$social .= '<a target="_blank" class="twitterlink top" href="'.$pmc_data['twitter'].'" title="'.pmc_translation('translation_twitter', 'Twitter').'"></a>'; 
	if($pmc_data['email_show'] == 1) 
	$social .= '<a target="_blank" class="emaillink top" href="mailto:'.$pmc_data['email'].'" title="'.pmc_translation('translation_email', 'Send us Email').'"></a>';  	
	if($pmc_data['vimeo_show'] == 1) 
	$social .= '<a target="_blank" class="vimeo top" href="'.$pmc_data['vimeo'].'" title="'.pmc_translation('translation_vimeo', 'Vimeo').'"></a>';
	echo $social;
}

/*translation function*/
function pmc_translation($theme_name, $translation_name){
	global $pmc_data, $sitepress;
	if (!function_exists('icl_object_id') or (ICL_LANGUAGE_CODE == $sitepress->get_default_language()) ) { 
		if(isset($pmc_data[$theme_name]))
			$string = pmc_stripText($pmc_data[$theme_name]); 
		else
			$string = '';
		} 
	else {  
		$string = sprintf( __('%s','wp-buler'),$translation_name); 				
	} 
	return $string;

}
add_filter('the_content', 'pmc_addlightbox');

/* add lightbox to images*/
function pmc_addlightbox($content)
{	global $post;
	$pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
  	$replacement = '<a$1href=$2$3.$4$5 rel="lightbox[%LIGHTID%]"$6>';
    $content = preg_replace($pattern, $replacement, $content);
	if(isset($post->ID))
		$content = str_replace("%LIGHTID%", $post->ID, $content);
    return $content;
}

/* remove double // char */
function pmc_stripText($string) 
{ 
    return str_replace("\\",'',$string);
} 


/*portfolio loop*/
function pmc_portfolio($portSize, $item, $post = 'port' ,$number = 0,$cat = ''){
	wp_enqueue_script('pmc_isotope');	
	global $pmc_data; 
	$categport = '';
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	if($number != 0)
		$showposts = $number;
			
		
	if($item == 3){
		$titleChar = 999;
	}
	else if($item == 2){
		$titleChar = 25;
	}	
	else {
		$titleChar = 28;
	}	

	if($post == 'post'){
		$postT = 'post';
		$postC = 'category';	
		$categport="";		
		}
	else{

		$postT = $pmc_data['port_slug'];
		$postC = 'portfoliocategory';
			
		}
		
	if($cat != ''){	
			$args = array(
			'tax_query' => array(array('taxonomy' => $postC,'field' => 'id','terms' => $cat)),
			'showposts'     => $showposts,
			'post_type' => $postT,
			'paged'    => $paged
			);
		}
	else{
			$args = array(
			'showposts'     => $showposts,
			'post_type' => $postT,
			'paged'    => $paged
			);
		}

	query_posts( $args );

	
	$currentindex = $linkPost = '';
	$counter = 0;
	$portfolio = '';
	$count = 0;
	while ( have_posts() ) : the_post();
		if($post == 'post')
			$postmeta = get_post_custom(get_the_ID()); 
		$do_not_duplicate = get_the_ID(); 
		$image = wp_get_attachment_image_src(get_post_thumbnail_id(), $portSize, false);
		$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', false);
		$entrycategory = get_the_term_list( get_the_ID(), $postC, '', ',', '' );
		$catstring = $entrycategory;
		$catstring = strip_tags($catstring);
		$catidlist = explode(",", $catstring);
		$catlist = '';
		for($i = 0; $i < sizeof($catidlist); ++$i){
			$catidlist[$i].=$currentindex;
			$find =     array("&", "/", " ","amp;","&#38;");
			$replace  = array("", "", "", "","");			
			$catlist .= str_replace($find,$replace,$catidlist[$i]). ' ';
			
		}

		$counter++;
		$categoryIn = get_the_term_list( get_the_ID(), $postC, '', ', ', '' );	
		$category = explode(',',$categoryIn);	
		if ( has_post_format( 'link' , get_the_ID()) and $post == 'post') {
			if(isset($postmeta["link_post_url"][0] )) $linkPost = $postmeta["link_post_url"][0];
			}
		else{
			if (function_exists('icl_object_id')) 
				$linkPost = get_permalink(icl_object_id(get_the_ID(), $pmc_data['port_slug'], true, true));
			else 
				$linkPost = get_permalink();
		}		
		
		if($item != 2){

		$portfolio .= '<div class="item'.$item.' '.$catlist .'" data-category="'. $catlist.'">';
			if($item != 3) {
			
				$portfolio .= '
				<div class="recentimage">
					<a class="overdefultlink" href="'. $linkPost .'">
						<div class="overdefult">	
								<div class="portIcon"></div>

						</div>
					</a>
					<div class="image">
						<div class="loading"></div>
						<img src="'. $image[0] .'">
					</div>
				</div>';}
			if($item == 3) {
					
				$portfolio .= '		
					<a href = "'.$full_image[0].'" title="'.esc_attr(  get_the_title(get_the_id()) ) .'" rel="lightbox" >
						<div class="recentimage">
								<div class="overdefult">
									<div class="portIcon"></div>
								</div>			
							<div class="image">
								<div class="loading"></div>
								<img src="'.$image[0].'" alt="'.get_the_title().'">
							</div>
						</div>
					</a>';
			}
		
			if($item != 3){ 
			
				$title = substr(the_title('','',FALSE), 0, 17);  
				
				if(strlen(the_title('','',FALSE)) > 17) 
					$title = substr(the_title('','',FALSE), 0, 17). '...';				
				$portfolio .= '<div class="recentdescription">
					<h3><a class="overdefultlink" href="'. $linkPost .'">'. $title .'</a></h3>
					<div class="portCategory">'. $category[0] .'</div>					
				</div>';
				}
			if($item == 3) {
				$title = substr(the_title('','',FALSE), 0, 99);  
				
				if(strlen(the_title('','',FALSE)) > 99) 
					$title = substr(the_title('','',FALSE), 0, 99). '...';				
				$portfolio .= '<div class="shortDescription">
						
						<div class="descriptionHomePortText">					
							<h3><a href="'. $linkPost .'">'. $title .'</a></h3>
						</div>
						<div class="description">'. shortcontent("[", "]", "", get_the_content() ,90) .'...'.pmc_closeTagsReturn(shortcontent("[", "]", "", get_the_content() ,90)).'</div>
						<div class="recentdescription-text"><a href="'. get_permalink( get_the_id() ) .'">'. pmc_translation('translation_morelinkblog', 'Read more about this...') .'</a></div>
					</div>';
			}
			

		$portfolio .= '</div>';
		
		} else {
		$category = get_the_term_list( get_the_ID(), $postC, '', '', '' );	
		$categoryHover = get_the_term_list( get_the_ID(), $postC, '', ', ', '' );			
		if($count != 2){
			$portfolio .= '<div class="one_half item2 '.$catlist .'" data-category="'. $catlist.'" >';
		}
		else{
			$portfolio .= '<div class="one_half last item2 '.$catlist .'" data-category="'. $catlist.'" >';
			$count = 0;
		}

			$portfolio .= '	<div class="recentimage">
					<a class="overdefultlink" href="'. $linkPost .'">
						<div class="overdefult">
								<div class="portDate"><i class="icon-calendar"></i>'. get_the_date() .'</div>
								<div class="portIcon"></div>
								<div class="portCategory">'. $categoryHover  .'</div>

						</div>
					</a>
				
					<div class="image">
						<div class="loading"></div>
						<img src="'. $full_image[0] .'">
					</div>
				</div>
				<div class="recentdescription">
					<h3><a class="overdefultlink" href="'.$linkPost.'">'. substr(the_title('','',FALSE),0,99999) .'</a></h3>
					<h3 class="category">'. $category .'</h3>	
					<div class="description">'. shortcontent("[", "]", "", get_the_content() ,125) .'...'.pmc_closeTagsReturn(shortcontent("[", "]", "", get_the_content() ,125)).'</div>
				</div>
			</div>';

		$count++;
		
		}

		

	endwhile; 	
	
						

		
	echo $portfolio;
}

/* custom post type -- portfolio */
register_taxonomy("portfoliocategory", array($pmc_data['port_slug']), array("hierarchical" => true, "label" => "Portfolio Categories", "singular_label" => "Portfolio Category", "rewrite" => true));
add_action('init', 'pmc_create_portfolio');

function pmc_create_portfolio() {
	global $pmc_data;
	$portfolio_args = array(
		'label' => 'Portfolio',
		'singular_label' => 'Portfolio',
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => true,
		'supports' => array('title', 'editor', 'thumbnail', 'author', 'comments', 'excerpt')
	);
	register_post_type($pmc_data['port_slug'],$portfolio_args);
}

add_action("admin_init", "pmc_add_portfolio");
add_action('save_post', 'pmc_update_portfolio_data');

function pmc_add_portfolio(){
	global $pmc_data;
	add_meta_box("portfolio_details", "Portfolio Entry Options", "pmc_portfolio_options", $pmc_data['port_slug'], "normal", "high");
}

function pmc_update_portfolio_data(){
	global $post;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
	if($post){
		if( isset($_POST["author"]) ) {
			update_post_meta($post->ID, "author", $_POST["author"]);
		}
		if( isset($_POST["date"]) ) {
			update_post_meta($post->ID, "date", $_POST["date"]);
		}
		if( isset($_POST["detail_active"]) ) {
			update_post_meta($post->ID, "detail_active", $_POST["detail_active"]);
		}else{
			update_post_meta($post->ID, "detail_active", 0);
		}
		if( isset($_POST["website_url"]) ) {
			update_post_meta($post->ID, "website_url", $_POST["website_url"]);
		}
		if( isset($_POST["status"]) ) {
			update_post_meta($post->ID, "status", $_POST["status"]);
		}		
		if( isset($_POST["customer"]) ) {
			update_post_meta($post->ID, "customer", $_POST["customer"]);
		}			
		if( isset($_POST["skils"]) ) {
			update_post_meta($post->ID, "skils", $_POST["skils"]);
		}			

	}
}

function pmc_portfolio_options(){
	global $post;
	$pmc_data = get_post_custom($post->ID);
	if (isset($pmc_data["author"][0])){
		$author = $pmc_data["author"][0];
	}else{
		$author = "";
	}
	if (isset($pmc_data["date"][0])){
		$date = $pmc_data["date"][0];
	}else{
		$date = "";
	}
	if (isset($pmc_data["status"][0])){
		$status = $pmc_data["status"][0];
	}else{
		$status = "";
	}	
	if (isset($pmc_data["detail_active"][0])){
		$detail_active = $pmc_data["detail_active"][0];
	}else{
		$detail_active = 0;
		$pmc_data["detail_active"][0] = 0;
	}
	if (isset($pmc_data["website_url"][0])){
		$website_url = $pmc_data["website_url"][0];
	}else{
		$website_url = "";
	}
	
	if (isset($pmc_data["customer"][0])){
		$customer = $pmc_data["customer"][0];
	}else{
		$customer = "";
	}	 

	if (isset($pmc_data["skils"][0])){
		$skils = $pmc_data["skils"][0];
	}else{
		$skils = "";
	}	 	
	?>
    <div id="portfolio-options">
        <table cellpadding="15" cellspacing="15">
        	<tr>
                <td colspan="2"><strong>Portfolio Overview Options:</strong></td>
            </tr>
            <tr>
                <td><label>Link to Detail Page: <i style="color: #999999;">(Do you want a project detail page?)</i></label></td><td><input type="checkbox" name="detail_active" value="1" <?php if( isset($detail_active)){ checked( '1', $pmc_data["detail_active"][0] ); } ?> /></td>	
            </tr>
            <tr>
            	<td><label>Project Link: <i style="color: #999999;">(The URL of your project)</i></label></td><td><input name="website_url" style="width:500px" value="<?php echo $website_url; ?>" /></td>
            </tr>
            <tr>
            	<td><label>Project Author: <i style="color: #999999;">(The URL of your project)</i></label></td><td><input name="author" style="width:500px" value="<?php echo $author; ?>" /></td>
            </tr>
            <tr>
            	<td><label>Project date: <i style="color: #999999;">(Date of project)</i></label></td><td><input name="date" style="width:500px" value="<?php echo $date; ?>" /></td>
            </tr>	
            <tr>
            	<td><label>Customer: <i style="color: #999999;">(Customer of project)</i></label></td><td><input name="customer" style="width:500px" value="<?php echo $customer; ?>" /></td>
            </tr>				
            <tr>
            	<td><label>Project status: <i style="color: #999999;">(Status of project)</i></label></td><td><input name="status" style="width:500px" value="<?php echo $status; ?>" /></td>
            </tr>	
            <tr>
            	<td><label>Required skils: <i style="color: #999999;">(each skill into new line)</i></label></td><td><textarea name="skils" style="width:520px; height:300px;" /><?php echo $skils; ?></textarea></td>
            </tr>				
        </table>
    </div>
      
<?php
}	
	
add_action('save_post', 'update_post_type');
add_action("admin_init", "add_post_type");


/* get category name */
function pmc_getcatname($catID,$posttype){
		if($catID != 0){
		$cat_obj = get_term($catID, $posttype);
		$cat_name = '';
		$cat_name = $cat_obj->name;
		return $cat_name;
		}
	}

	
/* custom post types */	
function add_post_type(){
	add_meta_box("slider_categories", "Post type", "post_type", "post", "normal", "high");
	
}	


function post_type(){
	global $post;
	$pmc_data = get_post_custom($post->ID);
	if (isset($pmc_data["slider_category"][0])){
		$slider_category = $pmc_data["slider_category"][0];
	}else{
		$slider_category = "";
	}
	if (isset($pmc_data["video_post_url"][0])){
		$video_post_url = $pmc_data["video_post_url"][0];
	}else{
		$video_post_url = "";
	}	
	if (isset($pmc_data["video_active_post"][0])){
		$video_active_post = $pmc_data["video_active_post"][0];
	}else{
		$video_active_post = 0;
		$pmc_data["video_active_post"][0] = 0;
	}	
	
	if (isset($pmc_data["link_post_url"][0])){
		$link_post_url = $pmc_data["link_post_url"][0];
	}else{
		$link_post_url = "";
	}	
	
	if (isset($pmc_data["audio_post_url"][0])){
		$audio_post_url = $pmc_data["audio_post_url"][0];
	}else{
		$audio_post_url = "";
	}	

	if (isset($pmc_data["audio_post_title"][0])){
		$audio_post_title = $pmc_data["audio_post_title"][0];
	}else{
		$audio_post_title = "";
	}	
	
	if (isset($pmc_data["selectv"][0])){
		$selectv = $pmc_data["selectv"][0];
	}else{
		$selectv = "";
	}	
	
	

?>
    <div id="portfolio-category-options">
        <table cellpadding="15" cellspacing="15">
	
            <tr class="videoonly" style="border-bottom:1px solid #000;">
            	<td style="border-bottom:1px solid #000;width:100%;"><label>Video URL(*required) - add if you select video post: <i style="color: #999999;">
				<br>Link should look for Youtube: http://www.youtube.com/watch?v=WhBoR_tgXCI - So ID is WhBoR_tgXCI
				<br>Link should look for Vimeo: http://vimeo.com/29017795 so ID is 29017795 <br></i></label><br><input name="video_post_url" style="width:500px" value="<?php echo $video_post_url; ?>" /></td>
            	
            <td class="select_video" style="text-align:left;border-bottom:1px solid #000;width:100%; " >
            	<label>Select video: <br/><i style="color: #999999;">
				<select name="selectv">
				<?php if ($selectv == 'vimeo') {?>
				  <option value="vimeo" selected>Vimeo</option>
				 <?php } else {?>
				  <option value="vimeo">Vimeo</option>						
				 <?php }?>	
				<?php if ($selectv == 'youtube') {?>				 
				  <option value="youtube" selected>YouTube</option>
				 <?php } else {?>
				  <option value="youtube">YouTube</option>						
				 <?php }?>					  
				</select>
	
            </td>	
				
			</tr>
						
            <tr class="linkonly" >
			
            	<td style="border-bottom:1px solid #000;width:100%;"><label>Link URL - add if you select link post : <i style="color: #999999;"></i></label><br></td><td style="text-align:left;border-bottom:1px solid #000;width:100%; " ><input name="link_post_url" style="width:500px" value="<?php echo $link_post_url; ?>" /></td>
            </tr>				

            <tr class="audioonly">
            	<td style="border-bottom:1px solid #000;width:100%;"><label>Audio URL - add if you select audio post: <i style="color: #999999;"></i></label><br></td><td style="text-align:left;border-bottom:1px solid #000;width:100%; " ><input name="audio_post_url" style="width:500px" value="<?php echo $audio_post_url; ?>" /></td>
            </tr>
            <tr class="audioonly">
            	<td style="border-bottom:1px solid #000;width:100%;"><label>Audio title - add if you select audio post: <i style="color: #999999;"></i></label><br></td><td style="text-align:left;border-bottom:1px solid #000;width:100%; " ><input name="audio_post_title" style="width:500px" value="<?php echo $audio_post_title; ?>" /></td>
            </tr>			
			
        </table>

    </div>
	
      
<?php


	
}


function update_post_type(){
	global $post;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
	if($post){
		if( isset($_POST["slider_category"]) ) {
			update_post_meta($post->ID, "slider_category", $_POST["slider_category"]);
		}	
		if( isset($_POST["video_post_url"]) ) {
			update_post_meta($post->ID, "video_post_url", $_POST["video_post_url"]);
		}	
		if( isset($_POST["video_active_post"]) ) {
			update_post_meta($post->ID, "video_active_post", $_POST["video_active_post"]);
		}else{
			update_post_meta($post->ID, "video_active_post", 0);
		}		
		if( isset($_POST["link_post_url"]) ) {
			update_post_meta($post->ID, "link_post_url", $_POST["link_post_url"]);
		}	
		if( isset($_POST["audio_post_url"]) ) {
			update_post_meta($post->ID, "audio_post_url", $_POST["audio_post_url"]);
		}		
		if( isset($_POST["audio_post_title"]) ) {
			update_post_meta($post->ID, "audio_post_title", $_POST["audio_post_title"]);
		}					
		if( isset($_POST["selectv"]) ) {
			update_post_meta($post->ID, "selectv", $_POST["selectv"]);
		}			
	}
	
	
	
}




if( !function_exists( 'buler_fallback_menu' ) )
{
	/**
	 * Create a navigation out of pages if the user didnt create a menu in the backend
	 *
	 */
	function buler_fallback_menu()
	{
		$current = "";
		if (is_front_page()){$current = "class='current-menu-item'";} 
		
		
		echo "<div class='fallback_menu'>";
		echo "<ul class='buler_fallback menu'>";
		echo "<li $current><a href='".get_bloginfo('url')."'>Home</a></li>";
		wp_list_pages('title_li=&sort_column=menu_order');
		echo "</ul></div>";
	}
}



/* close bold tags*/
function pmc_closeTagsReturn($string){
	$output  = '';
	$open = $close = 0;
	$open = substr_count($string , '<strong>');
	$close = substr_count($string , '</strong>');
	if($open > $close )	
		$output .='</strong>'; 
	return $output;
}


add_filter( 'the_category', 'add_nofollow_cat' );  

function add_nofollow_cat( $text ) { 
	$text = str_replace('rel="category tag"', "", $text); 
	return $text; 
}

/* get image from post */
function pmc_getImage($image){
	if ( has_post_thumbnail() ){
		the_post_thumbnail($image);
		}
	else
		echo '<img src ="'.get_template_directory_uri() . '/images/placeholder-580.png" alt="'.the_title('','',FALSE).'" >';
							
}

function pmc_add_this_script_footer(){ 
	global $pmc_data, $sitepress;
	
		if (!function_exists('icl_object_id') or (ICL_LANGUAGE_CODE == $sitepress->get_default_language()) ) { 
			$search = pmc_stripText($pmc_data['translation_enter_search']); 
			
			} 
		else {  
			$search = __('Enter search...','wp-buler'); 
				
		} 


?>
<script>	
	jQuery(document).ready(function(){	
		jQuery('.searchform #s').val('<?php echo $search ?>');
		
		jQuery('.searchform #s').focus(function() {
			jQuery('.searchform #s').val('');
		});
		
		jQuery('.searchform #s').focusout(function() {
			jQuery('.searchform #s').val('<?php echo $search ?>');
		});	
		
	});	</script>

<?php  }


add_action('wp_footer', 'pmc_add_this_script_footer'); 	

if ( ! isset( $content_width ) ) $content_width = 800;
?>