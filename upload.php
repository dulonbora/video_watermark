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
<title>Upload Video</title>
</head>
<body><div id="cn">
<?php
if(!isset($_FILES['file']['name']) || !isset($_POST['url']))
{
?>
<html>
<body>
<div class="wrapl"><a href="index.php">Files</a> | Upload File | <a href="logout.php">logout</a></div>
<form method="POST" action="upload.php" enctype="multipart/form-data">
Select File: <input type="file" name="file" />
<center><strong>OR</strong></center>
Url: <input type="text" value="http://" name="url" />
<br />
<input type="submit" name="submit" value="Go" /><br />
<a href="index.php">Back</a>
</body>
</html>
<?php
}
else
{
    echo "<a href=\"index.php\">Files</a> | <a href=\"upload.php\">Upload File</a> | <a href=\"logout.php\">logout</a><br />";
if(empty($_FILES['file']['name'])  && !empty($_POST['url']) && $_POST['url']!='http://')
{
    include_once 'func.realurl.php';
    $source = filter_var($_POST['url'],FILTER_VALIDATE_URL);
    $cp_source = $source;    
    $source = real_url($source);
    
    if(preg_match('|googlevideo.com|i',$_POST['url']))
    {
        $v = get_headers($_POST['url'],1);
$v = array_change_key_case($v,CASE_LOWER);

$url = $v['location'];
if(is_array($url))
$url = $v['location'][0];

@preg_match('|filename="(.*?)"|',$v['content-disposition'],$name);
$name = @format_name(strip_tags($name[1]));
if(!array_key_exists('content-disposition',$v))
{
    
   preg_match('|mime=video/(.*?)&|',$v['location'],$name); 
   $name = rand(999,100000000).".".format_name(strip_tags($name[1]));

}
$ext = strtolower(@end(@explode('.',$name)));
$path = $initial_save_dir.'/'.$name;
$final_path = $final_save_dir.'/'.$name;

if(in_array($ext,$allowedExts))
{  
    
               $qf = $init->prepare("INSERT INTO files(path,name,final_path) VALUES(?,?,?)");
           $qf->bind_param("sss",$path,$name,$final_path);
           $qf->execute();
           if(copy($url,$path))
        echo "File Saved<br />";
    

        die;
        }
        else
         die("File type not supported. Please upload only ".implode(', ',$allowedExts)." files");
        }
    if($source==false)
    {
echo "unable to recover file<br />";
    die;
    }

     $name = format_name(strip_tags(@end(@explode('/',$source))));
    $ext = strtolower(@end(@explode('.',$name)));
    $path = $initial_save_dir."/".$name;
    $final_path = $final_save_dir."/".$name;
    
    if(!empty($source))
    {
    if(in_array($ext,$allowedExts))
    {
     if(copy($source,$path))
     {
     
           $qf = $init->prepare("INSERT INTO files(path,name,final_path) VALUES(?,?,?)");
           $qf->bind_param("sss",$path,$name,$final_path);
           $qf->execute();
        echo "File Saved<br />";
     }
            else
                echo "Unable to save file<br />";
                }
        else
           echo "File type not supported. Please upload only ".implode(', ',$allowedExts)." files";
     }
     else
     echo "Invalid URL<br />";
} // url upload

elseif(!empty($_FILES['file']['name']))
{
$name = format_name(strip_tags($_FILES['file']['name']));
$ext = strtolower(@end(@explode('.',$name)));
$path = $initial_save_dir."/".$name;
$final_path = $final_save_dir."/".$name;

if(in_array($ext,$allowedExts))
    {
        if(move_uploaded_file($_FILES['file']['tmp_name'],$path))
        {

           $qf = $init->prepare("INSERT INTO files(path,name,final_path) VALUES(?,?,?)");
           $qf->bind_param("sss",$path,$name,$final_path);
           $qf->execute();

            echo "File Uploaded Successfully<br /><a href=\"upload.php\">Back</div>";
            }
                else
                    echo "Unable to upload file<br />It must be due to wrong permissions!!<br /><a href=\"upload.php\">Back</div>";
    }
    else
    echo "File type not supported. Please upload only ".implode(', ',$allowedExts)." files<br /><a href=\"upload.php\">Back</div>";
 } // system file upload   
}
?>
</div>
</body>
</html>