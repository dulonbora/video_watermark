<?php
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

* Developed By UniQiTech

* Video Watermarker

* Coded by UniqX

* Coded on 09/06/2015

* Contact: asomi.mobi@gmail.com

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
*/

if(empty($_POST['uname']) || empty($_POST['pwd']) || empty($_POST['submit']))
{
?>
<head>
<link rel="stylesheet" href="css.css" />
<title>Video Watermarker</title>
</head>
<body><div id="cn">
<div class="wrapl">Login</div>
<form method="POST" action="login.php">
Username: <input type="text" name="uname" value="" /><br />
Password: <input type="password" name="pwd" value="" /><br />
<input type="submit" name="submit" value="Login" />
</form>
<?php
}
else
{
setcookie("uname",$_POST['uname'],time()+86400);
setcookie("pwd",$_POST['pwd'],time()+86400);
header("Location: index.php");
die;
}
?>
</div>
</body>
</html>