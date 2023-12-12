<?php

namespace App\Helpers;

use Exception;

class CountryCodeFinder
{

    public function __construct($phone_number) {
        $phone_number = str_replace(' ', '', trim($phone_number));
        $this->_find($phone_number);
    }

    private $_patterns = [
        'pl' => '/^(\+48|0048|048|48|\+0048|\+048)?[ ]?\d{9}$/',
        'de' => '/^(\+49|0049|049|49|\+0049|\+049)?[ ]?[1-9]{1}[0-9]{2,9}$/'
    ];

    private $_codes = [
        'pl' => 48,
        'de' => 49
    ];

    private $_initial_possibilities = '+|00|0||+00|+0|';

    private $_code = null;
    private $_phone_number = null;

    public function phone() {
        return $this->_phone_number;
    }

    public function code() {
        return $this->_code;
    }

    private function _find($phone_number) {
        foreach ($this->_patterns as $key => $pattern) {
            if (preg_match($pattern, $phone_number)) {
                $this->_code = $this->_codes[$key];
                break 1;
            }
        }

        if ($this->_code === null) {
            throw new Exception('Twój numer telefonu wygląda na nieprawidłowy. Skontaktuj się z konsultantem w celu poprawy danych.');
        }

        foreach (explode('|', $this->_initial_possibilities) as $value) {
            if (strpos($phone_number, "{$value}{$this->_code}") === 0) {
                $this->_phone_number = substr($phone_number, 0, strlen("{$value}{$this->_code}"));
            }
        }

        $this->_phone_number = $this->_phone_number === null ? $phone_number : $this->_phone_number;
    }
}