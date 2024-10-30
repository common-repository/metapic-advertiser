<?php

class MetapicAPI
{

    /**
     * @param null $userData
     *
     * @throws Exception
     */
    public function __construct($userData = null)
    {
        if (is_array($userData)) {
            $this->activatePlugin($userData);
        }
    }


    /**
     * @param $userData
     *
     * @throws Exception
     */
    public function activatePlugin($userData)
    {

        $data = [
            "metapic_activation_key" => $userData['metapic_activation_key'],
        ];

        if (get_transient('metapic_config') !== false) {
            $this->config = get_transient('metapic_config');
        } else {
            $args = array(
                'method' => 'POST',
                'body' => wp_json_encode($data),
                'headers' => array('Content-Type' => 'application/json'),
                'timeout' => 15,
                'data_format' => 'body',
            );
            $response = wp_remote_post("https://api.metapic.com/advertiser/tracking-configs", $args);
            $response = json_decode($response['body'], true);
            if (!isset($response['organization_id'])) {
                throw new Exception('Bad credentials');
            }

            set_transient(
                'metapic_config',
                $response,
            );

            update_option('metapic_activation_key', $userData['metapic_activation_key']);
            update_option('metapic_org_ID', $response['organization_id']);
            update_option('metapic_program_ID', $response['program_id']);
            update_option('metapic_event_ID', $response['event_id']);
            update_option('metapic_currency', $response['currency']);
            update_option('metapic_segment_ID', 1);
        }
    }
}
