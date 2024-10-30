<?php
/*
Plugin Name: Metapic Advertiser
Plugin URI: https://metapic.com/en/
Description: Are you an advertiser or publisher and want to grow your business? Are you looking for leading performance marketing and technology solutions powered by a unique network of connections? Find out how we can help you grow your sales and reach your perfect customer.
Author: Metapic
Version: 1.0.3
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) {
    exit;
}
define('METAPIC_URI', WP_PLUGIN_URL . '/metapic-advertiser');
define('METAPIC_DIR', WP_PLUGIN_DIR . '/metapic-advertiser');
define('METAPIC_PLUG_VER', '3');
//Load class
require 'src/MetapicRouting/MetapicRouting.php';
require 'src/MetapicViews/MetapicViews.php';
require 'src/MetapicActions/MetapicActions.php';
require 'src/MetapicAPI/MetapicAPI.php';

add_action('init', function(){});

MetapicActions::init();

add_action('admin_menu', function () {
    add_menu_page('Affiliates', 'Metapic',
        'manage_options', 'metapic', 'metapicPage');
});

function metapicPage()
{
    if ( ! session_id() && !headers_sent()) {
        session_start();
    }
    echo '<div id="metapicApp" style="margin-left:-20px;min-height: 100vh;">';
    MetapicRouting::routing();
    echo '</div>';
    echo '<style>#wpfooter{display:none;} #wpbody-content{padding-bottom: 0;}</style>';
}

add_filter( 'woocommerce_default_address_fields' , 'njengah_disable_postcode_validation' );

function njengah_disable_postcode_validation( $address_fields ) {
    $address_fields['postcode']['required'] = false;
    return $address_fields;
}
