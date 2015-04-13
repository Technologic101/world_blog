<?php
/**
 * @see http://codex.wordpress.org/Template_Tags
 */
?>
<article id="page-<?php the_ID(); ?>" <?php post_class('page'); ?>>
    <header>
        <h2 class="title"><?php the_title(); ?></h2>
    </header>
    <div class="content">
        <?php the_content(); ?>
    </div>
    <footer>
    </footer>
</article>
