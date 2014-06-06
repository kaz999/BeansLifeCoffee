<?php
header("Content-Type:text/html; charset=UTF-8");
error_reporting(4);
session_start();
           
//ログイン機能
require('lgconnect.php');

//ユーザー名取得
//セッションIDにデータが入っているか、60分間以内にサイト内操作した場合、ログイン継続
if(isset($_SESSION['ID']) && $_SESSION['time'] + 3600 > time()){
    //60分以内にサイト内でアクションをした場合
    $_SESSION['time'] = time();
    
    //MYSQL接続
	require('dbconnect.php');
        
    $sql=sprintf('SELECT*FROM login WHERE ID="%s"',$_SESSION['ID']);
    $record=mysql_query($sql,$db);
    $menber=mysql_fetch_assoc($record);
}else{
	//セッションIDの中身がない又は操作せず60分以上経過した場合
    //強制ログアウト
	unset($_SESSION['login']);
    unset($_SESSION['ID']);
}
//ログアウト機能
//ログアウトボタンを押した時
if($_POST['logout_1']==1){
	//ログインセッション情報を削除
	unset($_SESSION['login']);
    unset($_SESSION['ID']);
}

//ショッピングカート

//商品の合計値段
$price=$_SESSION['item_sum'];

//ユーザー情報
$sei = $_SESSION['join']['sei'];
$mei = $_SESSION['join']['mei'];
$seikana = $_SESSION['join']['seikana'];
$meikana = $_SESSION['join']['meikana'];
$zipcode1 = $_SESSION['join']['zipcode1'];
$zipcode2 = $_SESSION['join']['zipcode2'];
$todohuken = $_SESSION['join']['todohuken'];
$shikugun = $_SESSION['join']['shikugun'];
$banchi = $_SESSION['join']['banchi'];
$tatemono = $_SESSION['join']['tatemono'];
$tel = $_SESSION['join']['tel'];
$fax = $_SESSION['join']['fax'];
$mail = $_SESSION['join']['mail'];
$remark = $_SESSION['join']['comment'];

//別住所の情報
$Asei = $_SESSION['join_c2']['sei'];
$Amei = $_SESSION['join_c2']['mei'];
$Aseikana = $_SESSION['join_c2']['seikana'];
$Ameikana = $_SESSION['join_c2']['meikana'];
$Azipcode1 = $_SESSION['join_c2']['zipcode1'];
$Azipcode2 = $_SESSION['join_c2']['zipcode2'];
$Atodohuken = $_SESSION['join_c2']['todohuken'];
$Ashikugun = $_SESSION['join_c2']['shikugun'];
$Abanchi = $_SESSION['join_c2']['banchi'];
$Atatemono = $_SESSION['join_c2']['tatemono'];
$Atel = $_SESSION['join_c2']['tel'];


//お届先・お支払情報
$deli=$_SESSION['join_c']['deli'];
$pay=$_SESSION['join_c']['pay'];

//データベース接続
require('dbconnect.php');

//ユーザーが購入した商品情報を、一つずつ抜き出す
//商品名
$item=$_SESSION['item'];
$itemInfo="";
for($i=0; $i<count($item); $i++){
   	$itemInfo=$itemInfo.$item[$i][1]."/";
}
//豆の状態
$beanInfo="";
for($j=0; $j<count($item); $j++){
	$beanInfo=$beanInfo.$item[$j][2]."/";
}
//数量
$qntInfo="";
for($k=0; $k<count($item); $k++){
	$qntInfo=$qntInfo.$item[$k][3]."/";
}
//個々の金額
$gpInfo="";
for($l=0; $l<count($item); $l++){
	$gpInfo=$gpInfo.$item[$l][4]."/";
}

//データベースに商品情報・顧客情報・届先支払情報の書き込み
$sql = sprintf('INSERT INTO customer SET SEI="%s", MEI="%s", SEIKANA="%s", MEIKANA="%s",ZIPCODE1="%s", ZIPCODE2="%s",
										 TODOHUKEN="%s", SHIKUGUN="%s", BANCHI="%s", TATEMONO="%s", TEL="%s", FAX="%s",
                                         MAIL="%s", REMARK="%s",
                                         A_SEI="%s", A_MEI="%s", A_SEIKANA="%s", A_MEIKANA="%s", A_ZIPCODE1="%s", A_ZIPCODE2="%s",
                                         A_TODOHUKEN="%s", A_SHIKUGUN="%s", A_BANCHI="%s", A_TATEMONO="%s", A_TEL="%s",
                                         ITEMINFO="%s",BEANINFO="%s",QNTINFO="%s",GOODSPRICEINFO="%s",
                                         PRICE="%s",PAY="%s"',
                                         
                                         mysql_real_escape_string($sei),
                                         mysql_real_escape_string($mei),
                                         mysql_real_escape_string($seikana),
                                         mysql_real_escape_string($meikana),
                                         mysql_real_escape_string($zipcode1),
                                         mysql_real_escape_string($zipcode2),
                                         mysql_real_escape_string($todohuken),
                                         mysql_real_escape_string($shikugun),
                                         mysql_real_escape_string($banchi),
                                         mysql_real_escape_string($tatemono),
                                         mysql_real_escape_string($tel),
                                         mysql_real_escape_string($fax),
                                         mysql_real_escape_string($mail),
                                         mysql_real_escape_string($remark),
                                         mysql_real_escape_string($Asei),
                                         mysql_real_escape_string($Amei),
                                         mysql_real_escape_string($Aseikana),
                                         mysql_real_escape_string($Ameikana),
                                         mysql_real_escape_string($Azipcode1),
                                         mysql_real_escape_string($Azipcode2),
                                         mysql_real_escape_string($Atodohuken),
                                         mysql_real_escape_string($Ashikugun),
                                         mysql_real_escape_string($Abanchi),
                                         mysql_real_escape_string($Atatemono),
                                         mysql_real_escape_string($Atel),
                                         mysql_real_escape_string($itemInfo),
                                         mysql_real_escape_string($beanInfo),
                                         mysql_real_escape_string($qntInfo),
                                         mysql_real_escape_string($gpInfo),
                                         mysql_real_escape_string($price),
                                         mysql_real_escape_string($pay)
			  );

mysql_query($sql,$db);

//ショッピングカート情報のセッションを削除
unset($_SESSION['join']);
unset($_SESSION['join_c']);
unset($_SESSION['join_c2']);
unset($_SESSION['item']);
unset($_SESSION['item_sum']);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja" dir="ltr"><!-- InstanceBegin template="/Templates/common.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Style-Type" content="text/javascript" />
<meta name="keywords" content="コーヒー,コーヒー豆,通販,ショッピング" />
<meta name="description" content="厳選されたコーヒー豆の販売とカフェのBeansLifeCoffee" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>BeansLifeCoffee ビーンズライフコーヒー</title>
<link href="css/complete.css" rel="stylesheet" type="text/css" media="screen" />
<!-- InstanceEndEditable -->
<link href="css/common.css" rel="stylesheet" type="text/css" />
<!--[if IE 6 ]>
<link rel="stylesheet" type="text/css" href="css/common_ie6.css" media="screen">
<![endif]-->
<script type="text/javascript" src="js/swfobject_modified.js"></script>
<script type="text/javascript" src="js/rollover.js"></script>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<!-- InstanceParam name="id" type="text" value="" -->
</head>

<body id="" onload="MM_preloadImages('images/cart_b_ov.gif','images/new_uesr_b_ov.gif','images/c_image1ov.jpg','images/c_image2ov.jpg','images/c_image3ov.jpg','images/c_image4ov.jpg',,'images/image_1ov.jpg','images/image_2ov.jpg','images/image_3ov.jpg')">
<!--Container開始-->
<div id="container">
<!--Header開始-->
<div id="header">
<h1 id="logo"><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/index.php"><img src="images/logo.gif" width="154" height="90" alt="beanslifecoffee" /></a></h1>
<div class="header_t">
	<p>3000円以上のお買い上げで送料無料!</p>
	<ul>
		<li class="subnavi_1"><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/guide.php">配達・送料</a></li>
		<li><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/sitemap.php">サイトマップ</a></li>
		<li><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/recruit.php">採用情報</a></li>
	</ul>
</div>
<!--Header終了-->
</div>
<!--GlobalNavi開始-->
<div id="g_navi">
	<ul>
		<li><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/index.php" id="home">ホーム</a></li>
		<li><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/goods.php" id="goods">商品一覧</a></li>
		<li><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/store_d.php" id="store">店舗メニュー</a></li>
		<li><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/howtoinfo.php" id="howtoinfo">ご購入ガイド</a></li>
    	<li><a href="https://noor-connect-ki.ssl-lolipop.jp/BeansLifeCoffee/sitework/inquiry.php" id="inquiry">お問合わせ</a></li>
    	<li><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/access.php" id="access">アクセス</a></li>
	</ul>
</div>
<!--GlobalNavi終了-->
<!--SideMenu開始-->
<div id="s_menu">
	<div id="side_info">
		<div id="s_cart">
			<div id="s_info">
				<h3 class="s_title"><img src="images/shopping_c.gif" alt="ShoppingCart" width="150" height="25" /></h3>
				<ul class="s_moji">
					<li>商品&nbsp;0点</li>
					<li><span class="sli">合計&nbsp;0円</span></li>
				</ul>
				<p class="c_info"><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/cartinfo.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('cart_image','','images/cart_b_ov.gif',1)">
				<img src="images/cart_b.gif" alt="カートの中身を見る" name="cart_image" width="146" height="20" border="0" /></a></p>
			</div>
			<p><img src="images/waku_1.gif" alt="ShoppingCart" width="159" height="109" class="s_waku" /></p>
		</div>
		<div id="u_login">
			<div id="u_info">
        		<h3 class="s_title"><img src="images/user_l.gif" width="150" height="25" alt="UserLogin" /></h3>
			<?php if($_SESSION['login'] == ""): ?>
           		<form id="login_f" name="login_f" method="post" action="">
   		    		<p class="f_text">mail&nbsp;<input name="l_mail" type="text" id="l_mail" size="15" /></p>
       		  		<p class="f_text">pass&nbsp;<input name="l_pass" type="password" id="l_pass" size="15"/></p>
              		<p class="l_bottom">
              		  <input name="l_btm" type="image" id="l_btm" src="images/login_b.gif" onmouseover="images/login_b_ov.gif" onmouseout="images/login_b.gif" alt="ログイン" />
              		  <input name="login_h" type="hidden" id="login_h" value="1" />
              		</p>
               </form>
              		<p class="pass_i"><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/r_line.php">パスワードをお忘れの方</a></p>
              		<p class="new_u">
                  	<a href="https://noor-connect-ki.ssl-lolipop.jp/BeansLifeCoffee/sitework/newuser.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image20','','images/new_uesr_b_ov.gif',1)">
                			<img src="images/new_uesr_b.gif" alt="新規登録の方はこちら" name="Image20" width="140" height="35" border="0" /></a></p>
            <?php endif; ?>
            <?php if($_SESSION['login']==2){
            	echo "\t".'<p class="user_t">ようこそ<br />'.$menber['SEI'].$menber['MEI'].'&nbsp;様<p>'."\n".
		   			 "\t".'<form id="logout_f" name="logout_f" method="post" action="">'."\n".
                     "\t".'<p class="user_p">所持ポイント&nbsp;-pt</p>'."\n".
     	   			 "\t".'<p class="user_i"><a href="#">ユーザー情報変更</a></p>'."\n".
     	   			 "\t".'<p class="logout">'."\n".
		   			 "\t".'<input name="logout" type="image" id="logout" src="images/logout.gif"/>'."\n".
		   			 "\t".'<input name="logout_1" type="hidden" id="logout_1" value="1" />'."\n".
     	   			 "\t".'</p>'."\n".
		   			 "\t".'</form>'."\n";
            }?>
            </div>
        	<p><img src="images/wkau_2.gif" width="159" height="174" alt="UserLogin" /></p>
 	 	</div>
        <h3 class="s_title_mc"><img src="images/menu_title.gif" width="157" height="27" alt="Menu" /></h3>
        <p class="menu_c_s"><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/store_d.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image21','','images/image_1ov.jpg',1)">
   	    <img src="images/image_1.jpg" alt="店舗メニューCoffee&amp;Softdrink" name="Image21" width="150" height="100" border="0" id="Image21" /></a></p>
       	<p class="menu_m_d"><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/store_f.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image22','','images/image_2ov.jpg',1)">
    <img src="images/image_2.jpg" alt="店舗メニューMeal&amp;Dessert" name="Image22" width="150" height="100" border="0" id="Image22" /></a></p>
        <h3 class="s_title_mc"><img src="images/campaign_title.gif" alt="Campaign" width="157" height="27" /></h3>
        <p class="cmn_i"><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/campaign.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image23','','images/image_3ov.jpg',1)">
            <img src="images/image_3.jpg" alt="キャンペーン" name="Image23" width="150" height="180" border="0" id="Image23" /></a></p>
	</div>
</div>
<!--SideMenu終了--><!-- InstanceBeginEditable name="contents" -->
<!--Contents開始-->
<div id="contents">
  <div id="gBack">
  <h3>手続き完了</h3>
  <p>商品のご購入、ありがとうございました。<br />
    自動メールをお送りしましたので、詳細はそちらでご確認ください。<br />
    またのお越しをお待ちしております。</p>
  <p><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/index.php">トップページへ戻る</a></p>
  </div>
</div>
<!--Contents終了-->
<!-- InstanceEndEditable --><!--Footer開始-->
<div id="footer">
	<ul>
		<li class="subnavi_2"><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/p_policy.php">プライバシーポリシー</a></li>
		<li><a href="http://connect-ki.noor.jp/BeansLifeCoffee/sitework/company.php">会社概要</a></li>
	</ul>
	<address>Copyright(c) Beans LIfe Coffee All Right Reserved.</address>
</div>
<!--Footer終了-->
</div>
<!--Container終了-->
</body>
<!-- InstanceEnd --></html>
