<?php
$post = get_post($ID);
?>
<style>
@media only screen {
    #article-<?php echo $ID; ?> .featured-image {background-image: url(<?php echo spark_get_featured_image_url('medium', $ID);?>);}
}
</style>
<article id="article-<?php echo $ID; ?>" <?php post_class('card post-preview small-24 cell bg4'); ?>>
    <a href="<?php echo get_permalink($ID); ?>">
        <div class="featured-image">
<?php
if (in_array(get_post_type($ID), array('post', 'events', 'stories'))) {
    $date = get_post_type($ID) == 'events' ? spark_get_post_meta($ID, 'event_date') : get_the_time(get_option('date_format'), $ID);
?>
            <p class="subheader"><?php echo $date; ?></p>
<?php
}
$count = 0;
$count += substr_count(strtolower($post->post_title), strtolower($string));
$count += substr_count(strtolower($post->post_content), strtolower($string));
echo '              <span class="count gf2 absolute">'.$count.'x</span>'."\n";
echo '              <span class="post_type gf2 absolute">'.$post->post_type.'</span>'."\n";
unset($count);
?>
        </div>
        <div class="article-excerpt">
<?php
$content = '            <p class="h5">'.get_the_title($ID).'</p>';
$content .= '            <p>'.strip_tags(spark_extract(strip_shortcodes($post->post_content), 300)).'</p>'."\n";
$content = str_replace(strtoupper($string), '<span class="highlight">'.strtoupper($string).'</span>', $content);
$content = str_replace(strtolower($string), '<span class="highlight">'.strtolower($string).'</span>', $content);
$content = str_replace(ucfirst($string), '<span class="highlight">'.ucfirst($string).'</span>', $content);
echo $content;
?>
        </div>
        <p class="button">Read more</p>
    </a>
</article>
