<?php

function my_theme_setup(){
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
}

add_action('after_setup_theme', 'my_theme_setup');

function furni_enqueue_scripts() {

    // CSS
    wp_enqueue_script('js-google-jquery',"https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js",[],'3.6.4',false);
    wp_enqueue_style( 'css-bootstrap', get_template_directory_uri(). '/assets/css/bootstrap.min.css', [], '', 'all' );
    wp_enqueue_style( 'css-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css', [], '', 'all' );
    wp_enqueue_style( 'css-slider', get_template_directory_uri(). '/assets/css/tiny-slider.css', [], '', 'all' );
    wp_enqueue_style( 'css-style', get_template_directory_uri(). '/assets/css/style.css', [], '', 'all' );

    // JS
    wp_enqueue_script('js-google-jquery',"https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js",[],'3.6.4',true);
    wp_enqueue_script( 'js-bootstrap-bundle', get_template_directory_uri(). '/assets/js/bootstrap.bundle.min.js', [], '', true );
    wp_enqueue_script( 'js-tiny-slider', get_template_directory_uri(). '/assets/js/tiny-slider.js', [], '', true );
    wp_enqueue_script( 'js-custom', get_template_directory_uri(). '/assets/js/custom.js', [], '', true );
    
}
add_action( 'wp_enqueue_scripts', 'furni_enqueue_scripts' );


function furni_custom_post_type_and_taxonomy(){

    register_post_type( 'item',
    [
        'labels'=>[
            'name'=>'Items',
            'menu_name' => _x('items', 'admin menu'),
            'name_admin_bar' => _x('items', 'admin bar'),
            'add_new' => _x('Add item', 'add new'),
            'add_new_item' => __('Add New item'),
            'new_item' => __('New item'),
            'edit_item' => __('Edit item'),
        ],
        'public'=>true,
        'has_archive'=>false,
        'rewrite' => ['slug' => ''],
        'supports' => ['title', 'editor','revisions', 'thumbnail'],
        'menu_icon' => 'dashicons-cart'
    ]);

    //Create Item taxonomy
    register_taxonomy('item_categories', 'item', [
        'labels' => [
            'name' => 'Item Categories',
            'singular_name' => 'Item Category',
            'search_items' => 'Search Item Categories',
            'all_items' => 'All Item Categories',
            'parent_item' => 'Parent Item Category',
            'parent_item_colon' => 'Parent Item Category:',
            'edit_item' => 'Edit Item Category',
            'update_item' => 'Update Item Category',
            'add_new_item' => 'Add New Item Category',
            'new_item_name' => 'New Item Category Name',
            'menu_name' => 'Item Categories',
        ],
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'item-categories'],
    ]);


}
add_action('init','furni_custom_post_type_and_taxonomy');





// flush_rewrite_rules();



// Creating Virtual Nav Menu

add_action('init', 'custom_menu');
function custom_menu(){

    register_nav_menu('primary',_('Header Menu') );

}

class AWP_MENU_WALKER extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $output .= "<li>";
        
        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target)     ? $item->target     : '';
        $atts['rel']    = !empty($item->xfn)        ? $item->xfn        : '';
        $atts['href']   = !empty($item->url)        ? $item->url        : '';
        $atts['class']  = 'nav-link';

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = '<a' . $attributes . '>';
        $item_output .= apply_filters('the_title', $item->title, $item->ID);
        $item_output .= '</a>';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * How to trim URL?
 */
//**************************To change main query********************************//

add_action('init', 'city_rewrite_rule', 10, 0);
function city_rewrite_rule () {
    add_rewrite_rule('/([^/]+)/?', 'index.php?&item=$matches[1]', 'top');
};

function custom_item_post_link($post_link, $post) {
    if ($post->post_type === 'item') {
        return home_url($post->post_name . '/');
    }
    return $post_link;
}

//Adding Meta Box in product page of woocommerce
function prefix_add_meta_box(){
    add_meta_box(
        'unique_mb_origin_id',
        __('Post Body', 'text-domain'),
        'prefix_mb_callback',
        'product',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'prefix_add_meta_box');

//Meta field callback function
function prefix_mb_callback($post){
    
    $origin = get_post_meta($post->ID, 'origin_key', true);
    ?>
    <label for="mb_origin_id"><?php esc_html_e('Country of Origin:', 'text-domain'); ?></label>
    <input type="text" class="regular-text" value="<?php echo esc_attr($origin); ?>" name="unique_mb_origin_id" id="mb_origin_id">
    <?php
}

//Save metabox data
function prefix_save_meta_data($post_id){

    // Check the user's permissions.
    if (isset($_POST['post_type']) && 'product' == $_POST['post_type']) {
        if (!current_user_can('edit_product', $post_id)) {
            return;
        }
    }

    // Save the meta field
    if (isset($_POST['unique_mb_origin_id'])) {
        $meta_value = sanitize_text_field($_POST['unique_mb_origin_id']);
        update_post_meta($post_id, 'origin_key', $meta_value);
    }
}
add_action('save_post', 'prefix_save_meta_data');

function create_updated_product_creds_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'updated_product_creds';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            post_id INT,
            post_name varchar(255),
            post_content LONGTEXT,
            post_image varchar(255),
            post_price INT,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

add_action('init', 'create_updated_product_creds_table');


function create_item($title, $content) { 
    $item_name = $title;
    $query = new WP_Query([
        'post_type' => 'item',
        'title' =>  $item_name,
        'post_status' => 'publish',
        'posts_per_page' => 1,
    ]);
    
    $check_item_exist = $query->have_posts() ? $query->posts[0] : null;
    if(empty($check_item_exist)){ 
        wp_insert_post(
           
            [
                'comment_status' => 'close',
                'ping_status'    => 'close',
                'post_author'    => 1,
                'post_title'     => ucwords($item_name),
                'post_name'      => strtolower(str_replace(' ', '-', trim($item_name))),
                'post_status'    => 'publish',
                'post_type'      => 'item',
                'post_content'   => $content
            ]
        );
    } else {
        $updated_post = [
            'ID' =>  $check_item_exist->ID,
            'title' =>  $item_name,
            'post_content'   => $content,
        ];
        wp_update_post($updated_post);
    }
}

function wpse_save_product_callback($post_id) {

    $post = get_post($post_id);
    if ($post->post_type !== 'product' || $post->post_status !== 'publish') {
        return;
    }

    $the_title = get_the_title($post_id);
    $origin = get_post_meta($post_id, 'origin_key', true);
    
    $the_content = $post->post_content . "\n\nCountry of Origin: " . $origin;
    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');
    $image_url = $image ? $image[0] : '';
    
    create_item($the_title, $the_content);
}
add_action('woocommerce_process_product_meta','wpse_save_product_callback');


//       ********************************************* Registering Button on Product Page ********************************************

add_action('woocommerce_process_product_meta', 'wpse_save_product_callback', 20);
add_action( 'manage_posts_extra_tablenav', 'admin_order_list_top_bar_button', 20, 1 );
function admin_order_list_top_bar_button( $which ) {
    global $typenow;

    if ( 'product' === $typenow && 'top' === $which ) {
        ?>
        <div class="alignleft actions custom">
            <button type="button" id="publish_new_products" style="height:32px;" class="button" value = "Refresh">
                <?php echo __( 'Publish New Products', 'woocommerce' ); ?>
            </button>
        </div>
        <?php
    }
}

// Add this function to enqueue your script and localize the admin-ajax.php URL and nonce
function enqueue_custom_scripts() {
    wp_enqueue_script('custom-admin-script', get_template_directory_uri() . '/update_list.js', array('jquery'), null, true);
    wp_localize_script('custom-admin-script', 'adminAjax', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]
    );
}
add_action('admin_enqueue_scripts', 'enqueue_custom_scripts');




add_action('wp_ajax_publish_new_products', 'update_product_credential');
add_action('wp_ajax_nopriv_publish_new_products', 'update_product_credential');

function update_product_credential() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'updated_product_creds';

    $args = [
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];

    $products = get_posts($args);
    $inserted_count = 0;
    $updated_count = 0;

    // Check if products exist
    if (empty($products)) {
        $wpdb->query("TRUNCATE TABLE $table_name");
        wp_send_json_success(['message' => 'Table Emptied']);
        wp_die();
    }

    foreach ($products as $product) {
        $post_id = $product->ID;

        $data = [
            'post_id' => $post_id,
            'post_name' => $product->post_title,
            'post_content' => $product->post_content,
            'post_image' => get_the_post_thumbnail_url($post_id, 'full'),
            'post_price' => get_post_meta($post_id,'_sale_price',true),
        ];

        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table_name WHERE post_id = %d",
            $post_id
        ));

        if (!$exists) {
            // Insert new product
            $result = $wpdb->insert($table_name, $data);
            if ($result) {
                $inserted_count++;
            }
        } else {
            // Update existing product
            $result = $wpdb->update($table_name, $data, ['post_id' => $post_id]);
            if ($result !== false) {
                $updated_count++;
            }
        }
    }

    // Send response based on counts
    if ($inserted_count > 0 || $updated_count > 0) {
        wp_send_json_success([
            'message' => sprintf('%d new products published and %d products updated successfully.', $inserted_count, $updated_count),
        ]);
    } else {
        wp_send_json_error(['message' => 'No new products to publish or update.']);
    }
}



//                  ********************************************* Login ********************************************
function furni_user_login() {

    check_ajax_referer('custom_login_nonce', 'custom_login_nonce_field');
    $username   = ($_POST["username"]);
    $user_password = $_POST["user_password"];
    
    $creds = [
        'user_login'    => $username,
        'user_password' => $user_password,
        'remember'      => true
    ];

    $user = wp_signon($creds, false);
    
    if (is_wp_error($user)) {
        wp_send_json(array('loggedin' => false, 'message' => __('Wrong username or password.')));
    } else {
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
        do_action('wp_login', $user->user_login, $user);
        wp_send_json(array('loggedin' => true, 'message' => __('Login successful.')));
    }
    die();
}
add_action('wp_ajax_nopriv_custom_login', 'furni_user_login');
add_action('wp_ajax_custom_login', 'furni_user_login');


//                  ***************************************** Register User *****************************************

function furni_user_create(){ 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = isset($_POST['username'])?$_POST['username']:'';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        $user_id = wp_create_user($username,$password,$email);
        if (is_wp_error($user_id)) {
            echo json_encode(['data' => 'Error in field']);
        } else {
            echo json_encode(['data' => 'User created successfully']);
        }
    }
    die();
}
add_action('wp_ajax_nopriv_register_user', 'furni_user_create');
add_action('wp_ajax_register_user', 'furni_user_create');


//                  ***************************************** User Redirect *****************************************

function redirect_user_to_login_page(){

    if(!is_user_logged_in()&&is_page(255)):
        wp_redirect('http://localhost/furni/register-new-user/');
    endif;
}
add_action('template_redirect','redirect_user_to_login_page');

//                  ****************************************  Chat Table  *****************************************

function create_chart_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'chat_messages';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT,
        sent_by VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

create_chart_table();

//                  ****************************************  Display Chat  *****************************************    

function ajax_display_data() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'chat_messages';
    $message = sanitize_textarea_field($_POST['textarea']);

    if (empty($message)) {
        wp_send_json_error(['message' => 'Message cannot be empty.']);
        return;
    }

    $current_user = wp_get_current_user();
    $user_email = $current_user->user_email;

    $data = [
        'sent_by' => $user_email,
        'message' => $message
    ];

    $result = $wpdb->insert($table_name, $data);

    if ($result === false) {
        wp_send_json_error(['message' => 'Failed to save message.']);
    } else {
        wp_send_json_success([
            'message' => 'Message sent successfully.'
        ]);
    }
}
add_action('wp_ajax_send_chat_message', 'ajax_display_data');
add_action('wp_ajax_nopriv_send_chat_message', 'ajax_display_data');


//                  ****************************************  Fetch Messages  *****************************************    

function ajax_fetch_messages() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'chat_messages';

    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name ORDER BY createdAt ASC"
        )
    );

    if ($results) {
        wp_send_json_success(['messages' => $results]);
    } else {
        wp_send_json_error(['message' => 'Failed to fetch messages.']);
    }
}
add_action('wp_ajax_fetch_chat_messages', 'ajax_fetch_messages');
add_action('wp_ajax_nopriv_fetch_chat_messages', 'ajax_fetch_messages');


// function display_wp_post_queries() {

//     global $wp_query;

//     echo "<pre>";
//     print_r($wp_query);
//     echo "</pre>";

//     die();

// }

// add_action('template_redirect', 'display_wp_post_queries');
// Ensure this code is at the very top of your functions.php file
if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Add custom rewrite rule for the new login URL
add_action('init', 'custom_login_rewrite_rule', 10, 0);
function custom_login_rewrite_rule() {
    add_rewrite_rule('^admin_dashboard/?$', 'wp-login.php', 'top');
}

// Redirect wp-admin to custom login page
add_action('init', 'redirect_wp_admin', 1);
function redirect_wp_admin() {
    if (strpos($_SERVER['REQUEST_URI'], 'wp-admin') !== false && !is_admin() && !current_user_can('manage_options')) {
        wp_safe_redirect(home_url('admin_dashboard'));
        exit();
    }
}

// Change login URL
add_filter('login_url', 'custom_login_url', 10, 3);
function custom_login_url($login_url, $redirect, $force_reauth) {
    return home_url('admin_dashboard', 'login');
}

// Handle custom login page
add_action('parse_request', 'handle_custom_login_page');
function handle_custom_login_page($wp) {
    if (isset($wp->request) && $wp->request == 'admin_dashboard') {
        require_once(ABSPATH . 'wp-login.php');
        exit;
    }
}

// Pre-define variables for wp-login.php
add_action('login_init', 'predefined_login_variables');
function predefined_login_variables() {
    global $user_login, $error;
    $user_login = isset($_POST['log']) ? wp_unslash($_POST['log']) : '';
    $error = '';
}

// Remove "Powered by WordPress" text
add_action('login_headerurl', '__return_empty_string');

// Customize login error messages
add_filter('login_errors', 'custom_login_error_messages');
function custom_login_error_messages($error) {
    global $errors;
    if (is_wp_error($errors) && $errors->get_error_code()) {
        $error = 'Invalid username or password. Please try again.';
    }
    return $error;
}

// Flush rewrite rules on theme switch
add_action('after_switch_theme', 'flush_rewrite_rules');

// Debug logging function
if (!function_exists('debug_log')) {
    function debug_log($message) {
        if (WP_DEBUG === true) {
            if (is_array($message) || is_object($message)) {
                error_log(print_r($message, true));
            } else {
                error_log($message);
            }
        }
    }
}

// Test rewrite rules
add_action('init', 'test_rewrite_rules', 999);
function test_rewrite_rules() {
    global $wp_rewrite;
    debug_log($wp_rewrite->rules);
}


