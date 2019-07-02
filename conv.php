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
<title>Convert</title>
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

if($wm==0)
echo "<font color=\"red\">NOTICE:</font><font color=\"#FD8C00\"> This file has not been <a style=\"color:#FD8C00;font-weight:bold;text-decoration:underline;\" href=\"wm.php?id=$id\">watermarked</a></font><br />";

if(!isset($_POST['submit']) && !empty($name) && !empty($path)) // if form not submitted
{
    if($wm==1)
$path_tc = $final_path;
else
$path_tc = $path;

require_once 'libs/getid3.php';
$n = new getID3;
$f = $n->analyze($path_tc);
$width = @$f['video']['resolution_x'];
$height = @$f['video']['resolution_y'];

if(array_key_exists('video',$f))
{
?>
NOTICE: Please note that the actual video resolution of this video is <?=$width;?>x<?=$height;?>
<?php
}
else
echo "File is being watermarked<br />";
?>
<div class="wrapl"><b><a href="index.php">Files</a> | <a href="show.php?id=<?=$id;?>"><?=$name;?></a> | <a href="upload.php">Upload</a></b></div>
<form method="post" action="conv.php?id=<?=$id;?>">

<?php
$add = $wm==1 && (array_key_exists('video',$f))?" selected":" disabled";
?>

Convert: <select name="cnvrt">
<option<?=$add;?> value="wm_file">watermarked video</option>
<option value="orig_file">original video</option>
</select><br />
Select a Format to be converted: <select name="ext">
<?php
foreach($convertables as $con)
{
    $sel = @end(@explode('.',$path))==$con?" selected":null;
echo "<option$sel value=\"$con\">$con</option>";
}
?>
</select><br />
Resolution: <select name="res">
<option value="0">--DEFAULT--</option>
<?php
foreach($resolutions as $res)
if($res!=0)
echo "<option value=\"$res\">$res</option>";
?>
</select><br />
Video Bitrate: 
<select name="vbitrate">
<option value="0">--NA--</option>
<?php
foreach($video_bitrates as $rate)
echo "<option value=\"$rate\">$rate</option>";
?>
</select><br />
Audio Bitrate: 
<select name="abitrate">
<option value="0">--NA--</option>
<?php
foreach($audio_bitrates as $rate)
echo "<option value=\"$rate\">$rate</option>";
?>
</select><br />
Sampling Rate: 
<select name="sr">
<option value="0">--DEFAULT--</option>
<?php
sort($sampling_rates);
foreach($sampling_rates as $rate)
echo "<option value=\"$rate\">$rate</option>";
?>
</select><br />
Encoder: 
<select name="encoder">
<option value="0">--DEFAULT--</option>
<?php
foreach($encoders as $enc=>$v)
echo "<option value=\"$enc\">$enc</option>";
?>
</select><br />
<input type="submit" name="submit" value="convert" />
</form><br />
<a href="index.php">Back</a>
<?php
}
else
{
    ?><div class="wrapl"><b><a href="index.php">Files</a> | <a href="show.php?id=<?=$id;?>"><?=$name;?></a> | <a href="upload.php">Upload</a> |</b></div><?php
if($wm==1)
{
    if($_POST['cnvrt']=='orig_file')
    {
        $source = 1;
    $path_tc = $path;
    }
    else
    {
        $source = 0;
$path_tc = $final_path;
}
}
else
$path_tc = $path;

if($_POST['cnvrt']=='orig_file')
$source = 1;
else
$source = 0;

$res = $_POST['res'];

if(!in_array($res,$resolutions))
die("Invalid Resolution<br /><a href=\"conv.php?id=$id\">Back</a>");

 $ext = strtolower($_POST['ext']);
 if(in_array($ext,$convertables))
{
$orig_ext = @end(@explode('.',$name));

    if($ext==$orig_ext && $res==0)
    die("conversion to same extension & same resolution is not allowed<br /><a href=\"conv.php?id=$id\">Back</a>"); 
    
$conv_file = preg_replace("|.$orig_ext|",'_'.rand(0,1000).".$ext",$name,1);
$final_path = $conv_final.'/'.$conv_file;

                $sr = intval($_POST['sr']);
                $enc = $_POST['encoder'];
                if(!array_key_exists($enc,$encoders))
                $encoders[$enc] = 0;
                
                if($enc=="DivX" && $ext!="avi")
                {
                  die("DivX is only applicable to avi files<br /><a href=\"conv.php?id=$id\">Back</a>");  
                }
                $audio_bitrate = intval($_POST['abitrate']);
                $video_bitrate =  intval($_POST['vbitrate']);
                
run_terminal(convert_file($path_tc,$final_path,$ext,$res,$video_bitrate,$audio_bitrate,$sr,$encoders[$enc]));

$q = $init->prepare("INSERT INTO converts(uid,name,path,ext,res,source,video_bitrate,audio_bitrate,sr,enc) VALUES(?,?,?,?,?,?,?,?,?,?)");

$q->bind_param("issssiiiis",$id,$conv_file,$final_path,$ext,$res,$source,$video_bitrate,$audio_bitrate,$sr,$enc);
if($q->execute())
{
    $con = 1;
    $q1 = $init->prepare("UPDATE files SET con=? WHERE uid=?");
    $q1->bind_param("ii",$con,$id);
    $q1->execute();
    
echo "The file is being converted. You can always check the process from <a href=\"index.php\">Files</a> section<br /><a href=\"conv.php?id=$id\">Back</a>";
}
}
else
echo "Conversion to this extension is not allowed<br /><a href=\"conv.php?id=$id\">Back</a>";
}

echo "| <a href=\"vid_2_mp3.php?id=$id\">Video 2 MP3 Converter</a>";
} // end of script
?>