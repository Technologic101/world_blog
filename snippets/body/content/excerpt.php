<?php
/**
 * @see http://codex.wordpress.org/Template_Tags
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('excerpt'); ?>>
    <header>
        <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div class="meta">
            <span class="author"><?php the_author(); ?></span> 
            <span class="date"><?php the_time('F j, Y'); ?></span>
        </div>
    </header>
    <div class="content">
        <?php the_excerpt(); ?> <a href="<?php the_permalink(); ?>">Read More...</a>
    </div>
    <footer>
        <div class="meta">
            <div class="categories"><?php the_category(', '); ?></div>
            <div class="tags"><?php the_tags('', ', ', ''); ?></div>
        </div>
    </footer>
</article>
