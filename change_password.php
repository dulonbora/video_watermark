<?php
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

* Developed By UniQiTech

* Video Watermarker

* Coded by UniqX

* Coded on 09/06/2015

* Contact: asomi.mobi@gmail.com

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
*/

session_start();
require 'protect.php';
include 'connect.php';

?>
<head>
<link rel="stylesheet" href="css.css" />
<title>Add Watermark</title>
</head>
<body><div id="cn">

<?php
$q = $init->query("SELECT pwd FROM credentials");
while($p=$q->fetch_assoc())
{
$p = $p['pwd'];
break;
}

if(!isset($_POST['submit']))
{
    $_SESSION['robot'] = rand(100,999);
    ?>
    <div class="wrapl">Change Password</div>
    <form method="post" action="change_password.php">
    Current Password: <input type="password" name="cpass" /><br />
    New Password: <input type="password" name="npass" /><br />
    Confirm Password: <input type="password" name="cfpass" /><br />
    Enter the 3 digits: <input type="text" name="rb" value="" /> <font color="red"><?=$_SESSION['robot'];?></font><br />
    <input type="submit" name="submit" value="Change" />
    </form>
    <?php
    }
    elseif($_SESSION['robot']!=$_POST['rb'])
    die("Invalid Captcha<br /><a href=\"change_password.php\">Back</a>");
    elseif(md5($_POST['cpass'])!=$p)
    die("Current password is not correct<br /><a href=\"change_password.php\">Back</a>");
    elseif($_POST['npass']!=$_POST['cfpass'])
    die("Passwords does not match<br /><a href=\"change_password.php\">Back</a>");
    elseif(md5($_POST['cpass'])==$p)
    {
        $ad = "admin";
        $pwd = md5($_POST['npass']);
        
        $ch = $init->prepare("UPDATE credentials SET pwd=? WHERE uname=?");
        $ch->bind_param("ss",$pwd,$ad);
        if($ch->execute())
        {
            if(isset($_COOKIE['uname'])){
setcookie("uname",null,time()-60*60*24*30);}
if(isset($_COOKIE['pwd']))
setcookie("pwd",null,time()-60*60*24*30);
        echo "Password Updated Successfully <a href=\"login.php\">login</a><br />";
        }
        
        
    }
    else
    {
        echo "All fields are necessary<br /><a href=\"change_password.php\">Back</a>";
        }
    ?>