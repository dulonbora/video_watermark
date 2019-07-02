<?php
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

* Developed By UniQiTech

* Video Watermarker

* Coded by UniqX

* Coded on 09/06/2015

* Contact: asomi.mobi@gmail.com

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
*/

function align_wm($inputvideo,$outputvideo,$al,$video_br,$audio_br)
{
global $initial_save_dir;
global $ext;
global $wm_name;

if(!file_exists($inputvideo))
die("Input file does not exist<br />");

if($ext=='3gp')
$in = ' -ac 1 -ar 8000';
else
$in = null;
switch($al)
    {
    case 'tl':
    $cmd = '-i '.escapeshellarg($inputvideo).' -i '.$initial_save_dir.'/'.$wm_name.''.$in.' -acodec libvo_aacenc -minrate 64k -b:a '.escapeshellarg($audio_br."k").' -b:v '.escapeshellarg($video_br."k").' -filter_complex "[0:v][1:v] overlay=2:2:enable=\'gte(t,3)\'" '.escapeshellarg($outputvideo).''; break;
     case 'tr':
     $cmd = '-i '.escapeshellarg($inputvideo).' -i '.$initial_save_dir.'/'.$wm_name.''.$in.' -acodec libvo_aacenc -minrate 64k -b:a '.escapeshellarg($audio_br."k").' -b:v '.escapeshellarg($video_br."k").' -filter_complex "[0:v][1:v] overlay=main_w-overlay_w-10:10:enable=\'gte(t,3)\'" '.escapeshellarg($outputvideo).''; break;
     case 'bl':
     $cmd = '-i '.escapeshellarg($inputvideo).' -i '.$initial_save_dir.'/'.$wm_name.''.$in.' -acodec libvo_aacenc -minrate 64k -b:a '.escapeshellarg($audio_br."k").' -b:v '.escapeshellarg($video_br."k").' -filter_complex "[0:v][1:v] overlay=10:main_h-overlay_h-10:enable=\'gte(t,3)\'" '.escapeshellarg($outputvideo).''; break;
     case 'br':
     $cmd = '-i '.escapeshellarg($inputvideo).' -i '.$initial_save_dir.'/'.$wm_name.''.$in.' -acodec libvo_aacenc -minrate 64k -b:a '.escapeshellarg($audio_br."k").' -b:v '.escapeshellarg($video_br."k").' -filter_complex "[0:v][1:v] overlay=main_w-overlay_w-10:main_h-overlay_h-10:enable=\'gte(t,3)\'" '.escapeshellarg($outputvideo).''; break;
     case 'bc':
     $cmd = '-i '.escapeshellarg($inputvideo).' -i '.$initial_save_dir.'/'.$wm_name.''.$in.' -acodec libvo_aacenc -minrate 64k -b:a '.escapeshellarg($audio_br."k").' -b:v '.escapeshellarg($video_br."k").' -filter_complex "[0:v][1:v] overlay=(main_w-overlay_w-10)/2:(main_h-overlay_h-10):enable=\'gte(t,3)\'" '.escapeshellarg($outputvideo).''; break;
    case 'c':
     $cmd = '-i '.escapeshellarg($inputvideo).' -i '.$initial_save_dir.'/'.$wm_name.''.$in.' -acodec libvo_aacenc -minrate 64k -b:a '.escapeshellarg($audio_br."k").' -b:v '.escapeshellarg($video_br."k").' -filter_complex "[0:v][1:v] overlay=(main_w-overlay_w-10)/2:(main_h-overlay_h-10)/2:enable=\'gte(t,3)\'" '.escapeshellarg($outputvideo).''; break;
    default:
    false; break;    
    }
return $cmd;
}
function run_terminal($cmd)
{
global $ps;
if(strtolower(substr(php_uname(),0,7))=='windows')
$cmd = "start /B ffmpeg $cmd -y>NUL";
else
$cmd = "ffmpeg $cmd -y>/dev/null &";

pclose(popen($cmd,'r'));
}
function vid_2_mp3($path,$conv_path,$rate)
{
    $out_path = str_replace(".".@end(@explode('.',$conv_path)),'_'.$rate.".mp3",$conv_path);
    
    if($rate!=0)
    $cmd = "-i ".escapeshellarg($path)." -vn -minrate 64k -b:a ".escapeshellarg($rate."k")." ".escapeshellarg($out_path);
    else
    $cmd = "-i ".escapeshellarg($path)." -vn ".escapeshellarg($out_path);
    return $cmd;
}
function convert_file($path,$conv_path,$conv_to_ext,$res=0,$bitrate,$audio_br,$ar=0,$encoder=0)
{
if($conv_to_ext=="3gp")
{
    $add = $res!=0?" -s ".escapeshellarg($res)."":null;
   $cmd = "-i ".escapeshellarg($path)." -vcodec libx264$add -minrate 64k -ac 1 -ar 8000 ".escapeshellarg($conv_path);
}
else
{
    $add = $res!=0?" -s ".escapeshellarg($res)."":null;
    $add1 = $ar!=0?" -ar ".escapeshellarg($ar)."":null;
    $add2 = $bitrate!='0'?" -b:v ".escapeshellarg($bitrate."k")."":null;
    $add3 = $audio_br!='0'?" -b:a ".escapeshellarg($audio_br."k")."":null;
    if($encoder=="divx" && $conv_to_ext=="avi")
    $add4 = "-vtag DIVX -vcodec mpeg4";
    else
    $add4 = $encoder=='0'?null:" -vcodec ".escapeshellarg($encoder);
    
   $cmd = "-i ".escapeshellarg($path)."$add -minrate 64k$add3 $add4$add1$add2 ".escapeshellarg($conv_path)."";
}
return $cmd;
}
function format_name($name)
{
$name = urldecode(htmlentities($name));
return str_replace(array('/'),array(null),$name);
}

function to_format($sec)
{
$h = intval(floor($sec/3600));
$m = floor(($sec-($h*3600))/60);
$s = floor($sec%60);
if($h=='0')
$h = '00';
if($s==0)
$s = '00';
if($m==0)
$m ='00';
if(strlen($h)==1)
$h = '0'.$h;
if(strlen($m)==1)
$m = '0'.$m;
if(strlen($s)==1)
$s = '0'.$s;
return $h.":".$m.":".$s;
}
?>