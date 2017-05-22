<?php
/**
 * UEHBase.php
 */
namespace UElearning\Util;

use \GuzzleHttp\Client;

class UEHBase {

    public function sendLog($xapi_array)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', UELEARNING_UEHBASE_URL, [
            'json' => $xapi_array
        ]);
    }
}
