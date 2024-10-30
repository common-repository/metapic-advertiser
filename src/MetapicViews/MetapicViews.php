<?php

class MetapicViews
{

    static $errors = [];

    static public function settings()
    {
        include METAPIC_DIR . '/templates/settings.php';
    }

    static public function dashboard()
    {
        try {
            include METAPIC_DIR . '/templates/dashboard.php';
        } catch (Exception $e) {
            if ($e->getMessage() === 'Bad credentials') {
                self::deleteData();
                $step2url
                    = admin_url('admin.php?page=metapic&tab=step1');
                wp_redirect($step2url);
                echo esc_js("<script>window.location = '$step2url';</script>");
                exit();
            }
            throw $e;
        }
    }

    static public function activate()
    {
        include METAPIC_DIR . '/templates/activate.php';
    }


    static function activationForm()
    {
        if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'metapic_activate' ) ) {
            exit(); 
        }
        
        $activationKey = sanitize_text_field( isset($_POST['metapic_activation_key']) ? $_POST['metapic_activation_key'] : '' );

        if (empty($activationKey)) {
            exit();
        }

        try {
            new MetapicAPI([
                'metapic_activation_key' => $activationKey,
            ]);
        } catch (Exception $e) {
            self::deleteData();
            $home = $e->getMessage() == 'NO_ACCOUNT_EVENT'
                ?
                admin_url('admin.php?page=metapic&error=2')
                :
                admin_url('admin.php?page=metapic&error=1');
            wp_redirect($home);
            echo esc_js("<script>window.location = '$home';</script>");
            exit();
        }

        $home = admin_url('admin.php?page=metapic');
        wp_redirect($home);
        echo esc_js("<script>window.location = '$home';</script>");
        exit();
    }

    public static function deleteData()
    {
        delete_option('metapic_credentials');
        delete_option('metapic_org_ID');
        delete_option('metapic_program_ID');
        delete_option('metapic_event_ID');
        delete_option('metapic_segment_ID');
        delete_option('metapic_last_time_feed_update');
        delete_option('metapic_feeds');
        delete_transient('metapic_config');
    }
}