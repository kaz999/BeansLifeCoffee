<?php
/*-------------------------

	     ログイン
     
-------------------------*/
//ログインボタンが押した時
if($_POST['login_h']=="1"){
	if($_POST['l_mail'] != '' && $_POST['l_pass'] != ''){
		//MYSQL接続
		$db=mysql_connect('mysql003.phy.lolipop.lan','LAA0473172','********');
		mysql_select_db('LAA0473172-database',$db);
		mysql_query("set character set utf8",$db);

		$sql=sprintf('SELECT*FROM login WHERE MAIL="%s" AND PASS="%s"',
	    			  mysql_real_escape_string($_POST['l_mail']),
	                  mysql_real_escape_string(sha1($_POST['l_pass']))
        			);
	    $record=mysql_query($sql,$db);
	    if($table = mysql_fetch_assoc($record)){
	    	//ログイン成功
	        $_SESSION['login']=2;
	        $_SESSION['ID'] = $table['ID'];
            $_SESSION['time'] = time();
            header('Location: https://noor-connect-ki.ssl-lolipop.jp/BeansLifeCoffee/sitework/index.php'.SID);
	    }else{
	    	//メール・パスワードが一致しなかった場合
            $_SESSION['l_mail']=$_POST['l_mail'];
	        header('Location: login_error.php?input=2'.SID);
            exit();
	    }
	}else{
		//テキストが空白の場合
        $_SESSION['l_mail']=null;
    	header('Location: login_error.php?input=1'.SID);
        exit();
	}
}
?>