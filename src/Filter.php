<?php

namespace Mtp\SanitizerLibraryPhp;

use Mtp\SanitizerLibraryPhp\exception\InvalidEmailException;
use Mtp\SanitizerLibraryPhp\exception\InvalidIntegerException;
use Mtp\SanitizerLibraryPhp\exception\InvalidFloatException;
use Mtp\SanitizerLibraryPhp\exception\InvalidURLException;
use Mtp\SanitizerLibraryPhp\exception\InvalidDateException;
use Mtp\SanitizerLibraryPhp\exception\InvalidNameException;

class Filter
{   
    private $data;
    public function __construct()
    {
    }
    
    public function setData($data){
        $this->data = $data;
        return $this;
   }
   public function getData(){
       return $this->data;
   }

    private function useHtmlSpecialCharacters($data, $quoteStyle = ENT_QUOTES, $charset = "UTF-8")
    {
        return htmlspecialchars($data, $quoteStyle, $charset);
    }

    private function useStripTags($data)
    {
        return strip_tags($data);
    }

    private function useStrReplace($search, $replace, $subject)
    {
        return str_replace($search, $replace, $subject);
    }

    private function escape($value)
    {
        $data = $this->useStrReplace(
            array("\\", "\0", "\n", "\r", "\x1a", "'", '"'),
            array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"'),
            $value
        );
        return $data;
    }

    public function useEscape($data) //thoát các ký tự đặc biệt trong một chuỗi để sử dụng trong truy vấn SQL
    {
        if (!empty($data)) {
           $data = $this->escape($data);
             $this->setData($data);
             return $this;
             //return $this;
        }
    }
    //làm sạch đầu vào
    public function sanitize($data, $type, $trim = true, $htmlspecialchars = true)
    {
        if (empty($type)) {
            $type = gettype($data);
        }
        $data = (string)$data; //khi dùng với filter_var thì phải chuyển nội bộ thành kiểu string
        switch ($type) {

            case "integer":
            case "int":
                $data = preg_replace(
                    "/[^0-9]/s",
                    "",
                    filter_var(
                        $data,
                        FILTER_SANITIZE_NUMBER_INT
                    )
                );
                $data = (int)intval($data) + 0;
                break;
                //ok

            case "float":
                $data = preg_replace(
                    "/[^0-9.]/s",
                    "",
                    filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)
                );
                $data = (float)floatval($data) + 0;
                break;
                //ok

            case "text":
            case "aZ0-9":
                $data = $this->clean($data, $trim, $htmlspecialchars);
                $data = preg_replace("/[^A-Za-z0-9]/s", "", $data);
                break;
                //ok 

            case "url":
                $data = filter_var($data, FILTER_SANITIZE_URL);
                break;
                //ok

            case "password":
                $data = $this->clean($data, false, false);
                break;
                //ok

            case "name": //kiểu này dùng để nhập input cho họ tên ko dấu
                $data = $this->clean($data, $trim, $htmlspecialchars);
                $data = preg_replace("/[^A-Za-z\s+]/s", "", $data);
                // $data = $this->clean($data, false, $htmlspecialchars);     
                break;
                //ok

            case "alpha": //kiểu chữ cái
                $data = preg_replace("/[^A-Za-z]/s", "", $data);
                $data = $this->clean($data, $trim, $htmlspecialchars);
                break;
                //ok

            case "vietnamese": //dùng để nhập tên tiếng việt
                $data = preg_replace('/[\d#$%^\&*()+=\-\[\]\';,.\/{}|\":<>?~\\\\]/', "", $data);
                $data = $this->clean($data, $trim, $htmlspecialchars);
                break;
                //ok


            case "alphaWithLgt": // kiểu chữ với dấu ><
                $data = $this->clean($data, false, $trim, false);
                $data = preg_replace("/[^A-Za-z\<>=]/s", "", $data);
                break;
                //ok

            case "string":
            case "message": //kiểu này dùng nhập comment, đăng nội dung bài viết
                $data = $this->clean($data, false, false, $htmlspecialchars);
                break;
                //ok

            case "email":
                $data = filter_var(strtolower($data), FILTER_SANITIZE_EMAIL);
                $data = $this->clean($data, $trim, $htmlspecialchars);
                break;
                //ok

            case "date":
                //$data = $this->clean($data, $trim, $htmlspecialchars);
                $data = preg_replace("/([^0-9\/\-])/s", "", $data);
                $data = $this->clean($data, $trim, $htmlspecialchars);
                break;
                //ok

            case "fileName":
                $arrayExplodefileName = explode('.', $data);
                $extension = array_pop($arrayExplodefileName);
                $fileNameWithoutExt = substr($data, 0, strrpos($data, '.'));
                $fileNameWithoutExt = $this->clean($fileNameWithoutExt, $trim, $htmlspecialchars);
                $fileNameWithoutExt = preg_replace('/[^a-zA-Z0-9\s\_\-]/s', '_', $fileNameWithoutExt);
                $data = $fileNameWithoutExt . '.' . $extension;
                break;

            default:
                $data = $this->clean($data, $trim, $htmlspecialchars);
                //$data = (string)$data;
                break;
        }
        $this->setData($data);
        return $this;
    }



    // làm sạch đầu vào cùng 1 lúc nhiều input 
    public function sanitizeMultiple(array $data_list, $type, $trim = true, $htmlspecialchars = true)
    {
        $data_listFiltered = array();
        foreach ($data_list as $data) {
            if (empty($type)) {
                $type = gettype($data);
            }
            $data = (string)$data; //khi dùng với filter_var thì phải chuyển nội bộ thành kiểu string
            switch ($type) {

                case "integer":
                case "int":
                    $data = preg_replace(
                        "/[^0-9]/s",
                        "",
                        filter_var(
                            $data,
                            FILTER_SANITIZE_NUMBER_INT
                        )
                    );
                    $data = (int)intval($data) + 0;
                    break;
                    //ok

                case "float":
                    $data = preg_replace(
                        "/[^0-9.]/s",
                        "",
                        filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)
                    );
                    $data = (float)floatval($data) + 0;
                    break;
                    //ok

                case "text":
                case "aZ0-9":
                    $data = $this->clean($data, $trim, $htmlspecialchars);
                    $data = preg_replace("/[^A-Za-z0-9]/s", "", $data);
                    break;
                    //ok 

                case "url":
                    $data = filter_var($data, FILTER_SANITIZE_URL);
                    break;
                    //ok

                case "password":
                    $data = $this->clean($data, false, false);
                    break;
                    //ok

                case "name": //kiểu này dùng để nhập input cho họ tên ko dấu
                    $data = $this->clean($data, $trim, $htmlspecialchars);
                    $data = preg_replace("/[^A-Za-z\s+]/s", "", $data);
                    // $data = $this->clean($data, false, $htmlspecialchars);     
                    break;
                    //ok

                case "alpha": //kiểu chữ cái
                    $data = preg_replace("/[^A-Za-z]/s", "", $data);
                    $data = $this->clean($data, $trim, $htmlspecialchars);
                    break;
                    //ok

                case "vietnamese": //dùng để nhập tên tiếng việt
                    $data = preg_replace('/[\d#$%^\&*()+=\-\[\]\';,.\/{}|\":<>?~\\\\]/', "", $data);
                    $data = $this->clean($data, $trim, $htmlspecialchars);
                    break;
                    //ok


                case "alphaWithLgt": // kiểu chữ với dấu ><
                    $data = $this->clean($data, false, $trim, false);
                    $data = preg_replace("/[^A-Za-z\<>=]/s", "", $data);
                    break;
                    //ok

                case "string":
                case "message": //kiểu này dùng nhập comment, đăng nội dung bài viết
                    $data = $this->clean($data, false, false, $htmlspecialchars);
                    break;
                    //ok

                case "email":
                    $data = filter_var(strtolower($data), FILTER_SANITIZE_EMAIL);
                    $data = $this->clean($data, $trim, $htmlspecialchars);
                    break;
                    //ok

                case "date":
                    //$data = $this->clean($data, $trim, $htmlspecialchars);
                    $data = preg_replace("/([^0-9\/\-])/s", "", $data);
                    $data = $this->clean($data, $trim, $htmlspecialchars);
                    break;
                    //ok


                case "fileName":
                    $arrayExplodefileName = explode('.', $data);
                    $extension = array_pop($arrayExplodefileName);
                    $fileNameWithoutExt = substr($data, 0, strrpos($data, '.'));
                    $fileNameWithoutExt = $this->clean($fileNameWithoutExt, $trim, $htmlspecialchars);
                    $fileNameWithoutExt = preg_replace('/[^a-zA-Z0-9\s\_\-]/s', '_', $fileNameWithoutExt);
                    $data = $fileNameWithoutExt . '.' . $extension;
                    break;

                default:
                    $data = $this->clean($data, $trim, $htmlspecialchars);
                    //$data = (string)$data;
                    break;
            }
            $data_listFiltered[] = $data;
        }
        return $data_listFiltered;
    }

    public function useBasicHTML($data, $optionalTag = "")
    {
        $allowTags = '<a><b><h1><h2><h3><h4><h5><h6><u><img><p><ul><li><ol><table><td><tr><th>';
        $notAllowTags = array(
            '<script>', '<link>'
        );
        if (!empty($optionalTag) && !in_array($optionalTag, $notAllowTags)) {
            $allowTags = $allowTags . $optionalTag;
        }

        return strip_tags($data, $allowTags);
    }

    //làm sạch đầu vào kiểu mảng
    public function sanitizeArray($array, $type = "", $trim = true, $htmlspecialchars = true)
    {
        $sanitizedArray = [];
        switch ($type) {
            case 'arrayInt':
                // $sanitizedArray = array_map('intval', $array);
                if ($this->checkAssociation($array) != true) {
                    foreach ($array as $value) {
                        if (intval($value) !== 0) {
                            // $value=$this->clean($value, $trim, $htmlspecialchars);
                            $sanitizedArray[] = $value;
                        }
                    }
                } else {
                    foreach ($array as $key => $value) {
                        if (intval($value) !== 0 && intval($key) !== 0) {
                            // $sanitizedArray[$this->clean($key, $trim, $htmlspecialchars)] = $this->clean($value, $trim, $htmlspecialchars);
                            $sanitizedArray[$key] = $value;
                        }
                    }
                }
                break;

            case "aZ0-9":
                if ($this->checkAssociation($array) != true) {
                    foreach ($array as $value) {
                        $sanitizedArray[] = $this->clean($value, $trim, $htmlspecialchars);
                    }
                } else {
                    foreach ($array as $key => $value) {
                        $sanitizedArray[$this->clean($key, $trim, $htmlspecialchars)] = $this->clean($value, $trim, $htmlspecialchars);
                    }
                }
                break;

            default:
                if ($this->checkAssociation($array) != true) {
                    foreach ($array as $value) {
                        $sanitizedArray[] = $this->clean($value, $trim, $htmlspecialchars);
                    }
                } else {
                    foreach ($array as $key => $value) {
                        $sanitizedArray[$this->clean($key, $trim, $htmlspecialchars)] = $this->clean($value, $trim, $htmlspecialchars);
                    }
                }
                break;
        }

        return $sanitizedArray;
    }

    public function clean($text, $stripTag = true, $trim = true, $htmlspecialchars = true)
    {
        if ($stripTag) {
            $text = $this->useStripTags($text);
        }
        if ($htmlspecialchars) {
            $text = $this->useHtmlSpecialCharacters($text);
        }
        if ($trim) {
            $text = trim($text);
        }
        return $text;
    }

    // return true nếu đây là phải mảng liên kết, false thì đó ko là mảng liên kết
    private function checkAssociation($array)
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    // xác thực xem đã hợp lệ hay chưa
    public function useValidate($text, $type)
    {
        if (empty($type)) {
            $type = gettype($text);
        }
        $text = (string)$text;
        switch ($type) {
            case 'email':
                try {
                    if (filter_var($text, FILTER_VALIDATE_EMAIL)) {
                        return "Valid Email";
                    } else {
                        throw new InvalidEmailException;
                    }
                } catch (InvalidEmailException $e) {
                    return $e->errorMessage();
                }
                break;

            case 'int':
            case 'integer':
                try {
                    if (filter_var($text, FILTER_VALIDATE_INT)) {
                        return "Valid integer value";
                    } else {
                        throw new InvalidIntegerException;
                    }
                } catch (InvalidIntegerException $e) {
                    return $e->errorMessage();
                }
                break;

            case 'float':
                try {
                    if (filter_var($text, FILTER_VALIDATE_FLOAT)) {
                        return "Valid float value";
                    } else {
                        throw new InvalidFloatException;
                    }
                } catch (InvalidFloatException $e) {
                    return $e->errorMessage();
                }
                break;

            case 'url':
                try {
                    if (filter_var($text, FILTER_VALIDATE_URL)) {
                        return "Valid URL";
                    } else {
                        throw new InvalidURLException;
                    }
                } catch (InvalidURLException $e) {
                    return $e->errorMessage();
                }
                break;

            case "date":
                try {
                    if (preg_match(("/^((0[1-9]|[1-2][0-9]|3[0-1])[\/\-](0[1-9]|1[0-2])[\/\-][0-9]{4})|((0[1-9]|1[0-2])[\/\-](0[1-9]|[1-2][0-9]|3[0-1])[\/\-][0-9]{4})$/"), $text)) {
                        return "Valid date format";
                    } else {
                        throw new InvalidDateException;
                    }
                } catch (InvalidDateException $e) {
                    return $e->errorMessage();
                }

            case "name":
                try {
                    if (!preg_match('/[\d#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $text)) {
                        return "Valid name";
                    } else {
                        throw new InvalidNameException;
                    }
                } catch (InvalidNameException $e) {
                    return $e->errorMessage();
                }


            default:
                return "Don't handle validation";
                break;
        }
    }
}
