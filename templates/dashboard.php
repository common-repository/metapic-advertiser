<?php
if (!defined('ABSPATH')) {
    exit;
}
//    $urlCreateAccount = admin_url('admin.php?page=metapic&tab=step1');
$urlCreateAccount = 'https://advertiser.metapic.com/login';
$login    = admin_url('admin.php?page=metapic&tab=activationForm');
$urlLogin = admin_url('admin.php?page=metapic');
require "header.php";

?>
<main class="sign">
    <div class="sign_container container -signin">
        <div class="sign_content">
            <div class="sign_title">Plug in is activated.</div>
            <div class="sign_text">
                Sales tracking is enabled, check your account in metapic for more info.
            </div>
        </div>
    </div>
</main>
<footer class="footer">
    <div class="footer_container container -center">
        <div class="footer_textBox">
            <div class="footer_text">
                Login to metapic account?
                <a target="_blank" href="<?php echo esc_url($urlCreateAccount) ?>" class="footer_link">login to advertiser account</a>
            </div>
        </div>
    </div>
</footer>