<?php 
$views = __(" views","rng-postviews");
?>
<div style="display: block;overflow: auto;">
    <table style="width: 80%;text-align: center;border-collapse: collapse;margin: auto; font-family: arial;">
        <thead>
            <tr style="border-bottom: 1px solid #ddd;">
                <th><?php _e("Date","rng-postviews"); ?></th>
                <th><?php _e("Post Views","rng-postviews"); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr style="background: #f9f9f9;border-bottom: 1px solid #ddd;">
                <td style="padding: 6px 5px;"><?php echo current($days_period) . __("(Today)","rng-postviews") ?></td>
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
                <td style="padding: 6px 5px;"><?php _e("Current Week","rng-postviews"); ?></td>
                <td style="padding: 6px 5px;"><?php echo current($weeks_postviews) . $views; ?></td>
            </tr>
            <tr style="background: #f9f9f9;border-bottom: 1px solid #ddd;">
                <td style="padding: 6px 5px;"><?php _e("Average (views/day)","rng-postviews"); ?></td>
                <td style="padding: 6px 5px;"><?php echo $average_views_per_week; ?></td>
            </tr>
        </tbody>
    </table>
</div>