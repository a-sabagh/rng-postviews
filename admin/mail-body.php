<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$views = esc_html__(" views", "rng-postviews");
?>
<div style="font-family: arial;">
    <h3 style="display: block;border-bottom: 1px solid #bababa; width: 85%;margin: 15px 0;padding-bottom: 5px;"><?php esc_html_e("PostViews weekly Reporting","rng-postviews"); ?></h3>
    <div style="display: block;overflow: 0;">
        <table style="width: 85%;border-collapse: collapse;margin: 0;">
            <thead>
                <tr style="border-bottom: 1px solid #ddd;">
                    <th><?php esc_html_e("Date", "rng-postviews"); ?></th>
                    <th><?php esc_html_e("Post Views", "rng-postviews"); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr style="background: #f9f9f9;border-bottom: 1px solid #ddd;">
                    <td style="padding: 6px 5px;"><?php echo current($days_period) . esc_html__(" (Today)", "rng-postviews") ?></td>
                    <td style="padding: 6px 5px;"><?php echo current($days_postviews) . $views; ?></td>
                </tr>
                <tr  style="background: #fff;border-bottom: 1px solid #ddd;">
                    <td style="padding: 6px 5px;"><?php echo next($days_period); ?></td>
                    <td style="padding: 6px 5px;"><?php echo next($days_postviews) . $views; ?></td>
                </tr>
                <tr style="background: #f9f9f9;border-bottom: 1px solid #ddd;">
                    <td style="padding: 6px 5px;"><?php echo next($days_period); ?></td>
                    <td style="padding: 6px 5px;"><?php echo next($days_postviews) . $views; ?></td>
                </tr>
                <tr  style="background: #fff;border-bottom: 1px solid #ddd;">
                    <td style="padding: 6px 5px;"><?php echo next($days_period); ?></td>
                    <td style="padding: 6px 5px;"><?php echo next($days_postviews) . $views; ?></td>
                </tr>
                <tr style="background: #f9f9f9;border-bottom: 1px solid #ddd;">
                    <td style="padding: 6px 5px;"><?php echo next($days_period); ?></td>
                    <td style="padding: 6px 5px;"><?php echo next($days_postviews) . $views; ?></td>
                </tr>
                <tr  style="background: #fff;border-bottom: 1px solid #ddd;">
                    <td style="padding: 6px 5px;"><?php echo next($days_period); ?></td>
                    <td style="padding: 6px 5px;"><?php echo next($days_postviews) . $views; ?></td>
                </tr>
                <tr style="background: #f9f9f9;border-bottom: 1px solid #ddd;">
                    <td style="padding: 6px 5px;"><?php echo next($days_period); ?></td>
                    <td style="padding: 6px 5px;"><?php echo next($days_postviews) . $views; ?></td>
                </tr>
                <tr  style="background: #fff;border-bottom: 1px solid #ddd;">
                    <td style="padding: 6px 5px;"><?php esc_html_e("Current Week", "rng-postviews"); ?></td>
                    <td style="padding: 6px 5px;"><?php echo current($weeks_postviews) . $views; ?></td>
                </tr>
                <tr style="background: #f9f9f9;border-bottom: 1px solid #ddd;">
                    <td style="padding: 6px 5px;"><?php esc_html_e("Average (views/day)", "rng-postviews"); ?></td>
                    <td style="padding: 6px 5px;"><?php echo $average_views_per_week; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>