<?php
namespace Mtp\SanitizerLibraryPhp\exception;
class InvalidURLException extends \Exception{
    public function errorMessage() {
        $errorMsg = "Invalid URL";
        return $errorMsg;
    }
}




?>