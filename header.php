<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$front_id        = get_option( 'page_on_front' );
$announce_text   = get_field( 'announcement_text', $front_id )      ?: 'Sign up and get 20% off to your first order.';
$announce_label  = get_field( 'announcement_link_text', $front_id ) ?: 'Sign Up Now';
$announce_url    = get_field( 'announcement_link', $front_id );
?>

<div class="announcement-bar">
    <?php echo esc_html( $announce_text ); ?>
    <?php if ( $announce_url ) : ?>
        <a href="<?php echo esc_url( $announce_url ); ?>"><?php echo esc_html( $announce_label ); ?></a>
    <?php endif; ?>
</div>

<header class="site-header">
    <div class="header-menu-icon">
        <img src="<?php echo get_template_directory_uri(); ?>/img/menu.svg" alt="Menu">
    </div>
    <div class="site-logo">
        <a href="<?php echo esc_url( home_url() ); ?>">SHOP.CO</a>
    </div>
    <div class="header-icons">
        <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/img/search-black.svg" alt="Search"></a>
        <a href="<?php echo esc_url( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#' ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/cart.svg" alt="Cart"></a>
        <a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : '#' ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/user.svg" alt="Account"></a>
    </div>
</header>
