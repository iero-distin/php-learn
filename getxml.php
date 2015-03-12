<?php 
/*
TODO: pagination
      cookie set to function with show_* as param
*/
require ('auth.php');

function xml(){
    if ($_SESSION['group_id']!=0){
        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?><answer>not allowed</answer>";
    }
    $dbh=get_dbh();
    $sql='select c_name as name,descr as description,phone, login as owner from claim join user on owner=uid';
    $request=$dbh->prepare($sql);
    $request->execute([]);
    $xmlstr= "<list>\n";
    while ($row = $request->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $xmlstr.="<claim ";
        foreach ($row as $key => $cell)
            $xmlstr.= "$key='$cell' ";
        $xmlstr.= "></claim>\n";
    }
    $xmlstr.= "</list>";
    return $xmlstr;
}
?>
    <?php
session_start();
if (isset($_COOKIE['auth_sid'],$_SESSION['auth_sid']) &&  ($_COOKIE['auth_sid']==$_SESSION['auth_sid'])){
    header('Content-type: text/xml');
    //header('Content-Disposition: attachment; filename="claim.xml"');
    echo(xml()); 
    exit();
}
else
    if  (isset($_REQUEST['login'],$_REQUEST['pwd'])){
        if (!login($_REQUEST['login'],$_REQUEST['pwd'])){
            echo('incorrect login/password');
            show_auth();
        }
        else{
            header('Content-type: text/xml');
            //header('Content-Disposition: attachment; filename="claim.xml"');
            echo(xml());
            exit();
        }
    }
    else{
        echo('authentication required');
        show_auth();
    }
?>
