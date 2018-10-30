<?php
/*
 * Template Name: Checkout
 */

if (count($_SESSION[BB_CART_SESSION_ITEM]) == 0) {
    wp_redirect('/donate');
    exit;
}

get_header();
Spark_Theme::section('name=checkout&file=checkout.php' );
get_footer();
