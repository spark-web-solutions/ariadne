<style>
@media only screen {
    .cell.column-block { margin-bottom:1rem;}
    .cell.column-block span {display:block;}
    .cell.column-block span.description {padding: 1rem; border:2px solid <?php echo spark_get_theme_mod('colour6');?>; border-top:0; box-shadow: 1px 1px 2px rgba(0,0,0,0.2);}
    .cell.column-block span.description > span {font-size:0.8rem; line-height:1rem;margin-bottom: 0rem;}
    .cell.column-block span.description > span.title {font-weight: 900; font-size: 1rem; margin-bottom: 0.375rem;}
    .cell.column-block .button.read-more { background-color: <?php echo spark_get_theme_mod('colour5');?>; color:<?php echo spark_get_theme_mod('colour1');?>; border-radius: 0; padding: 0.4rem 1.5rem; float: right; margin-bottom:0; margin-right: 1rem;box-shadow: 1px 1px 2px rgba(0,0,0,0.2);}
    .cell.column-block span.title {text-overflow: ellipsis; white-space: nowrap;overflow: hidden;}
    .background-center.background-cover {height: 120px;background-color:<?php echo spark_get_theme_mod('colour4');?>;box-shadow: 1px 1px 2px rgba(0,0,0,0.2);}
}
@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */
    .cell.column-block span.title {font-size: 1.1rem; line-height: 1.1rem;}
    .cell.column-block span.description > span {font-size:0.9rem;margin-bottom: 1rem;}
    .cell.column-block span.title {white-space: normal;overflow: visible;}
    .background-center.background-cover {height: 160px;}
}
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */
    .post-preview .title {margin-bottom: 0.5rem;}
    .post-preview .description {margin-bottom: 0rem;}
}
</style>
<?php
// set up variables
$post = get_post($ID);
$image = spark_get_featured_image_url('medium',$ID);
$title = $post->post_title;
$content = $post->post_content;
$link = get_permalink($post);
$extract = spark_extract($content, 100);

// create card
echo '<a href="'.$link.'" class="preview cell column-block">'."\n";
echo '  <span class="background-center background-cover" style="background-image: url('.$image.');"></span>'."\n";
echo '  <span class="description" data-equalizer-watch>'."\n";
echo '      <span class="title h4 text8">'.$title.'</span>'."\n";
echo '      <span class="text8 blurb">'.$extract.'</span>'."\n";
echo '  </span>'."\n";
echo '  <span class="button read-more"><em class="gf3">Read More</em></span>'."\n";
echo '</a>'."\n";
