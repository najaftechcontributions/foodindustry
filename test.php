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
// PART 1: DELIVERY PRICING CONFIGURATION
// ============================================

// Standard Delivery Prices (48+ hours)
define('STANDARD_DELIVERY_PRICE', 15);

// Express Delivery Prices (24 hours)
define('EXPRESS_DELIVERY_PRICE', 30);

// ============================================
// PART 2: PRODUCT PAGE - DATE/TIME SELECTION
// ============================================

// Add calendar and time selection to product page (BEFORE add to cart)
add_action('woocommerce_before_add_to_cart_button', 'add_service_datetime_selection');
function add_service_datetime_selection()
{
    global $product;

    $min_standard_date = date('Y-m-d', strtotime('+48 hours'));
    $min_express_date = date('Y-m-d', strtotime('+24 hours'));
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
                    <input type="radio" name="service_type" value="store_pickup" required style="margin-right: 8px;" checked>
                    <div>
                        <strong style="font-size: 16px;">🏪 Store Drop-off</strong>
                        <div style="margin-top: 8px; color: #666; font-size: 14px;">
                            Bring your shoes to our store
                        </div>
                        <div style="margin-top: 8px; font-size: 18px; color: #28a745; font-weight: bold;">
                            FREE
                        </div>
                    </div>
                </label>

                <label class="service-option-box" style="flex: 1; min-width: 200px; padding: 15px; background: white; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                    <input type="radio" name="service_type" value="pickup_delivery" required style="margin-right: 8px;">
                    <div>
                        <strong style="font-size: 16px;">🚗 Pickup & Delivery</strong>
                        <div style="margin-top: 8px; color: #666; font-size: 14px;">
                            We collect and return your shoes
                        </div>
                        <div style="margin-top: 8px; font-size: 14px; color: #666;">
                            <span id="delivery_price_display">Select delivery speed below</span>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Delivery Options (shown only when pickup_delivery is selected) -->
        <div id="delivery_options_section" style="display: none; margin-bottom: 20px;">

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
                                Delivery starts from 48 hours
                            </div>
                            <div style="margin-top: 8px; font-size: 18px; color: #007bff; font-weight: bold;">
                                $<?php echo STANDARD_DELIVERY_PRICE; ?>
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
                                $<?php echo EXPRESS_DELIVERY_PRICE; ?>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Pickup Address -->
            <div style="margin-bottom: 20px;">
                <label for="product_delivery_address" style="display: block; font-weight: 600; margin-bottom: 8px;">
                    Pickup Address <span style="color: red;">*</span>
                </label>
                <input type="text" id="product_delivery_address" name="product_delivery_address"
                    placeholder="Enter your complete address for shoe pickup"
                    style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                <small style="display: block; margin-top: 5px; color: #666;">
                    We'll pick up your shoes from this address and return them after cleaning
                </small>
            </div>

            <!-- Delivery Fee Summary -->
            <div id="delivery_fee_summary" style="padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; text-align: center;">
                <div style="font-size: 14px; margin-bottom: 5px;">Total Delivery Fee</div>
                <div style="font-size: 28px; font-weight: bold;">$<span id="delivery_fee_amount"><?php echo STANDARD_DELIVERY_PRICE; ?></span></div>
                <div style="font-size: 12px; margin-top: 5px; opacity: 0.9;">This will be added to your cart</div>
            </div>
        </div>

        <!-- Service Date Selection -->
        <div style="margin-bottom: 20px;">
            <label for="service_date" style="display: block; font-weight: 600; margin-bottom: 8px;">
                <span id="date_label">Drop-off Date</span> <span style="color: red;">*</span>
            </label>
            <input type="date" id="service_date" name="service_date" required
                min="<?php echo $min_standard_date; ?>"
                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            <small id="date_helper" style="display: block; margin-top: 5px; color: #666;">
                Earliest available: <?php echo date('D, M j, Y', strtotime('+48 hours')); ?>
            </small>
        </div>

        <!-- Time Slot Selection -->
        <div style="margin-bottom: 20px;">
            <label for="service_time" style="display: block; font-weight: 600; margin-bottom: 8px;">
                <span id="time_label">Preferred Time Slot</span> <span style="color: red;">*</span>
            </label>
            <select id="service_time" name="service_time" required
                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                <option value="">Select a time slot</option>
                <option value="09:00-12:00">🌅 Morning (9:00 AM - 12:00 PM)</option>
                <option value="12:00-15:00">☀️ Afternoon (12:00 PM - 3:00 PM)</option>
                <option value="15:00-18:00">🌆 Evening (3:00 PM - 6:00 PM)</option>
                <option value="18:00-21:00">🌙 Late Evening (6:00 PM - 9:00 PM)</option>
            </select>
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
            <strong>ℹ️ Important:</strong> Please select your service options, date and time before adding to cart
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
            const STANDARD_PRICE = <?php echo STANDARD_DELIVERY_PRICE; ?>;
            const EXPRESS_PRICE = <?php echo EXPRESS_DELIVERY_PRICE; ?>;
            const MIN_STANDARD_DATE = '<?php echo $min_standard_date; ?>';
            const MIN_EXPRESS_DATE = '<?php echo $min_express_date; ?>';

            // Service type change handler
            $('input[name="service_type"]').on('change', function() {
                var serviceType = $(this).val();

                if (serviceType === 'pickup_delivery') {
                    $('#delivery_options_section').slideDown();
                    $('#date_label').text('Pickup Date');
                    $('#time_label').text('Pickup Time Slot');
                    updateDateAndPrice();
                } else {
                    $('#delivery_options_section').slideUp();
                    $('#date_label').text('Drop-off Date');
                    $('#time_label').text('Preferred Time Slot');
                    $('#service_date').attr('min', MIN_STANDARD_DATE);
                    $('#date_helper').text('Earliest available: <?php echo date('D, M j, Y', strtotime('+48 hours')); ?>');
                }
            });

            // Delivery speed change handler
            $('input[name="delivery_speed"]').on('change', function() {
                updateDateAndPrice();
            });

            function updateDateAndPrice() {
                var speed = $('input[name="delivery_speed"]:checked').val();
                var price = (speed === 'express') ? EXPRESS_PRICE : STANDARD_PRICE;
                var minDate = (speed === 'express') ? MIN_EXPRESS_DATE : MIN_STANDARD_DATE;
                var hours = (speed === 'express') ? 24 : 48;

                // Update price display
                $('#delivery_fee_amount').text(price);
                $('#delivery_price_display').html('<strong style="color: #007bff;">$' + price + '</strong>');

                // Update minimum date
                $('#service_date').attr('min', minDate);

                // Update date helper text
                var helperDate = new Date();
                helperDate.setHours(helperDate.getHours() + hours);
                var helperText = (speed === 'express') ?
                    'Express: Available from ' + formatDate(helperDate) + ' (24 hours)' :
                    'Standard: Available from ' + formatDate(helperDate) + ' (48+ hours)';
                $('#date_helper').text(helperText);
            }

            function formatDate(date) {
                return date.toLocaleDateString('en-US', {
                    weekday: 'short',
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            // Validation before add to cart
            $('.single_add_to_cart_button').on('click', function(e) {
                var isValid = true;
                var errors = [];

                // Check service date
                if (!$('#service_date').val()) {
                    errors.push('Please select a service date');
                    isValid = false;
                }

                // Check time slot
                if (!$('#service_time').val()) {
                    errors.push('Please select a time slot');
                    isValid = false;
                }

                // Check delivery options if pickup_delivery is selected
                if ($('input[name="service_type"]:checked').val() === 'pickup_delivery') {
                    if (!$('#product_delivery_address').val()) {
                        errors.push('Please enter your pickup address');
                        isValid = false;
                    }
                    if (!$('input[name="delivery_speed"]:checked').val()) {
                        errors.push('Please select delivery speed');
                        isValid = false;
                    }
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
// PART 3: ADD TO CART WITH CUSTOM DATA
// ============================================

// Save booking details when adding to cart
add_filter('woocommerce_add_cart_item_data', 'add_booking_data_to_cart', 10, 2);
function add_booking_data_to_cart($cart_item_data, $product_id)
{
    if (isset($_POST['service_type'])) {
        $cart_item_data['service_type'] = sanitize_text_field($_POST['service_type']);
    }
    if (isset($_POST['service_date'])) {
        $cart_item_data['service_date'] = sanitize_text_field($_POST['service_date']);
    }
    if (isset($_POST['service_time'])) {
        $cart_item_data['service_time'] = sanitize_text_field($_POST['service_time']);
    }
    if (isset($_POST['product_delivery_address'])) {
        $cart_item_data['delivery_address'] = sanitize_text_field($_POST['product_delivery_address']);
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
        $service_label = $cart_item['service_type'] === 'store_pickup' ? '🏪 Store Drop-off' : '🚗 Pickup & Delivery';
        $item_data[] = array(
            'name' => 'Service Type',
            'value' => $service_label
        );
    }

    if (isset($cart_item['service_date'])) {
        $date = date('D, M j, Y', strtotime($cart_item['service_date']));
        $date_label = $cart_item['service_type'] === 'store_pickup' ? 'Drop-off Date' : 'Pickup Date';
        $item_data[] = array(
            'name' => $date_label,
            'value' => $date
        );
    }

    if (isset($cart_item['service_time'])) {
        $item_data[] = array(
            'name' => 'Time Slot',
            'value' => $cart_item['service_time']
        );
    }

    if (isset($cart_item['delivery_address'])) {
        $item_data[] = array(
            'name' => 'Pickup Address',
            'value' => $cart_item['delivery_address']
        );
    }

    if (isset($cart_item['delivery_speed'])) {
        $speed_label = $cart_item['delivery_speed'] === 'express' ? '⚡ Express (24 hours)' : '📦 Standard (48+ hours)';
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

    return $item_data;
}

// ============================================
// PART 4: ADD DELIVERY FEE TO CART
// ============================================

// Add delivery fee to cart total
add_action('woocommerce_cart_calculate_fees', 'add_delivery_fee_to_cart');
function add_delivery_fee_to_cart()
{
    if (is_admin() && !defined('DOING_AJAX')) return;

    $delivery_fee = 0;

    // Check each cart item for delivery service
    foreach (WC()->cart->get_cart() as $cart_item) {
        if (isset($cart_item['service_type']) && $cart_item['service_type'] === 'pickup_delivery') {
            if (isset($cart_item['delivery_speed'])) {
                $speed = $cart_item['delivery_speed'];
                $fee = ($speed === 'express') ? EXPRESS_DELIVERY_PRICE : STANDARD_DELIVERY_PRICE;
                $delivery_fee += $fee;
            }
        }
    }

    // Add delivery fee if applicable
    if ($delivery_fee > 0) {
        WC()->cart->add_fee('Pickup & Delivery Service', $delivery_fee);
    }
}

// ============================================
// PART 5: SAVE TO ORDER
// ============================================

// Save booking data to order
add_action('woocommerce_checkout_create_order_line_item', 'save_booking_data_to_order_items', 10, 4);
function save_booking_data_to_order_items($item, $cart_item_key, $values, $order)
{
    if (isset($values['service_type'])) {
        $service_label = $values['service_type'] === 'store_pickup' ? 'Store Drop-off' : 'Pickup & Delivery';
        $item->add_meta_data('Service Type', $service_label);
    }
    if (isset($values['service_date'])) {
        $item->add_meta_data('Service Date', date('D, M j, Y', strtotime($values['service_date'])));
    }
    if (isset($values['service_time'])) {
        $item->add_meta_data('Time Slot', $values['service_time']);
    }
    if (isset($values['delivery_address'])) {
        $item->add_meta_data('Pickup Address', $values['delivery_address']);
    }
    if (isset($values['delivery_speed'])) {
        $speed_label = $values['delivery_speed'] === 'express' ? 'Express (24 hours)' : 'Standard (48+ hours)';
        $item->add_meta_data('Delivery Speed', $speed_label);

        // Add delivery cost to order meta
        $cost = $values['delivery_speed'] === 'express' ? EXPRESS_DELIVERY_PRICE : STANDARD_DELIVERY_PRICE;
        $item->add_meta_data('Delivery Cost', '$' . number_format($cost, 2));
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
            echo '<p><strong>Date:</strong> ' . esc_html($item->get_meta('Service Date')) . '</p>';
            echo '<p><strong>Time:</strong> ' . esc_html($item->get_meta('Time Slot')) . '</p>';

            if ($item->get_meta('Pickup Address')) {
                echo '<p><strong>Pickup Address:</strong> ' . esc_html($item->get_meta('Pickup Address')) . '</p>';
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
            echo '<p><strong>Date:</strong> ' . esc_html($item->get_meta('Service Date')) . '</p>';
            echo '<p><strong>Time:</strong> ' . esc_html($item->get_meta('Time Slot')) . '</p>';

            if ($item->get_meta('Pickup Address')) {
                echo '<p><strong>We will pickup from:</strong> ' . esc_html($item->get_meta('Pickup Address')) . '</p>';
                echo '<p><strong>Delivery Speed:</strong> ' . esc_html($item->get_meta('Delivery Speed')) . '</p>';
            }
            break;
        }
    }
}
?>