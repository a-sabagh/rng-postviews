=== rng-postviews ===
Contributors: asabagh
Tags: rng, post viewed, postviews, view-post
Requires at least: 4.0
Tested up to: 5.1
Stable tag: 4.1
License: GPLv2 or later

WordPress plugin that set post view count for each post and reports them in a chart.

== Description ==
WordPress plugin that set post view count for each post and reports them in a chart.also you can access to post views count of each post in front end and admin panel.

= You can see post view count with below ways: =
1. WordPress widget
2. Short code `[rngja_postviews]`
3. Developers can access post views in wp_postmeta with meta_key `ja_postviews`
4. Function `rngja_get_post_viewe_count`

For showing post views count with function put this code in your main loop
```
if (function_exists('rngja_get_post_viewe_count')) {
    echo rngja_get_post_viewe_count(get_the_ID());
}
```

= And also in admin panel: =
1. Wordpress Dashboard widget
2. Post list screen

Main features in rng-postviews include

*    Very light and easy to use
*    Have function and short code for showing post views count
*    WordPress Widget
*    This plugin avoids from any conflict with other plugins
*    Has not any overhead

it is strongly was recommended that after the plugin is activated, go to the `Settings > Last post viewed` and configure the plugin.

= Github Repository =
Also You can this plugin on github:
https://github.com/a-sabagh/rng-postviews

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/rng-postviews` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to the permalink settings and change URL structure then undo
4. Use the `Settings > Postviews Settings` screen to configure the plugin

1, 2, 3, 4: You're done!
