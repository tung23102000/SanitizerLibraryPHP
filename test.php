<?php require "vendor/autoload.php"; ?>
<?php

use Mtp\SanitizerLibraryPhp\Filter;

$filter = new Filter();
//________________________Ví dụ: useEscape____________________________________
// $strNotFilter= "SELECT * FROM user where username = 'tung'";
//  echo $strFilter;
//  echo "<br>";
//  echo "<br>";
// echo $filter->useEscape($strNotFilter);

//________________________Ví dụ: sanitizeMultiple_____________________________
// $data = "jshajdhsa 7t786o697";
// $data2 = "^%*%&HJKHJSKHDJK7867";
// $datas  = $filter->sanitizeMultiple(array($data,$data2),"int");
// var_dump($datas);

//________________________Ví dụ: useBasicHTML_________________________________
$data = "<i>MTP</i>";
$data = $filter->UseBasicHTML($data,'<i>');
echo $data;

//________________________Ví dụ: sanitizeArray________________________________
// $array=array("red", "green", "blue", "yellow<script>alert('xss')</script>");
// $array = [
//     'name' => 'mtp<script>',
//     'email' => 'mtp@gmail.com</>',
//     'age' => '22abc',
//     'weight<script>alert("xss");</script>' => '67jsad',
//     'github' => 'https://github.com/mtp'

// ];
// $array = [
//     'acb' => 1,
//     'deg' => 2,
//      1 => 3,
//      5 => 4,
//      6 => 8,
//      "<script>alert('xss')</script>" => 7
// ];
//   $array = [1,2,3,4,'abc',6,'defh'];
//   $array = $filter->sanitizeArray($array,'arrayInt');
//  var_dump ($array);

// ______________________________sanitize_______________________________
if (isset($_POST['submit'])) {
    $data = $_POST['data'];
    $type = $_POST['type'];
    $fileName = $_FILES['file']['name'];
    $filterData = $filter->sanitize($data, $type);

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <style>
        body {
            background-image: linear-gradient(88deg, #94bffb, #024a5e);
            background-color: aqua;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h1 class="text-center" style="color: #09e6e6; text-transform: uppercase; letter-spacing: 3px;">Test page</h1>
        <div class=" d-flex justify-content-center align-self-center">

            <div class="form-group my-3">
                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                    <!-- <input type="text" name="data" placeholder="Nhập dữ liệu vào đây" style="width: 450px; height: 46px; border-radius: 5px;"> -->
                    <div class="form-group">
                        <textarea name="data" class="form-control" rows="4" style="width: 450px;" placeholder="Type something here..."></textarea>
                    </div>

                    <div class="form-group my-3">
                        <label for="" style="color: #1721a1; font-weight: bold;">Option: </label>
                        <select name="type">
                            <option selected>----Choose one option----</option>
                            <option value='int'>int</option>
                            <option value='integer'>integer</option>
                            <option value='float'>float</option>
                            <option value='text'>text</option>
                            <option value='aZ0-9'>aZ0-9</option>
                            <option value='url'>url</option>
                            <option value='password'>password</option>
                            <option value='name'>name</option>
                            <option value='alpha'>alpha</option>
                            <option value='vietnamese'>vietnamese</option>
                            <option value='alphaWithLgt'>alphaWithLgt</option>
                            <option value='string'>string</option>
                            <option value='message'>message</option>
                            <option value='email'>email</option>
                            <option value='date'>date</option>
                            <option value='fileName'>fileName</option>
                            <option value=''>There's no one at all =))</option>


                        </select>

                    </div>
                    <input type="file" name="file" >
                   
                    <div class="form-group justify-content-center" style="margin:0 auto; width: 40%;">
                        <button class="btn btn-success" type="submit" name="submit" style="width: 100%;">Submit</button>
                    </div>

                </form>
            </div>
        

        
        </div>
        <?php if(isset($_POST['submit'])){ ?>

        
        <h6 class="text-center" style="color: #FFF;">Output after filtering: <?php if (isset($_POST['data'])) {
                                                                                    echo $filterData;
                                                                                } ?></h6>
<!-- _______________________________useValidate_______________________________ -->
        <h6 class="text-center" style="color: #a41a1a; text-transform: uppercase; font-weight: bold,italic">Before checking validation: <?php if (isset($_POST['data'])) {
                                                                                                                                            echo $filter->useValidate($data, $type);
                                                                                                                                        } ?></h6>
        <h6 class="text-center" style="color: #2bef2b; text-transform: uppercase; font-weight: bold,italic">After checking validation: <?php if (isset($_POST['data'])) {
                                                                                                                                        echo $filter->useValidate($filterData, $type);
                                                                                                                                    } ?></h6>
        <?php  if(!empty($_FILES['file']['name'])){         ?>                                                                                                                   
        <h6 class="text-center" style="color: #2bef2b; text-transform: uppercase; font-weight: bold,italic">File: <?php echo $filter->sanitize($fileName,'fileName'); ?></h6>
        <?php } ?>
        <?php } ?>
        <div class="finish" style="text-align: center;">
        <a href="test2.php">Finish</a>
    </div>
    </div>
   
    
</body>

</html>