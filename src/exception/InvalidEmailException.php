<?php
namespace Mtp\SanitizerLibraryPhp\exception;
class InvalidEmailException extends \Exception {
    public function errorMessage() {
        $errorMsg = "Invalid email address";
        return $errorMsg;
    }
}




?>