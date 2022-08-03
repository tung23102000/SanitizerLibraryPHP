<?php
namespace Mtp\SanitizerLibraryPhp\exception;
class InvalidIntegerException extends \Exception{
    public function errorMessage() {
        $errorMsg = "Invalid integer value";
        return $errorMsg;
    }
}

?>