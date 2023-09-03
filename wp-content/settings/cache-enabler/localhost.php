<?php
/**
 * The settings file for Cache Enabler.
 *
 * This file is automatically created, mirroring the plugin settings saved in the
 * database. It is used to cache and deliver pages.
 *
 * @site  http://localhost/purevoicefight
 * @time  Sat, 02 Sep 2023 15:31:31 GMT
 *
 * @since  1.5.0
 * @since  1.6.0  The `clear_site_cache_on_saved_post` setting was added.
 * @since  1.6.0  The `clear_complete_cache_on_saved_post` setting was removed.
 * @since  1.6.0  The `clear_site_cache_on_new_comment` setting was added.
 * @since  1.6.0  The `clear_complete_cache_on_new_comment` setting was removed.
 * @since  1.6.0  The `clear_site_cache_on_changed_plugin` setting was added.
 * @since  1.6.0  The `clear_complete_cache_on_changed_plugin` setting was removed.
 * @since  1.6.1  The `clear_site_cache_on_saved_comment` setting was added.
 * @since  1.6.1  The `clear_site_cache_on_new_comment` setting was removed.
 * @since  1.7.0  The `mobile_cache` setting was added.
 * @since  1.8.0  The `use_trailing_slashes` setting was added.
 * @since  1.8.0  The `permalink_structure` setting was deprecated.
 */

return array (
  'version' => '1.8.7',
  'use_trailing_slashes' => 1,
  'permalink_structure' => 'has_trailing_slash',
  'cache_expires' => 1,
  'cache_expiry_time' => 1,
  'clear_site_cache_on_saved_post' => 0,
  'clear_site_cache_on_saved_comment' => 0,
  'clear_site_cache_on_saved_term' => 0,
  'clear_site_cache_on_saved_user' => 0,
  'clear_site_cache_on_changed_plugin' => 0,
  'convert_image_urls_to_webp' => 0,
  'mobile_cache' => 0,
  'compress_cache' => 1,
  'minify_html' => 0,
  'minify_inline_css_js' => 0,
  'excluded_post_ids' => '',
  'excluded_page_paths' => '/^\\/(wp\\-admin|wp\\-cron\\.php|wp\\-includes|wp\\-json|xmlrpc\\.php|affiliate\\-area\\.php|page\\/ref|ref|checkout|purchase\\-confirmation|events\\/|courses|groups|lessons|quizzes|sfwd\\-|topic\\/|addons|administrator|cart|login|my\\-account|resetpass|store|thank\\-you)\\/?/',
  'excluded_query_strings' => '/^\\/(_ga|_ke|age\\-verified|cn\\-reloaded|fb_action_ids|fb_action_types|fb_source|fbclid|gclid|mc_cid|mc_eid|ref|usqp|utm_campaign|utm_content|utm_expid|utm_medium|utm_source|utm_term)\\/?/',
  'excluded_cookies' => '/^(?!wordpress_test_cookie)(comment_author_|wordpress_|wp\\-postpass_|wp\\-resetpass\\-|wp\\-settings\\-|edd_cart|edd_cart_fees|edd_cart_messages|edd_discounts|edd_items_in_cart|edd_purchase|edd_resume_payment|preset_discount|learndash_|learndash\\-|ld_|ldadv\\-|woocommerce_|wp_woocommerce_session).*/',
);