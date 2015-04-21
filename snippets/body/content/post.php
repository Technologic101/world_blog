<?php
/**
 * @see http://codex.wordpress.org/Template_Tags
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
    <header>
        <h2 class="title"><?php the_title(); ?></h2>
        <div class="meta"><?php echo get_field('date'); ?>
        </div>
        <div class="close"></div>
    </header>
    <div class="content">
        <?php the_content(); ?>
    </div>
    <footer>
        <div class="meta">
            <div class="categories"><?php the_category(', '); ?></div>
            <div class="tags"><?php the_tags('', ', ', ''); ?></div>
            <span class="date">Published on <date><?php the_time('F j, Y'); ?></date></span>
        </div>
    </footer>
</article>
