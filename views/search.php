<?php 
/**
 * Search View
 *
 * Location: #content 
 */ 
?>
<header>
    <h2 class="title"><?php the_search_query(); ?></h2>
</header>
<div class="content">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php echo envoy2014_render('snippets/body/content/excerpt.php'); ?>
    <?php endwhile; endif; ?>
</div>
<footer>
    <?php echo envoy2014_render('snippets/body/content/post-pager.php'); ?>
</footer>
