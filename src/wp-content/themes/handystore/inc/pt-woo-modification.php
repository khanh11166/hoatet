<?php

/*-------Woocommerce modifications----------*/

/* Contents:
	1. Style & Scripts
	2. Fixing woocommerce price filter
	3. Product columns filter
	4. Custom catalog order
	5. Woocommerce Main content wrapper
	6. Changing 'add to cart' buttons text
	7. Modifying Product Loop layout
	8. Modifying Single Product layout
	9. Adding single product pagination
	10. Custom chekout fields order output
	11. Add meta box for activating extra gallery on product hover
	12. Add meta box for adding custom Product Badge
	13. Hide WC Vendors "Sold by"
	14. Catalog Mode Function
	15. Variables Products fix
 */

if ( class_exists('Woocommerce') ) {

	// ----- 1. Style & Scripts

	// Deactivating Woocommerce styles
	if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
		add_filter( 'woocommerce_enqueue_styles', '__return_false' );
	} else {
		define( 'WOOCOMMERCE_USE_CSS', false );
	}

	// Adding new styles
	if ( ! function_exists( 'pt_woo_custom_style' ) ) {
		function pt_woo_custom_style() {
			wp_register_style( 'plumtree-woo-styles', get_template_directory_uri() . '/woo-styles.css', null, 1.0, 'screen' );
			wp_enqueue_style( 'plumtree-woo-styles' );
		}
	}
	add_action( 'wp_enqueue_scripts', 'pt_woo_custom_style' );


	// ----- 2. Fixing woocommerce price filter
	add_action( 'init', 'pt_price_filter_init' );

	function pt_price_filter_init() {
		if (function_exists('WC')) {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '';

			wp_register_script( 'wc-price-slider', WC()->plugin_url() . '/assets/js/frontend/price-slider' . $suffix . '.js', array( 'jquery-ui-slider' ), WC_VERSION, true );

			wp_localize_script( 'wc-price-slider', 'woocommerce_price_slider_params', array(
				'currency_symbol' 	=> get_woocommerce_currency_symbol(),
				'currency_pos'      => get_option( 'woocommerce_currency_pos' ),
				'min_price'			=> isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '',
				'max_price'			=> isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : ''
			) );
		}
	}


	// ----- 3. Product columns filter
	if ( ! function_exists( 'pt_loop_shop_columns' ) ) {
		function pt_loop_shop_columns(){
			$qty = (get_option('store_columns') != '') ? get_option('store_columns') : '3';
			return $qty;
		}
	}
	add_filter('loop_shop_columns', 'pt_loop_shop_columns');


	// ----- 4. Custom catalog order
	if ( ! function_exists( 'pt_default_catalog_orderby' ) ) {
		function pt_default_catalog_orderby(){
			return 'date'; // Can also use title and price
		}
	}
	if ( !get_option('woocommerce_default_catalog_orderby') ) {
		add_filter('woocommerce_default_catalog_orderby', 'pt_default_catalog_orderby');
	}


	// ----- 5. Woocommerce Main content wrapper
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

	if ( ! function_exists( 'pt_theme_wrapper_start' ) ) {
		function pt_theme_wrapper_start() {
			// Check if site turned to boxed version
			$boxed = ''; $boxed_element = ''; $row_class = '';
			if (get_option('site_layout')=='boxed') {$boxed = 'container'; $boxed_element = 'col-md-12 col-sm-12'; $row_class = 'row';}

			if (!$boxed || $boxed=='') { echo '<div class="container">'; }
			echo '<div class="row">';

			if ( pt_show_layout()=='layout-one-col' ) { $content_class = "col-xs-12 col-md-12 col-sm-12"; }
			elseif ( pt_show_layout()=='layout-two-col-left' ) { $content_class = "col-xs-12 col-md-9 col-sm-8 col-md-push-3 col-sm-push-4"; }
			else { $content_class = "col-xs-12 col-md-9 col-sm-8"; }

			echo '<div id="content" class="site-content woocommerce '.esc_attr($content_class).'" role="main">';
		}
	}

	if ( ! function_exists( 'pt_theme_wrapper_end' ) ) {
		function pt_theme_wrapper_end() {
			// Check if site turned to boxed version
			$boxed = ''; $boxed_element = ''; $row_class = '';
			if (get_option('site_layout')=='boxed') {$boxed = 'container'; $boxed_element = 'col-md-12 col-sm-12'; $row_class = 'row';}

			echo '</div><!-- #content -->';
			get_sidebar();
			echo '</div>';
			echo '</div><!--.main -->';
			if (!$boxed || $boxed=='') { echo '</div>'; }
		}
	}

	add_action('woocommerce_before_main_content', 'pt_theme_wrapper_start', 10);
	add_action('woocommerce_after_main_content', 'pt_theme_wrapper_end', 10);


	// ----- 6. Changing 'add to cart' buttons text

	// Changing by product type on archive pages
	if ( ! function_exists( 'pt_custom_woocommerce_product_add_to_cart_text' ) ) {
		function pt_custom_woocommerce_product_add_to_cart_text() {
			global $product;
			$product_type = $product->product_type;
			switch ( $product_type ) {
				case 'external':
					$text = __('Buy product', 'plumtree');
					return '<i title="'.esc_attr($text).'" class="fa fa-shopping-cart"></i>';
				break;
				case 'grouped':
					$text = __('View products', 'plumtree');
					return '<i title="'.esc_attr($text).'" class="fa fa-search"></i>';
				break;
				case 'simple':
					$text = __('Add to cart', 'plumtree');
					return  '<i title="'.esc_attr($text).'" class="fa fa-shopping-cart"></i>';
				break;
				case 'variable':
					$text = __('Select options', 'plumtree');
					return '<i title="'.esc_attr($text).'" class="fa fa-search"></i>';
				break;
				default:
					$text = __('Read more', 'plumtree');
					return '<i title="'.esc_attr($text).'" class="fa fa-search"></i>';
			}
		}
	}
	add_filter( 'woocommerce_product_add_to_cart_text' , 'pt_custom_woocommerce_product_add_to_cart_text' );

	// Changing on single product page
	if ( ! function_exists( 'pt_custom_single_add_to_cart_button_text' ) ) {
		function pt_custom_single_add_to_cart_button_text() {
			global $product;
			$product_type = $product->product_type;
			if ( $product_type == 'external' ) {
				$text = __('Buy product', 'plumtree');
			} else {
				$text = __('Add to Cart', 'plumtree');
			}
			return esc_attr($text);
		}
	}
	add_filter( 'woocommerce_product_single_add_to_cart_text', 'pt_custom_single_add_to_cart_button_text' );


	// ----- 7. Modifying Product Loop layout
	// Adding store page title to breadcrumbs wrapper
	add_action( 'woocommerce_before_main_content', 'pt_store_title', 5 );
	if ( ! function_exists( 'pt_store_title' ) ) {
		function pt_store_title(){
			if ( (is_shop() || is_product_category() || is_product_tag()) && !is_front_page() ) {
				echo '<div class="page-title">'.get_the_title( get_option( 'woocommerce_shop_page_id' ) ).'</div>';
			}
		}
	}

	// Adding advanced shop title
	add_filter( 'woocommerce_page_title', 'custom_woocommerce_page_title');
	if ( ! function_exists( 'custom_woocommerce_page_title' ) ) {
		function custom_woocommerce_page_title( $page_title ) {
			if ( is_shop() ) {
				return __('All Products', 'plumtree');
			} else {
				return $page_title;
			}
		}
	}

	// Modifying shop control buttons
	add_action( 'woocommerce_before_shop_loop', 'pt_shop_controls_wrapper_start', 10 );
	if ( ! function_exists( 'pt_shop_controls_wrapper_start' ) ) {
		function pt_shop_controls_wrapper_start(){ ?>
			<div class="shop-controls-wrapper">
		<?php }
	}

	add_action( 'woocommerce_before_shop_loop', 'pt_shop_controls_wrapper_end', 40 );
	if ( ! function_exists( 'pt_shop_controls_wrapper_end' ) ) {
	function pt_shop_controls_wrapper_end(){ ?>
		</div>
	<?php }
	}

	// Adding view all Link
	add_action( 'woocommerce_before_shop_loop', 'pt_view_all_link', 25 );
	if ( ! function_exists( 'pt_view_all_link' ) ) {
		function pt_view_all_link(){
			global $wp_query;
			if ( $wp_query->max_num_pages > 1 ) { ?>
				<a rel="nofollow" class="view-all" href="?showall=1"><?php _e('View All', 'plumtree'); ?></a>
			<?php }
			if( isset( $_GET['showall'] ) ){
				$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) ); ?>
			    <a rel="nofollow" class="view-all" href="<?php echo esc_url($shop_page_url); ?>"><?php _e('View Less', 'plumtree'); ?></a>
			<?php }
		}
	}

	// Products per page filter
	if ( ! function_exists( 'pt_show_products_per_page' ) ) {
		function pt_show_products_per_page() {
			if( isset( $_GET['showall'] ) ){
				$qty = '-1';
			} else {
				$qty = (get_option('store_per_page') != '') ? get_option('store_per_page') : '6';
			}
			return $qty;
		}
	}
	add_filter('loop_shop_per_page', 'pt_show_products_per_page', 20 );

	// Adding list/grid view
	if ( ! function_exists( 'pt_view_switcher' ) ) {
		function pt_view_switcher() { ?>
			<div class="pt-view-switcher">
				<span class="pt-list<?php if(get_option('default_list_type')=='list') echo ' active';?>" title="<?php _e('List View', 'plumtree'); ?>"><i class="fa fa-th-list"></i></span>
				<span class="pt-grid<?php if(get_option('default_list_type')=='grid') echo ' active';?>" title="<?php _e('Grid View', 'plumtree'); ?>"><i class="fa fa-th"></i></span>
			</div>
		<?php }
	}

	if ( (get_option('list_grid_switcher')) === 'on' ) {
		add_action( 'woocommerce_before_shop_loop', 'pt_view_switcher', 35 );
	}

	// Moving add_to_cart button
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	if ( ! function_exists( 'pt_output_variables' ) ) {
		function pt_output_variables() {
	    	global $product;
			if( $product->product_type == "variable" && (is_shop() || is_product_category() || is_product_tag()) ){
				woocommerce_variable_add_to_cart();
				wc_get_template_part( 'loop/add-to-cart.php' );
			} else {
				wc_get_template_part( 'loop/add-to-cart.php' );
			}
		}
	}
	add_action( 'woocommerce_after_shop_loop_item_title', 'pt_output_variables', 15 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 15 );

	// Adding new custom Badge
	if ( ! function_exists( 'pt_output_custom_badge' ) ) {
		function pt_output_custom_badge(){
			global $post;
			$badge_text = get_post_meta( $post->ID, 'custom-badge-text' );
			$badge_class = get_post_meta( $post->ID, 'custom-badge-class' );
			if (isset($badge_class[0]) && ($badge_class[0] != '')) { $new_class = $badge_class[0]; } else { $new_class='custom-badge'; }
			if ( isset($badge_text[0]) && ( $badge_text[0] != '') ) { ?>
				<span class="<?php echo esc_attr($new_class); ?>"><?php echo esc_attr($badge_text[0]); ?></span>
			<?php }
		}
	}
	add_action( 'woocommerce_before_shop_loop_item_title', 'pt_output_custom_badge', 11 );

	// Modifying Pagination args
	if ( ! function_exists( 'pt_new_pagination_args' ) ) {
		function pt_new_pagination_args($args) {
			$args['prev_text'] = __( '<i class="fa fa-chevron-left"></i>', 'plumtree' );
			$args['next_text'] = __( '<i class="fa fa-chevron-right"></i>', 'plumtree' );
			return $args;
		}
	}
	add_filter('woocommerce_pagination_args','pt_new_pagination_args');


	// ----- 8. Modifying Single Product layout

	// Breadcrumbs
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

	if ( (get_option('store_breadcrumbs')) === 'on' ) {
		add_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 6 );
	}

	if ( ! function_exists( 'pt_breadcrumbs_wrap_begin' ) ) {
		function pt_breadcrumbs_wrap_begin(){ ?>
			<div class="breadcrumbs-wrapper"><div class="container"><div class="row">
		<?php }
	}
	add_action( 'woocommerce_before_main_content', 'pt_breadcrumbs_wrap_begin', 4 );

	if ( ! function_exists( 'pt_breadcrumbs_wrap_end' ) ) {
		function pt_breadcrumbs_wrap_end(){ ?>
			</div></div></div>
		<?php }
	}
	add_action( 'woocommerce_before_main_content', 'pt_breadcrumbs_wrap_end', 7 );

	add_filter( 'woocommerce_breadcrumb_defaults', 'pt_custom_breadcrumbs' );
	if ( ! function_exists( 'pt_custom_breadcrumbs' ) ) {
		function pt_custom_breadcrumbs() {
			return array(
				'delimiter' => '<span> &#47; </span>',
				'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
				'wrap_after' => '</nav>',
				'before' => '',
				'after' => '',
				'home' => _x( 'Home', 'breadcrumb', 'plumtree' ),
			);
		}
	}

	// Images wrapper
	if ( ! function_exists( 'pt_images_wrapper_start' ) ) {
		function pt_images_wrapper_start(){ ?>
			<div class="images-wrapper">
		<?php }
	}
	
	if ( ! function_exists( 'pt_images_wrapper_end' ) ) {
		function pt_images_wrapper_end(){ ?>
			</div>
		<?php }
	}
	add_action('woocommerce_before_single_product_summary', 'pt_images_wrapper_start', 5);
	add_action('woocommerce_before_single_product_summary', 'pt_images_wrapper_end', 25);

	// Compare button moving
	if( ( class_exists('YITH_Woocompare_Frontend') ) && ( get_option('yith_woocompare_compare_button_in_product_page') == 'yes' ) ) {
		remove_action( 'woocommerce_single_product_summary', array( $yith_woocompare->obj, 'add_compare_link'), 35 );
		add_action( 'woocommerce_single_product_summary', array( $yith_woocompare->obj, 'add_compare_link'), 24  );
	}

	// Wishlist button moving
	if ( ( class_exists( 'YITH_WCWL_Shortcode' ) ) && ( get_option('yith_wcwl_enabled') == true ) && ( get_option('yith_wcwl_button_position') == 'shortcode' ) ) {
		function output_wishlist_button() {
			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
		}
		add_action( 'woocommerce_single_product_summary', 'output_wishlist_button', 25  );
	}

	// Social shares
	if (get_option('use_pt_shares_for_product')=='on') {
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
		add_action('woocommerce_single_product_summary', 'pt_share_buttons_output', 50);
	}

	// Tabs modification
	if ( ! function_exists( 'pt_custom_product_tabs' ) ) {
		function pt_custom_product_tabs( $tabs ) {

			global $post, $product;

			$product_content = $post->post_content;

			if ($product_content && $product_content!=='') {
				$tabs['description']['priority'] = 10;
			} else {
				unset( $tabs['description'] );
			}

			if( $product->has_attributes() || $product->has_dimensions() || $product->has_weight() ) {
				$tabs['additional_information']['title'] = __( 'Specification', 'plumtree' );
				$tabs['additional_information']['priority'] = 20;
			} else {
				unset( $tabs['additional_information'] );
			}

			return $tabs;

		}
	}
	add_filter( 'woocommerce_product_tabs', 'pt_custom_product_tabs', 98 );

	// Reviews avatar size
	if ( ! function_exists( 'pt_custom_review_gravatar' ) ) {
		function pt_custom_review_gravatar() {
			return '70';
		}
	}
	add_filter('woocommerce_review_gravatar_size', 'pt_custom_review_gravatar');

	// Up-sells Products
	remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
	if (get_option('show_upsells')=='on') {
		if ( ! function_exists( 'pt_output_upsells' ) ) {
			function pt_output_upsells() {
				$upsell_qty = get_option('upsells_qty');
				woocommerce_upsell_display( $upsell_qty, $upsell_qty ); // Display $per_page products in $cols
			}
		}
		add_action('woocommerce_after_single_product_summary', 'pt_output_upsells', 20);
	}

	// Related Products
	remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

	if ( ! function_exists( 'pt_output_related_products' ) ) {
		function pt_output_related_products($args) {
			$related_qty = get_option('related_products_qty');
			$args['posts_per_page'] = $related_qty; // related products
			$args['columns'] = $related_qty; // arranged in columns
			return $args;
		}
	}
	add_filter( 'woocommerce_output_related_products_args', 'pt_output_related_products' );

	if (get_option('show_related_products')=='on') {
		add_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 30);
	}


	// ----- 9. Adding single product pagination
	if ( get_option('product_pagination') === 'on' ) {
		if ( ! function_exists( 'pt_single_product_pagi' ) ) {
			function pt_single_product_pagi(){
				if(is_product()) :
				?>
			<nav class="navigation single-product-navi" role="navigation">
				<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'plumtree' ); ?></h1>
					<div class="nav-links">
						<?php previous_post_link('%link', '<i class="fa fa-angle-left"></i>&nbsp;&nbsp;&nbsp;'.__('Previous Product', 'plumtree')); ?>
						<?php next_post_link('%link', __('Next Product', 'plumtree').'&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>'); ?>
					</div>
			</nav>
				<?php
				endif;
			}
		}
		add_action( 'woocommerce_before_main_content', 'pt_single_product_pagi', 5 );
	}


	// ----- 10. Checkout modification
	// Adding new mark-up
	if ( ! function_exists( 'pt_checkout_wrapper_start' ) ) {
		function pt_checkout_wrapper_start(){
			echo '<div class="order-wrapper">';
		}
	}
	add_action( 'woocommerce_checkout_after_customer_details', 'pt_checkout_wrapper_start');

	if ( ! function_exists( 'pt_checkout_wrapper_end' ) ) {
		function pt_checkout_wrapper_end(){
			echo '</div>';
		}
	}
	add_action( 'woocommerce_checkout_after_order_review', 'pt_checkout_wrapper_end');

	// Add payment method heading
	if ( ! function_exists( 'pt_payments_heading' ) ) {
		function pt_payments_heading(){
			echo '<h3 id="payment_heading">'.__('Payment Methods', 'plumtree').'</h3>';
		}
	}
	add_action( 'woocommerce_review_order_before_payment', 'pt_payments_heading');

	// Custom chekout fields order output
	if ( ! function_exists( 'pt_default_address_fields' ) ) {
		function pt_default_address_fields( $fields ) {
		    $fields = array(
				'first_name' => array(
					'label'    => __( 'First Name', 'woocommerce' ),
					'required' => true,
					'class'    => array( 'form-row-wide' ),
				),
				'last_name' => array(
					'label'    => __( 'Last Name', 'woocommerce' ),
					'required' => true,
					'class'    => array( 'form-row-wide' ),
					'clear'    => true
				),
				'company' => array(
					'label' => __( 'Company Name', 'woocommerce' ),
					'class' => array( 'form-row-wide' ),
				),
				'address_1' => array(
					'label'       => __( 'Address', 'woocommerce' ),
					'placeholder' => _x( 'Street address', 'placeholder', 'woocommerce' ),
					'required'    => true,
					'class'       => array( 'form-row-wide', 'address-field' )
				),
				'address_2' => array(
					'label'       => __( 'Additional address info', 'woocommerce' ),
					'placeholder' => _x( 'Apartment, suite, unit etc. (optional)', 'placeholder', 'woocommerce' ),
					'class'       => array( 'form-row-wide', 'address-field' ),
					'required'    => false,
					'clear'    	  => true
				),
				'country' => array(
					'type'     => 'country',
					'label'    => __( 'Country', 'woocommerce' ),
					'required' => true,
					'class'    => array( 'form-row-wide', 'address-field', 'update_totals_on_change' ),
				),
				'city' => array(
					'label'       => __( 'Town / City', 'woocommerce' ),
					'placeholder' => __( 'Town / City', 'woocommerce' ),
					'required'    => true,
					'class'       => array( 'form-row-wide', 'address-field' )
				),
				'state' => array(
					'type'        => 'state',
					'label'       => __( 'State / County', 'woocommerce' ),
					'placeholder' => __( 'State / County', 'woocommerce' ),
					'required'    => true,
					'class'       => array( 'form-row-wide', 'address-field' ),
					'validate'    => array( 'state' )
				),
				'postcode' => array(
					'label'       => __( 'Postcode / Zip', 'woocommerce' ),
					'placeholder' => __( 'Postcode / Zip', 'woocommerce' ),
					'required'    => true,
					'class'       => array( 'form-row-wide', 'address-field' ),
					'clear'       => true,
					'validate'    => array( 'postcode' )
				),
			);
			return $fields;
		}
	}
	add_filter( 'woocommerce_default_address_fields' , 'pt_default_address_fields' );


	// ----- 11. Add meta box for activating extra gallery on product hover

	add_action( 'add_meta_boxes', 'pt_product_extra_gallery_metabox' );
	add_action( 'save_post', 'pt_product_extra_gallery_save' );

	if ( ! function_exists( 'pt_product_extra_gallery_metabox' ) ) {
		function pt_product_extra_gallery_metabox() {
		    add_meta_box( 'product_extra_gallery', 'Product Extra Gallery', 'pt_product_extra_gallery_call', 'product', 'side', 'default' );
		}
	}

	if ( ! function_exists( 'pt_product_extra_gallery_call' ) ) {
		function pt_product_extra_gallery_call($post) {
			global $post;
			wp_nonce_field( 'pt_product_extra_gallery_call', 'pt_product_extra_gallery_nonce' );
			// Get previous meta data
			$values = get_post_custom($post->ID);
			$check = isset( $values['pt_product_extra_gallery'] ) ? esc_attr( $values['pt_product_extra_gallery'][0] ) : 'off';
			?>
			<div class="product-extra-gallery">
				<label for="pt_product_extra_gallery"><input type="checkbox" name="pt_product_extra_gallery" id="pt_product_extra_gallery" <?php checked( $check, 'on' ); ?> /><?php _e( 'Use extra gallery for this product', 'plumtree' ) ?></label>
				<p><?php _e( 'Check the checkbox if you want to use extra gallery (appeared on hover) for this product. The first 3 images of the product gallery are going to be used for gallery.', 'plumtree'); ?></p>
			</div>
			<?php
		}
	}

	// When the post is saved, saves our custom data
	if ( ! function_exists( 'pt_product_extra_gallery_save' ) ) {
		function pt_product_extra_gallery_save( $post_id ) {
		    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		            return;

		    if ( ( isset ( $_POST['pt_product_extra_gallery_nonce'] ) ) && ( ! wp_verify_nonce( $_POST['pt_product_extra_gallery_nonce'], 'pt_product_extra_gallery_call' ) ) )
		            return;

		    if ( ! current_user_can( 'edit_post', $post_id ) ) {
		            return;
		    }

		    // OK, we're authenticated: we need to find and save the data
		    $chk = isset( $_POST['pt_product_extra_gallery'] ) && $_POST['pt_product_extra_gallery'] ? 'on' : 'off';
			update_post_meta( $post_id, 'pt_product_extra_gallery', $chk );
		}
	}


	// ----- 12. Add meta box for adding custom Product Badge

	add_action( 'add_meta_boxes', 'pt_product_custom_badge_metabox' );
	add_action( 'save_post', 'pt_product_custom_badge_save' );

	if ( ! function_exists( 'pt_product_custom_badge_metabox' ) ) {
		function pt_product_custom_badge_metabox() {
		    add_meta_box( 'product_custom_badge', 'Product Custom Badge', 'pt_product_custom_badge_call', 'product', 'side', 'default' );
		}
	}
	
	if ( ! function_exists( 'pt_product_custom_badge_call' ) ) {
		function pt_product_custom_badge_call($post) {
			global $post;
			wp_nonce_field( 'pt_product_custom_badge_call', 'pt_product_custom_badge_nonce' );
			// Get previous meta data
			$stored_meta = get_post_meta( $post->ID );
			?>
			<div class="product-custom-badge">
				<p><?php _e( 'This block should be used for adding custom "Badge/Label". Below you can enter your own text for the label & add additional class for further CSS styling', 'plumtree'); ?></p>
			    <p>
			        <label for="custom-badge-text"><?php _e( 'Label Text', 'plumtree' )?></label>
			        <input type="text" name="custom-badge-text" id="custom-badge-text" value="<?php if ( isset ( $stored_meta['custom-badge-text'] ) ) echo esc_attr($stored_meta['custom-badge-text'][0]); ?>" />
			    </p>
			    <p>
			        <label for="custom-badge-class"><?php _e( 'Label Class', 'plumtree' )?></label>
			        <input type="text" name="custom-badge-class" id="custom-badge-class" value="<?php if ( isset ( $stored_meta['custom-badge-class'] ) ) echo esc_attr($stored_meta['custom-badge-class'][0]); ?>" />
			    </p>
			</div>
			<?php
		}
	}

	// When the post is saved, saves our custom data
	if ( ! function_exists( 'pt_product_custom_badge_save' ) ) {
		function pt_product_custom_badge_save( $post_id ) {
		    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		            return;

		    if ( ( isset ( $_POST['pt_product_custom_badge_nonce'] ) ) && ( ! wp_verify_nonce( $_POST['pt_product_custom_badge_nonce'], 'pt_product_custom_badge_call' ) ) )
		            return;

		    if ( ! current_user_can( 'edit_post', $post_id ) ) {
		            return;
		    }

		    // OK, we're authenticated: we need to find and save the data
		    if( isset( $_POST[ 'custom-badge-text' ] ) ) {
				update_post_meta( $post_id, 'custom-badge-text', sanitize_text_field( $_POST[ 'custom-badge-text' ] ) );
			}
		    if( isset( $_POST[ 'custom-badge-class' ] ) ) {
				update_post_meta( $post_id, 'custom-badge-class', sanitize_text_field( $_POST[ 'custom-badge-class' ] ) );
			}
		}
	}


	// ----- 13. Hide WC Vendors "Sold by"
	if ( class_exists('WCV_Vendors') ) {

		function template_loop_sold_by($product_id) {
			$vendor_id     = WCV_Vendors::get_vendor_from_product( $product_id );
			$sold_by = WCV_Vendors::is_vendor( $vendor_id )
				? sprintf( '<a href="%s">%s</a>', WCV_Vendors::get_vendor_shop_page( $vendor_id ), WCV_Vendors::get_vendor_sold_by( $vendor_id ) )
				: get_bloginfo( 'name' );
			echo '<small class="wcvendors_sold_by_in_loop">' . apply_filters('wcvendors_sold_by_in_loop', __( 'Sold by:<br/>', 'plumtree' )). $sold_by . '</small>';
		}

		remove_action( 'woocommerce_after_shop_loop_item', array('WCV_Vendor_Shop', 'template_loop_sold_by'), 9 );

		// Uncomment the line below to show sold by message.
		//add_action( 'woocommerce_after_shop_loop_item_title', 'template_loop_sold_by', 15 );

	}


	// ----- 14. Catalog Mode Function
	if (get_option('catalog_mode') == 'on') {
		remove_action( 'woocommerce_after_shop_loop_item_title', 'pt_output_variables', 15 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 15 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	}


	// ----- 15. Variables Products fix
	// Display Price For Variable Product With Same Variations Prices
	add_filter('woocommerce_available_variation', 'pt_variables_price_fix', 10, 3);
	
	if ( ! function_exists( 'pt_variables_price_fix' ) ) {
		function pt_variables_price_fix( $value, $object = null, $variation = null ) {
			if ($value['price_html'] == '') {
				$value['price_html'] = '<span class="price">' . $variation->get_price_html() . '</span>';
			}
			return $value;
		}
	}

} // end of file