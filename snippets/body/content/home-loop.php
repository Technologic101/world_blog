<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <?php the_content(); ?>
<?php endwhile; endif; ?>
<?php $query = new WP_Query( array( 'posts_per_page' => -1 ) ); ?>
<?php 
    $posts = array();
?>
<?php 
    if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
        $posts[get_the_ID()] = array(
            'latitude'  => floatval(get_field('latitude')),
            'longitude' => floatval(get_field('longitude')),
            'icon'      => get_field('icon'),
            'name'      => get_the_title(),
            'url'       => get_field('url')
        );
    endwhile; endif;
?>
<?php wp_localize_script('envoy-global', 'acf_fields', $posts); ?>