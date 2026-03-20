<?php
/**
 * ShopCo Mobile Theme Functions
 *
 * Handles theme setup, ACF field registration,
 * and one-click sample data generation.
 */

/* ---------- Enqueue ---------- */
function shopco_enqueue_scripts() {
    wp_enqueue_style( 'shopco-style', get_stylesheet_uri(), array(), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'shopco_enqueue_scripts' );

/* ---------- Theme supports ---------- */
function shopco_theme_setup() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'shopco_theme_setup' );

/* ---------- Allow SVG uploads ---------- */
add_filter( 'upload_mimes', function ( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});

/* ---------- Register ACF field group (code-based) ---------- */
if ( function_exists( 'acf_add_local_field_group' ) ) :
    acf_add_local_field_group( array(
        'key'      => 'group_shopco_homepage',
        'title'    => 'Homepage Settings',
        'fields'   => array(
            array(
                'key'           => 'field_announcement_text',
                'label'         => 'Announcement Bar Text',
                'name'          => 'announcement_text',
                'type'          => 'text',
                'default_value' => 'Sign up and get 20% off to your first order.',
            ),
            array(
                'key'           => 'field_announcement_link_text',
                'label'         => 'Announcement Link Text',
                'name'          => 'announcement_link_text',
                'type'          => 'text',
                'default_value' => 'Sign Up Now',
            ),
            array(
                'key'           => 'field_announcement_link',
                'label'         => 'Announcement Link URL',
                'name'          => 'announcement_link',
                'type'          => 'url',
                'default_value' => '#',
            ),
            array(
                'key'           => 'field_hero_heading',
                'label'         => 'Hero Heading',
                'name'          => 'hero_heading',
                'type'          => 'text',
                'default_value' => 'FIND CLOTHES THAT MATCHES YOUR STYLE',
            ),
            array(
                'key'           => 'field_hero_subheading',
                'label'         => 'Hero Subheading',
                'name'          => 'hero_subheading',
                'type'          => 'textarea',
                'default_value' => 'Browse through our diverse range of meticulously crafted garments, designed to bring out your individuality and cater to your sense of style.',
            ),
            array(
                'key'           => 'field_hero_button_text',
                'label'         => 'Hero Button Text',
                'name'          => 'hero_button_text',
                'type'          => 'text',
                'default_value' => 'Shop Now',
            ),
            array(
                'key'   => 'field_hero_button_link',
                'label' => 'Hero Button Link',
                'name'  => 'hero_button_link',
                'type'  => 'url',
            ),
            array(
                'key'           => 'field_hero_image',
                'label'         => 'Hero Image',
                'name'          => 'hero_image',
                'type'          => 'image',
                'return_format' => 'array',
            ),
            array(
                'key'           => 'field_brand_logo_1',
                'label'         => 'Brand Logo 1',
                'name'          => 'brand_logo_1',
                'type'          => 'image',
                'return_format' => 'url',
            ),
            array(
                'key'           => 'field_brand_logo_2',
                'label'         => 'Brand Logo 2',
                'name'          => 'brand_logo_2',
                'type'          => 'image',
                'return_format' => 'url',
            ),
            array(
                'key'           => 'field_brand_logo_3',
                'label'         => 'Brand Logo 3',
                'name'          => 'brand_logo_3',
                'type'          => 'image',
                'return_format' => 'url',
            ),
            array(
                'key'           => 'field_brand_logo_4',
                'label'         => 'Brand Logo 4',
                'name'          => 'brand_logo_4',
                'type'          => 'image',
                'return_format' => 'url',
            ),
            array(
                'key'           => 'field_brand_logo_5',
                'label'         => 'Brand Logo 5',
                'name'          => 'brand_logo_5',
                'type'          => 'image',
                'return_format' => 'url',
            ),
            array(
                'key'           => 'field_new_arrivals_category',
                'label'         => 'New Arrivals Category',
                'name'          => 'new_arrivals_category',
                'type'          => 'taxonomy',
                'taxonomy'      => 'product_cat',
                'field_type'    => 'select',
                'return_format' => 'id',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'page_type',
                    'operator' => '==',
                    'value'    => 'front_page',
                ),
            ),
        ),
        'hide_on_screen' => array( 'the_content' ),
    ) );
endif;

/*
 * ──────────────────────────────────────────
 *  One-click sample data generator
 *  Visit  wp-admin/?shopco_setup=1
 *  Also runs automatically on theme activation.
 * ──────────────────────────────────────────
 */

/**
 * Import an image from the bundled /assets/ folder
 * into the WP Media Library (skips if already imported).
 */
function shopco_import_asset( $filename ) {
    global $wpdb;

    $existing = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'attachment'",
            sanitize_file_name( $filename )
        )
    );
    if ( $existing ) return (int) $existing;

    $search_paths = array(
        WP_CONTENT_DIR . '/themes/assets/' . $filename,
        WP_CONTENT_DIR . '/themes/assets/Shopco_files/' . $filename,
    );
    $source = '';
    foreach ( $search_paths as $path ) {
        if ( file_exists( $path ) ) { $source = $path; break; }
    }
    if ( ! $source ) return false;

    $upload_dir = wp_upload_dir();
    $dest       = $upload_dir['path'] . '/' . $filename;
    copy( $source, $dest );

    $filetype = wp_check_filetype( $filename );
    if ( ! $filetype['type'] && false !== strpos( $filename, '.svg' ) ) {
        $filetype['type'] = 'image/svg+xml';
    }

    $attach_id = wp_insert_attachment( array(
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_file_name( $filename ),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ), $dest );

    if ( $attach_id && ! is_wp_error( $attach_id ) ) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
        wp_update_attachment_metadata(
            $attach_id,
            wp_generate_attachment_metadata( $attach_id, $dest )
        );
        return $attach_id;
    }
    return false;
}

/**
 * Generate sample WooCommerce products, set up the
 * homepage, and pre-fill ACF fields so the site works
 * right away out of the box.
 */
function shopco_auto_setup() {

    if ( ! taxonomy_exists( 'product_cat' ) ) return;

    // Category
    $cat = term_exists( 'New Arrivals', 'product_cat' );
    if ( ! $cat ) {
        $cat = wp_insert_term( 'New Arrivals', 'product_cat' );
    }
    $cat_id = is_array( $cat ) ? $cat['term_id'] : 0;

    // Sample products
    $products = array(
        array( 'title' => 'T-shirt with Tape Details', 'price' => '120',                                     'image' => 'pic1.png' ),
        array( 'title' => 'Skinny Fit Jeans',          'price' => '260', 'sale' => '240',                     'image' => 'pic2.png' ),
        array( 'title' => 'Checkered Shirt',           'price' => '180',                                      'image' => 'pic3.png' ),
        array( 'title' => 'Sleeve Striped T-shirt',    'price' => '160', 'sale' => '130',                     'image' => 'pic4.png' ),
    );

    foreach ( $products as $p ) {
        if ( get_page_by_title( $p['title'], OBJECT, 'product' ) ) continue;

        $id = wp_insert_post( array(
            'post_title'  => $p['title'],
            'post_status' => 'publish',
            'post_type'   => 'product',
        ) );
        if ( ! $id ) continue;

        wp_set_object_terms( $id, 'simple', 'product_type' );
        wp_set_object_terms( $id, (int) $cat_id, 'product_cat' );

        if ( ! empty( $p['sale'] ) ) {
            update_post_meta( $id, '_regular_price', $p['price'] );
            update_post_meta( $id, '_sale_price',    $p['sale'] );
            update_post_meta( $id, '_price',         $p['sale'] );
        } else {
            update_post_meta( $id, '_regular_price', $p['price'] );
            update_post_meta( $id, '_price',         $p['price'] );
        }

        $img = shopco_import_asset( $p['image'] );
        if ( $img ) set_post_thumbnail( $id, $img );
    }

    // Homepage
    $home = get_page_by_title( 'Home' );
    if ( ! $home ) {
        $home_id = wp_insert_post( array(
            'post_title'    => 'Home',
            'post_type'     => 'page',
            'post_status'   => 'publish',
            'page_template' => 'front-page.php',
        ) );
    } else {
        $home_id = $home->ID;
    }
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $home_id );

    // Pre-fill ACF fields
    if ( function_exists( 'update_field' ) && $home_id ) {
        $defaults = array(
            'announcement_text' => 'Sign up and get 20% off to your first order.',
            'hero_heading'      => 'FIND CLOTHES THAT MATCHES YOUR STYLE',
            'hero_subheading'   => 'Browse through our diverse range of meticulously crafted garments, designed to bring out your individuality and cater to your sense of style.',
            'hero_button_text'  => 'Shop Now',
        );
        foreach ( $defaults as $key => $val ) {
            if ( ! get_field( $key, $home_id ) ) update_field( $key, $val, $home_id );
        }
        if ( $cat_id && ! get_field( 'new_arrivals_category', $home_id ) ) {
            update_field( 'new_arrivals_category', $cat_id, $home_id );
        }

        // Hero image
        $hero = shopco_import_asset( 'header-res-homepage.png' );
        if ( $hero ) update_field( 'hero_image', $hero, $home_id );

        // Brand logos
        $logo_files = array(
            'brand_logo_1' => 'versace-logo.svg',
            'brand_logo_2' => 'zara-logo.svg',
            'brand_logo_3' => 'gucci-logo.svg',
            'brand_logo_4' => 'prada-logo.svg',
            'brand_logo_5' => 'calvin-klein-logo.svg',
        );
        foreach ( $logo_files as $field_name => $file ) {
            $logo_id = shopco_import_asset( $file );
            if ( $logo_id && ! get_field( $field_name, $home_id ) ) {
                update_field( $field_name, $logo_id, $home_id );
            }
        }
    }
}
add_action( 'after_switch_theme', 'shopco_auto_setup' );

/* Admin button so the user can re-run setup at any time */
add_action( 'admin_notices', function () {
    if ( ! current_user_can( 'manage_options' ) ) return;

    if ( isset( $_GET['shopco_setup'] ) ) {
        shopco_auto_setup();
        echo '<div class="notice notice-success is-dismissible"><p>Sample products and homepage settings created.</p></div>';
        return;
    }
    $url = esc_url( admin_url( '?shopco_setup=1' ) );
    echo '<div class="notice notice-info"><p><a href="' . $url . '" class="button button-primary">Run Shop.co Auto-Setup</a> &mdash; Creates sample products, homepage, and ACF defaults.</p></div>';
});
