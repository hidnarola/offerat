<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// Event for DX_Auth
// You can use DX_Auth_Event to extend DX_Auth to fullfil your needs
// For example: you can use event below to PM user when he already activated the account, etc.

class Push_notification {

    public function sendMessageToAndroidPhone($API_KEY, $registrationIds, $messageText) {
        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = 'Authorization: key=' . $API_KEY;
        $data = array(
            'registration_ids' => $registrationIds,
            'data' => array('payload' => $messageText) //TODO Add more params with just simple data instead
        );
        $data_string = json_encode($data);
        $ch = curl_init();
        if ($_SERVER['HTTP_HOST'] == 'localhost')
            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
        else
            curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");

        if ($headers)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    function sendMessageToIPhones($deviceTokens = array(), $msg = '', $url = '') {

        $output = '';
        // Put your private key's passphrase here:		
        $passphrase = 'password';
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', dirname(__FILE__) . '/ck_Prod.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server        
        $iosServer = 'ssl://gateway.sandbox.push.apple.com:2195';
        // $iosServer = 'ssl://gateway.push.apple.com:2195';

        $fp = stream_socket_client($iosServer, $err, $errstr, 300, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);

        // Create the payload body
        $body['aps'] = array(
            'alert' => $msg,
            'sound' => 'default'
        );
        $body['url'] = $url;
        // Encode the payload as JSON        
        $payload = $this->my_json_encode($body);

        foreach ($deviceTokens as $dt) {

            $deviceToken = $dt;
            // Build the binary notification
            error_reporting(0);
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));

            if (!$result) {
                $output .= $deviceToken . ",";
            } else {
                $output .= $result . '<br>';
            }
        }

        // Close the connection to the server
        fclose($fp);
        return $output;
    }

    public function send_notifications_to_iphone($deviceTokens = array(), $msg = '', $url = '') {
  
        $curl_url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = IOS_FCM_PUSH_NOTIFICATION_API_KEY;
        $responses = [];
		 $body = array();
		 $alert = array();
		/* 
		 $alert [] = array (
		 'title' => '',
		 'text' => $msg
		 );
        // Create the payload body
        $body['aps'] = array(
             'alert' => $alert[0],
             'sound' => 'default',
             'click_action' => 'offeratpushnoti'
         );
         */
        // $body['url'] = $url;

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key=' . $serverKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curl_url);

        foreach ($deviceTokens as $dt) {
            $deviceToken = $dt;
            $notification = array('title' => $msg['name'],'text' => $msg['push_message'],'sound' => 'default', 'badge' => '1', 'click_action' => 'offeratpushnoti');
            $arrayToSend = array('to' => $deviceToken,'content_available' => true, 'mutable_content'=> true, 'data' => $msg ,'notification' => $notification);
            
            pr($arrayToSend);
            $json = json_encode($arrayToSend);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            //Send the request
            $responses[] = curl_exec($ch);
        }
        curl_close($ch);
    }

    public function my_json_encode($arr) {
        //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding
        array_walk_recursive($arr, function (&$item, $key) {
            if (is_string($item))
                $item = mb_encode_numericentity($item, array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
        });
        return mb_decode_numericentity(json_encode($arr), array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
    }

}

?>