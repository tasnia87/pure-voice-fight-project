<?php

function cactusthemes_scripts_styles_child_theme() {
	global $wp_styles;
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array('bootstrap', 'font-awesome', 'owl-carousel', 'owl-carousel-theme'));
}
add_action( 'wp_enqueue_scripts', 'cactusthemes_scripts_styles_child_theme' );

/* Disable VC auto-update */
function cactusthemes_vc_disable_update() {
    if (function_exists('vc_license') && function_exists('vc_updater') && ! vc_license()->isActivated()) {

        remove_filter( 'upgrader_pre_download', array( vc_updater(), 'preUpgradeFilter' ), 10);
        remove_filter( 'pre_set_site_transient_update_plugins', array(
            vc_updater()->updateManager(),
            'check_update'
        ) );

    }
}
