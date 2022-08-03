<?php
namespace Mtp\SanitizerLibraryPhp\exception;
class InvalidFloatException extends \Exception{
    public function errorMessage() {
        $errorMsg = "Invalid float value";
        return $errorMsg;
    }
}






?>