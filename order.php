<?php


//db settings
$host = 'localhost';
$db = 'dbpribor';
$charset = 'utf8';
$user = 'root';
$pass = '';



$dsn = "mysql:host=$host; dbname=$db; charset=$charset";
$opt = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

);

$pdo = new PDO($dsn, $user, $pass, $opt);
$pdo->exec("set names utf8");


//Post processing

$name     = ''; // имя отправителя
$email    = ''; // email отправителя
$city     = ''; // город отправителя
$newmailnum     = ''; // номер отделения новой почты отправителя
$subject  = ''; // тема
$message  = ''; // сообщение
$phone    = ''; // Телефон

//POST query extracing 

if(isset($_POST))
{
    $name     = $_POST['customername'];
    $email    = $_POST['customeremail'];
    $phone    = $_POST['customerphone'];
    $city    = $_POST['customercity'];
    $nm_num  = $_POST['customernewmailnum'];
    $ip       = $_SERVER['REMOTE_ADDR'];
};



$sqlquery = $pdo->prepare("INSERT INTO dbp_orders_collector (
          customer_name, 
          customer_email, 
          customer_phone, 
          customer_city,
          customer_newmail_num,
          customer_ip
          ) VALUES (
          :customer_name, 
          :customer_email, 
          :customer_phone, 
          :customer_city, 
          :customer_newmail_num,
          :customer_ip)"
);

$sqlquery->execute(array(
        'customer_name'    => $name,
        'customer_email'   => $email,
        'customer_phone'   => $phone,
        'customer_city'    => $city,
        'customer_newmail_num' => $nm_num,
        'customer_ip'      => $ip
    ));

if(get_magic_quotes_gpc())
    {
        $message = stripslashes($message);
    }



// Email на который должны приходить письма
    $to      = "alexbit@yandex.ru";
    $subject  = "Уведомление о новом заказе".$name;

    // сообщение
    $msg     = <<<HTML
==========================================================
Контактное лицо
==========================================================
Имя :             $name
----------------------------------------------------------
IP  :             $ip
----------------------------------------------------------
==========================================================
Контактные данные:
==========================================================
Телефон:        $phone

----------------------------------------------------------
E-Mail :          $email 
----------------------------------------------------------
==========================================================
Доставка на город:
==========================================================
$city,  Отделение НП - $nm_num
----------------------------------------------------------
==========================================================
IP отправителя:
==========================================================
$ip

HTML;
    mail($to, $subject, $msg, "From: $email\r\nReply-To: $email\r\nReturn-Path: $email\r\n");

    echo 'Ваша заявка оформлена. Вы будете перенаправленны через 10 секунд на стартовую страницу';





;
//window.location = "http://wplanding/"
echo '<script type="text/javascript">setTimeout(\'location.replace("http://testtask11.krasnoperovav.com")\', 10000) </script>';

?>







?>