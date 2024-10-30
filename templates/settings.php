<?php
if (!defined('ABSPATH')) {
    exit;
}
$dashboard = admin_url('admin.php?page=metapic');
$settings  = admin_url('admin.php?page=metapic&tab=settings');
$urlLogin  = admin_url('admin.php?page=metapic');

$activationKey = get_option('metapic_activation_key', '');
$org_ID     = get_option('metapic_org_ID', '');
$event_ID   = get_option('metapic_event_ID', '');
$program_ID = get_option('metapic_program_ID', '');
$currency = get_option('metapic_currency', '');
$segmentID  = get_option('metapic_segment_ID', '');

$api = new MetapicAPI();

require "header.php";
?>
<main class="main -max">
    <div class="main_container container">
        <div class="main_top">
            <div class="main_welcome">Tracking settings</div>
        </div>
        <div class="main_top_text">
            Please check that these values are correct.
        </div>
        <form method="post" id="tracking-form" class="form" novalidate>
            <div class="main_grid -tracking">
                <div class="tracking_details">
                    <div class="tracking_details_title" title="segment <?php echo esc_attr($segmentID) ?>">Details
                    </div>
                    <div class="tracking_details_box">
                        <div class="form_grid -one">
                            <div class="form_control">
                                <div class="form_label">Organization ID</div>
                                <input type="text" class="form_input" readonly value="<?php echo esc_attr($org_ID) ?>" />
                            </div>
                            <div class="form_control">
                                <div class="form_label">Program ID</div>
                                <input type="text" class="form_input" readonly value="<?php echo esc_attr($program_ID) ?>" />
                            </div>
                            <div class="form_control">
                                <div class="form_label">Event ID</div>
                                <input type="text" class="form_input" readonly value="<?php echo esc_attr($event_ID) ?>" />
                            </div>
                            <div class="form_control">
                                <div class="form_label">Currency</div>
                                <input type="text" class="form_input" readonly value="<?php echo esc_attr($currency) ?>" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tracking_commission">
                    <div class="tracking_commission_title">Activation Key</div>
                    <div class="tracking_commission_box">
                        <div class="form_grid -one">
                            <div class="form_control">
                                <div class="form_label">Activation Key</div>
                                <?php $nonce = wp_nonce_field('metapic_activate'); ?>
                                <input type="text" name="metapic_activation_key" class="form_input" value="<?php echo esc_attr($activationKey) ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <form method="post" id="deactivate-form">
            <?php $nonce = wp_nonce_field('metapic_deactivate'); ?>
            <input type="hidden" name="deactivate" value="1" />
        </form>
    </div>
</main>
<footer class="footer -white">
    <div class="footer_container container -main">
        <button type="submit" form="deactivate-form" class="custom_button -whiteRed">Deactivate</button>
    </div>
</footer>