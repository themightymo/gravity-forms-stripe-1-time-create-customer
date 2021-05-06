<?php

/**
 * Plugin Name: Gravity Forms Stripe 1-time Purchase Create Customer
 * Plugin URI: https://themightymo.com/
 * Description: Stripe customer email address fixer
 * Version: 1.0
 * Text Domain: 
 * Author: The Mighty Mo! WordPress Design
 * Author URI: https://themightymo.com/
 */

add_filter( 'gform_stripe_customer_id', function ( $customer_id, $feed, $entry, $form ) {
    GFCommon::log_debug( __METHOD__ . '(): running.' );
    if ( rgars( $feed, 'meta/transactionType' ) == 'product' && rgars( $feed, 'meta/feedName' ) == '1-time' ) {
        GFCommon::log_debug( __METHOD__ . '(): Working for feed ' . rgars( $feed, 'meta/feedName' ) );
        $customer_meta = array();
        
        $email_field = rgars( $feed, 'meta/receipt_field' );
        if ( ! empty( $email_field ) && strtolower( $email_field ) !== 'do not send receipt' ) {
            $customer_meta['email'] = gf_stripe()->get_field_value( $form, $entry, $email_field );
        }

        $customer = gf_stripe()->create_customer( $customer_meta, $feed, $entry, $form );
        //GFCommon::log_debug( __METHOD__ . '(): Returning Customer ID ' . $customer->email );
 
        return $customer->id;
    }
 
    return $customer_id;
}, 10, 4 );