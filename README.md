***______________________________ABOUT ME__________________________________***
Name: SanitizerLibraryPHP.
--> Đây là một thư viện đơn giản để làm sạch và xác thực tính hợp lệ dữ liệu đầu vào của người dùng.

Cần làm rõ 2 khái niệm: làm sạch (Sanitize) và xác thực tính hợp lệ (Validate) ????

+ Làm sạch (Sanitize) là việc loại bỏ những thứ không được phép có (sửa đổi đầu vào) để cho đảm bảo đầu vào hợp lệ.

+ Xác thực tính hợp lệ là việc kiểm tra xem liệu đầu vào nhận được đã đáp ứng các tiêu chí, yêu cầu ban đầu chưa. 

***______________________________HOW TO USE_________________________________***
1. Cài đặt từ GitHub:
https://github.com/tung23102000/SanitizerLibraryPHP

2. Mở terminal, gõ lệnh: composer dump-autoload 
--> tạo thư mục vendor để autoload
và thêm phần require "../vendor/autoload.php"; 
3. Thêm phần " use Mtp\SanitizerLibraryPhp\Filter; " vào tệp .php mà bạn muốn sử dụng và khởi tạo class " $filter = new Filter();".

4. Bây giờ bạn hoàn toàn có thể sử dụng thư viện này:
Ví dụ: 
<?php require "vendor/autoload.php"; 
use SanitizerLibraryPhp\Filter;
$filter = new Filter();
?>
+  Thoát các ký tự đặc biệt trong một chuỗi
 - useEscape
$strNotFilter= "SELECT * FROM user where username = 'tung'";
$strFilter = $filter->useEscape($strNotFilter);

+ Làm sạch đầu vào
- sanitize
$data = "<script>alert('Prevent Xss');</script>"
$data = $filter->sanitize($data, "type");
//type có thể là string, int, message, aZ0-9, name, password, alpha, url...
$data = $filter->sanitize($data,"message");

+ Làm sạch đầu vào cùng 1 lúc nhiều inputs
$data = "jshajdhsa 7t786o697";
$data2 = "^%*%&HJKHJSKHDJK7867";
$datas  = $filter->sanitizeMultiple(array($data,$data2),"int");

+ Làm sạch với mảng dữ liệu 
$array1=array("red", "green", "blue", "yellow<script></script>");
// $array2 = [
//     'name' => 'mtp<script>',
//     'email' => 'MTP@gmail.com</script>',
//     'age' => '18abc',
//     'weight<script>alert("xss");</script>' => '67',
//     'github' => 'https://github.com/mtp'
// ];
$array1= $filter->sanitizeArray($array1);
$array2 = $filter->sanitizeArray($array2);
var_dump($array1);
var_dump($array2);


+ Làm sạch đầu vào ngoài trừ một số thẻ HTML cơ bản: h1,h2,h3,h4,h5,h6,a,b,ul,li,ol,u..
useBasicHTML($data, $optionalTag="")
* sử dụng các thẻ HTML ở trong white list (nếu không nhập vào tùy chọn $optionalTag)
$data = "<h1>ABC</h1>";
$data = $filter ->useBasicHTML($data);
* sử dụng linh hoạt thêm 1 số thẻ HTML tùy chọn (cho phép người dùng có thể nhập (thêm) thẻ HTML mà họ muốn ngoài các thẻ có sẵn trong white list)
$data = "<i>ABC<i>";
//thẻ i ko có sẵn trong white list
$data = $filter->useBasicHTML($data,'<i>');
echo $data;// ABC hiển thị dưới dạng chữ in nghiêng

+ Xác thực hợp lệ một số kiểu dữ liệu: email, date, float,...
$data = "mtp@gmail.com";
echo $filter->useValidate($data,'email');












