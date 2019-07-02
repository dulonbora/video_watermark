<?php
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

* Developed By UniQiTech

* Video Watermarker

* Coded by UniqX

* Coded on 09/06/2015

* Contact: asomi.mobi@gmail.com

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
*/

require 'protect.php';
include 'connect.php';

?>
<head>
<link rel="stylesheet" href="css.css" />
<title>Delete</title>
</head>
<body><div id="cn">
<?php
$id = intval($_GET['id']);
$conf = @$_POST['act'];

    $q = $init->prepare("SELECT name FROM files WHERE uid=?");
    $q->bind_param("i",$id);
    $q->execute();
    $q->store_result();
    $q->bind_result($name);
    $q->fetch();
    $count = $q->num_rows;
    
    if($count<=0)
    die("File not found<br /><a href=\"index.php\">Home</a>");
    
//confirmation
if(!isset($_POST['act']) || !isset($_POST['submit']))
{
    
    echo '<form method="POST" action="del.php?id='.$id.'">
    Are you sure to delete? All files associated with the file <b>'.$name.'</b> will be removed<br />
    <select name="act">
    <option value="0">cancel delete</option>
    <option value="1">confirm delete</option>
    </select><br />
    <input type="submit" name="submit" value="Confirm" />
    </form>';
    echo '<br /><a href="show.php?id='.$id.'">Back</a> | <a href="index.php">Home</a>';
    }
    elseif($conf==1)
    {
$q = $init->prepare("SELECT wm_image,path,final_path FROM files WHERE uid=?");
$q->bind_param("i",$id);
$q->execute();
$q->store_result();
$q->bind_result($wm_image,$path,$final_path);
$q->fetch();

if(file_exists($path))
unlink($path);

if(file_exists($final_path))
unlink($final_path);

if(file_exists($wm_image))
unlink($wm_image);

$qS = $init->prepare("SELECT path FROM converts where uid=?");
if($qS)
{
$qS->bind_param("i",$id);
$qS->execute();
$qS->bind_result($path);
while($qS->fetch())
{
    if(file_exists($path))
    unlink($path);
}
}

$qS = $init->prepare("SELECT path FROM previews where uid=?");
if($qS)
{
$qS->bind_param("i",$id);
$qS->execute();
$qS->bind_result($path);
while($qS->fetch())
{
    if(file_exists($path))
    unlink($path);
}
}

$q1 = $init->prepare("DELETE FROM files WHERE uid=?");
$q1->bind_param("i",$id);
$q1->execute();

$q2 = $init->prepare("DELETE FROM converts WHERE uid=?");
$q2->bind_param("i",$id);
$q2->execute();

$q3 = $init->prepare("DELETE FROM previews WHERE uid=?");
$q3->bind_param("i",$id);
$q3->execute();

echo "All files associated with $name has been deleted<br /> <a href=\"index.php\">Home</a>";
}
else
{
    echo "Deletion Aborted";
    echo '<br /><a href="show.php?id='.$id.'">Back</a> | <a href="index.php">Home</a>';
    }
?>
</body>
</html>