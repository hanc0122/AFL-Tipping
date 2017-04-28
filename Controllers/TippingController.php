<?php
/**
 * Created by PhpStorm.
 * User: joshhancock
 * Date: 28/04/2017
 * Time: 8:40 PM
 */

namespace Tipping\Controllers;


use GuzzleHttp\Client;

class TippingController
{
    protected $token;

    /**
     * @return string|null
     */
    public function getToken()
    {
        if(!is_null($this->token)) return $this->token;

        $url = "http://www.afl.com.au/api/cfs/afl/WMCTok";
        $headers = ["Referer" => "http://www.afl.com.au/stats"];
        $token = null;

        $client = new Client();

        try {
            $response = $client->request("POST", $url, $headers);
            $token = json_decode($response->getBody()->read(1000))->token;
        } catch(\Exception $e) {
        }

        return $this->token = $token;
    }

    public function getStats()
    {
        $stats = [];
        $url = "http://www.afl.com.au/api/cfs/afl/statsCentre/teams?competitionId=CD_S2017014";
        $headers = [
            "Referer" => "http://www.afl.com.au/stats",
            "X-media-mis-token" => $this->getToken()
        ];

        $client = new Client();

        try {
            $response = $client->request("GET", $url, $headers);
            $stats = ($response->getBody());
        } catch(\Exception $e) {
        }

        return $stats;
    }

}

require "../vendor/autoload.php";

$controller = new TippingController();
echo $controller->getToken();
print_r($controller->getStats());

