<?php


function my_theme_setup(){
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
}

add_action('after_setup_theme', 'my_theme_setup');


function furni_enqueue_scripts() {

    // CSS
    wp_enqueue_style( 'css-bootstrap', get_template_directory_uri(). '/assets/css/bootstrap.min.css', [], '', 'all' );
    wp_enqueue_style( 'css-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css', [], '', 'all' );
    wp_enqueue_style( 'css-slider', get_template_directory_uri(). '/assets/css/tiny-slider.css', [], '', 'all' );
    wp_enqueue_style( 'css-style', get_template_directory_uri(). '/assets/css/style.css', [], '', 'all' );

    // JS
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



/**
 * How to trim URL?
 */
//**************************To change main query********************************//

add_action('init', 'city_rewrite_rule', 10, 0);
function city_rewrite_rule () {
    add_rewrite_rule('^([^/]+)/?$', 'index.php?item=$matches[1]', 'top');
};

function custom_item_post_link($post_link, $post) {
    if ($post->post_type === 'item') {
        return home_url($post->post_name . '/');
    }
    return $post_link;
}
add_filter('post_type_link', 'custom_item_post_link', 10, 2);




// function set_external_url_post_link( $post_link, $post ) {

//     if ( 'item' === $post->post_type ) {

//         $parsed = parse_url($post_link);
//         $path = $parsed['path'];
//         $path_arr = explode('/',$path);
//         foreach($path_arr as $key=>$value){
//           if('item' == $value){
//             unset($path_arr[$key]);
//           }
//         };

//         $implode_url = implode('/',$path_arr);
//         $URL = "http://localhost".$implode_url;

//         return $URL;
//     }
//     return $post_link;
// }
// add_filter( 'post_type_link', 'set_external_url_post_link', 10, 2 );

// add_filter('pre_get_posts', 'change_term_request', 1, 1);
// function change_term_request($query) {

//     // print_r($query);

//     if(is_admin()){
//         return $query;
//     }

//     if(!is_main_query()){
//         return $query;
//     }
//     if(is_page(15)){
//         return $query;
//     }

//     $query->set('post_type',array('item','page','post'));

//     return $query;
// }

// // add_action('template_redirect','redirect_function');
// function redirect_function(){
    
//     global $wp_query;
//     echo "<pre>";
//     print_r($wp_query);
    

// }

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

/**
 * Meta field callback function
 */
function prefix_mb_callback($post){
    // Add nonce for security
    wp_nonce_field('prefix_save_meta_data', 'prefix_meta_nonce');
    
    $origin = get_post_meta($post->ID, 'origin_key', true);
    ?>
    <label for="mb_origin_id"><?php esc_html_e('Country of Origin:', 'text-domain'); ?></label>
    <input type="text" class="regular-text" value="<?php echo esc_attr($origin); ?>" name="unique_mb_origin_id" id="mb_origin_id">
    <?php
}

/**
 * Save metabox data
 */
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


// On Publish of Product //

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
            array(
                'comment_status' => 'close',
                'ping_status'    => 'close',
                'post_author'    => 1,
                'post_title'     => ucwords($item_name),
                'post_name'      => strtolower(str_replace(' ', '-', trim($item_name))),
                'post_status'    => 'publish',
                'post_type'      => 'item',
                'post_content'   => $content
            )
        );
    } else {
        $updated_post = [
            'ID' =>  $check_item_exist->ID,
            'post_content'   => $content
        ];
        wp_update_post($updated_post);
    }
}


function wpse_save_post_callback( $post_id, $post, $update ) {

    if ( $post->post_type !== 'product' || $post->post_status !== 'publish' ) {
        return;
    }

    $the_title = get_the_title($post_id);
    $origin = get_post_meta($post_id, 'origin_key', true);


    $the_content = $post->post_content . "\n\nCountry of Origin: " . $origin;

    create_item($the_title, $the_content);
    
}
add_action( 'save_post', 'wpse_save_post_callback', 10, 3 );




?>