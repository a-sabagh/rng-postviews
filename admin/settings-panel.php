<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!current_user_can("manage_options")) {
    return;
}
if (isset($_GET['settings-updated']) and $_GET['settings-updated'] == TRUE) {
    add_settings_error("ja-postviews-settings", "ja-postviews-settings", __("Settings saved", "rng-postviews"), "updated");
} elseif (isset($_GET['settings-updated']) and $_GET['settings-updated'] == FALSE) {
    add_settings_error("ja-postviews-settings", "ja-postviews-settings", __("Error with saving", "rng-postviews"));
}
?>
<div class="wrap">
    <h1><?php echo get_admin_page_title(); ?></h1>
    <form action="options.php" method="post">
        <?php
        settings_fields("ja-postviews-settings");
        do_settings_sections("ja-postviews-settings");
        submit_button(__("save", "rng-postviews"));
        ?>
    </form>
</div>

