<?php defined('BASEPATH') OR exit('No direct script access allowed');

function my_crypt($string, $action = 'e' )
{
    $secret_key = md5(APP_NAME).'_key';
    $secret_iv = md5(APP_NAME).'_iv';

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }

    return $output;
}

function re($array='')
{
    $CI =& get_instance();
    echo "<pre>";
    print_r($array);
    echo "</pre>";
    exit;
}

function e_id($id)
{
    return 41254 * $id;
}

function d_id($id)
{
    return $id / 41254;
}

if ( ! function_exists('script'))
{
    function script($url='', $type='application/javascript')
    {
        return "\n<script src=\"".base_url($url)."\" type=\"$type\"></script>\n";
    }
}

if ( ! function_exists('responseMsg'))
{
    function responseMsg($success, $succmsg, $failmsg, $redirect = null, $validate = null, $data = null)
    {
        $response = [
            'error'    => $success ? false : true,
            'message'  => $success ? $succmsg : $failmsg
        ];

        if($redirect) $response['redirect'] = $redirect;
        if($validate) $response['validate'] = $validate;
        if($data) $response['data'] = $data;

        die(json_encode($response));
    }
}

function send_curl_request($url, $req_type, $data=[], $bearer_token = null)
{
    $CI =& get_instance();
    $ch = curl_init($CI->api_url . $url);

    if ($req_type == 'post') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if($bearer_token) {
        $headers = [
            'Authorization: Bearer ' . $bearer_token,
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $response = curl_exec($ch);

    if ($response === false) {
        $return = [
            'status'  => false,
            'message' => curl_error($ch)
        ];
    } else {
        $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        if ($http_status_code !== 200) {
            $return = [
                'status'  => false,
                'message' => 'Some error occured.'
            ];
        } else {
            $response = json_decode($response, true);
            $return = [
                'status'   => !empty($response['status']) ? $response['status'] : false,
                'message'  => !empty($response['message']) ? $response['message'] : 'No response message',
                'row'      => !empty($response['row']) ? $response['row'] : []
            ];
        }
    }

    $CI->db->insert('api_logs', [
        'api'        => $url,
        'request'    => json_encode($data),
        'response'   => json_encode($return),
        'created_at' => date('Y-m-d H:i:s'),
        'status'     => $return['status']
    ]);

    curl_close($ch);

    return $return;
}