<?php
if ( ! defined('ABSPATH')) {
    exit;
}
// phpcs:ignore WordPress.Security.NonceVerification.Recommended	
$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'main';
if (!in_array($tab, ['settings'])) {
    $tab = 'main';
}

$products    = admin_url('admin.php?page=metapic&tab=products');
$codes       = admin_url('admin.php?page=metapic&tab=codes');
$tracking    = admin_url('admin.php?page=metapic&tab=tracking');
$settings    = admin_url('admin.php?page=metapic&tab=settings');
$products    = admin_url('admin.php?page=metapic&tab=products');
$urlLogin    = admin_url('admin.php?page=metapic');

$settingClass = $tab == 'settings' ? '-current' : '';
?>
<header class="header">
    <div class="header_container container">
        <a href="#" class="header_logo"><img
                    src="<?php echo esc_url(METAPIC_URI) ?>/assets/images/logo.svg"
                    alt="grow"/></a>
        <div class="header_menu">
            <button class="header_menu_open">
                <img src="<?php echo esc_url(METAPIC_URI) ?>/assets/images/menu-open.svg">
            </button>
            <div class="menu">
                <a href="#" class="menu_logo"><img
                            src="<?php echo esc_url(METAPIC_URI) ?>/assets/images/logo.svg"
                            alt="grow"/></a>
                <a href="<?php echo esc_url($settings) ?>"
                   class="menu_item <?php echo esc_attr($settingClass) ?>">
                    <img src="<?php echo esc_url(METAPIC_URI) ?>/assets/images/menu-settings<?php echo esc_attr($settingClass) ?>.svg">
                    <div class="menu_item_text">
                        Settings
                    </div>
                </a>
                <button class="header_menu_close">
                    <img src="<?php echo esc_url(METAPIC_URI) ?>/assets/images/menu-close.svg">
                </button>
            </div>
        </div>
    </div>
</header>