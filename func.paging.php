<?php
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

* Developed By UniQiTech

* Video Watermarker

* Coded by UniqX

* Coded on 09/06/2015

* Contact: asomi.mobi@gmail.com

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
*/

function navigation($page,$perpage,$lastpage,$prev=false,$arr=false){
if($page=='1' && $lastpage!='1'){
    echo '<td><a href="index.php?page=2">More</a></td>';}
if($page>1){
$prev = $page-1;
$next = $page+1;

echo '<td><a href="index.php?page='.$prev.'">Previous</a></td>';

if($page!=$lastpage){ 
 echo '<td><a href="index.php?page='.$next.'">Next</a></td>';}
 
 echo '<td><a href="index.php">Home</a></td>';
}
}
?>