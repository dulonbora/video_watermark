<?php
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

* Developed By UniQiTech

* Video Watermarker

* Coded by UniqX

* Coded on 09/06/2015

* Contact: asomi.mobi@gmail.com

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
*/

$convs = array();

require 'protect.php';
include 'connect.php';
?>
<head>
<link rel="stylesheet" href="css.css" />
<title>Video Watermarker</title>
</head>
<body><div id="cn">
<?php
        $page = intval(@$_GET['page']);
    
    if(!isset($page) || empty($page) || $page<=0)
    $page = 1;
    
        $total = $init->query("SELECT uid FROM files");
        $total = $total->num_rows;
     
     $resultsPerPage = 5;    
    $limit1 = $page * $resultsPerPage - $resultsPerPage;
    $totalpages = ceil($total/$resultsPerPage);
    
$q = $init->query("SELECT * FROM files ORDER BY uid DESC LIMIT $limit1,$resultsPerPage");

require_once 'libs/getid3.php';

$del_conv = $init->query("SELECT path,id FROM converts");
while($pth=$del_conv->fetch_assoc())
{
    
   if(!file_exists($pth['path']))
   {

    $del = $init->prepare("DELETE FROM converts WHERE uid=?");
    $del->bind_param("i",$pth['id']);
    $del->execute();
   }
}

echo "<div class=\"wrapl\"><a class=\"wrap\" href=\"index.php\">Refresh</a> | <a class=\"wrap\" href=\"upload.php\">Upload</a> | <a class=\"wrap\" href=\"logout.php\">logout</a> | <a href=\"change_password.php\">Change Password</a></div>";

echo '<table style="border-collapse:collapse;text-align:center;" border="1" bordercolor="#dedede" height="60" cellpadding="5"><tr style="background-color:#dedede;border:1px solid #dedede;">';

if($q->num_rows>0)
{

while($r=$q->fetch_assoc())
{
$wm = $r['wm'];
$con = $r['con'];
$name = $r['name'];
$path = $r['path'];
$fin_path = $r['final_path'];
$ext = strtolower(@end(@explode('.',$fin_path)));
$id = intval($r['uid']);

if($wm>=1)
{
$n = new getID3;
$vidinfo = $n->analyze($fin_path);
if(!array_key_exists('video',$vidinfo))
{
$stat = "<font color=\"red\">processing...</font>";
$add = "$name";
}
else
{
$stat = "<font color=\"green\">watermarked</font>";
$add = "<a class=\"color\" href=\"show.php?id=$id\">".$name."</a>";
}
}


if(file_exists($path))
{
    if(in_array($ext,$vm_exts))
    echo $wm==1?"<div class=\"wrap line\">$add -<b>$stat (<a href=\"wm.php?id=$id\">re-watermark</a>)</b> | <b><a href=\"conv.php?id=$id\">convert</a> | <a href=\"vid_2_mp3.php?id=$id\">Video2MP3</a>":"<div class=\"line wrap\"><a class=\"color\" href=\"show.php?id=$id\">".$name."</a> -<b><a href=\"wm.php?id=$id\">watermark</a></b> | <b><a href=\"conv.php?id=$id\">convert</a> | <a href=\"vid_2_mp3.php?id=$id\">Video2MP3</a>";
    else
    echo "<div class=\"wrap line\"><a class=\"color\" href=\"show.php?id=$id\">".$name."</a></div>";
if($con==1)
{
$conv = $init->query("SELECT ext,path FROM converts WHERE uid='$id' AND ext NOT IN('mp3')");
if($conv->num_rows>0)
{
while($f=$conv->fetch_assoc()){
$convs[] = array($f['ext'],$f['path']);}

$convs = array_values($convs);


foreach($convs as $c)
{
    $cn = new getID3;
$cvidinfo = $cn->analyze($c[1]);
if(!array_key_exists('video',$cvidinfo))
$cstat = " [$c[0] <font color=\"red\">processing...</font>]";
else
$cstat = " [$c[0] <font color=\"green\">converted</font>]";
echo $cstat;

unset($cn);
unset($cvidinfo);
}
echo "</b></div>";
}
else
echo "</b></div>";
}
else
echo "</b></div>";

}
else
{
$q1 = $init->prepare("DELETE FROM files WHERE uid=?");
$q1->bind_param("i",$id);
$q1->execute();

}
unset($n);
unset($vidinfo);
unset($convs);
unset($fsv);
unset($cfs);
}
if($totalpages>1)
{
    include_once 'func.paging.php';
    navigation($page,$resultsPerPage,$totalpages);

}
}
else
echo "File(s) not uploaded, you can begin by <a href=\"upload.php\">uploading</a> videos<br />";
?>

</tr>
</table>
</body>
</html>