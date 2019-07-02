<?php
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

* Developed By UniQiTech

* Video Watermarker

* Coded by UniqX

* Coded on 09/06/2015

* Contact: asomi.mobi@gmail.com

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
*/

$v = null;
function real_url($durl)
{
    $h = get_headers($durl,1);
    $h = array_change_key_case($h,CASE_LOWER);        

if(array_key_exists('content-disposition',$h) && !array_key_exists('location',$h))
return false;                

if(!empty($h['location']))
{
$v = $h['location'];
return $v;
}
elseif(count(@$v)<=0)
{
    return $durl;
}
}

?>