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
    
    //ログイン状態でcartinfo_2.phpにきた場合
    //ユーザー情報を取得し、テキストフォームに表示
    $_POST['sei']=$menber['SEI'];
    $_POST['mei']=$menber['MEI'];
    $_POST['seikana']=$menber['SEIKANA'];
    $_POST['meikana']=$menber['MEIKANA'];
    $_POST['zipcode1']=$menber['ZIPCODE1'];
    $_POST['zipcode2']=$menber['ZIPCODE2'];
    $_POST['todohuken']=$menber['TODOHUKEN'];
    $_POST['shikugun']=$menber['SHIKUGUN'];
    $_POST['banchi']=$menber['BANCHI'];
    $_POST['tatemono']=$menber['TATEMONO'];
    $_POST['tel']=$menber['TEL'];
    $_POST['fax']=$menber['FAX'];
    $_POST['mail']=$menber['MAIL'];
    $_POST['mail_re']=$menber['MAIL'];
        
}else{
	//セッションIDの中身がない又は操作せず60分以上経過した場合
    //強制ログアウト
	unset($_SESSION['login']);
    unset($_SESSION['ID']);
}

/*-------------------------

	     ログアウト
     
-------------------------*/
//ログアウトボタンを押した時
if($_POST['logout_1']==1){
	//ログインセッション情報を削除
	unset($_SESSION['login']);
    unset($_SESSION['ID']);
}

//都道府県
$todohuken=array('お選びください','北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県',
				 '東京都','神奈川県','新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府',
				 '大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県','徳島県','高知県','福岡県','佐賀県',
				 '長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県');
                 
//都道府県の生成
for($i=0; $i<count($todohuken); $i++){
	$s_todohuken = $s_todohuken . '<option value="' . $todohuken[$i] . '">' . $todohuken[$i] . '</option>\n';
}

/*-------------------------

ショッピングカート
	 
-------------------------*/

//商品情報を保持したままで、１時間以内操作しないと商品情報を削除
if(isset($_SESSION[item]) && $_SESSION['time_g']+3600 > time()){
	$_SESSION['time_g'] = time();
}else{
	unset($_SESSION['item']);
    unset($_SESSION['item_sum']);
}

//商品情報の取得
$item = $_SESSION['item'];

//商品が一点も入ってない状態で、購入手続きボタンを押した時
if($item == null){
   	//前ページへ移動
	header('Location: cartinfo.php?input=1'.SID);
    exit();
}

//お客様情報のエラー文生成
if($_POST['btn03']=='1'){
	if(!empty($_POST)){
		if($_POST['sei'] == ''){
	    	$error['sei'] = 'blank';
	    }
	    if($_POST['mei'] == ''){
	    	$error['mei'] = 'blank';
	    }
	    if($_POST['seikana'] == ''){
	    	$error['seikana'] = 'blank';
	    }
	    if($_POST['meikana'] == ''){
	    	$error['meikana'] = 'blank';
	    }
	    if($_POST['zipcode1'] == ''){
	    	$error['zipcode1'] = 'blank';
	    }
	    if($_POST['zipcode2'] == ''){
	    	$error['zipcode2'] = 'blank';
	    }
	    if($_POST['todohuken'] == 'お選びください'){
	    	$error['todohuken'] = 'blank';
	    }
	    if($_POST['shikugun'] == ''){
	    	$error['shikugun'] = 'blank';
	    }
	    if($_POST['banchi'] == ''){
	    	$error['banchi'] = 'blank';
	    }
	    if($_POST['tel'] == ''){
	    	$error['tel'] = 'blank';
	    }
	    if($_POST['mail'] == ''){
	    	$error['mail'] = 'blank';
	    }
	    if($_POST['mail'] != $_POST['mail_re']){
	    	$error['mail_re'] = 'match';
	    }
	    if($_POST['mail_re'] == ''){
	    	$error['mail_re'] = 'blank';
	    }
	    //必須項目が全て記述されていたとき、確認ページへ移動
	    if(empty($error)){
	    	$_SESSION['join']=$_POST;
	        header('Location: https://noor-connect-ki.ssl-lolipop.jp/Connect/cartinfo_3.php'.SID);
	        exit();
	    }
	}
}
//cartinfo_3.phpから戻ってきたとき、フォーム内容を再表示
if(2==$_GET['input']){
	$_POST['sei']=$_SESSION['join']['sei'];
    $_POST['mei']=$_SESSION['join']['mei'];
    $_POST['seikana']=$_SESSION['join']['seikana'];
    $_POST['meikana']=$_SESSION['join']['meikana'];
    $_POST['zipcode1']=$_SESSION['join']['zipcode1'];
    $_POST['zipcode2']=$_SESSION['join']['zipcode2'];
    $_POST['todohuken']=$_SESSION['join']['todohuken'];
    $_POST['shikugun']=$_SESSION['join']['shikugun'];
    $_POST['banchi']=$_SESSION['join']['banchi'];
    $_POST['tatemono']=$_SESSION['join']['tatemono'];
    $_POST['tel']=$_SESSION['join']['tel'];
    $_POST['fax']=$_SESSION['join']['fax'];
    $_POST['mail']=$_SESSION['join']['mail'];
    $_POST['mail_re']=$_SESSION['join']['mail_re'];
    $_POST['comment']=$_SESSION['join']['comment'];
}

//サイドメニューのカート情報
//商品数
$item=$_SESSION['item'];
$item_count=count($item);
//合計金額
$item_sum=$_SESSION['item_sum'];
$total=number_format($item_sum);

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
<link href="css/delivery.css" rel="stylesheet" type="text/css" media="screen" />
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
<h1 id="logo"><a href="http://connect-ki.noor.jp/BeansLifeCoffee/index.php"><img src="images/logo.gif" width="154" height="90" alt="beanslifecoffee" /></a></h1>
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
					<li>商品&nbsp;<?php echo $item_count; ?>点</li>
					<li><span class="sli">合計&nbsp;<?php echo $total; ?>円</span></li>
				</ul>
				<p class="c_info"><a href="cartinfo.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('cart_image','','images/cart_b_ov.gif',1)">
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
              		<p class="pass_i"><a href="r_line.php">パスワードをお忘れの方</a></p>
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
        <p class="menu_c_s"><a href="http://connect-ki.noor.jp/BeansLifeCoffee/store_d.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image21','','images/image_1ov.jpg',1)">
   	    <img src="images/image_1.jpg" alt="店舗メニューCoffee&amp;Softdrink" name="Image21" width="150" height="100" border="0" id="Image21" /></a></p>
       	<p class="menu_m_d"><a href="http://connect-ki.noor.jp/BeansLifeCoffee/store_f.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image22','','images/image_2ov.jpg',1)">
    <img src="images/image_2.jpg" alt="店舗メニューMeal&amp;Dessert" name="Image22" width="150" height="100" border="0" id="Image22" /></a></p>
        <h3 class="s_title_mc"><img src="images/campaign_title.gif" alt="Campaign" width="157" height="27" /></h3>
        <p class="cmn_i"><a href="http://connect-ki.noor.jp/BeansLifeCoffee/campaign.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image23','','images/image_3ov.jpg',1)">
            <img src="images/image_3.jpg" alt="キャンペーン" name="Image23" width="150" height="180" border="0" id="Image23" /></a></p>
	</div>
</div>
<!--SideMenu終了--><!-- InstanceBeginEditable name="contents" -->
<!--Contents開始-->
<div id="contents">
  <div id="gBack">
  <h3>お客様情報  </h3>
  <h4><img src="images/cartinImage02.gif" width="550" height="35" /></h4>
  <p class="tx1"><span>*</span>印は必須入力ですので、ご了承下さい。</p>
  <form action="cartinfo_2.php" method="post" name="cst_form" id="cst_form">
  <table>
  <tr>
    <th scope="row"><span>*</span>お名前</th>
    <td>
    	姓&nbsp;<input name="sei" type="text" id="sei" size="10" 
        			   value="<?php echo htmlspecialchars($_POST['sei'],ENT_QUOTES,'UTF-8'); ?>" /><!--
     -->&nbsp;<!--
     -->名&nbsp;<input name="mei" type="text" id="mei" size="10" 
     				   value="<?php echo htmlspecialchars($_POST['mei'],ENT_QUOTES,'UTF-8'); ?>" />
     <?php if($error['sei']=='blank' || $error['mei']=='blank'): ?>
     	<p class="error">*姓名が未入力です</p>
     <?php endif; ?>
    </td>
  </tr>
  <tr>
    <th scope="row"><span>*</span>お名前(カナ)</th>
    <td>
    	セイ&nbsp;<input name="seikana" type="text" id="seikana" size="10" 
        				 value="<?php echo htmlspecialchars($_POST['seikana'],ENT_QUOTES,'UTF-8'); ?>" /><!--
     -->&nbsp;<!--
     -->メイ&nbsp;<input name="meikana" type="text" id="meikana" size="10" 
     					 value="<?php echo htmlspecialchars($_POST['meikana'],ENT_QUOTES,'UTF-8'); ?>" />
     <?php if($error['seikana']=='blank' || $error['meikana']=='blank'): ?>
     	<p class="error">*フリガナが未入力です</p>
     <?php endif; ?>
    </td>
  </tr>
  <tr>
    <th scope="row"><span>*</span>郵便番号</th>
    <td>
    	<input name="zipcode1" type="text" id="zipcode1" size="3" maxlength="3" 
        	   value="<?php echo htmlspecialchars($_POST['zipcode1'],ENT_QUOTES,'UTF-8'); ?>" />-<!--
     --><input name="zipcode2" type="text" id="zipcode2" size="4" maxlength="4" 
     		   value="<?php echo htmlspecialchars($_POST['zipcode2'],ENT_QUOTES,'UTF-8'); ?>" />
     <?php if($error['zipcode1']=='blank' || $error['zipcode2']=='blank'): ?>
     	<p class="error">*郵便番号が未入力です</p>
     <?php endif; ?>
    </td>
  </tr>
  <tr>
    <th scope="row"><span>*</span>都道府県</th>
    <td>
    	<select name="todohuken" id="todohuken">
        	<?php echo $s_todohuken; ?>
    	</select>
        <?php if($error['todohuken']=='blank'): ?>
        	<p class="error">*都道府県をお選びください</p>
        <?php endif; ?>
    </td>
  </tr>
  <tr>
    <th scope="row"><span>*</span>市区郡</th>
    <td>
    	<input name="shikugun" type="text" id="shikugun" size="45" 
        	   value="<?php echo htmlspecialchars($_POST['shikugun'],ENT_QUOTES,'UTF-8'); ?>" />
    	<?php if($error['shikugun']=='blank'): ?>
    		<p class="error">*市区郡が未入力です</p>
    	<?php endif; ?>
	</td>
  </tr>
  <tr>
    <th scope="row"><span>*</span>番地</th>
    <td>
    	<input name="banchi" type="text" id="banchi" size="45" 
        	   value="<?php echo htmlspecialchars($_POST['banchi'],ENT_QUOTES,'UTF-8'); ?>" />
    	<?php if($error['banchi']=='blank'): ?>
    		<p class="error">*番地が未入力です</p>
    	<?php endif; ?>
	</td>
  </tr>
  <tr>
    <th scope="row">建物名</th>
    <td><input name="tatemono" type="text" id="tatemono" size="45" 
    		   value="<?php echo htmlspecialchars($_POST['tatemono'],ENT_QUOTES,'UTF-8'); ?>" /></td>
  </tr>
  <tr>
    <th scope="row"><span>*</span>電話番号</th>
    <td>
    	<input name="tel" type="text" id="tel" size="30" 
        	   value="<?php echo htmlspecialchars($_POST['tel'],ENT_QUOTES,'UTF-8'); ?>" />
    	<?php if($error['tel']=='blank'): ?>
    		<p class="error">*電話番号が未入力です</p>
    	<?php endif; ?>
    </td>
  </tr>
  <tr>
    <th scope="row">FAX番号</th>
    <td><input name="fax" type="text" id="fax" size="30" 
    		   value="<?php echo htmlspecialchars($_POST['fax'],ENT_QUOTES,'UTF-8'); ?>" /></td>
  </tr>
  <tr>
    <th scope="row"><span>*</span>メールアドレス</th>
    <td>
    	<input name="mail" type="text" id="mail" size="45" 
        	   value="<?php echo htmlspecialchars($_POST['mail'],ENT_QUOTES,'UTF-8'); ?>" />
    	<?php if($error['mail']=='blank'): ?>
    		<p class="error">*メールアドレスが未入力です</p>
    	<?php endif; ?>
    </td>
  </tr>
  <tr>
    <th scope="row"><span>*</span>メールアドレスの確認</th>
    <td>
    	<input name="mail_re" type="text" id="mail_re" size="45" 
        	   value="<?php echo htmlspecialchars($_POST['mail_re'],ENT_QUOTES,'UTF-8'); ?>" />
    	<?php if($error['mail_re']=='blank'): ?>
    		<p class="error">*メールアドレス(確認)が未入力です</p>
    	<?php endif; ?>
        <?php if($error['mail_re'] == 'match'): ?>
           	<p class="error">*メールアドレスとメールアドレス(確認)が一致していません</p>
        <?php endif; ?>
    </td>
  </tr>
  <tr>
    <th scope="row">備考</th>
    <td>
    <textarea name="comment" id="comment" cols="45" rows="5"><?php echo htmlspecialchars($_POST['comment'],ENT_QUOTES,'UTF-8'); ?></textarea>
    </td>
  </tr>
</table>
  <div class="btnArea">
  	<p><a href="cartinfo.php<?php echo SID; ?>"><img src="images/modoru2.gif" width="130" height="30" alt="戻る" /></a></p>
    <p><input type="image" name="test" id="test" src="images/hassou_shiharai.gif" />
       <input type="hidden" name="btn03" value="1" />
    </p>
  </div>
  </form>
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
