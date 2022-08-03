<?php
namespace Mtp\SanitizerLibraryPhp\exception;
class InvalidDateException extends \Exception{
    public function errorMessage() {
        $errorMsg = "Invalid date";
        return $errorMsg;
    }
}




?>