<?php

namespace App\Helpers;

use Exception;

class SMS
{
    protected array $url = [
        'default' => 'https://api.smsapi.pl/sms.do',
        'backup' => 'https://api2.smsapi.pl/sms.do'
    ];

    private bool $_backup = false;

    public array $params = [
        'to' => '',
        'from' => 'Veritas',
        // 'from' => 'VeritasApp',
        'message' => '',
        'format' => 'json',
        'encoding' => 'utf-8'
    ];

    // private string $_bearer_token = 'uAQ0Ed3jKmJXfOUJ58eCaLtncz6g4WCUoqoRnz60';
    private string $_bearer_token = 'XUB2M6ipuWxCx4kxLdg7cpDhLciINQNYH3Jah4aB'; // nowy wygenerowany

    public function send() {
        $url = !$this->_backup ? $this->url['default'] : $this->url['backup'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer {$this->_bearer_token}"
        ));

        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_status != 200 && $this->_backup == false) {
            $this->_backup = true;
            $this->send($this->params);
        }

        $response = json_decode($response, JSON_UNESCAPED_UNICODE);

        return isset($response['error']) ? false : true;
    }

}