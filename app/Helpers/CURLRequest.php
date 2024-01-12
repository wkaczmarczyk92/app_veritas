<?php

namespace App\Helpers;

use App\Helpers\CURL;

use Exception;

class CURLRequest
{
    private CURL $curl;

    public function caretaker_departure_date(?string $date, int $crt_id_caretaker)
    {
        $this->curl = new CURL;
        $this->curl->url = 'https://local.grupa-veritas.pl/api/caretakers/availabilityCheck.php';
        $this->curl->content_type = 'json';
        $this->curl->json_data = json_encode([
            'date' => $date,
            'caretakerId' => $crt_id_caretaker
        ]);

        return $this->_response($this->curl->send());
    }

    public function update_caretaker_data($arr)
    {
        $this->curl = new CURL;
        $this->curl->url = 'https://local.grupa-veritas.pl/api/caretakers/profiles/saveCaretakerSection';
        $this->curl->content_type = 'json';
        $this->curl->json_data = json_encode($arr, JSON_UNESCAPED_UNICODE);
        return $this->_response($this->curl->send());
    }

    public function send_new_family_recommendation($arr)
    {
        $this->curl = new CURL;
        $this->curl->url = 'https://veritas.pl/w/app/api/API/store_family_recommendation';
        $this->curl->content_type = 'json';
        $this->curl->json_data = json_encode($arr, JSON_UNESCAPED_UNICODE);
        return $this->_response($this->curl->send());
        // return $this->curl->send();
    }

    public function send_new_caretaker_recommendation_to_leads($arr)
    {
        $this->curl = new CURL;
        $this->curl->url = 'https://veritas.pl/w/app/api/API/store_lead_from_veritas_app';
        $this->curl->content_type = 'json';
        $this->curl->json_data = json_encode($arr, JSON_UNESCAPED_UNICODE);
        return $this->_response($this->curl->send());
    }

    public function get_user_worked_days_in_previous_month($pesels, $year, $month)
    {
        $this->curl = new CURL;
        $this->curl->url = 'https://local.grupa-veritas.pl/api/scandi/daysInMonthApp.php';
        $this->curl->content_type = 'json';
        $this->curl->json_data = json_encode([
            'pesels' => $pesels,
            'year' => $year,
            'month' => $month,
            'isScandi' => false
        ], JSON_UNESCAPED_UNICODE);
        return $this->_responseJSON($this->curl->send());
    }

    // public function get_caretaker_recruiter()

    private function _response($response)
    {
        return json_decode(trim($response, "\xEF\xBB\xBF"));
    }

    private function _responseJSON($response)
    {
        return json_decode($response);
    }
}
