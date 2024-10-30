<?php

class MetapicActions
{

    static $instance = null;
    static $dev = false;

    static function init()
    {
        if (self::$instance === null) {
            self::$instance = new MetapicActions();
        }
    }

    public function __construct()
    {
        $this->actions();
    }

    private function actions()
    {
        add_action('admin_head', [$this, 'admin_head'], 1, 1);
        add_action('admin_footer', [$this, 'admin_footer']);
        add_action('admin_notices', [$this, 'admin_notices']);
        add_action('wp_loaded', [$this, 'wp_loaded']);
        add_action('wp_head', [$this, 'wp_head']);
        add_action('woocommerce_thankyou', [$this, 'woocommerce_thankyou']);
    }

    public function admin_head()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (isset($_GET['page']) && $_GET['page'] === 'metapic') {
            wp_enqueue_style(
                'custom-fonts',
                'https://fonts.googleapis.com/css?family=Inter:600|Inter:400|Inter:500&display=swap',
                array(),
                '1.0.0'
            );
            wp_enqueue_style(
                'custom-style',
                METAPIC_URI . "/assets/styles/style.css?a=" . METAPIC_PLUG_VER,
                array(),
                '1.0.0'
            );
        }
    }

    public function admin_footer()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (isset($_GET['page']) && $_GET['page'] === 'metapic') {
            wp_enqueue_script(
                'metapic-advertiser-script',
                METAPIC_URI . '/assets/scripts/scripts.js?a=' . METAPIC_PLUG_VER,
                array(),
                '1.0.0', 
                array(
                    'in_footer' => true,
                )
            );
        }
    }

    public function admin_notices()
    {
        if ( ! class_exists('WooCommerce', true)) {
            $class   = 'notice notice-error';
            $message = __('Metapic plugin requires woocoomerce',
                'metapic');

            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class),
                esc_html($message));
        }
    }

    public function wp_loaded()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $tduid = isset($_GET['tduid']) ? sanitize_key($_GET['tduid']) : null;
        if($tduid){
            self::setFirstPartyServerCookie($tduid);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            MetapicRouting::postRouting();
        }
    }

    public function wp_head()
    {
        $org_id     = get_option('metapic_org_ID');
        $program_id = get_option('metapic_program_ID');
        if ( ! $org_id || ! $program_id) {
            return;
        }
        ?>
            <!-- Start Metapic Landing Page Tag Insert on all landing pages to handle first party cookies-->
            <script>
                (function (i, s, o, g, r, a, m) {
                    i['TDConversionObject'] = r;
                    i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o), m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', '<?php echo esc_url("https://svht.tradedoubler.com/tr_sdk.js?org=$org_id&prog=$program_id&dr=true&rand=") ?>' + Math.random(), 'tdconv');
            </script>
            <!-- End Metapic tag-->
        <?php
    }

    public function woocommerce_thankyou($order_id)
    {
        // Get order object
        $order      = wc_get_order($order_id);
        $order_data = $order->get_data();

        // Get data from settings
        $org_id             = get_option('metapic_org_ID');
        $program_id         = get_option('metapic_program_ID');
        $event_id           = get_option('metapic_event_ID');
        $vat_commision      = get_option('metapic_vat_commision', 'no');
        $shipping_commision = get_option('metapic_shipping_commision',
            'no');


        // Order details variables
        $order_value    = $order->get_total();
        $order_number   = $order->get_order_number();
        $order_currency = $order->get_currency();
        $coupon_codes   = $order->get_coupon_codes();
        $total_tax      = $order_data['total_tax'];
        $total_shipping = $order_data['shipping_total'];
        $first_coupon   = isset($coupon_codes[0]) ? $coupon_codes[0] : '';

        if ($vat_commision === 'no') {
            $order_value -= $total_tax;
        }

        if ($shipping_commision === 'no') {
            $order_value -= $total_shipping;
        }

        if ( ! $org_id || ! $program_id) {
            return;
        }

        $s2sUrl = "https://tbs.tradedoubler.com/report?organization=$org_id&event=$event_id".
        "&ordervalue=$order_number&ordervalue=$order_value&voucher=$first_coupon".
        "&currency=$order_currency&tduid=".self::getFirstPartyCookie();
        if(null !== self::getFirstPartyCookie()){
            $args = array('timeout' => 15);
            wp_remote_get( $s2sUrl, $args );   
        };


        
        // Inject tradeoubler conversion tag
        ?>
        <!-- Start Metapic Conversion Tag -->
        <script language='JavaScript'>
            document.addEventListener('DOMContentLoaded',function(){
                tdconv('init', <?php echo esc_js($org_id) ?>, {'element': 'iframe' });
                tdconv('track', 'sale', {
                    'transactionId':'<?php echo esc_js($order_number); ?>', 
                    'ordervalue':'<?php echo esc_js($order_value); ?>', 
                    'voucher':'<?php echo esc_js($first_coupon); ?>', 
                    'currency':'<?php echo esc_js($order_currency); ?>', 
                    'event': <?php echo esc_js($event_id); ?>
                })
            })
        </script>
        <!-- End Metapic Conversion tag -->
        <?php
    }

    public static function setFirstPartyServerCookie($tduid){
        setcookie('sGuid', $tduid, time()+(60*60*24*365), '/', get_site_url(), false, true);
    }

    public static function getFirstPartyCookie(){
        $sGuid = isset($_COOKIE['sGuid']) ? sanitize_key($_COOKIE['sGuid']) : null;
        $tduid = isset($_COOKIE['tduid']) ? sanitize_key($_COOKIE['tduid']) : null;

        if($sGuid){
            return $sGuid;
        }
        return $tduid;
    }
}