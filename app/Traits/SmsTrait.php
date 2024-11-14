<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait SmsTrait
{
    public function sendSMS($tarNum, $tarMsg)
    {
        $route_to = env('ROUTE_TO');
        $username = env('SMS_USERNAME');
        $password = env('SMS_PASSWORD');
        $caller_id = env('CALLER_ID');

        $url = "https://www.sendquickasp.com/client_api/index.php?route_to=$route_to&username=$username&passwd=$password&tar_num=" . $tarNum . "&tar_msg=" . $tarMsg . "&callerid=$caller_id";

        try {
            // Make the API request
            $response = Http::get($url);

            return session()->flash('success', $response);
        } catch (\Exception $e) {
            return session()->flash('error', $e->getMessage());
        }
    }
}
