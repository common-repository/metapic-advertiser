<?php
if (!defined('ABSPATH')) {
    exit;
}
$urlCreateAccount = 'https://advertiser.metapic.com/register-advertiser';
$login    = admin_url('admin.php?page=metapic&tab=activationForm');
$urlLogin = admin_url('admin.php?page=metapic');
// phpcs:ignore WordPress.Security.NonceVerification.Recommended	
$error = isset($_GET['error']) && $_GET['error'] == '1';
?>
<header class="header">
    <div class="header_container container">
        <a target="_blank" href="#" class="header_logo"><img src="<?php echo esc_url(METAPIC_URI) ?>/assets/images/logo.svg" alt="grow" /></a>
    </div>
</header>
<main class="sign">
    <div class="sign_container container -signin">
        <div class="sign_content">
            <div class="sign_title">Activate your plug</div>
            <div class="sign_text">
                Enter your Metapic Activation Key
            </div>
            <?php if ($error) { ?>
                <div style='padding: 15px; margin-bottom: 20px;
border: 1px solid #ebccd1;background-color: #f2dede;color: #a94442; border-radius: 4px'>
                    Incorrect Activation Key
                </div>
            <?php } ?>
            <form action="<?php echo esc_attr($login) ?>" method="post" class="form -novalidate">
                <?php $nonce = wp_nonce_field('metapic_activate'); ?>
                <div class="form_control">
                    <div class="form_label">Activation Key</div>
                    <input type="text" class="form_input -image -password" name="metapic_activation_key" required />
                </div>
                <br/>
                <button class="custom_button" type="submit">Activate</button>
            </form>
        </div>
    </div>
</main>
<footer class="footer">
    <div class="footer_container container -center">
        <div class="footer_textBox">
            <div class="footer_text">
                New to Metapic?
                <a target="_blank" href="<?php echo esc_url($urlCreateAccount) ?>" class="footer_link">Create advertiser account</a>
            </div>
        </div>
    </div>
</footer>