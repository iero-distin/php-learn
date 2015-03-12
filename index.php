<?php 
/*
TODO: pagination

*/
require ('auth.php');
function show_list(){
    $dbh=get_dbh();
    $sql='select c_name,descr from claim  where owner=?';
    $sqlparams=[$_SESSION['uid']];
    if ($_SESSION['group_id']==0){
        $sql='select c_name,descr from claim';
        $sqlparams=[];
        echo '<a href="getxml.php">download in xml</a>';
    }    
    $request=$dbh->prepare($sql);
    $request->execute($sqlparams);
    //$result=$request->fetchAll( PDO::FETCH_ASSOC);
    echo '<table><hr><td>название</td><td>описание</td></hr>';
    while ($row = $request->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
        echo "<tr>";
        foreach ($row as $cell)
            echo "<td><a href=\"view.php?c_name=$row[0]\">$cell</a></td>";
        echo '</tr>';
    }
    echo '</table>';
}
?>
<html> 
  <body>
<?php include('navigation.part');?>
    <?php
session_start();
if (isset($_COOKIE['auth_sid'],$_SESSION['auth_sid']) &&  ($_COOKIE['auth_sid']==$_SESSION['auth_sid'])){
    echo(show_list()); }
else
    if  (isset($_REQUEST['login'],$_REQUEST['pwd'])){
        if (!login($_REQUEST['login'],$_REQUEST['pwd'])){
            echo('incorrect login/password');
            echo(show_auth());
        }
        else{
            echo(show_list());
        }
    }
    else{
        echo('authentication required');
        echo(show_auth());
    }
?>
  </body>
</html>
