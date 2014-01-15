<?php
/**
 * Plugin Name: Woocommerce Product Shippings
 * Plugin URI: www.dreamfox.nl 
 * Version: 1.0.7
 * Author: Marco van Loghum
 * Author URI: www.dreamfox.nl 
 * Description: Extend Woocommerce plugin to add shipping methods to a product
 * Requires at least: 3.5
 * Tested up to: 3.8
 */
require_once ABSPATH . WPINC . '/pluggable.php';;
require_once dirname(dirname(__FILE__)).'/woocommerce/classes/class-wc-payment-gateways.php';
require_once dirname(dirname(__FILE__)).'/woocommerce/classes/class-wc-cart.php';
require_once dirname(dirname(__FILE__)).'/woocommerce/classes/class-wc-shipping.php';

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
			if( !wp_get_single_post( $product ) || !count( get_post_meta( $product, 'shippings', true ) )  )
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
function wps_ship_meta_box_save( $post_id ) {  
	if(!is_admin()) return;   
	
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
		update_post_meta($post_id, 'shippings', $shippings); 
	}
}

function wps_shipping_method_disable_country( $available_methods ) {
	global $woocommerce;

	$arrayKeys = array_keys($available_methods);
	$items = $woocommerce->cart->cart_contents;
	$itemsShips = '';
	if($items){
		foreach($items as $item){
			$itemsShips = get_post_meta($item['product_id'], 'shippings', true);
			if(count($itemsShips)){
				foreach($arrayKeys as $key){
					if( is_array( $itemsShips ) && !in_array($key, $itemsShips))
						unset($available_methods[$key]);	
				}
			}
		}		
	}
		
	return $available_methods;
		
}
add_filter( 'woocommerce_available_shipping_methods', 'wps_shipping_method_disable_country' );
?>