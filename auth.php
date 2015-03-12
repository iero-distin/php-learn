<?php 
function get_dbh(){
    $dbname='intaro';
    $host='127.0.0.1';
    $port='3306';
    $dsn = 'mysql:host='.$host.';port='.$port.';dbname='.$dbname;
    $username = 'intaro';
    $password = 'Tykhygg8Twep';
    $options = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    ); 
    $dbh = new PDO($dsn, $username, $password, $options);
    return $dbh;
}

function check_auth($username,$password){
    $dbh=get_dbh();
    $request=$dbh->prepare('select uid,group_id,login from user where login=? and passwd=sha2(?,0)');
    $request->execute([$username,$password]);
    $result=$request->fetchAll( PDO::FETCH_ASSOC);
    $dbh=Null;
    if (count($result) !=1)
        return NUll;
    return $result;
}

function login($username,$password){
    $login=preg_replace("/[;\'\"].*$/",'',$username);
    $pwd=str_replace([';',"'",'"'],['$','#','^'],$password);
    try{
        $A=check_auth($login,$pwd)[0];
        if ($A){
            session_start();
            if (!isset($_SESSION['auth_sid']))
                $_SESSION['auth_sid']=md5($A['login'].time());
            setcookie("auth_sid", $_SESSION['auth_sid'], time()+3600);
            foreach ($A as $key => $val)
                $_SESSION[$key]=$val;
            return $A;
        }
    else 
        return logout();
    }
    catch (Exception $e){
	return 'fail';
    }
}
    
function logout(){
    if(isset($_COOKIE['auth_sid'])) 
        setcookie('auth_sid', "", time()-3600);
    session_start();
    session_destroy();
    return NULL;
}
function show_auth(){
    $Ret = <<<'frm'
        <form >
    <input name='login'><br>
	<input name='pwd'><br>
	<input type='submit'><br>
    </form>
frm;
return $Ret;
/*function get_logout(){
    return '<input type=button>'
    }*/
}
?>