<?php
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

* Developed By UniQiTech

* Video Watermarker

* Coded by UniqX

* Coded on 09/06/2015

* Contact: asomi.mobi@gmail.com

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
*/

require 'config.php';
$uname = @$_COOKIE['uname'];
$pwd = md5(@$_COOKIE['pwd']);

$q = $init->prepare("SELECT uname,pwd FROM credentials WHERE uname=? AND pwd=?");
$q->bind_param("ss",$uname,$pwd);
$q->execute();

if(empty($uname) || empty($pwd) || !($q->fetch()))
{
setcookie("uname",null,time()-3600);
setcookie("pwd",null,time()-3600);
header("Location: login.php?att=failed");
die;
}
?>