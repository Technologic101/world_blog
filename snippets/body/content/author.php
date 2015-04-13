<?php 
/**
 * @see http://codex.wordpress.org/Function_Reference/the_author
 * the_author() .post-author-name
 *
 * @see http://codex.wordpress.org/Function_Reference/the_author_meta
 * the_author_meta('*') .post-author-meta=*
 * 
 * @see http://codex.wordpress.org/Function_Reference/get_avatar
 * get_avatar() .post-author-avatar
 */
?>
<div class="post-author">
    <div class="post-author-avatar"><?php echo get_avatar(get_the_author_meta('user_email', $user_ID), '80'); ?></div>
    <div class="post-author-name"><?php the_author(); ?></div>
    <div class="post-author-meta-description"><?php the_author_meta('description'); ?></div>
</div>
