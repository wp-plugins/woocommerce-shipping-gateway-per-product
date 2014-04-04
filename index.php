<?php
/**
 * Plugin Name: Woocommerce Product Shippings
 * Plugin URI: www.dreamfox.nl 
 * Version: 1.1.5
 * Author: Marco van Loghum
 * Author URI: www.dreamfox.nl 
 * Description: Extend Woocommerce plugin to add shipping methods to a product
 * Requires at least: 3.5
 * Tested up to: 3.8.1
 */

add_action( 'add_meta_boxes', 'wps_ship_meta_box_add', 50 );  
function wps_ship_meta_box_add() {  
    add_meta_box( 'shippings', 'Shippings', 'wps_shipping_form', 'product', 'side', 'core' ); 
}

function wps_shipping_form()  
{
	global $post, $woocommerce;
	$productIds = get_option('woocommerce_product_apply_ship');
	$postShippings = count ( get_post_meta($post->ID, 'shippings', true) ) ? get_post_meta($post->ID, 'shippings', true) : array() ;
	if( is_array( $productIds ) ){
		foreach( $productIds as $key => $product ){
			if( !get_post( $product ) || !count( get_post_meta( $product, 'shippings', true ) )  )
				unset( $productIds[$key] );
		}
	}
	update_option('woocommerce_product_apply_ship', $productIds);
	$productIds = get_option('woocommerce_product_apply_ship');
	
	if(count($productIds)>=10 && !count ( get_post_meta($post->ID, 'shippings', true) ) ){
		echo 'Please download full version package!';
		return;
	}
	
	
	if($woocommerce->shipping->load_shipping_methods())
		foreach($woocommerce->shipping->load_shipping_methods() as $key=>$method){
			if($method->enabled=='yes')
				$shippings[$key] = $method;
		}
		
	foreach($shippings as $ship){
		$checked = '';
		if( is_array( $postShippings) && in_array($ship->id, $postShippings)) $checked = ' checked="checked" '; ?>  
		<input type="checkbox" <?php echo $checked; ?> value="<?php echo $ship->id; ?>" name="ship[]" id="ship_<?php echo $ship->id ?>" />
		<label for="ship_<?php echo $ship->id ?>"><?php echo $ship->title; ?></label>  
		<br />  
		<?php
	 }      
} 

add_action('save_post', 'wps_ship_meta_box_save', 10, 2 );
function wps_ship_meta_box_save( $post_id, $post ) {  
	// Restrict to save for autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
	 	return $post_id;

	// Restrict to save for revisions
   	if ( isset( $post->post_type ) && $post->post_type == 'revision' )
      return $post_id;
	
	if($_POST['post_type']=='product'){
		$productIds = get_option('woocommerce_product_apply_ship');
		if( !in_array( $post_id, $productIds ) ){
			$productIds[] = $post_id;
			update_option('woocommerce_product_apply_ship', $productIds);
		}
		//delete_post_meta($post_id, 'shippings');
		$shippings = array();
		if($_POST['ship']){
			foreach($_POST['ship'] as $ship)
				$shippings[] =  $ship;
		}
		if( count( $shippings ) )
			update_post_meta($post_id, 'shippings', $shippings);
		else
			delete_post_meta($post_id, 'shippings');
	}
}

function wps_shipping_method_disable_country( $available_methods ) {
	global $woocommerce;
	$arrayKeys = array_keys($available_methods);
	if( count(  $woocommerce->cart ) ){
		$items = $woocommerce->cart->cart_contents;
		$itemsShips = '';
		if( is_array( $items ) ){
			foreach($items as $item){
				$itemsShips = get_post_meta($item['product_id'], 'shippings', true);
				if(is_array($itemsShips)){
					foreach($arrayKeys as $key){
						$method_id = $available_methods[$key]->method_id;
                        if( !empty( $method_id ) && !in_array( $method_id ,$itemsShips) ){
							unset($available_methods[$key]);
					    }
					}
				}
			}		
		}
	}
	return $available_methods;		
}
add_filter( 'woocommerce_available_shipping_methods', 'wps_shipping_method_disable_country', 99 );

function update_user_database(){
	$is_shipping_updated	=	get_option( 'is_shipping_updated' );
	if( !$is_shipping_updated ){
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'product',
			'fields' => 'ids'		 
		);
		$products = get_posts( $args );
		foreach( $products as $pro_id ){
			$itemsShips = get_post_meta( $pro_id, 'shippings', true);
			if( empty( $itemsShips ) ){print_r( $pro_id ); delete_post_meta($pro_id, 'shippings');}
		}
		update_option( 'is_shipping_updated', true );
	}
}
add_action('wp_head', 'update_user_database');

?>