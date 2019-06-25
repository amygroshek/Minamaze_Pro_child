<?php
/**
 * Template Name: Tiles
 *
 * @package ThinkUpThemes
 */

get_header(); ?>

<?php
    $args = array(
        'post_type' => 'product',
        'orderby' => 'title',
        'order' => 'asc',
        'posts_per_page' => 9,
        'tax_query' => array(
            // 'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => 'flexbox-list'
            ),
        ),
    );
    $query = new WP_Query( $args );
    $opts = array(
        'grids' => 'Sales Pages Grid',
        'small_screen_grid' => 'Mobile',
        'breakpoint' => 800,
        'byline_template' => '<p>%excerpt%</p><a class="btn btn-primary laurel-btn" href="%link%">Purchase</a>',
        'byline_template_textonly' => '<a class="btn btn-primary laurel-btn" href="%link%">Purchase</a>',
        'text_color' => '#2d2d2d',
        'image_text_color' => false,
        'colors' => array(
            "#fff", //#43266f
            "#fff",
            "#fff",
            "#fff",
            "#fff",
        ),
        'byline_opacity'  => '1',
        'padding' => 10,
        'image_size' => 'medium',
    );
    the_wp_tiles( $query, $opts );
?>

    <?php get_template_part( 'page' ); ?>

<?php get_footer(); ?>
