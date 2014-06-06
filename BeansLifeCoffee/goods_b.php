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
//ショッピングカート機能
//商品情報を保持したままで、１時間以内操作しないと商品情報を削除
if(isset($_SESSION[item]) && $_SESSION['time_g']+10 > time()){
	$_SESSION['time_g'] = time();
}else{
	unset($_SESSION['item']);
    unset($_SESSION['item_sum']);
}

	//豆の状態を選んでいない場合、エラー文を生成
	if($_POST['goods']=="ブルーマウンテンブレンド" && $_POST['bean']=="0"){
		$error['blueMB'] = 'blank';
	}elseif($_POST['goods']=="モカブレンド" && $_POST['bean']=="0"){
		$error['mokaB'] = 'blank';
	}elseif($_POST['goods']=="コロンビアブレンド" && $_POST['bean']=="0"){
    	$error['columBB'] = 'blank';
    }
    
	//豆の状態を選んで「カートに入れる」を選択した場合
    if($_POST['goods']=="ブルーマウンテンブレンド" && $_POST['bean'] != "0"){
    	$image="images/oriBlue_mini.gif";
    	$goodsName=$_POST['goods'];
    	$bean=$_POST['bean'];
        $qnt=$_POST['qnt'];
        $price=$_POST['price']*$_POST['qnt'];
        
        $item=$_SESSION['item'];
        $item[]=array($image,$goodsName,$bean,$qnt,$price);
        $_SESSION['item']=$item;
        $_SESSION['time_g']=time();
        $item_count=count($item);
        header('Location: cartinfo.php'.SID);
        exit();
    }
    if($_POST['goods']=="モカブレンド" && $_POST['bean'] != "0"){
    	$image="images/oriMoka_mini.gif";
    	$goodsName=$_POST['goods'];
    	$bean=$_POST['bean'];
        $qnt=$_POST['qnt'];
        $price=$_POST['price']*$_POST['qnt'];
        
        $item=$_SESSION['item'];
        $item[]=array($image,$goodsName,$bean,$qnt,$price);
        $_SESSION['item']=$item;
        $_SESSION['time_g']=time();
        $item_count=count($item);
        header('Location: cartinfo.php'.SID);
        exit();
    }
    if($_POST['goods']=="コロンビアブレンド" && $_POST['bean'] != "0"){
    	$image="images/oriColum_mini.gif";
    	$goodsName=$_POST['goods'];
    	$bean=$_POST['bean'];
        $qnt=$_POST['qnt'];
        $price=$_POST['price']*$_POST['qnt'];
        
        $item=$_SESSION['item'];
        $item[]=array($image,$goodsName,$bean,$qnt,$price);
        $_SESSION['item']=$item;
        $_SESSION['time_g']=time();
        $item_count=count($item);
        header('Location: cartinfo.php'.SID);
        exit();
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
<!-- InstanceEndEditable -->
<link href="css/common.css" rel="stylesheet" type="text/css" />
<!--[if IE 6 ]>
<link rel="stylesheet" type="text/css" href="css/common_ie6.css" media="screen">
<![endif]-->
<script type="text/javascript" src="js/swfobject_modified.js"></script>
<script type="text/javascript" src="js/rollover.js"></script>
<!-- InstanceBeginEditable name="head" -->
<link href="css/goods_b.css" rel="stylesheet" type="text/css" media="screen" />
<link href="css/goods_b_tab.css" rel="stylesheet" type="text/css" media="screen" />
<!-- InstanceEndEditable -->
<!-- InstanceParam name="id" type="text" value="goods_page" -->
</head>

<body id="goods_page" onload="MM_preloadImages('images/cart_b_ov.gif','images/new_uesr_b_ov.gif','images/c_image1ov.jpg','images/c_image2ov.jpg','images/c_image3ov.jpg','images/c_image4ov.jpg',,'images/image_1ov.jpg','images/image_2ov.jpg','images/image_3ov.jpg')">
<!--Container開始-->
<div id="container">
<!--Header開始-->
<div id="header">
<h1 id="logo"><a href="index.php"><img src="images/logo.gif" width="154" height="90" alt="beanslifecoffee" /></a></h1>
<div class="header_t">
	<p>3000円以上のお買い上げで送料無料!</p>
	<ul>
		<li class="subnavi_1"><a href="guide.php">配達・送料</a></li>
		<li><a href="sitemap.php">サイトマップ</a></li>
		<li><a href="recruit.php">採用情報</a></li>
	</ul>
</div>
<!--Header終了-->
</div>
<!--GlobalNavi開始-->
<div id="g_navi">
	<ul>
		<li><a href="index.php" id="home">ホーム</a></li>
		<li><a href="goods.php" id="goods">商品一覧</a></li>
		<li><a href="store_d.php" id="store">店舗メニュー</a></li>
		<li><a href="howtoinfo.php" id="howtoinfo">ご購入ガイド</a></li>
    	<li><a href="https://noor-connect-ki.ssl-lolipop.jp/BeansLifeCoffee/sitework/inquiry.php" id="inquiry">お問合わせ</a></li>
    	<li><a href="access.php" id="access">アクセス</a></li>
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
        <p class="menu_c_s"><a href="store_d.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image21','','images/image_1ov.jpg',1)">
   	    <img src="images/image_1.jpg" alt="店舗メニューCoffee&amp;Softdrink" name="Image21" width="150" height="100" border="0" id="Image21" /></a></p>
       	<p class="menu_m_d"><a href="store_f.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image22','','images/image_2ov.jpg',1)">
    <img src="images/image_2.jpg" alt="店舗メニューMeal&amp;Dessert" name="Image22" width="150" height="100" border="0" id="Image22" /></a></p>
        <h3 class="s_title_mc"><img src="images/campaign_title.gif" alt="Campaign" width="157" height="27" /></h3>
        <p class="cmn_i"><a href="campaign.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image23','','images/image_3ov.jpg',1)">
            <img src="images/image_3.jpg" alt="キャンペーン" name="Image23" width="150" height="180" border="0" id="Image23" /></a></p>
	</div>
</div>
<!--SideMenu終了--><!-- InstanceBeginEditable name="contents" -->
<!--Contents開始-->
<div id="contents">
<div id="contents">
    <h3><img src="images/goodsImage.gif" width="610" height="200" alt="商品一覧Goodslist" /></h3>
    <div id="gNavi">
	  <ul>
    		<li><a href="goods.php" id="gs">ストレートコーヒー</a></li>
   			<li><a href="goods_b.php" id="gb">ブレンドコーヒー</a></li>
      </ul>
    </div>
	<div id="gBack">
        <div class="gWpr">
          <p class="gImage"><img src="images/oriBlue.gif" width="156" height="195" alt="ブルーマウンテンブレンド" /></p>
          <div class="tText">
            <p class="gTitle"><span>new</span>ブルーマウンテンブレンド</p>
            <p class="gText">
            	ブルーマウンテン本来の味と<br />
            	香りをそのままに、癖のない<br />
            	仕上がりになっています。<br />
            	値段もお手頃価格です。 
            </p>
          </div>
          <div class="pQuant">
            <p class="gPrice">100g&nbsp;\860&nbsp;(税込)</p>
            <form action="" method="post" name="select_1" id="select_1">
              <dl>
                <dt class="Bean">豆の状態</dt>
                <dd>
                  <select name="bean" id="bean">
                    <option value="0">選択してください</option>
                    <option value="豆のまま">豆のまま</option>
                    <option value="粗挽き">粗挽き</option>
                    <option value="中挽き">中挽き</option>
                    <option value="細挽き">細挽き</option>
                  </select>
                </dd>
              </dl>
              <?php if($error['blueMB'] == 'blank'): ?>
               	<p class="be">*豆の種類を選んでください</p>
              <?php endif; ?>
              <dl>
                <dt class="Quant">数量</dt>
                <dd>
                  <select name="qnt" id="qnt">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                  </select>
                </dd>
              </dl>
              <p class="btn">
                <input name="cartIn" type="image" id="cartIn" src="images/cartImage2.gif" alt="カートに入れる" />
                <input name="goods" type="hidden" id="goods" value="ブルーマウンテンブレンド" />
              	<input name="price" type="hidden" id="price" value="860" />
                <input name="btn01" type="hidden" id="btn01" value="1" />
              </p>
            </form>
          </div>
      </div>
        <div class="gWpr">
          <p class="gImage"><img src="images/oriMoka.gif" width="156" height="195" alt="モカブレンド" /></p>
          <div class="tText">
            <p class="gTitle">モカブレンド</p>
            <p class="gText">モカ本来の酸味よりマイルドに仕上がっているので、酸味の強いコーヒーが苦手な方でも違和感なくいただけます。</p>
          </div>
          <div class="pQuant">
            <p class="gPrice">100g&nbsp;\530&nbsp;(税込)</p>
            <form action="" method="post" name="select_1" id="select_1">
              <dl>
                <dt class="Bean">豆の状態</dt>
                <dd>
                  <select name="bean" id="bean">
                    <option value="0">選択してください</option>
                    <option value="豆のまま">豆のまま</option>
                    <option value="粗挽き">粗挽き</option>
                    <option value="中挽き">中挽き</option>
                    <option value="細挽き">細挽き</option>
                  </select>
                </dd>
              </dl>
              <?php if($error['mokaB'] == 'blank'): ?>
               	<p class="be">*豆の種類を選んでください</p>
              <?php endif; ?>
              <dl>
                <dt class="Quant">数量</dt>
                <dd>
                  <select name="qnt" id="qnt">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                  </select>
                </dd>
              </dl>
              <p class="btn">
              	<input name="cartIn" type="image" id="cartIn" src="images/cartImage2.gif" alt="カートに入れる" />
              	<input name="goods" type="hidden" id="goods" value="モカブレンド" />
              	<input name="price" type="hidden" id="price" value="530" />
                <input name="btn01" type="hidden" id="btn01" value="1" />
              </p>
            </form>
          </div>
      </div>
        <div class="gWpr">
          <p class="gImage"><img src="images/oriColum.gif" width="156" height="195" alt="コロンビアブレンド" /></p>
          <div class="tText">
            <p class="gTitle">コロンビアブレンド</p>
            <p class="gText">
            	酸味と苦味のバランスを重視し<br />
            	誰にでも親しめるポピュラーな<br />
            	コーヒーに仕上がっています。
            </p>
          </div>
          <div class="pQuant">
            <p class="gPrice">100g&nbsp;\550&nbsp;(税込)</p>
            <form action="" method="post" name="select_1" id="select_1">
              <dl>
                <dt class="Bean">豆の状態</dt>
                <dd>
                  <select name="bean" id="bean">
                    <option value="0">選択してください</option>
                    <option value="豆のまま">豆のまま</option>
                    <option value="粗挽き">粗挽き</option>
                    <option value="中挽き">中挽き</option>
                    <option value="細挽き">細挽き</option>
                  </select>
                </dd>
              </dl>
              <?php if($error['columBB'] == 'blank'): ?>
               	<p class="be">*豆の種類を選んでください</p>
              <?php endif; ?>
              <dl>
                <dt class="Quant">数量</dt>
                <dd>
                  <select name="qnt" id="qnt">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                  </select>
                </dd>
              </dl>
              <p class="btn">
              	<input name="cartIn" type="image" id="cartIn" src="images/cartImage2.gif" alt="カートに入れる" />
              	<input name="goods" type="hidden" id="goods" value="コロンビアブレンド" />
              	<input name="price" type="hidden" id="price" value="550" />
                <input name="btn01" type="hidden" id="btn01" value="1" />
              </p>
            </form>
          </div>
      </div>
    </div>
</div>

</div>
<!--Contents終了-->
<!-- InstanceEndEditable --><!--Footer開始-->
<div id="footer">
	<ul>
		<li class="subnavi_2"><a href="p_policy.php">プライバシーポリシー</a></li>
		<li><a href="company.php">会社概要</a></li>
	</ul>
	<address>Copyright(c) Beans LIfe Coffee All Right Reserved.</address>
</div>
<!--Footer終了-->
</div>
<!--Container終了-->
</body>
<!-- InstanceEnd --></html>
