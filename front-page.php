<?php
/**
 * Template Name: Homepage Template
 *
 * Mobile homepage – pulls all content dynamically
 * from ACF custom fields and WooCommerce products.
 */
get_header();

// ACF fields with sensible defaults
$hero_heading     = get_field( 'hero_heading' )     ?: 'FIND CLOTHES THAT MATCHES YOUR STYLE';
$hero_subheading  = get_field( 'hero_subheading' )  ?: 'Browse through our diverse range of meticulously crafted garments, designed to bring out your individuality and cater to your sense of style.';
$hero_button_text = get_field( 'hero_button_text' ) ?: 'Shop Now';
$hero_button_link = get_field( 'hero_button_link' ) ?: ( function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : '#' );
$hero_image       = get_field( 'hero_image' );
$arrivals_cat     = get_field( 'new_arrivals_category' );

// Resolve hero image URL (ACF can return id | array | url)
$hero_img_url = '';
if ( is_array( $hero_image ) )        $hero_img_url = $hero_image['url'];
elseif ( is_numeric( $hero_image ) )  $hero_img_url = wp_get_attachment_url( $hero_image );
elseif ( $hero_image )                $hero_img_url = $hero_image;

if ( ! $hero_img_url ) {
    $hero_img_url = get_template_directory_uri() . '/img/header-res-homepage.png';
}
?>

<!-- Hero -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title"><?php echo esc_html( $hero_heading ); ?></h1>
        <p class="hero-subtitle"><?php echo esc_html( $hero_subheading ); ?></p>
        <a href="<?php echo esc_url( $hero_button_link ); ?>" class="hero-btn"><?php echo esc_html( $hero_button_text ); ?></a>

        <div class="hero-stats">
            <div class="stat-item">
                <div class="stat-value">200+</div>
                <div class="stat-label">International Brands</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <div class="stat-value">2,000+</div>
                <div class="stat-label">High-Quality Products</div>
            </div>
            <div class="stat-item stat-item-full">
                <div class="stat-value">30,000+</div>
                <div class="stat-label">Happy Customers</div>
            </div>
        </div>
    </div>

    <div class="hero-image">
        <img src="<?php echo esc_url( $hero_img_url ); ?>" alt="<?php echo esc_attr( $hero_heading ); ?>">
        <img src="<?php echo get_template_directory_uri(); ?>/img/big-star.svg"   alt="" class="star-big">
        <img src="<?php echo get_template_directory_uri(); ?>/img/small-star.svg" alt="" class="star-small">
    </div>
</section>

<!-- Brands -->
<section class="brands-bar">
<?php 
    $logos_found = false;
    for ( $i = 1; $i <= 5; $i++ ) {
        $logo_field = get_field( "brand_logo_{$i}" );
        if ( $logo_field ) {
            $logos_found = true;
            $src = is_array( $logo_field ) ? $logo_field['url'] : ( is_numeric( $logo_field ) ? wp_get_attachment_url( $logo_field ) : $logo_field );
            echo '<img src="' . esc_url( $src ) . '" alt="Brand Logo ' . $i . '">';
        }
    }

    if ( ! $logos_found ) :
        $defaults = array(
            'versace-logo.svg'      => 'Versace',
            'zara-logo.svg'         => 'Zara',
            'gucci-logo.svg'        => 'Gucci',
            'prada-logo.svg'        => 'Prada',
            'calvin-klein-logo.svg' => 'Calvin Klein',
        );
        foreach ( $defaults as $file => $name ) : ?>
            <img src="<?php echo get_template_directory_uri(); ?>/img/<?php echo $file; ?>" alt="<?php echo esc_attr( $name ); ?>">
        <?php endforeach; ?>
<?php endif; ?>
</section>

<!-- New Arrivals -->
<section class="product-section">
    <h2 class="section-heading">NEW ARRIVALS</h2>

    <div class="products-grid">
    <?php
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'post_status'    => 'publish',
    );
    if ( $arrivals_cat ) {
        $args['tax_query'] = array( array(
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $arrivals_cat,
        ) );
    }

    $products = new WP_Query( $args );

    if ( $products->have_posts() ) :
        while ( $products->have_posts() ) : $products->the_post();
            global $product;

            // Discount percentage
            $discount = 0;
            if ( $product->is_on_sale() && $product->get_regular_price() ) {
                $regular  = (float) $product->get_regular_price();
                $sale     = (float) $product->get_sale_price();
                $discount = $regular > 0 ? round( ( $regular - $sale ) / $regular * 100 ) : 0;
            }

            // Star rating
            $rating      = $product->get_average_rating() ?: 4.5;
            $full_stars  = floor( $rating );
            $has_half    = ( $rating - $full_stars ) >= 0.25;
            $empty_stars = 5 - $full_stars - ( $has_half ? 1 : 0 );
            ?>
            <div class="product-card">
                <div class="product-image">
                    <?php the_post_thumbnail( 'woocommerce_thumbnail' ); ?>
                </div>
                <div class="product-title"><?php the_title(); ?></div>
                <div class="product-rating">
                    <span class="stars"><?php
                        echo str_repeat( '★', $full_stars );
                        if ( $has_half )    echo '<span style="opacity:.6">★</span>';
                        if ( $empty_stars ) echo '<span style="opacity:.2">' . str_repeat( '★', $empty_stars ) . '</span>';
                    ?></span>
                    <span class="rating-number"><?php echo number_format( $rating, 1 ); ?>/5</span>
                </div>
                <div class="product-price-row">
                    <?php echo $product->get_price_html(); ?>
                    <?php if ( $discount ) : ?>
                        <span class="discount-badge">-<?php echo $discount; ?>%</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; wp_reset_postdata();
    else : ?>
        <p class="no-products">No products found. Add products in WooCommerce.</p>
    <?php endif; ?>
    </div>

    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="view-all-btn">View All</a>
</section>

<?php get_footer();
