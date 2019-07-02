<?php
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

* Developed By UniQiTech

* Video Watermarker

* Coded by UniqX

* Coded on 09/06/2015

* Contact: asomi.mobi@gmail.com

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
*/

$siteurl = 'http://localhost/';
$prev_limit = 5;
$prev_sec = 5;

$vm_exts = array("3gp","mp4");
$other_exts = array("zip","mp3","amr","wav");
$allowedExts = array_merge($vm_exts,$other_exts);
$initial_file_save = "files/";
$initial_save_dir = "temp/";
$final_save_dir = "converted/";
$conv_final = "conv/";

$audio_bitrates = array(32,48,56,64,96,128,160,192,240,384);
$video_bitrates = array(64,96,128,160,192,240,384,576,720,960,1056,1440,1680,2016,2500,3000,3500); 

$encoders = array("XVid"=>"libxvid","DivX"=>"divx","MPEG-1"=>"mpeg1video","MPEG-2"=>"mpeg2video","MPEG-4"=>"mpeg4","H.264"=>"libx264");
$sampling_rates = array(48000,44100,32000,24000,16000,12000,11050);
$convertables = array("mp4","3gp","avi");

$prev_frames = array(20,50,80,120,150,180,220,250,300);
$resolutions = array(0,"240x176","320x176","320x240","480x320","480x360","640x360","640x480","720x480","720x540","800x600","960x540","960x720","1024x576","1024x768","1280x720","1600x900","1920x1080");

$host = "localhost";
$username = "root";
$password = '';
$db = "wtr";

include_once 'connect.php';
?>