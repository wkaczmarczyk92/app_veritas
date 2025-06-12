<?php

namespace App\Helpers;

use Exception;

class CURL
{
    public $url;
    public $post_data = [];
    public $json_data = '';
    public $content_type = 'post';

    private $headers = [
        'post' => [
            'Content-Type: application/x-www-form-urlencoded'
        ],
        'json' => [
            'Content-Type: application/json'
        ]
    ];

    private $_token = 'token: bearereyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE4MTAzODQ2MTQsImRhdGEiOnsiaWQiOiI5NzIwIiwidXNlcm5hbWUiOiJzY2FuZGljYXJlIiwiZnVsbFVzZXJOYW1lIjoiU2NhbmRpIENhcmUiLCJ1cnVfcmVkaXJlY3RfdXJsIjpudWxsLCJ0eXBlIjowfX0.OBAR4jJLQLn332nDD2ZWQk9dnbsq_06By9S-n-YZxcI';

    public function send()
    {
        $this->_check_data();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            ...$this->headers[$this->content_type],
            $this->_token
        ]);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            ($this->content_type == 'post' ? http_build_query($this->post_data) : $this->json_data)
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception("cURL Error: $error");
        }

        curl_close($ch);

        return $response;
    }

    private function _check_data()
    {
        if (!empty($this->post_data) and !empty($this->json_data)) {
            throw new Exception('Invalid data.');
        }
    }
}


// {
//     section: 'personal_data',
//     form: {
//         "crt_birthday": "1970-06-17",
//         "crt_first_name": "Dorota",
//         "crt_identity_card": "CGP079939",
//         "crt_is_passport": false,
//         "crt_foreign": false,
//         "crt_last_name": "Adamczyk-Dzwonkowska",
//         "crt_main_email": null,
//         "crt_main_phone_number": null,
//         "crt_passport": "",
//         "crt_sex": false,
//         "crt_id_caretaker": "",
//         "crt_id_caretaker_main_nationality": "1",
//         "phone_numbers_caretaker": {
//           "entries": [
//             {
//               "name": "+48692044953",
//               "verified": true
//             }
//           ],
//           "main_index": 0
//         },
//         "emails_caretaker": {
//           "names": [
//             "dorotadamczyk17@gmail.com"
//           ],
//           "main_index": 0
//         },
//         "crt_pesel": "70061702627"
//       },
//     indexValue: idOpiekunk,
//     caretakerHpId: null
// }