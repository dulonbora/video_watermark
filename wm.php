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
<title>Add Watermark</title>
</head>
<body><div id="cn">

<?php
$id = intval($_GET['id']);

$q = $init->prepare("SELECT wm,path,name FROM files WHERE uid=?");
$q->bind_param("i",$id);
if($q->execute())
{
$q->store_result();
$q->bind_result($wm,$path,$name);
$q->fetch();

if(!isset($_POST['submit']) && !empty($name) && !empty($path)) // watermark if no watermark has been applied
{
?>
<body>
<div class="wrapl"><b><a href="index.php">Files</a> | <a href="show.php?id=<?=$id;?>"><?=$name;?></a> | <a href="upload.php">Upload</a> | <font color="red"><?=$name;?></font></b></div>
<form method="POST" enctype="multipart/form-data" action="wm.php?id=<?=$id;?>">
select a watermark file (png,jpeg): <input type="file" name="wm_file" />
<br />
<center><strong>OR</strong></center>
select from url: <input type="text" name="wm_url"  value="http://"/>
<br />
Alignment: <select name="wm_align">
<option value="tl">Top Left</option>
<option value="tr">Top Right</option>
<option value="bl">Bottom Left</option>
<option value="br">Bottom Right</option>
<option value="bc">Bottom Center</option>
<option value="c">Center</option>
</select><br />
<input type="submit" name="submit" value="watermark" />
</form>
<a href="index.php">Back</a>
</body>
<?php
}
elseif(isset($_POST['submit']))
{
    echo '<div class="wrapl"><a href="index.php">Files</a> |'.$name.' | <a href="upload.php">Upload</a> | <a href="logout.php">logout</a></div>';
if(isset($_POST['wm_align']) && (($_POST['wm_url']!='http://' && !empty($_POST['wm_url'])) || (!empty($_FILES['wm_file']['name']))))
        {
        //saving watermark file
        if(empty($_FILES['wm_file']['name']) && !empty($_POST['wm_url']) && $_POST['wm_url']!='http://')
    {
    $wm_source = $_POST['wm_url'];
    $wm_ext = strtolower(@end(@explode('.',$wm_source)));
    
    $wm_name = md5(str_shuffle("abshdfgfgsdssl47547412")).'.'.$wm_ext;
    $t = "import";
    }
    elseif(!empty($_FILES['wm_file']['name']))
            {
            $wm_source = $_FILES['wm_file']['tmp_name'];
            $wm_ext = strtolower(@end(@explode('.',$_FILES['wm_file']['name'])));
            $wm_name = md5(str_shuffle("abshdfgfgsdssl47547412")).'.'.$wm_ext;
            $t = "upload";
            }
            if($t=="import" && in_array($wm_ext,array("png","jpeg","jpg")))
            {
            if(copy($wm_source,$initial_save_dir.$wm_name))
                {
                    
                    $info = str_replace("\n","<br />",shell_exec("ffmpeg -i ".escapeshellarg($initial_save_dir.$name)." 2<&1"));
                    preg_match('|Video:(.*?)kb/s|',$info,$video_kbs);
                    preg_match('|Audio:(.*?)kb/s|',$info,$audio_kbs);

                    $video_bitrate =  intval(@end(@explode(',',$video_kbs[1])));
                    $audio_bitrate = intval(@end(@explode(',',$audio_kbs[1])));
                    
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
         
                    
                    $del = $init->prepare("DELETE FROM previews WHERE uid=?");
                    $del->bind_param("i",$id);
                    $del->execute();
                    
                    $q = $init->prepare("UPDATE files SET wm_image=?,wm=?,prev_status=? WHERE uid=?");
                    $tmp = $initial_save_dir.$wm_name;
                    $wm = 1;
                    $prev_status = 0;
                    $q->bind_param("siii",$tmp,$wm,$prev_status,$id);
                    if($q->execute())
                    {
                        // watermarking goes here
                        $ext = @end(@explode('.',$name));
                       run_terminal(align_wm($initial_save_dir.$name,$final_save_dir.$name,$_POST['wm_align'],$video_bitrate,$audio_bitrate));
                      
                       echo "<b>File is being watermarked. Check out <a href=\"index.php\">Files</a> section to track the process</b><br />";
                    }
                }
            }
            elseif($t=="upload" && in_array($wm_ext,array("png","jpeg","jpg")))
            {

               
               $info = str_replace("\n","<br />",shell_exec("ffmpeg -i \"$initial_save_dir$name\" 2<&1"));
                    preg_match('|Video:(.*?)kb/s|',$info,$video_kbs);
                    preg_match('|Audio:(.*?)kb/s|',$info,$audio_kbs);

                    $video_bitrate =  intval(@end(@explode(',',$video_kbs[1])));
                    $audio_bitrate = intval(@end(@explode(',',$audio_kbs[1])));
                move_uploaded_file($wm_source,$initial_save_dir.$wm_name);
                
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
                
                    $del = $init->prepare("DELETE FROM previews WHERE uid=?");
                    $del->bind_param("i",$id);
                    $del->execute();
                
                $q = $init->prepare("UPDATE files SET wm_image=?,wm=?,prev_status=? WHERE uid=?");
                    $tmp = $initial_save_dir.$wm_name;
                    $wm = 1;
                    $prev_status = 0;
                    $q->bind_param("siii",$tmp,$wm,$prev_status,$id);
                    if($q->execute())
                    {
                        //watermarking goes here
                        $ext = @end(@explode('.',$name));
                        run_terminal(align_wm($initial_save_dir.$name,$final_save_dir.$name,$_POST['wm_align'],$video_bitrate,$audio_bitrate));
                        echo "<b>File is being watermarked. Check out <a href=\"index.php\">Files</a> section to track the process</b><br />";
                    }
                }
                else
                die("Unsupported watermark image<br /><a href=\"wm.php?id=$id\">Back</a>");
}
}
}
else
echo "video does not exists";
?>
</div>
</body>
</html>