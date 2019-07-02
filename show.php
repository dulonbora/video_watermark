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
include 'func.php';

?>
<head>
<link rel="stylesheet" href="css.css" />
<title>Video Details</title>
</head>
<body><div id="cn">
<?php
$id = intval($_GET['id']);
$page = intval(@$_GET['page']);
$page = $page==0?1:$page;
$perpage = $prev_limit;


$q = $init->prepare("SELECT wm,path,name,final_path,con,prev_status,video2mp3 FROM files WHERE uid=?");
$q->bind_param("i",$id);
$q->execute();
$q->store_result();
$q->bind_result($wm,$path,$name,$final_path,$con,$prev_status,$vid2mp3);
$q->fetch();

if($q->num_rows<=0)
{
    die("File Not found<br />");
}

$ext = strtolower(@end(@explode('.',$final_path)));
if($con==1)
{
    $q1 = $init->query("SELECT source,name,path,res,ext,video_bitrate,audio_bitrate,sr,enc FROM converts WHERE uid='$id' AND ext NOT IN('mp3')");
    $count = $q1->num_rows;
    
        $q2 = $init->query("SELECT source,name,path,res,ext FROM converts WHERE uid='$id' AND ext IN('mp3')");
    $mp3_counts = $q2->num_rows;
}
else
{
$count = 0;
$mp3_counts = 0;
}
if($wm==1)
$prev = $final_path;
else
$prev = $path;

echo "<div class=\"wrapl\"><b><a href=\"show.php?id=$id\">Refresh</a> | $name | <a class=\"wrap\" href=\"index.php\">Files</a> | <a class=\"wrap\" href=\"upload.php\">Upload</a> | <a class=\"wrap\" href=\"logout.php\">logout</a></b></div>";

if(in_array($ext,$vm_exts))
{

require_once 'libs/getid3.php';
    $n = new getID3;
    $f = $n->analyze($prev);
    if(array_key_exists('video',$f))
    {

$f = new getID3;
$vid = $f->analyze($prev);
$sec = $vid['playtime_seconds'];

$inc = $prev_sec;
$temp_sec = ceil($sec)-1;
$lengths = array();
for($i=$inc;$i<=$temp_sec;$i+=$inc)
{
$lengths[] = to_format($i);
}

$total = count($lengths);

$offset = @(int)$_GET['offset'];

$newICounter = (($offset + $perpage) <= sizeof($lengths)) ? ($offset + $perpage) : sizeof($lengths);

for($i=$offset;$i<$newICounter;$i++)
 {  
    $nwm_fw_name = "previews/nwm_".$id.'-'.str_replace(':',null,$lengths[$i]).".jpg";
    $wm_fw_name = "previews/wm_".$id.'-'.str_replace(':',null,$lengths[$i]).".jpg";
    
    $fw_name = $wm==1?$wm_fw_name:$nwm_fw_name;
    
    $c = $init->query("SELECT id FROM previews WHERE uid='$id'");

    if(file_exists($fw_name) && $c->num_rows>0)
    {
        
        echo "<img width=\"400\" height=\"200\" src=\"$fw_name\" /> ";
    }
    else
    {
        $add = $init->prepare("INSERT INTO previews(uid,path) VALUES(?,?)");
        $add->bind_param("is",$id,$fw_name);
        $add->execute();
        
        if(strtolower(substr(php_uname(),0,7))=='windows')
        $cmdccat = ">NUL";
        else
        $cmdccat = ">/dev/null";
        
shell_exec("ffmpeg -i ".escapeshellarg($prev)." -ss ".$lengths[$i]." -s 400x200 -vframes 1 \"$fw_name\" -y$cmdccat");
echo "<img width=\"400\" height=\"200\" src=\"$fw_name\" /> ";
}
}


function ShowNav($offset, $limit, $totalnum, $query) {
    global $PHP_SELF,$id;
    if ($totalnum > $limit) {
            // calculate number of pages needing links 
            $pages = intval($totalnum/$limit);

            // $pages now contains int of pages needed unless there is a remainder from division 
            if ($totalnum%$limit) $pages++;

            if (($offset + $limit) > $totalnum) {
                $lastnum = $totalnum;
                }
            else {
                $lastnum = ($offset + $limit);
                }
            ?>
                <table cellpadding="4"><tr><td>More Previews: </td>
            <?php
            for ($i=1; $i <= $pages; $i++) {  // loop thru 
                $newoffset=$limit*($i-1);
                if ($newoffset != $offset) {
            ?>
                    <td>
                        <a href="<?php print  $PHP_SELF; ?>?offset=<?php print $newoffset; ?><?php print $query; ?>&id=<?php print $id; ?>"><?php print $i; ?>
                        </a>
                    </td>
            <?php
                    }     
                else {
            ?>
                    <td><?php print $i; ?></td>
            <?php
                    }
                }
            ?>
                    </tr></table>
            <?php
        }
    return;
    }
ShowNav($offset,$perpage,sizeof($lengths),"");

        $stat_q = "<font color=\"green\">watermarked</font> ( <a href=\"wm.php?id=$id\">re-watermark</a> )";
    $wm_q = true;
    }
    else
    {
        $stat_q = "<font color=\"red\">processing...</font>";
    $wm_q = false;
    }
  }  
?>
<div class="wrapl">File Details</div>
<div class="line">Name: <?=$name;?> <a href="del.php?id=<?=$id;?>">Delete</a></div>
<?php
if(in_array($ext,$vm_exts))
{
?>
<div class="line">watermark status: <?php echo $wm==1?$stat_q:"<font color=\"red\">not watermarked</font> ( <a href=\"wm.php?id=$id\">watermark</a> )";?></div>
<div class="line">conversion status: <?php echo $con==1 && $count>0?"<font color=\"green\">converted ( <font color=\"red\">$count</font> files)</font> ( <a href=\"conv.php?id=$id\">convert more</a> )":"<font color=\"red\">not converted</font> ( <a href=\"conv.php?id=$id\">convert</a> )";?></div>
<?php
if($wm==1)
{
    echo "<div class=\"line\">";
    if($wm_q==true)
    {
        echo "<a style=\"text-decoration:underline;\" href=\"$final_path\">Download Watermarked File</a> [<input type=\"text\" value=\"$siteurl$final_path\" />]";
    }
    else
    echo "<font color=\"red\">File is being watermarked</font><br />";
echo "</div>";
}
}
echo "<div class=\"line\"><a style=\"text-decoration:underline;\" href=\"$path\">Download Original File</a> [<input type=\"text\" value=\"$siteurl$path\" />]</div>";
if(in_array($ext,$vm_exts))
{
echo "<div class=\"wrapl\">Converted Files ($count) </div>";

if($count==0 && $mp3_counts==0)
    echo "<div class=\"line\">There are no converted file(s)</div>";
else
{    
if($count>0 && $con==1)
{
        echo '<br /><center><table style="border-collapse:collapse;text-align:center;" border="1" bordercolor="#dedede" height="60" cellpadding="5"><tr style="background-color:#dedede;border:1px solid #dedede;">
	
        <th>Name</th>
		<th>Resolution</th>
		<th>Format</th>
		<th>Status</th>
        <th>Video Bitrate</th>
        <th>Audio Bitrate</th>
        <th>Frequency</th>
        <th>Encoder</th>
        <th>Source</th>
        <th>Download</th>
        <th>Copy</th>
	</tr>';
   while($r=$q1->fetch_assoc())
   {
    $stat = new getID3;
    $vid = $stat->analyze($r['path']);
    if(array_key_exists('video',$vid))
    {
        $box = "<input type=\"text\" value=\"$siteurl".$r['path']."\" />";
    $lnk = "<a style=\"text-decoration:underline;\" href=\"".$r['path']."\">Download</a>";
    $stat = "<font color=\"green\">converted</font>";
    }
    else
    {
        $box = "...";
        $lnk = "...";
    $stat = "<font color=\"red\">processing...</font>";
    }
    
    if($r['res']==0)
    {
        $wd = @$vid['video']['resolution_x'];
        $ht = @$vid['video']['resolution_y'];
        $r['res'] = $wd.'x'.$ht;
    }
    
    if($r['source']==0)
    $stat2 = "watermarked file";
    else
    $stat2 = "original file";

    $r['video_bitrate'] = $r['video_bitrate']==0?"DEFAULT":$r['video_bitrate'];
    $r['audio_bitrate'] = $r['audio_bitrate']==0?"DEFAULT":$r['audio_bitrate'];
    $r['sr'] = $r['sr']==0?"DEFAULT":$r['sr'];
    $r['enc'] = $r['enc']=="0"?"DEFAULT":$r['enc'];
    
    echo "<tr><td>".$r['name'].'</td><td>'.$r['res'].'</td><td>'.$r['ext'].'</td><td>'.$stat."</td><td>".$r['video_bitrate']."</td><td>".$r['audio_bitrate']."</td><td>".$r['sr']."</td><td>".$r['enc']."</td><td>$stat2</td><td>$lnk</td><td>$box</td></div>";
    
    unset($stat);
    unset($vid);
    
    }
    echo "</table>";
    
    }
        if($mp3_counts>0)
{
    echo "<br /><div class=\"wrapl\">Video2MP3 Converts ($mp3_counts)</div>";
        echo '<br /><center><table style="border-collapse:collapse;text-align:center;" border="1" bordercolor="#dedede" height="60" cellpadding="5"><tr style="background-color:#dedede;border:1px solid #dedede;">
	
        <th>Name</th>
		<th>Bitrate</th>
		<th>Status</th>
        <th>Download</th>
        <th>Copy</th>
	</tr>';
   while($r=$q2->fetch_assoc())
   {
    $stat = new getID3;
    $vid = $stat->analyze($r['path']);
    if(array_key_exists('audio',$vid))
    {
        $box = "<input type=\"text\" value=\"$siteurl".$r['path']."\" />";
    $lnk = "<a style=\"text-decoration:underline;\" href=\"".$r['path']."\">Download</a>";
    $stat = "<font color=\"green\">converted</font>";
    }
    else
    {
        $box = "...";
        $lnk = "...";
    $stat = "<font color=\"red\">processing...</font>";
    }
    $r['res'] = $r['res']==0?"DEFAULT":$r['res'];
    echo "<tr><td>".str_replace('.'.@end(@explode('.',$r['name'])),'.mp3',$r['name']).'</td><td>'.$r['res'].'</td><td>'.$stat."</td><td>$lnk</td><td>$box</td></div>";
    
    unset($stat);
    unset($vid);
    }
    
    }
}
}

?>
</div>
</body>
</html>