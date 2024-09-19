<?php
// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue styles for the admin panel
add_action('admin_enqueue_scripts', 'enqueue_cpcpref_crypto_gainer_styles');
function enqueue_cpcpref_crypto_gainer_styles($hook) {
    if ($hook !== 'toplevel_page_cpcpref-crypto-gainer') {
        return;
    }
    wp_enqueue_style('cpcpref-crypto-gainer-admin-css', plugins_url('includes/css/crypto-gainer.css', __FILE__), array(), '1.0.0');
}

// Function to display the settings page
function cpcpref_crypto_gainer_settings_page() {
    ?>
    <div class="wrap">
        <div class="cpt-crypto-settings-container">  
            <div class="cpt-crypto-settings-box">

                <h2 ><?php esc_html_e('Main Settings', 'cpcpref-crypto-gainer'); ?></h2>
                <form method="post" action="options.php">
                    <?php settings_fields('cpcpref_crypto_gainer_settings_group'); ?>
                    
                    <div class="cpcpref-settings-container">
                        <?php
                        do_settings_sections('cpcpref_setting_group_1'); 
                        do_settings_sections('cpcpref_setting_group_2'); 
                        ?>
                    </div>
                    
                    <div style="display: flex; justify-content: center;">
                        <?php submit_button(__('Generate Shortcode & Preview', 'cpcpref-crypto-gainer')); ?>
                    </div>
                </form>

                <!-- Editor for shortcode -->
                <br>
                <div class="cpcpref-settings-container" style="border: 1px solid grey; padding: 10px;">
                    <span>
                        <h3> Shortcode </h3>
                        <textarea readonly rows="5" cols="100%" style="resize:none; background-color: white;"><?php  echo esc_textarea(cpcpref_crypto_preview()); ?></textarea>
                    </span>
                    <span>
                        <h3> Usage </h3> 
                        1 - Configure your plugin settings.<br>
                        2 - Click on generate Shortcode & previw .<br>
                        3 - Copy the shortcode and paste it into any page or post.<br>
                        4 - Enjoy!<br><br><br>
                    </span>
                </div>

                <!-- Shortcode preview -->
                <br>
                <h2 ><?php esc_html_e('Preview', 'cpcpref-crypto-gainer'); ?></h2><br>
                <?php echo do_shortcode(cpcpref_crypto_preview()); ?>
            </div>
        </div>
    </div>
    <?php
}

// Function to generate shortcode preview
function cpcpref_crypto_preview() {
    $show_gainers = get_option('cpcpref_crypto_gainer_gainers', true);
    $show_losers = get_option('cpcpref_crypto_gainer_losers', true);
    $show_credits = get_option('cpcpref_crypto_gainer_credits', false); 
    $text_color = get_option('cpcpref_crypto_gainer_text_color', '#34495e');
    $box_color = get_option('cpcpref_crypto_gainer_box_color', '#fff');
    $box_width = get_option('cpcpref_crypto_gainer_box_width', 600); 
    $item_padding = get_option('cpcpref_crypto_gainer_item_padding', 10);
    $max_items = get_option('cpcpref_crypto_gainer_max_items', 5);
    
    return '[cpcpref_crypto show_gainers="' . esc_attr($show_gainers) . '" show_losers="' . esc_attr($show_losers) . '" show_credits="' . esc_attr($show_credits) . '" box_color="' . esc_attr($box_color) . '" text_color="' . esc_attr($text_color) . '" box_width="' . esc_attr($box_width) . '" item_padding="' . esc_attr($item_padding) . '" max_items="' . esc_attr($max_items) . '"]';
}



// Function to register settings
function cpcpref_crypto_gainer_register_settings() {
    register_setting('cpcpref_crypto_gainer_settings_group', 'cpcpref_crypto_gainer_gainers', 'intval');
    register_setting('cpcpref_crypto_gainer_settings_group', 'cpcpref_crypto_gainer_losers', 'intval'); 
    register_setting('cpcpref_crypto_gainer_settings_group', 'cpcpref_crypto_gainer_credits', 'intval');
    register_setting('cpcpref_crypto_gainer_settings_group', 'cpcpref_crypto_gainer_max_items', 'intval');
    register_setting('cpcpref_crypto_gainer_settings_group', 'cpcpref_crypto_gainer_text_color', 'sanitize_hex_color');
    register_setting('cpcpref_crypto_gainer_settings_group', 'cpcpref_crypto_gainer_box_color', 'sanitize_hex_color');
    register_setting('cpcpref_crypto_gainer_settings_group', 'cpcpref_crypto_gainer_box_width', 'intval');
    register_setting('cpcpref_crypto_gainer_settings_group', 'cpcpref_crypto_gainer_item_padding', 'intval');
    
    // SETTINGS GROUP 1
    add_settings_section(
        'cpcpref_crypto_gainer_main_section',
        '',
        'cpcpref_crypto_gainer_main_section_cb',
        'cpcpref_setting_group_1'
    );

    add_settings_field(
        'cpcpref_crypto_gainer_gainers',
        __('Show Top Gainers', 'cpcpref-crypto-gainer'),
        'cpcpref_crypto_gainer_gainers_cb',
        'cpcpref_setting_group_1',
        'cpcpref_crypto_gainer_main_section'
    );

    add_settings_field(
        'cpcpref_crypto_gainer_losers',
        __('Show Top Losers', 'cpcpref-crypto-gainer'),
        'cpcpref_crypto_gainer_losers_cb',
        'cpcpref_setting_group_1',
        'cpcpref_crypto_gainer_main_section'
    );

    add_settings_field(
        'cpcpref_crypto_gainer_credits',
        __('Show Credits', 'cpcpref-crypto-gainer'),
        'cpcpref_crypto_gainer_credits_cb',
        'cpcpref_setting_group_1',
        'cpcpref_crypto_gainer_main_section'
    );

    add_settings_field(
        'cpcpref_crypto_gainer_text_color',
        __('Text Color', 'cpcpref-crypto-gainer'),
        'cpcpref_crypto_gainer_text_color_field_cb',
        'cpcpref_setting_group_1',
        'cpcpref_crypto_gainer_main_section'
    );

    add_settings_field(
        'cpcpref_crypto_gainer_box_color',
        __('Box Color', 'cpcpref-crypto-gainer'),
        'cpcpref_crypto_gainer_box_color_field_cb',
        'cpcpref_setting_group_1',
        'cpcpref_crypto_gainer_main_section'
    );


    // SETTINGS GROUP 2
    add_settings_section(
        'cpcpref_crypto_gainer_main_section',
        '',
        'cpcpref_crypto_gainer_main_section_cb',
        'cpcpref_setting_group_2'
    );

    add_settings_field(
        'cpcpref_crypto_gainer_max_items',
        __('Coins to show', 'cpcpref-crypto-gainer'),
        'cpcpref_crypto_gainer_max_items_cb',
        'cpcpref_setting_group_2',
        'cpcpref_crypto_gainer_main_section'
    );

    add_settings_field(
        'cpcpref_crypto_gainer_box_width',
        __('Max Width (px)', 'cpcpref-crypto-gainer'),
        'cpcpref_crypto_gainer_box_width_cb',
        'cpcpref_setting_group_2',
        'cpcpref_crypto_gainer_main_section'
    );

    add_settings_field(
        'cpcpref_crypto_gainer_item_padding',
        __('Items padding (px)', 'cpcpref-crypto-gainer'),
        'cpcpref_crypto_gainer_item_padding_cb',
        'cpcpref_setting_group_2',
        'cpcpref_crypto_gainer_main_section'
    );

}

// Section callback
function cpcpref_crypto_gainer_main_section_cb() {
    // NOTHING TO DO HERE - CAN BE USED LATER FOR MESSAGE DISPLAY
}

// Callback function for Show Gainers checkbox
function cpcpref_crypto_gainer_gainers_cb() {
    $show_marketcap = get_option('cpcpref_crypto_gainer_gainers', true); // Default to true if not set
    echo '<input type="checkbox" id="cpcpref_crypto_gainer_gainers" name="cpcpref_crypto_gainer_gainers" value="1" ' . checked(1, $show_marketcap, false) . '>';
    echo '<label for="cpcpref_crypto_gainer_gainers">' . esc_html__(' Show Top Gainers', 'cpcpref-crypto-gainer') . '</label>';
}

// Callback function for Show Losers checkbox
function cpcpref_crypto_gainer_losers_cb() {
    $show_marketcap = get_option('cpcpref_crypto_gainer_losers', true); // Default to true if not set
    echo '<input type="checkbox" id="cpcpref_crypto_gainer_losers" name="cpcpref_crypto_gainer_losers" value="1" ' . checked(1, $show_marketcap, false) . '>';
    echo '<label for="cpcpref_crypto_gainer_losers">' . esc_html__(' Show Top Losers', 'cpcpref-crypto-gainer') . '</label>';
}

// Callback function for Show Credits checkbox
function cpcpref_crypto_gainer_credits_cb() {
    $show_credits = get_option('cpcpref_crypto_gainer_credits', false); // Default to false if not set
    echo '<input type="checkbox" id="cpcpref_crypto_gainer_credits" name="cpcpref_crypto_gainer_credits" value="1" ' . checked(1, $show_credits, false) . '>';
    echo '<label for="cpcpref_crypto_gainer_credits">' . esc_html__(' Show Credits', 'cpcpref-crypto-gainer') . '</label>';
}

// Callback function for max coins input field
function cpcpref_crypto_gainer_max_items_cb() {
    $max_items = get_option('cpcpref_crypto_gainer_max_items', 5); // Default value is 5
    echo '<input type="number" id="cpcpref_crypto_gainer_max_items" name="cpcpref_crypto_gainer_max_items" value="' . esc_attr($max_items) . '">';
}

// Callback function for the text color field
function cpcpref_crypto_gainer_text_color_field_cb() {
    $text_color = get_option('cpcpref_crypto_gainer_text_color', '#34495e'); // Default to black if not set
    echo '<input type="text" id="cpcpref_crypto_gainer_text_color" name="cpcpref_crypto_gainer_text_color" value="' . esc_attr($text_color) . '" class="color-field">';
}

// Callback function for the box color field
function cpcpref_crypto_gainer_box_color_field_cb() {
    $box_color = get_option('cpcpref_crypto_gainer_box_color', '#fff'); // Default to white if not set
    echo '<input type="text" id="cpcpref_crypto_gainer_box_color" name="cpcpref_crypto_gainer_box_color" value="' . esc_attr($box_color) . '" class="color-field">';
}

// Callback function for box_width input field
function cpcpref_crypto_gainer_box_width_cb() {
    $box_width = get_option('cpcpref_crypto_gainer_box_width', 600); // Default value is 600
    echo '<input type="number" id="cpcpref_crypto_gainer_box_width" name="cpcpref_crypto_gainer_box_width" value="' . esc_attr($box_width) . '">';
}

// Callback function for item_padding input field
function cpcpref_crypto_gainer_item_padding_cb() {
    $item_padding = get_option('cpcpref_crypto_gainer_item_padding', 10); // Default value is 10
    echo '<input type="number" id="cpcpref_crypto_gainer_item_padding" name="cpcpref_crypto_gainer_item_padding" value="' . esc_attr($item_padding) . '">';
}




// Enqueue Select2 and color picker script and style
function cpcpref_crypto_gainer_enqueue_scripts() {
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('cpcpref-crypto-gainer-color-picker', plugins_url('includes/js/cpcpref-crypto-gainer-color-picker.js', __FILE__), array('wp-color-picker'), '1.0.0', true);

    // Enqueue Select2
    wp_enqueue_style('select2', plugin_dir_url(__FILE__) . 'includes/css/select2.css', array(), '1.0.0');
    wp_enqueue_script('select2', plugins_url('includes/js/select2.js', __FILE__), array('jquery'), '1.0.0', true);

    // Initialize Select2 and Color Picker
    wp_add_inline_script('select2', 'jQuery(document).ready(function($) { $("#crypto_price_table_coins").select2(); $(".color-field").wpColorPicker(); });');
}
add_action('admin_enqueue_scripts', 'cpcpref_crypto_gainer_enqueue_scripts');



// Hook into admin_init to register settings
add_action('admin_init', 'cpcpref_crypto_gainer_register_settings');
?>