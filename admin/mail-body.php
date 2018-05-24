<?php
$views = __(" views", "rng-postviews");
?>
<div style="font-family: arial;">
    <h3 style="display: block;border-bottom: 1px solid #bababa;text-align: center; width: 85%;margin: 15px auto;padding-bottom: 5px;">PostViews weekly Reporting</h3>
    <div style="display: block;overflow: auto;">
        <table style="width: 85%;text-align: center;border-collapse: collapse;margin: auto;">
            <thead>
                <tr style="border-bottom: 1px solid #ddd;">
                    <th><?php _e("Date", "rng-postviews"); ?></th>
                    <th><?php _e("Post Views", "rng-postviews"); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr style="background: #f9f9f9;border-bottom: 1px solid #ddd;">
                    <td style="padding: 6px 5px;"><?php echo current($days_period) . __("(Today)", "rng-postviews") ?></td>
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
                    <td style="padding: 6px 5px;"><?php _e("Current Week", "rng-postviews"); ?></td>
                    <td style="padding: 6px 5px;"><?php echo current($weeks_postviews) . $views; ?></td>
                </tr>
                <tr style="background: #f9f9f9;border-bottom: 1px solid #ddd;">
                    <td style="padding: 6px 5px;"><?php _e("Average (views/day)", "rng-postviews"); ?></td>
                    <td style="padding: 6px 5px;"><?php echo $average_views_per_week; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>