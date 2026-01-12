<?php

/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('HELLO_ELEMENTOR_VERSION', '3.4.5');
define('EHP_THEME_SLUG', 'hello-elementor');

define('HELLO_THEME_PATH', get_template_directory());
define('HELLO_THEME_URL', get_template_directory_uri());
define('HELLO_THEME_ASSETS_PATH', HELLO_THEME_PATH . '/assets/');
define('HELLO_THEME_ASSETS_URL', HELLO_THEME_URL . '/assets/');
define('HELLO_THEME_SCRIPTS_PATH', HELLO_THEME_ASSETS_PATH . 'js/');
define('HELLO_THEME_SCRIPTS_URL', HELLO_THEME_ASSETS_URL . 'js/');
define('HELLO_THEME_STYLE_PATH', HELLO_THEME_ASSETS_PATH . 'css/');
define('HELLO_THEME_STYLE_URL', HELLO_THEME_ASSETS_URL . 'css/');
define('HELLO_THEME_IMAGES_PATH', HELLO_THEME_ASSETS_PATH . 'images/');
define('HELLO_THEME_IMAGES_URL', HELLO_THEME_ASSETS_URL . 'images/');

if (! isset($content_width)) {
    $content_width = 800; // Pixels.
}

if (! function_exists('hello_elementor_setup')) {
    /**
     * Set up theme support.
     *
     * @return void
     */
    function hello_elementor_setup()
    {
        if (is_admin()) {
            hello_maybe_update_theme_version_in_db();
        }

        if (apply_filters('hello_elementor_register_menus', true)) {
            register_nav_menus(['menu-1' => esc_html__('Header', 'hello-elementor')]);
            register_nav_menus(['menu-2' => esc_html__('Footer', 'hello-elementor')]);
        }

        if (apply_filters('hello_elementor_post_type_support', true)) {
            add_post_type_support('page', 'excerpt');
        }

        if (apply_filters('hello_elementor_add_theme_support', true)) {
            add_theme_support('post-thumbnails');
            add_theme_support('automatic-feed-links');
            add_theme_support('title-tag');
            add_theme_support(
                'html5',
                [
                    'search-form',
                    'comment-form',
                    'comment-list',
                    'gallery',
                    'caption',
                    'script',
                    'style',
                    'navigation-widgets',
                ]
            );
            add_theme_support(
                'custom-logo',
                [
                    'height'      => 100,
                    'width'       => 350,
                    'flex-height' => true,
                    'flex-width'  => true,
                ]
            );
            add_theme_support('align-wide');
            add_theme_support('responsive-embeds');

            /*
			 * Editor Styles
			 */
            add_theme_support('editor-styles');
            add_editor_style('assets/css/editor-styles.css');

            /*
			 * WooCommerce.
			 */
            if (apply_filters('hello_elementor_add_woocommerce_support', true)) {
                // WooCommerce in general.
                add_theme_support('woocommerce');
                // Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
                // zoom.
                add_theme_support('wc-product-gallery-zoom');
                // lightbox.
                add_theme_support('wc-product-gallery-lightbox');
                // swipe.
                add_theme_support('wc-product-gallery-slider');
            }
        }
    }
}
add_action('after_setup_theme', 'hello_elementor_setup');

function hello_maybe_update_theme_version_in_db()
{
    $theme_version_option_name = 'hello_theme_version';
    // The theme version saved in the database.
    $hello_theme_db_version = get_option($theme_version_option_name);

    // If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
    if (! $hello_theme_db_version || version_compare($hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<')) {
        update_option($theme_version_option_name, HELLO_ELEMENTOR_VERSION);
    }
}

if (! function_exists('hello_elementor_display_header_footer')) {
    /**
     * Check whether to display header footer.
     *
     * @return bool
     */
    function hello_elementor_display_header_footer()
    {
        $hello_elementor_header_footer = true;

        return apply_filters('hello_elementor_header_footer', $hello_elementor_header_footer);
    }
}

if (! function_exists('hello_elementor_scripts_styles')) {
    /**
     * Theme Scripts & Styles.
     *
     * @return void
     */
    function hello_elementor_scripts_styles()
    {
        if (apply_filters('hello_elementor_enqueue_style', true)) {
            wp_enqueue_style(
                'hello-elementor',
                HELLO_THEME_STYLE_URL . 'reset.css',
                [],
                HELLO_ELEMENTOR_VERSION
            );
        }

        if (apply_filters('hello_elementor_enqueue_theme_style', true)) {
            wp_enqueue_style(
                'hello-elementor-theme-style',
                HELLO_THEME_STYLE_URL . 'theme.css',
                [],
                HELLO_ELEMENTOR_VERSION
            );
        }

        if (hello_elementor_display_header_footer()) {
            wp_enqueue_style(
                'hello-elementor-header-footer',
                HELLO_THEME_STYLE_URL . 'header-footer.css',
                [],
                HELLO_ELEMENTOR_VERSION
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'hello_elementor_scripts_styles');

if (! function_exists('hello_elementor_register_elementor_locations')) {
    /**
     * Register Elementor Locations.
     *
     * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
     *
     * @return void
     */
    function hello_elementor_register_elementor_locations($elementor_theme_manager)
    {
        if (apply_filters('hello_elementor_register_elementor_locations', true)) {
            $elementor_theme_manager->register_all_core_location();
        }
    }
}
add_action('elementor/theme/register_locations', 'hello_elementor_register_elementor_locations');

if (! function_exists('hello_elementor_content_width')) {
    /**
     * Set default content width.
     *
     * @return void
     */
    function hello_elementor_content_width()
    {
        $GLOBALS['content_width'] = apply_filters('hello_elementor_content_width', 800);
    }
}
add_action('after_setup_theme', 'hello_elementor_content_width', 0);

if (! function_exists('hello_elementor_add_description_meta_tag')) {
    /**
     * Add description meta tag with excerpt text.
     *
     * @return void
     */
    function hello_elementor_add_description_meta_tag()
    {
        if (! apply_filters('hello_elementor_description_meta_tag', true)) {
            return;
        }

        if (! is_singular()) {
            return;
        }

        $post = get_queried_object();
        if (empty($post->post_excerpt)) {
            return;
        }

        echo '<meta name="description" content="' . esc_attr(wp_strip_all_tags($post->post_excerpt)) . '">' . "\n";
    }
}
add_action('wp_head', 'hello_elementor_add_description_meta_tag');

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Header & footer styling option, inside Elementor
require get_template_directory() . '/includes/elementor-functions.php';

if (! function_exists('hello_elementor_customizer')) {
    // Customizer controls
    function hello_elementor_customizer()
    {
        if (! is_customize_preview()) {
            return;
        }

        if (! hello_elementor_display_header_footer()) {
            return;
        }

        require get_template_directory() . '/includes/customizer-functions.php';
    }
}
add_action('init', 'hello_elementor_customizer');

if (! function_exists('hello_elementor_check_hide_title')) {
    /**
     * Check whether to display the page title.
     *
     * @param bool $val default value.
     *
     * @return bool
     */
    function hello_elementor_check_hide_title($val)
    {
        if (defined('ELEMENTOR_VERSION')) {
            $current_doc = Elementor\Plugin::instance()->documents->get(get_the_ID());
            if ($current_doc && 'yes' === $current_doc->get_settings('hide_title')) {
                $val = false;
            }
        }
        return $val;
    }
}
add_filter('hello_elementor_page_title', 'hello_elementor_check_hide_title');

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if (! function_exists('hello_elementor_body_open')) {
    function hello_elementor_body_open()
    {
        wp_body_open();
    }
}

require HELLO_THEME_PATH . '/theme.php';

HelloTheme\Theme::instance();













/**
 * WooCommerce Shoe Cleaning Service - Complete Pricing System
 * Add this code to your theme's functions.php file
 */

// ============================================
// PART 1: DELIVERY PRICING ADMIN SETTINGS
// ============================================

// Create admin settings page for delivery pricing
add_action('admin_menu', 'add_delivery_pricing_menu');
function add_delivery_pricing_menu()
{
    add_menu_page(
        'Delivery Pricing',           // Page title
        'Delivery Pricing',           // Menu title
        'manage_options',             // Capability
        'delivery-pricing-settings',  // Menu slug
        'delivery_pricing_settings_page', // Function
        'dashicons-money-alt',        // Icon
        30                           // Position
    );
}

// Settings page content
function delivery_pricing_settings_page()
{
    // Save settings if form is submitted
    if (isset($_POST['submit_delivery_pricing'])) {
        update_option('standard_price_local', sanitize_text_field($_POST['standard_price_local']));
        update_option('express_price_local', sanitize_text_field($_POST['express_price_local']));
        update_option('standard_price_radius', sanitize_text_field($_POST['standard_price_radius']));
        update_option('express_price_radius', sanitize_text_field($_POST['express_price_radius']));

        // Update local ZIP codes
        if (isset($_POST['local_zip_codes'])) {
            $zips = sanitize_textarea_field($_POST['local_zip_codes']);
            $zips = array_map('trim', explode(',', $zips));
            $zips = array_filter($zips);
            update_option('local_zip_codes', $zips);
        }

        echo '<div class="notice notice-success is-dismissible"><p>Settings saved successfully!</p></div>';
    }

    // Get current values
    $standard_price_local = get_option('standard_price_local', '15');
    $express_price_local = get_option('express_price_local', '25');
    $standard_price_radius = get_option('standard_price_radius', '20');
    $express_price_radius = get_option('express_price_radius', '30');
    $local_zips = get_option('local_zip_codes', array('17050', '17011', '17055', '17001'));
    $zip_string = implode(', ', $local_zips);
?>

    <div class="wrap">
        <h1><span class="dashicons dashicons-money-alt"></span> Delivery Pricing Settings</h1>

        <div style="background: white; padding: 20px; margin-top: 20px; border-radius: 5px; border-left: 4px solid #007cba;">
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th colspan="2" style="padding-bottom: 10px;">
                            <h2 style="margin-top: 0;">💰 Delivery Pricing</h2>
                            <p style="font-weight: normal;">Set your delivery prices for different service areas</p>
                        </th>
                    </tr>

                    <tr>
                        <th scope="row" style="width: 300px;">
                            <label for="standard_price_local">Standard Delivery - Local Area (48h)</label>
                        </th>
                        <td>
                            <input type="number"
                                id="standard_price_local"
                                name="standard_price_local"
                                value="<?php echo esc_attr($standard_price_local); ?>"
                                step="0.01"
                                min="0"
                                style="width: 150px; padding: 8px;">
                            <p class="description">Price for standard delivery (48+ hours) in local area</p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="express_price_local">Express Delivery - Local Area (24h)</label>
                        </th>
                        <td>
                            <input type="number"
                                id="express_price_local"
                                name="express_price_local"
                                value="<?php echo esc_attr($express_price_local); ?>"
                                step="0.01"
                                min="0"
                                style="width: 150px; padding: 8px;">
                            <p class="description">Price for express delivery (24 hours) in local area</p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="standard_price_radius">Standard Delivery - 20-Mile Radius (48h)</label>
                        </th>
                        <td>
                            <input type="number"
                                id="standard_price_radius"
                                name="standard_price_radius"
                                value="<?php echo esc_attr($standard_price_radius); ?>"
                                step="0.01"
                                min="0"
                                style="width: 150px; padding: 8px;">
                            <p class="description">Price for standard delivery (48+ hours) in 20-mile radius area</p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="express_price_radius">Express Delivery - 20-Mile Radius (24h)</label>
                        </th>
                        <td>
                            <input type="number"
                                id="express_price_radius"
                                name="express_price_radius"
                                value="<?php echo esc_attr($express_price_radius); ?>"
                                step="0.01"
                                min="0"
                                style="width: 150px; padding: 8px;">
                            <p class="description">Price for express delivery (24 hours) in 20-mile radius area</p>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>

                    <tr>
                        <th colspan="2" style="padding-bottom: 10px;">
                            <h2>📍 Service Area Configuration</h2>
                            <p style="font-weight: normal;">Define which ZIP codes are considered "Local Area"</p>
                        </th>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="local_zip_codes">Local ZIP Codes</label>
                        </th>
                        <td>
                            <textarea
                                id="local_zip_codes"
                                name="local_zip_codes"
                                rows="3"
                                style="width: 100%; padding: 8px; font-family: monospace;"
                                placeholder="17050, 17011, 17055, 17001"><?php echo esc_textarea($zip_string); ?></textarea>
                            <p class="description">Enter ZIP codes separated by commas. Customers in these ZIPs get local pricing.</p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            Current Pricing Summary
                        </th>
                        <td>
                            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #ddd;">
                                <h4 style="margin-top: 0;">Local Area (ZIP: <?php echo esc_html($zip_string); ?>)</h4>
                                <p>✅ Standard (48h): <strong>$<?php echo esc_html($standard_price_local); ?></strong></p>
                                <p>✅ Express (24h): <strong>$<?php echo esc_html($express_price_local); ?></strong></p>

                                <h4>20-Mile Radius Area</h4>
                                <p>📦 Standard (48h): <strong>$<?php echo esc_html($standard_price_radius); ?></strong></p>
                                <p>⚡ Express (24h): <strong>$<?php echo esc_html($express_price_radius); ?></strong></p>
                            </div>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit"
                        name="submit_delivery_pricing"
                        class="button button-primary"
                        value="Save Changes">
                </p>
            </form>
        </div>

        <div style="margin-top: 30px; padding: 20px; background: #fff8e5; border-left: 4px solid #ffb900;">
            <h3 style="margin-top: 0;">💡 How It Works</h3>
            <ol>
                <li>Customers enter their ZIP code on the product page</li>
                <li>If their ZIP is in your "Local ZIP Codes" list, they get local pricing</li>
                <li>Otherwise, they get 20-mile radius pricing</li>
                <li>Prices update instantly without page reload</li>
                <li>Delivery fees are added automatically to cart</li>
            </ol>
        </div>
    </div>

    <style>
        .form-table th {
            padding: 20px 10px 20px 0;
        }

        .form-table td {
            padding: 15px 10px;
        }

        input[type="number"] {
            font-size: 16px;
            font-weight: bold;
        }
    </style>
<?php
}

// Initialize default options
add_action('admin_init', 'initialize_delivery_pricing_options');
function initialize_delivery_pricing_options()
{
    if (get_option('standard_price_local') === false) {
        update_option('standard_price_local', '15');
        update_option('express_price_local', '25');
        update_option('standard_price_radius', '20');
        update_option('express_price_radius', '30');
        update_option('local_zip_codes', array('17050', '17011', '17055', '17001'));
    }
}

// ============================================
// PART 2: DELIVERY PRICING FUNCTIONS
// ============================================

/**
 * Determine delivery zone based on ZIP code
 */
function get_delivery_zone($zip_code)
{
    $local_zips = get_option('local_zip_codes', array('17050', '17011', '17055', '17001'));

    // Clean the ZIP code
    $zip_code = preg_replace('/[^0-9]/', '', $zip_code);

    // Check if it's a local ZIP
    if (in_array($zip_code, $local_zips)) {
        return 'local';
    } else {
        return 'radius';
    }
}

/**
 * Get delivery price based on zone and speed
 */
function get_delivery_price($zone, $speed)
{
    if ($zone === 'local') {
        $price = $speed === 'express' ? get_option('express_price_local', '25') : get_option('standard_price_local', '15');
    } else {
        $price = $speed === 'express' ? get_option('express_price_radius', '30') : get_option('standard_price_radius', '20');
    }
    return floatval($price);
}

// ============================================
// PART 3: PRODUCT PAGE - DATE/TIME SELECTION
// ============================================

// ============================================
// PART 3: PRODUCT PAGE - DATE/TIME SELECTION
// ============================================

// Add calendar and time selection to product page (BEFORE add to cart)
add_action('woocommerce_before_add_to_cart_button', 'add_service_datetime_selection');
function add_service_datetime_selection()
{
    global $product;

    $min_standard_date = date('Y-m-d', strtotime('+48 hours'));
    $min_express_date = date('Y-m-d', strtotime('+24 hours'));

    // Get current prices
    $standard_local = get_option('standard_price_local', '15');
    $express_local = get_option('express_price_local', '25');
?>
    <div class="shoe-cleaning-booking" style="margin: 20px 0; padding: 20px; background: #f8f9fa; border-radius: 8px; border: 2px solid #e9ecef;">

        <h3 style="margin-top: 0; color: #333;">📅 Schedule Your Shoe Cleaning Service</h3>

        <!-- Service Type Selection -->
        <div class="service-type-selection" style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 10px;">
                Service Option <span style="color: red;">*</span>
            </label>

            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <label class="service-option-box" style="flex: 1; min-width: 200px; padding: 15px; background: white; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                    <input type="radio" name="service_type" value="pickup_delivery" required style="margin-right: 8px;" checked>
                    <div>
                        <strong style="font-size: 16px;">🚗 Pickup & Delivery</strong>
                        <div style="margin-top: 8px; color: #666; font-size: 14px;">
                            We collect and return your shoes to your location
                        </div>
                        <div style="margin-top: 8px; font-size: 14px; color: #666;">
                            <span id="delivery_price_display">Select delivery speed below</span>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Delivery Options -->
        <div id="delivery_options_section" style="margin-bottom: 20px;">

            <!-- Pickup Address -->
            <div style="margin-bottom: 20px;">
                <label for="product_delivery_address" style="display: block; font-weight: 600; margin-bottom: 8px;">
                    Pickup Address <span style="color: red;">*</span>
                </label>
                <input type="text" id="product_delivery_address" name="product_delivery_address"
                    placeholder="Enter your complete address for shoe pickup"
                    style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>

            <!-- ZIP Code -->
            <div style="margin-bottom: 20px;">
                <label for="product_zip_code" style="display: block; font-weight: 600; margin-bottom: 8px;">
                    ZIP Code <span style="color: red;">*</span>
                </label>
                <input type="text" id="product_zip_code" name="product_zip_code"
                    placeholder="Enter your ZIP code (e.g., 17050)"
                    value="17050"
                    style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                <small style="display: block; margin-top: 5px; color: #666;">
                    Enter your ZIP code to see accurate delivery pricing
                </small>
            </div>

            <!-- Delivery Speed Selection -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 10px;">
                    Delivery Speed <span style="color: red;">*</span>
                </label>

                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <label class="delivery-speed-box" style="flex: 1; min-width: 200px; padding: 15px; background: white; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                        <input type="radio" name="delivery_speed" value="standard" style="margin-right: 8px;" checked>
                        <div>
                            <strong style="font-size: 16px;">📦 Standard Delivery</strong>
                            <div style="margin-top: 8px; color: #666; font-size: 14px;">
                                Delivery starts from 48 hours after pickup
                            </div>
                            <div style="margin-top: 8px; font-size: 18px; color: #007bff; font-weight: bold;">
                                $<span id="standard_price"><?php echo $standard_local; ?></span>
                            </div>
                            <div style="margin-top: 4px; font-size: 12px; color: #666;">
                                <span id="standard_zone">Local Area</span>
                            </div>
                        </div>
                    </label>

                    <label class="delivery-speed-box" style="flex: 1; min-width: 200px; padding: 15px; background: white; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                        <input type="radio" name="delivery_speed" value="express" style="margin-right: 8px;">
                        <div>
                            <strong style="font-size: 16px;">⚡ Express Delivery</strong>
                            <div style="margin-top: 8px; color: #666; font-size: 14px;">
                                Fast delivery within 24 hours
                            </div>
                            <div style="margin-top: 8px; font-size: 18px; color: #dc3545; font-weight: bold;">
                                $<span id="express_price"><?php echo $express_local; ?></span>
                            </div>
                            <div style="margin-top: 4px; font-size: 12px; color: #666;">
                                <span id="express_zone">Local Area</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Delivery Fee Summary -->
            <div id="delivery_fee_summary" style="padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; text-align: center; margin-top: 20px;">
                <div style="font-size: 14px; margin-bottom: 5px;">Total Delivery Fee</div>
                <div style="font-size: 28px; font-weight: bold;">$<span id="delivery_fee_amount"><?php echo $standard_local; ?></span></div>
                <div style="font-size: 12px; margin-top: 5px; opacity: 0.9;" id="zone_display">Local Area</div>
            </div>
        </div>

        <!-- Pickup Date Selection -->
        <div style="margin-bottom: 20px;">
            <label for="pickup_date" style="display: block; font-weight: 600; margin-bottom: 8px;">
                Pickup Date <span style="color: red;">*</span>
            </label>
            <input type="date" id="pickup_date" name="pickup_date" required
                min="<?php echo $min_standard_date; ?>"
                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            <small id="pickup_date_helper" style="display: block; margin-top: 5px; color: #666;">
                Earliest pickup: <?php echo date('D, M j, Y', strtotime('+48 hours')); ?>
            </small>
        </div>

        <!-- Pickup Time Slot -->
        <div style="margin-bottom: 20px;">
            <label for="pickup_time" style="display: block; font-weight: 600; margin-bottom: 8px;">
                Pickup Time Slot <span style="color: red;">*</span>
            </label>
            <select id="pickup_time" name="pickup_time" required
                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                <option value="">Select a pickup time</option>
                <option value="10:00-13:00">⏰ Pickup Window (10:00 AM - 1:00 PM)</option>
            </select>
        </div>

        <!-- Delivery Date (Calculated) -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">
                Estimated Delivery Date
            </label>
            <div id="delivery_date_display" style="padding: 12px; background: #e9ecef; border-radius: 6px; font-size: 14px;">
                Select pickup date to see delivery date
            </div>
            <small style="display: block; margin-top: 5px; color: #666;">
                Delivery is <span id="delivery_hours">48</span> hours after pickup
            </small>
        </div>

        <!-- Special Instructions -->
        <div style="margin-bottom: 15px;">
            <label for="service_notes" style="display: block; font-weight: 600; margin-bottom: 8px;">
                Special Instructions (Optional)
            </label>
            <textarea id="service_notes" name="service_notes" rows="3"
                placeholder="E.g., Gate code, specific cleaning requirements, shoe material details..."
                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical;"></textarea>
        </div>

        <div style="padding: 12px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px; font-size: 13px;">
            <strong>ℹ️ Important:</strong> We offer pickup & delivery service only. Pickup: 10 AM - 1 PM, Delivery: 2 PM - 5 PM, Monday to Saturday.
        </div>
    </div>

    <style>
        /* Box hover effects */
        .shoe-cleaning-booking .service-option-box:hover,
        .shoe-cleaning-booking .delivery-speed-box:hover {
            border-color: #007bff !important;
            background-color: #f0f8ff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
        }

        .shoe-cleaning-booking .service-option-box:has(input[type="radio"]:checked),
        .shoe-cleaning-booking .delivery-speed-box:has(input[type="radio"]:checked) {
            border-color: #007bff !important;
            background-color: #e3f2fd !important;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }
    </style>

    <script>
        jQuery(document).ready(function($) {
            // Function to check if ZIP is local using PHP data
            function isLocalZip(zipCode) {
                var localZips = <?php echo json_encode(get_option('local_zip_codes', array('17050', '17011', '17055', '17001'))); ?>;
                var cleanZip = zipCode.replace(/[^0-9]/g, '');
                return localZips.includes(cleanZip);
            }

            // Function to get prices from PHP
            // Function to get prices from PHP
            function getPrices() {
                return {
                    standardLocal: <?php echo get_option('standard_price_local', '15'); ?>,
                    expressLocal: <?php echo get_option('express_price_local', '25'); ?>,
                    standardRadius: <?php echo get_option('standard_price_radius', '20'); ?>,
                    expressRadius: <?php echo get_option('express_price_radius', '30'); ?>
                };
            }

            // Update delivery date when pickup date changes
            $('#pickup_date, input[name="delivery_speed"]').on('change', function() {
                updateDeliveryDateAndPrice();
            });

            // Update price when ZIP code changes
            $('#product_zip_code').on('input', function() {
                updateDeliveryDateAndPrice();
            });

            function updateDeliveryDateAndPrice() {
                var zipCode = $('#product_zip_code').val();
                var speed = $('input[name="delivery_speed"]:checked').val();
                var pickupDate = $('#pickup_date').val();
                var prices = getPrices();

                // Determine zone
                var isLocal = isLocalZip(zipCode);

                // Set prices based on zone
                var standardPrice = isLocal ? prices.standardLocal : prices.standardRadius;
                var expressPrice = isLocal ? prices.expressLocal : prices.expressRadius;

                // Update price displays
                $('#standard_price').text(standardPrice);
                $('#express_price').text(expressPrice);

                // Update zone labels
                var zoneLabel = isLocal ? 'Local Area' : '20-mile radius area';
                $('#standard_zone').text(zoneLabel);
                $('#express_zone').text(zoneLabel);
                $('#zone_display').text(zoneLabel);

                // Show/hide zone indicator
                if (zipCode.trim().length >= 3) {
                    $('#zip_indicator').remove();
                    var indicatorColor = isLocal ? '#28a745' : '#ffc107';
                    var indicatorText = isLocal ? '✓ Local ZIP code' : '⚠ Outside local area';
                    $('#product_zip_code').after('<small id="zip_indicator" style="display:block;margin-top:5px;color:' + indicatorColor + ';font-weight:bold;padding:3px 8px;background:' + (isLocal ? '#e8f5e8' : '#fff8e5') + ';border-radius:3px;">' + indicatorText + '</small>');
                }

                // Calculate delivery hours
                var deliveryHours = (speed === 'express') ? 24 : 48;
                $('#delivery_hours').text(deliveryHours);

                // Set current price
                var currentPrice = (speed === 'express') ? expressPrice : standardPrice;
                $('#delivery_fee_amount').text(currentPrice);

                // Update minimum date based on speed
                var minHours = (speed === 'express') ? 24 : 48;
                var minDate = new Date();
                minDate.setHours(minDate.getHours() + minHours);
                var minDateString = minDate.toISOString().split('T')[0];

                $('#pickup_date').attr('min', minDateString);

                // Update helper text
                var minDateFormatted = minDate.toLocaleDateString('en-US', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric'
                });
                $('#pickup_date_helper').html('Earliest pickup: ' + minDateFormatted + ' (' + minHours + ' hours from now)');

                // Update delivery date display if pickup date is selected
                if (pickupDate) {
                    var pickupDateTime = new Date(pickupDate + 'T12:00:00');
                    var deliveryDateTime = new Date(pickupDateTime);
                    deliveryDateTime.setHours(deliveryDateTime.getHours() + deliveryHours);

                    var deliveryDateString = deliveryDateTime.toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    $('#delivery_date_display').html('<strong>' + deliveryDateString + '</strong><br><small>Delivery window: 2:00 PM - 5:00 PM</small>');
                }
            }

            // Initialize on page load
            updateDeliveryDateAndPrice();

            // Validation before add to cart
            $('.single_add_to_cart_button').on('click', function(e) {
                var isValid = true;
                var errors = [];

                // Check pickup date
                if (!$('#pickup_date').val()) {
                    errors.push('Please select a pickup date');
                    isValid = false;
                }

                // Check pickup time
                if (!$('#pickup_time').val()) {
                    errors.push('Please select a pickup time slot');
                    isValid = false;
                }

                // Check address
                if (!$('#product_delivery_address').val()) {
                    errors.push('Please enter your pickup address');
                    isValid = false;
                }

                // Check ZIP code
                if (!$('#product_zip_code').val()) {
                    errors.push('Please enter your ZIP code');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    alert('⚠️ Please complete the following:\n\n• ' + errors.join('\n• '));
                    return false;
                }
            });
        });
    </script>
<?php
}

// ============================================
// PART 4: ADD TO CART WITH CUSTOM DATA
// ============================================

// Save booking details when adding to cart
add_filter('woocommerce_add_cart_item_data', 'add_booking_data_to_cart', 10, 2);
function add_booking_data_to_cart($cart_item_data, $product_id)
{
    if (isset($_POST['service_type'])) {
        $cart_item_data['service_type'] = sanitize_text_field($_POST['service_type']);
    }
    if (isset($_POST['pickup_date'])) {
        $cart_item_data['pickup_date'] = sanitize_text_field($_POST['pickup_date']);
    }
    if (isset($_POST['pickup_time'])) {
        $cart_item_data['pickup_time'] = sanitize_text_field($_POST['pickup_time']);
    }
    if (isset($_POST['product_delivery_address'])) {
        $cart_item_data['delivery_address'] = sanitize_text_field($_POST['product_delivery_address']);
    }
    if (isset($_POST['product_zip_code'])) {
        $zip_code = sanitize_text_field($_POST['product_zip_code']);
        $cart_item_data['delivery_zip'] = $zip_code;
        $cart_item_data['delivery_zone'] = get_delivery_zone($zip_code);
    }
    if (isset($_POST['delivery_speed'])) {
        $cart_item_data['delivery_speed'] = sanitize_text_field($_POST['delivery_speed']);
    }
    if (isset($_POST['service_notes'])) {
        $cart_item_data['service_notes'] = sanitize_textarea_field($_POST['service_notes']);
    }

    // Make each cart item unique
    $cart_item_data['unique_key'] = md5(microtime() . rand());

    return $cart_item_data;
}

// Display booking info in cart
add_filter('woocommerce_get_item_data', 'display_booking_data_in_cart', 10, 2);
function display_booking_data_in_cart($item_data, $cart_item)
{
    if (isset($cart_item['service_type'])) {
        $item_data[] = array(
            'name' => 'Service Type',
            'value' => '🚗 Pickup & Delivery'
        );
    }

    if (isset($cart_item['pickup_date'])) {
        $date = date('D, M j, Y', strtotime($cart_item['pickup_date']));
        $item_data[] = array(
            'name' => 'Pickup Date',
            'value' => $date
        );
    }

    if (isset($cart_item['pickup_time'])) {
        $item_data[] = array(
            'name' => 'Pickup Time',
            'value' => '10:00 AM - 1:00 PM'
        );
    }

    if (isset($cart_item['delivery_address'])) {
        $item_data[] = array(
            'name' => 'Pickup Address',
            'value' => $cart_item['delivery_address']
        );
    }

    if (isset($cart_item['delivery_zip'])) {
        $item_data[] = array(
            'name' => 'ZIP Code',
            'value' => $cart_item['delivery_zip']
        );
    }

    if (isset($cart_item['delivery_speed'])) {
        $speed_label = $cart_item['delivery_speed'] === 'express' ? '⚡ Express (24 hours)' : '📦 Standard (48 hours)';
        $item_data[] = array(
            'name' => 'Delivery Speed',
            'value' => $speed_label
        );
    }

    if (isset($cart_item['service_notes']) && !empty($cart_item['service_notes'])) {
        $item_data[] = array(
            'name' => 'Special Instructions',
            'value' => $cart_item['service_notes']
        );
    }

    // Add estimated delivery date
    if (isset($cart_item['pickup_date']) && isset($cart_item['delivery_speed'])) {
        $hours = $cart_item['delivery_speed'] === 'express' ? 24 : 48;
        $delivery_date = date('D, M j, Y', strtotime($cart_item['pickup_date'] . ' + ' . $hours . ' hours'));
        $item_data[] = array(
            'name' => 'Estimated Delivery',
            'value' => $delivery_date . ' (2:00 PM - 5:00 PM)'
        );
    }

    return $item_data;
}

// ============================================
// PART 5: ADD DELIVERY FEE TO CART
// ============================================

// Add delivery fee to cart total
add_action('woocommerce_cart_calculate_fees', 'add_delivery_fee_to_cart');
function add_delivery_fee_to_cart()
{
    if (is_admin() && !defined('DOING_AJAX')) return;

    $total_delivery_fee = 0;

    // Check each cart item for delivery service
    foreach (WC()->cart->get_cart() as $cart_item) {
        if (isset($cart_item['service_type']) && $cart_item['service_type'] === 'pickup_delivery') {
            if (isset($cart_item['delivery_speed']) && isset($cart_item['delivery_zone'])) {
                $speed = $cart_item['delivery_speed'];
                $zone = $cart_item['delivery_zone'];

                $fee = get_delivery_price($zone, $speed);
                $total_delivery_fee += $fee;
            }
        }
    }

    // Add delivery fee if applicable
    if ($total_delivery_fee > 0) {
        $label = 'Pickup & Delivery Service';
        WC()->cart->add_fee($label, $total_delivery_fee, true, '');
    }
}

// ============================================
// PART 6: SAVE TO ORDER
// ============================================

// Save booking data to order
add_action('woocommerce_checkout_create_order_line_item', 'save_booking_data_to_order_items', 10, 4);
function save_booking_data_to_order_items($item, $cart_item_key, $values, $order)
{
    if (isset($values['service_type'])) {
        $item->add_meta_data('Service Type', 'Pickup & Delivery');
    }
    if (isset($values['pickup_date'])) {
        $item->add_meta_data('Pickup Date', date('D, M j, Y', strtotime($values['pickup_date'])));
    }
    if (isset($values['pickup_time'])) {
        $item->add_meta_data('Pickup Time', '10:00 AM - 1:00 PM');
    }
    if (isset($values['delivery_address'])) {
        $item->add_meta_data('Pickup Address', $values['delivery_address']);
    }
    if (isset($values['delivery_zip'])) {
        $item->add_meta_data('ZIP Code', $values['delivery_zip']);
    }
    if (isset($values['delivery_speed'])) {
        $speed_label = $values['delivery_speed'] === 'express' ? 'Express (24 hours)' : 'Standard (48 hours)';
        $item->add_meta_data('Delivery Speed', $speed_label);

        // Add delivery cost
        $zone = isset($values['delivery_zone']) ? $values['delivery_zone'] : 'local';
        $cost = get_delivery_price($zone, $values['delivery_speed']);
        $item->add_meta_data('Delivery Cost', '$' . number_format($cost, 2));
    }

    // Add estimated delivery date
    if (isset($values['pickup_date']) && isset($values['delivery_speed'])) {
        $hours = $values['delivery_speed'] === 'express' ? 24 : 48;
        $delivery_date = date('D, M j, Y', strtotime($values['pickup_date'] . ' + ' . $hours . ' hours'));
        $item->add_meta_data('Estimated Delivery', $delivery_date . ' (2:00 PM - 5:00 PM)');
    }

    if (isset($values['service_notes'])) {
        $item->add_meta_data('Special Instructions', $values['service_notes']);
    }
}

// Display in admin order details
add_action('woocommerce_admin_order_data_after_billing_address', 'display_booking_in_admin_order', 10, 1);
function display_booking_in_admin_order($order)
{
    echo '<div class="order_data_column" style="clear:both; padding: 20px; background: #f9f9f9; margin-top: 20px; border-radius: 5px;">';
    echo '<h3 style="margin-top: 0;">🧼 Shoe Cleaning Service Details</h3>';

    $has_service = false;
    foreach ($order->get_items() as $item) {
        $service_type = $item->get_meta('Service Type');
        if ($service_type) {
            $has_service = true;
            echo '<p><strong>Service Type:</strong> ' . esc_html($service_type) . '</p>';
            echo '<p><strong>Pickup Date:</strong> ' . esc_html($item->get_meta('Pickup Date')) . '</p>';
            echo '<p><strong>Pickup Time:</strong> ' . esc_html($item->get_meta('Pickup Time')) . '</p>';
            echo '<p><strong>Estimated Delivery:</strong> ' . esc_html($item->get_meta('Estimated Delivery')) . '</p>';

            if ($item->get_meta('Pickup Address')) {
                echo '<p><strong>Pickup Address:</strong> ' . esc_html($item->get_meta('Pickup Address')) . '</p>';
                echo '<p><strong>ZIP Code:</strong> ' . esc_html($item->get_meta('ZIP Code')) . '</p>';
                echo '<p><strong>Delivery Speed:</strong> ' . esc_html($item->get_meta('Delivery Speed')) . '</p>';
                echo '<p><strong>Delivery Fee:</strong> ' . esc_html($item->get_meta('Delivery Cost')) . '</p>';
            }

            if ($item->get_meta('Special Instructions')) {
                echo '<p><strong>Instructions:</strong> ' . esc_html($item->get_meta('Special Instructions')) . '</p>';
            }
            break;
        }
    }

    if (!$has_service) {
        echo '<p><em>No service details available</em></p>';
    }

    echo '</div>';
}

// Add to order emails
add_action('woocommerce_email_after_order_table', 'add_booking_to_email', 10, 4);
function add_booking_to_email($order, $sent_to_admin, $plain_text, $email)
{
    foreach ($order->get_items() as $item) {
        if ($item->get_meta('Service Type')) {
            echo '<h2 style="color: #333;">Your Shoe Cleaning Service Details</h2>';
            echo '<p><strong>Service Type:</strong> ' . esc_html($item->get_meta('Service Type')) . '</p>';
            echo '<p><strong>Pickup Date:</strong> ' . esc_html($item->get_meta('Pickup Date')) . '</p>';
            echo '<p><strong>Pickup Time:</strong> ' . esc_html($item->get_meta('Pickup Time')) . '</p>';
            echo '<p><strong>Estimated Delivery:</strong> ' . esc_html($item->get_meta('Estimated Delivery')) . '</p>';

            if ($item->get_meta('Pickup Address')) {
                echo '<p><strong>We will pickup from:</strong> ' . esc_html($item->get_meta('Pickup Address')) . '</p>';
                echo '<p><strong>Delivery Speed:</strong> ' . esc_html($item->get_meta('Delivery Speed')) . '</p>';
            }
            break;
        }
    }
}

?>