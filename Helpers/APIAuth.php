<?php

namespace App\Utils\Helpers;

use Error;

class APIAuth
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function checkAuth(string $token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "8000",
            CURLOPT_URL => env('API_AUTH_BASE_URL')."/api/users/check-auth",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: $token",
            ),
        ));
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new Error($err);
        } else {
            return $httpcode === 200 ? json_decode($response)->success : false;
        }
    }
}
