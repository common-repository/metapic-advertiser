<?php

class MetapicRouting
{

    /**
     * @throws Exception
     */
    static public function postRouting()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended	
        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'main';

        if (!in_array($tab, ['main', 'settings', 'activationForm'])) {
            $tab = 'main';
        }

        switch ($tab) {
            case 'settings':
                if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'metapic_deactivate' ) ) {
                    exit(); 
                }
                delete_transient('metapic_config');
                $deactivate = isset($_POST['deactivate']) ? rest_sanitize_boolean($_POST['deactivate']) : 'false';
                if(!$deactivate){
                    MetapicViews::activationForm();
                }
                break;
            case 'activationForm':
                MetapicViews::activationForm();
                break;
        }
    }

    static public function routing()
    {
        self::getRouting();
    }

    static public function getRouting()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'main';

        if (!in_array($tab, ['main', 'settings'])) {
            $tab = 'main';
        }

        if (!class_exists('WooCommerce', true)) {
            return;
        }

        if (self::userIsActive()) {
            switch ($tab) {
                case 'settings':
                    MetapicViews::settings();
                    break;
                case 'main':
                default:
                    MetapicViews::dashboard();
                    break;
            }
        } else {
            switch ($tab) {
                case 'main':
                default:
                    MetapicViews::activate();
                    break;
            }
        }
    }


    static public function userIsActive()
    {
        return get_transient('metapic_config') !== false;
    }

}
