<?php
require('auth.php');

function process_file(){
    $pic=$_FILES['pic'];
    if (!isset($pic['error']) || is_array($pic['error']) ) {
        return Null;
    }
    if(!$extension=array_search($finfo->file($pic['tmp_name']),array('jpg'=>'image/jpeg','png'=>'image/png','gif'=>'image/gif')))
        return Null;
    $prefix="/intaro/img/";
    $filename=$prefix.sha1_file($pic['tmp_name']);
    if (!move_uploaded_file($pic['tmp_name'],$filename))
        return Null;
    return $filename;
}
process_file();
?>
<html> 
  <body>
<form enctype="multipart/form-data" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
<input type='file' name='pic' /><br>
<input type = 'submit' name='ok' value='добавить заявку'/></form>
  </body>
</html>
