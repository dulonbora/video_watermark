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
require 'func.php';

?>
<head>
<link rel="stylesheet" href="css.css" />
<title>Video2MP3 Converter</title></title>
</head>
<body><div id="cn">

<?php
$id = intval($_GET['id']);

$q = $init->prepare("SELECT path,name,wm,final_path FROM files WHERE uid=?");
$q->bind_param("i",$id);
if($q->execute())
{
$q->store_result();
$q->bind_result($path,$name,$wm,$final_path);
$q->fetch();

if(!isset($_POST['submit']) && !empty($name) && !empty($path)) // if form not submitted
{
    ?>
<div class="wrapl"><b><a href="index.php">Files</a> | <a href="show.php?id=<?=$id;?>"><?=$name;?></a> | <a href="upload.php">Upload</a></b></div>
<form method="post" action="vid_2_mp3.php?id=<?=$id;?>">
Select Bitrate: <select name="bitrate">
<option value="0">--DEFAULT--</option>
<?php
foreach($audio_bitrates as $rate)
echo "<option value=\"$rate\">$rate</option>";
echo '</select>';
?>
<br />
<input type="submit" name="submit" value="convert" />
</form><br />
<a href="index.php">Back</a>
<?php
}
else
{
    $conv_path = $conv_final.$name;
    $rate = $_POST['bitrate'];
    $tconv_path = str_replace(".".@end(@explode('.',$conv_path)),'_'.$rate.".mp3",$conv_path);
    
    run_terminal(vid_2_mp3($path,$conv_path,$_POST['bitrate']));
    $q = $init->prepare("UPDATE files SET video2mp3=?,con=? WHERE uid=?");
    $st = 1;
    $c = 1;
    $q->bind_param("iii",$st,$c,$id);
    $q->execute();
    
    $q1 = $init->prepare("INSERT INTO converts(uid,name,path,ext,res,source) VALUES(?,?,?,?,?,?)");
    
    $src = 0;
    $ext = "mp3";
    $q1->bind_param("issssi",$id,$name,$tconv_path,$ext,$rate,$src);
    $q1->execute();
    
    echo "The video is being converted. You can always check the process from <a href=\"index.php\">Files</a> section<br /><a href=\"vid_2_mp3.php?id=$id\">Back</a>";
}


}
?>