<?php
namespace Mtp\SanitizerLibraryPhp\exception;
class InvalidNameException extends \Exception{
    public function errorMessage() {
        $errorMsg = "Invalid name";
        return $errorMsg;
    }
}




?>