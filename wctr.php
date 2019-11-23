<?php

/**
 * WooCommerce Coupon For Tax Removal
 *
 * @link              https://github.com/projoomexperts/Woocommerce-Coupon-Tax-Removal/
 * @since             1.0.3
 * @package           Wctr
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Coupon For Tax Removal
 * Plugin URI:        https://github.com/projoomexperts/Woocommerce-Coupon-Tax-Removal/
 * Description:       This plugin allows woocommerce coupons to remove tax from cart.
 * Version:           1.0.3
 * Author:            ProJoomExperts
 * Author URI:        https://www.freelancer.com/u/projoomexperts
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wctr
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


add_action( 'woocommerce_cart_calculate_fees', 'check_coupon_code' );
function check_coupon_code( $cart ) {
    global $woocommerce;
    $woocommerce->customer->set_is_vat_exempt( false );
    if($cart->get_applied_coupons()){
	    $coupons = $cart->get_applied_coupons();
	    foreach($coupons as $coupon){
		$coupon_detail = new WC_Coupon($coupon);	
		$taxrule = $coupon_detail->get_meta('remove_tax');
		if( $taxrule == 'yes'){
			$woocommerce->customer->set_is_vat_exempt( true );
		}
	    }
    }
}



function add_coupon_tax_checkbox() { 
    woocommerce_wp_checkbox( array( 'id' => 'remove_tax', 'label' => __( 'Remove Tax', 'woocommerce' ), 'description' => sprintf( __( 'Check this box if you want this coupon to remove all taxes', 'woocommerce' ) ) ) );
}
add_action( 'woocommerce_coupon_options', 'add_coupon_tax_checkbox', 10, 0 );


function save_coupon_tax_checkbox( $post_id ) {
    $remove_tax = isset( $_POST['remove_tax'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, 'remove_tax', $remove_tax );
}
add_action( 'woocommerce_coupon_options_save', 'save_coupon_tax_checkbox');










