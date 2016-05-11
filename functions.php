<?php

/* --------------------------------------------------------------
   General
   -------------------------------------------------------------- */

// Set content width

if ( ! isset( $content_width ) ) {
    $content_width = 1170;
}


/* --------------------------------------------------------------
   Plugins
   -------------------------------------------------------------- */

//  Disable contact form 7 scripts and styles

// add_filter( 'wpcf7_load_js', '__return_false' );
// add_filter( 'wpcf7_load_css', '__return_false' );


//  Use this to load contact form 7 scripts and styles on the contact page template

// if ( function_exists( 'wpcf7_enqueue_scripts' ) ) wpcf7_enqueue_scripts();
// if ( function_exists( 'wpcf7_enqueue_styles' ) ) wpcf7_enqueue_styles();


//  Hide yoast for defined custom post types

/* 
function yoast_is_toast(){
    if (!current_user_can('activate_plugins')) {
        remove_meta_box('wpseo_meta', 'custom_post_type', 'normal');
    }
} add_action('add_meta_boxes', 'yoast_is_toast', 99); 
*/


/* --------------------------------------------------------------
   Menus
   -------------------------------------------------------------- */

// Configure example nav

function primary_menu() {
    wp_nav_menu(
        array(
            'theme_location'  => 'primary',
            'menu'            => '',
            'container'       => '',
            'container_class' => '',
            'container_id'    => '',
            'menu_class'      => 'menu',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul>%3$s</ul>',
            'depth'           => 0,
            'walker'          => ''
        )
    );
}


/* --------------------------------------------------------------
   Widget Areas
   -------------------------------------------------------------- */



/* --------------------------------------------------------------
   Custom Posts
   -------------------------------------------------------------- */



/* --------------------------------------------------------------
   Wordpress Setup
   -------------------------------------------------------------- */


// Remove p tags from images

function filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
} add_filter('the_content', 'filter_ptags_on_images');


// Set custom excerpt length

function set_excerpt_length( $length ) {
    return 50;
} add_filter( 'excerpt_length', 'set_excerpt_length', 999 );


// Set excerpt more link

function set_excerpt_more( $more ) {
    return ' ...';
} add_filter('excerpt_more', 'set_excerpt_more');


// Set Wordpress login logo image

function set_login_logo() { ?> 
    <style type="text/css"> 
        #login h1 a { 
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/symbol.png);
            -webkit-background-size: 140px;
            background-size: 140px;
            height: 140px;
            width: auto;
        }
    </style>
<?php } add_action( 'login_enqueue_scripts', 'set_login_logo' );


// Set Wordpress login logo url

function set_logo_url() {
    return home_url();
} add_filter( 'login_headerurl', 'set_logo_url' );


// Set Wordpress login logo title

function set_logo_title() {
    return get_bloginfo("name");
} add_filter( 'login_headertitle', 'set_logo_title' );


// Remove Wordpress admin logos

function remove_admin_logos() { ?> 
    <style type="text/css"> 
        #wpadminbar #wp-admin-bar-wp-logo, #footer-thankyou { display: none; } 
    </style>
<?php } add_action('wp_before_admin_bar_render', 'remove_admin_logos', 0);


// Remove unused wordpress interface items

function remove_menus() {
    // remove_menu_page( 'edit-comments.php' );   // Comments
    // remove_menu_page( 'tools.php' );        // Tools
} add_action( 'admin_menu', 'remove_menus' );


// Disable emoji's

function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );    
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );  
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
} add_action( 'init', 'disable_emojis' );


// Show superscript & subscript in visual editor

function mce_buttons($buttons) {   
    $buttons[] = 'superscript';
    $buttons[] = 'subscript';
    return $buttons;
} add_filter('mce_buttons_2', 'mce_buttons');


// Setup Theme

function setup_theme() {
    // Add custom image sizes

    // add_image_size( 'banner', 1920, 1080, true );
    
    // Register menus

    register_nav_menus(array(
        'primary' => __( 'Primary Menu', 'cornerstone' )
    ));

    // Add theme support

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form', 
        'comment-form', 
        'comment-list', 
        'gallery', 
        'caption',
        'widgets'
    ));

} add_action('after_setup_theme', 'setup_theme');


// Dequeue jQuery Migrate

function dequeue_jquery_migrate(&$scripts){
    if(!is_admin()){
        $scripts->remove('jquery');
        // Uncomment if you want to load jQuery from Wordpress Core
        // $scripts->add('jquery', false, array( 'jquery-core' ));
    }
} add_filter( 'wp_default_scripts', 'dequeue_jquery_migrate' );


// Queue CSS and JS

function queue_scripts() {
    $theme = wp_get_theme();
    $theme_version = $theme['Version'];
    wp_enqueue_style('theme-styles', get_stylesheet_uri(), '', $theme_version);
    wp_enqueue_script('jquery', get_template_directory_uri() . '/js/jquery.js', '', '2.2.3', true);
    wp_enqueue_script('theme-scripts', get_template_directory_uri() . '/js/scripts.js', array( 'jquery' ), $theme_version, true);
} add_action( 'wp_enqueue_scripts', 'queue_scripts' );


// Cleanup Wordpress head

function theme_init() {
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'wp_generator');
} add_action('init', 'theme_init');


?>