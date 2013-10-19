<?php
/**
 * Plugin Name: Woocommerce Product Shipping
 * Plugin URI: www.dreamfox.nl 
 * Version: 1.0.4
 * Author: Marco van Loghum
 * Author URI: www.dreamfox.nl 
 * Description: Extend Woocommerce plugin to add shipping methods to a product
 * Requires at least: 3.5
 * Tested up to: 3.7
 */
require_once ABSPATH . WPINC . '/pluggable.php';;
require_once dirname(dirname(__FILE__)).'/woocommerce/classes/class-wc-payment-gateways.php';
require_once dirname(dirname(__FILE__)).'/woocommerce/classes/class-wc-cart.php';require_once dirname(dirname(__FILE__)).'/woocommerce/classes/class-wc-shipping.php';



add_action( 'add_meta_boxes', 'ship_meta_box_add', 50 );  
function ship_meta_box_add()  
{  
    add_meta_box( 'shipping', 'Shipping', 'shipping_form', 'product', 'side', 'core' ); 
}




function shipping_form()  
{
	global $post, $woocommerce;
	
	$productIds = get_option('woocommerce_product_apply_ship');
	
	if($productIds)
		foreach($productIds as $key=>$product)
			if(!wp_get_single_post($product))
				unset($productIds[$key]);
	update_option('woocommerce_product_apply_ship', $productIds);
	
	
	if(count($productIds)>=10&&!in_array($post->ID, $productIds)){
		echo 'Please download full version package!';
		return;
	}
	
	
	$postShippings = get_metadata('post', $post->ID, 'shipping', false) ;
	if($woocommerce->shipping->load_shipping_methods())
		foreach($woocommerce->shipping->load_shipping_methods() as $key=>$method){
			if($method->enabled=='yes')
				$shippings[$key] = $method;
		}
	foreach($shippings as $ship){
	$checked = '';
	if(in_array($ship->id, $postShippings)) $checked = ' checked="yes" ';
    ?>  
    <input type="checkbox" <?php echo $checked; ?> value="<?php echo $ship->id; ?>" name="ship[]" />
    <label for="my_meta_box_text"><?php echo $ship->title; ?></label>  
    <br />  
    <?php }      
} 

add_action('save_post', 'ship_meta_box_save', 10, 2 );
function ship_meta_box_save( $post_id )  
{ 
	if(!is_admin()) return;   
	if($_POST['post_type']=='product'){
	
	
	$productIds = get_option('woocommerce_product_apply_ship');
	if(!in_array($post_id, $productIds)&&count($productIds)<=10){
		$productIds[] = $post_id;
		update_option('woocommerce_product_apply_ship', $productIds);
	}
	
	
	
	delete_post_meta($post_id, 'shipping');	
	if($_POST['ship'])
		foreach($_POST['ship'] as $ship)
    		add_post_meta($post_id, 'shipping', $ship); 
	}
}



function shipping_method_disable_country( $available_methods ) {
	global $woocommerce;
	
	
	
	$arrayKeys = array_keys($available_methods);
	$items = $woocommerce->cart->cart_contents;
	$itemsShips = '';
	if($items)
		foreach($items as $item){
		$itemsShips[] = get_post_meta($item['product_id'], 'shipping', false);
		}
		


		

	if($itemsShips)
		foreach($itemsShips as $objs){
		if(count($objs))
			foreach($arrayKeys as $key){
				if(!in_array($key, $objs))
					unset($available_methods[$key]);	
			}

		}		

	return $available_methods;
	
	
}
add_filter( 'woocommerce_available_shipping_methods', 'shipping_method_disable_country' );





