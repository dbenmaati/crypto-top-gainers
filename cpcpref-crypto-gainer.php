<?php

/**
 * @version 1.0.0
 */

/*
Plugin Name: Crypto Top Gainers & Losers
Description: Customizable Cryptocurrency Price Table with real-time price update, marketcap and flexible settings. 
Version: 1.0.0
Author: icogems
Author URI: https://icogems.com
License: GPLv2 or later
Text Domain: cpcpref-crypto-gainer
*/

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Include the settings page file
require_once(plugin_dir_path(__FILE__) . 'cpcpref-crypto-gainer-admin.php');


// Enqueue styles for the table
function cpcpref_crypto_gainer_enqueue_styles() {
    if (!is_singular()) { 
        return; 
    }
    wp_enqueue_style('cpcpref-crypto-gainer-style', plugin_dir_url(__FILE__) . 'includes/css/crypto-gainer.css', array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'cpcpref_crypto_gainer_enqueue_styles');



// Shortcode function to display the TOP GAINER
function cpcpref_crypto_shortcode($attr) {

    wp_enqueue_script('cpcpref-crypto-gainer-script', plugin_dir_url(__FILE__) . 'includes/js/cpcpref-crypto-gainer.js', array('jquery'), '1.0.0', true);
    wp_localize_script('cpcpref-crypto-gainer-script', 'CpcprefCryptoGainer', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('crypto-price-table-pro-nonce'),
        'img_url' => plugin_dir_url(__FILE__) . 'includes/img/',

        'text_color' => $attr['text_color'],
        'item_padding' => $attr['item_padding'],
        'max_items' => $attr['max_items'],
    ));


    $show_gainers = esc_attr($attr['show_gainers']);
    $show_losers = esc_attr($attr['show_losers']);
    $show_credits = esc_attr($attr['show_credits']);
    $box_color = esc_attr($attr['box_color']);
    $text_color = esc_attr($attr['text_color']);
    $box_width = esc_attr($attr['box_width']);
    $item_padding = esc_attr($attr['item_padding']);


    $output = '<div class="cpcpref-crypto-container">';
    if ($show_gainers == 1) {
        // Top gainers will be inserted here dynamically 
        $output .= '<div class="cpcpref-section" style="max-width: ' . $box_width . 'px; background: ' . $box_color . ';">';
        $output .= '<div class="cpcpref-section-header">';
        $output .= '<span class="cpcpref-section-title" style="color: ' . $text_color . ';">Top Gainers</span>';
        $output .= '<a href="https://icogems.com" target="_blank" rel="nofollow" class="cpcpref-details-link">More Crypto Details</a>';
        $output .= '</div>';
        $output .= '<ul class="cpcpref-crypto-list" id="cpcpref-top-gainers">';
        $output .= '</ul>';
        if ($show_credits == 1) { $output .= '<br><div style="text-align: right; font-size: 12px;"> Powered By <a href="https://icogems.com" target="_blank" rel="nofollow" style="color: orange; text-decoration: none;">ICOGEMS</a> </div>'; }
        $output .= '</div>';
    }
    if ($show_losers == 1) {
        // Top Losers will be inserted here dynamically 
        $output .= '<div class="cpcpref-section" style="max-width: ' . $box_width . 'px; background: ' . $box_color . ';">';
        $output .= '<div class="cpcpref-section-header">';
        $output .= '<span class="cpcpref-section-title" style="color: ' . $text_color . ';">Top Losers</span>';
        $output .= '<a href="https://icogems.com" target="_blank" rel="nofollow" class="cpcpref-details-link">More Crypto Details</a>';
        $output .= '</div>';
        $output .= '<ul class="cpcpref-crypto-list" id="cpcpref-top-losers">';
        $output .= '</ul>';
        if ($show_credits == 1) { $output .= '<br><div style="text-align: right; font-size: 12px;"> Powered By <a href="https://icogems.com" target="_blank" rel="nofollow" style="color: orange; text-decoration: none;">ICOGEMS</a> </div>'; }
        $output .= '</div>';
    }
    $output .= '</div>';
    
    return $output;
}
add_shortcode('cpcpref_crypto', 'cpcpref_crypto_shortcode');


// Function to register admin menu
function cpcpref_crypto_gainer_admin_menu() {
    add_menu_page(
        'Crypto Gainer/Loser Settings',
        'Crypto Gainer/Loser',
        'manage_options',
        'cpcpref-crypto-gainer',
        'cpcpref_crypto_gainer_settings_page',
        'dashicons-chart-line',
        100
    );
}
add_action('admin_menu', 'cpcpref_crypto_gainer_admin_menu');