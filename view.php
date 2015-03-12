<?php
require('auth.php');

function show_claim(){
    $Fields=[['c_name','название','text'],['descr','описание','text'],['phone','контактный телефон','text'],['pic','фото','img']];
    $dbh=get_dbh();
    $request=$dbh->prepare('select c_name,descr,phone,pic from claim  where c_name=?');
    $request->execute([preg_replace("/[;\'\"].*$/",'',$_REQUEST['c_name'])]);
    $result=$request->fetchAll( PDO::FETCH_ASSOC)[0];
    echo('<table>');
    foreach ($Fields as $f){
        $data=$result[$f[0]];
        if ($data){
            echo ("<tr><td>$f[1]:</td>");
            if ($f[2]=='text')
                echo("<td> $data </td>");
            if ($f[2]=='img')
                echo("<td><img src=\"$data\"></td></tr>");    
        }
    }
    echo('</table>');
}
?>
<html> 
  <body>
<?php include('navigation.part');?>
<?php
session_start();
if (isset($_COOKIE['auth_sid'],$_SESSION['auth_sid']) &&  ($_COOKIE['auth_sid']==$_SESSION['auth_sid'])){
    echo(show_claim()); }
else
    if  (isset($_REQUEST['login'],$_REQUEST['pwd'])){
        if (!login($_REQUEST['login'],$_REQUEST['pwd'])){
            echo('incorrect login/password');
            echo(show_auth());
        }
        else{
            echo(show_claim());
        }
    }
    else{
        echo('authentication required');
        echo(show_auth());
    }
?>
  </body>
</html>
