<?php
/**
 * @version F.1.0
 *
 */

// -------------------------------
// 1. setup the post and the data
// -------------------------------
global $post;
extract(Spark_Theme::setup_data(__FILE__));
$t_period = LONG_TERM;

// -------------------------------------------
// 2. setup local css transient for this file
// -------------------------------------------
$t_args = array('name' => 'css', 'file' => $file);
$transient = Spark_Transients::name($t_args);
if (!Spark_Transients::use_transients()) {
    delete_transient($transient);
}
if (false === ($ob = get_transient($transient))) {
    ob_start();
?>
<style>
/* START: <?php echo $file.' - '.date("Y-m-d H:i:s"); ?> */
@media only screen {
    #row-copyright {background-color: <?php echo spark_get_theme_mod('colour2'); ?>;color: <?php echo spark_get_theme_mod('colour1'); ?>;font-weight: 700; font-size:0.8rem; padding-top: 1rem; padding-left: .9375rem; padding-right: .9375rem; }
    #row-copyright div { padding-bottom: 1rem; }
    #row-copyright a {color:<?php echo spark_get_theme_mod('colour6'); ?>; }
    #row-copyright li {display: inline-block; text-align: center;}
    #row-copyright li:after {content:"|"; padding-left: 0.5rem; padding-right: 0.5rem;}
    #row-copyright li:last-of-type:after {content:" ";}
}
@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */}
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */ }
@media only screen and (min-width: <?php echo ROW_MAX_WIDTH; ?> ) {}
@media only screen and (min-width: <?php echo SITE_MAX_WIDTH; ?> ) {}
/* END: <?php echo $file; ?> */
</style>
<?php
    $ob = ob_get_clean();
    set_transient($transient, $ob, $t_period);
}
echo $ob;
unset($ob, $t_args, $transient);

// -------------------------------------------
// 3. setup local css transient for this post
// -------------------------------------------
$t_args = array('name' => 'css'.$transient_suffix, 'file' => $file);
$transient = Spark_Transients::name($t_args);
if (!Spark_Transients::use_transients()) {
    delete_transient($transient);
}
if (false === ($ob = get_transient($transient))) {
    ob_start();
?>
<style>
/* START: <?php echo $file.' - '.date("Y-m-d H:i:s"); ?> */
@media only screen {}
@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */ }
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */ }
@media only screen and (min-width: <?php echo ROW_MAX_WIDTH; ?> ) {}
@media only screen and (min-width: <?php echo SITE_MAX_WIDTH; ?> ) {}
/* END: <?php echo $file; ?> */
</style>
<?php
    $ob = ob_get_clean();
    set_transient($transient, $ob, $t_period);
}
echo $ob;
unset($ob, $t_args, $transient);

// ----------------------------
// 4. setup output transient/s
// ----------------------------
$t_args = array('name' => 'markup'.$transient_suffix, 'file' => $file);
$transient = Spark_Transients::name($t_args);
if (!Spark_Transients::use_transients()) {
    delete_transient($transient);
}
if (false === ($ob = get_transient($transient))) {
    ob_start();

    // section content - start
    echo '<!-- START: '.$file.' -->'."\n";

    // section content
?>

<div class="gf2 small-24 hide-for-medium text-center cell hide-for-print">
    <?php echo spark_get_theme_mod('copyright'); ?> | <a class="gf2" href="/privacy">Privacy Policy</a><br><a href="https://sparkweb.com.au/">Website design and development by Spark Web Solutions</a>
</div>
<div class="gf2 show-for-medium medium-24 cell hide-for-print">
    <?php echo spark_get_theme_mod('copyright'); ?> | <a class="gf2" href="/privacy">Privacy Policy</a> | <a href="https://sparkweb.com.au/">Website design and development by Spark Web Solutions</a>
</div>
<?php
    // section content - end
    echo '<!-- END:'.$file.' -->'."\n";

    $ob = ob_get_clean();
    set_transient($transient, $ob, $t_period);
}
echo $ob;
unset($ob, $t_args, $transient);
