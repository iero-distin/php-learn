<?php
require('auth.php');

function show_form(){
    $Fields=[['c_name','название','text'],['descr','описание','text'],['phone','контактный телефон','text'],['pic','фото','file']];
    if ( isset($_REQUEST['c_name'],$_REQUEST['descr']) ){
        $dbh=get_dbh();
        $request=$dbh->prepare('insert into claim (c_name,descr,phone,pic,owner) values (:c_name,:descr,:phone,:pic,:owner)');
        $Arr=[];
        foreach ($Fields as $f)
            $Arr[$f[0]]=$_REQUEST[$f[0]];
        $Arr['pic']=process_file($_FILES['pic']);
        $Arr['owner']=$_SESSION['uid'];
        $request->execute($Arr);
    }
    echo ('<form enctype="multipart/form-data" method="POST">');
    echo ('<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />');
    foreach ($Fields as $f){
        echo "$f[1] : <input type=$f[2] name=$f[0] /><br>";
    }
    echo ("<input type = 'submit' name='ok' value='добавить заявку'/></form>");
    
}
function process_file($file){
    if (!isset($file) || !isset($file['error']) || is_array($file['error']) ) { //file uploaded
        return Null;
    }
    $finfo=new finfo(FILEINFO_MIME_TYPE);
    if(!$extension=array_search($finfo->file($file['tmp_name']),array('jpg'=>'image/jpeg','png'=>'image/png','gif'=>'image/gif'))) //right type
        return Null;
    $prefix="/intaro/img/";
    $filename=$prefix.sha1_file($file['tmp_name']); //uniq file
    if (!move_uploaded_file($file['tmp_name'],$filename))
        return Null;
    return $filename;
}
?>
<html> 
  <body>
<?php include('navigation.part');?>
    <?php
session_start();
if (isset($_COOKIE['auth_sid'],$_SESSION['auth_sid']) &&  ($_COOKIE['auth_sid']==$_SESSION['auth_sid'])){
    show_form(); }
else
    if  (isset($_REQUEST['login'],$_REQUEST['pwd'])){
        if (!login($_REQUEST['login'],$_REQUEST['pwd'])){
            echo('incorrect login/password');
            echo(show_auth());
        }
        else{
            show_form();
        }
    }
    else{
        echo('authentication required');
        echo(show_auth());
    }
?>
  </body>
</html>
