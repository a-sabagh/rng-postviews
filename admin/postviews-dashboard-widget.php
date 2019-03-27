<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>
<div class="container">
    <div class="featured-tab-container">
        <ul class="featured-head">
            <li><a href="#ja-daily-chart-tab"><?php esc_html_e("daily", "rng-postviews"); ?></a></li>
            <li><a href="#ja-weekly-tab"><?php esc_html_e("weekly", "rng-postviews"); ?></a></li>
        </ul><!--.featured-head-->
        <div class="featured-tab-container">
            <div class="tab-menu-content" id="ja-daily-chart-tab">
                <canvas id="ja-daily-chart"></canvas>
            </div><!--#featured-tab-menu-->
            <div class="tab-menu-content" id="ja-weekly-tab">
                <canvas id="ja-weekly-chart"></canvas>
            </div><!--.featured-tab-menu-->
        </div><!--#featured-tab-container-->
    </div><!--.featured-tab-->
</div><!--.container-->