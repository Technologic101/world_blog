<?php
$s = $wp_query->query_vars['name'];
$posts = array();

if (false === empty($s)) {
    $s = preg_replace("/(.*)-(html|htm|php|asp|aspx)$/","$1", $s);
    $posts = query_posts(array('post_type' => 'any', 'name' => $s, 'posts_per_page' => 10));

    if (count($posts) == 0) {
        $s = str_replace("-", " ", $s);
        $posts = query_posts(array('post_type' => 'any', 'name' => $s, 'posts_per_page' => 10));
    }
}
?>
<h2>Our apologies</h2>
<h3>The page you are looking for cannot be displayed</h3>

<p>We welcome you to check out the rest of our web site!</p>

<?php if (count($posts) > 0): ?>
<p>Were you looking for the following?</p>
<ul>
    <?php foreach ($posts as $_post): ?>
    <li><a href="<?php echo get_permalink($_post->ID); ?>"><?php echo $_post->post_title; ?></a></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php wp_nav_menu(array('menu' => 'main', 'container' => false, 'menu_class' => '')); ?>
