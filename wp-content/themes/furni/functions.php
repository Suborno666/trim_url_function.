<?php


function my_theme_setup(){
    add_theme_support('post-thumbnails');
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

// function custom_item_post_link($post_link, $post) {
//     if ($post->post_type === 'item') {
//         return home_url($post->post_name . '/');
//     }
//     return $post_link;
// }
// add_filter('post_type_link', 'custom_item_post_link', 10, 2);

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
?>