<?php

namespace App\Helpers;

class XMLReader
{
    public $file = null;
    private $_file_real_path = null;

    private $_xml = null;
    private $_xml_string = null;

    public function __construct($file = null)
    {
        $this->file = $file;

        if ($this->file !== null) {
            $this->_file_real_path = $this->file->getRealPath();
            $this->_load_xml();
        }
    }

    private function _load_xml() {}
}
