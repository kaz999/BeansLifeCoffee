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

//ショッピング情報
//商品情報を保持したままで、１時間以内操作しないと商品情報を削除
if(isset($_SESSION[item]) && $_SESSION['time_g']+10 > time()){
	$_SESSION['time_g'] = time();
}else{
	unset($_SESSION['item']);
    unset($_SESSION['item_sum']);
}
//お届け先・支払方法
if(!empty($_POST)){
	if($_POST['deli']==''){
		$error_d['deli']='blank';
	}
    if($_POST['pay']==''){
    	$error_d['pay']='blank';
    }
    if($_POST['deli']=='購入者の住所'){
    	if(empty($error_d)){
    		$_SESSION['join_c']=$_POST;
    		header('Location: https://noor-connect-ki.ssl-lolipop.jp/Connect/cartinfo_4.php'.SID);
    	    exit();
    	}
    }elseif($_POST['deli']=='別の住所'){
		//ラジオボタン「別の住所へ」を押し、必須事項に記述がない場合、エラー文を生成
    	if($_POST['sei']==''){
    		$error_a['sei']='blank';
    	}
    	if($_POST['mei']==''){
    	  	$error_a['mei']='blank';
    	}
    	if($_POST['seikana']==''){
    		$error_a['seikana']='blank';
    	}
   		if($_POST['meikana']==''){
   	     	$error_a['meikana']='blank';
   	    }
        if($_POST['zipcode1']==''){
        	$error_a['zipcode1']='blank';
        }
        if($_POST['zipcode2']==''){
        	$error_a['zipcode2']='blank';
        }
        if($_POST['todohuken']=='お選びください'){
        	$error_a['todohuken']='blank';
        }
        if($_POST['shikugun']==''){
        	$error_a['shikugun']='blank';
        }
        if($_POST['banchi']==''){
        	$error_a['banchi']='blank';
        }
        if($_POST['tel']==''){
    		$error_a['tel']='blank';
        }
    	if(empty($error_a)){
        	$_SESSION['join_c']=$_POST;
        	$_SESSION['join_c2']=$_POST;
        	header('Location: https://noor-connect-ki.ssl-lolipop.jp/Connect/cartinfo_4.php'.SID);
            exit();
        }
	}
}

//cartinfo_4.phpから戻ってきたとき、記述した内容を再表示
if(3==$_GET['input']){

	//お届先・お支払方法の情報
	$deli=$_SESSION['join_c']['deli'];
    $pay=$_SESSION['join_c']['pay'];
    
    //別住所の情報
    $_POST['sei']=$_SESSION['join_c2']['sei'];
    $_POST['mei']=$_SESSION['join_c2']['mei'];
    $_POST['seikana']=$_SESSION['join_c2']['seikana'];
    $_POST['meikana']=$_SESSION['join_c2']['meikana'];
    $_POST['zipcode1']=$_SESSION['join_c2']['zipcode1'];
    $_POST['zipcode2']=$_SESSION['join_c2']['zipcode2'];
    $_POST['shikugun']=$_SESSION['join_c2']['shikugun'];
    $_POST['banchi']=$_SESSION['join_c2']['banchi'];
    $_POST['tatemono']=$_SESSION['join_c2']['tatemono'];
    $_POST['tel']=$_SESSION['join_c2']['tel'];
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
					<li>商品&nbsp;<?php echo $item_count; ?>点</li>
					<li><span class="sli">合計&nbsp;<?php echo $total; ?>円</span></li>
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
    <h3>発送・支払方法</h3>
    <h4><img src="images/cartinImage03.gif" width="550" height="35" /></h4>
    <form id="form1" name="form1" method="post" action="">
	<div class="sc">
        <?php if($error_d['deli']=='blank'): ?>
    		<p class="error">*お届け先を選んでください</p>
    	<?php endif; ?>
  		<p>お届先選択</p>
  		<p><label><input type="radio" name="deli" value="購入者の住所" id="deli"
        		  <?php if($deli=='購入者の住所'){echo"checked";}?>/>&nbsp;購入者の住所へ送る</label></p>
  		<p><label><input type="radio" name="deli" value="別の住所" id="deli"
        		  <?php if($deli=='別の住所'){echo"checked";}?>/>&nbsp;別の住所へ</label></p>
	</div>
	<table>
        <tr>
        	<th scope="row"><span>*</span>お名前</th>
        		<td>
                	姓:<input name="sei" type="text" id="sei" size="10" 
                    		  value="<?php echo htmlspecialchars($_POST['sei'],ENT_QUOTES,'UTF-8'); ?>" />
            		名:<input name="mei" type="text" id="mei" size="10" 
                    		  value="<?php echo htmlspecialchars($_POST['mei'],ENT_QUOTES,'UTF-8'); ?>" />
                    <?php if($error_a['sei']=='blank' || $error_a['mei']=='blank'): ?>
     					<p class="error">*姓名が未入力です</p>
     				<?php endif; ?>
        		</td>
       	</tr>
        <tr>
          	<th scope="row"><span>*</span>お名前(カナ)</th>
          		<td>
                	セイ:<input name="seikana" type="text" id="seikana" size="10" 
                    		    value="<?php echo htmlspecialchars($_POST['seikana'],ENT_QUOTES,'UTF-8'); ?>" />
                	メイ:<input name="meikana" type="text" id="meikana" size="10" 
                    			value="<?php echo htmlspecialchars($_POST['meikana'],ENT_QUOTES,'UTF-8'); ?>" />
                    <?php if($error_a['seikana']=='blank' || $error_a['meikana']=='blank'): ?>
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
                    <?php if($error_a['zipcode1']=='blank' || $error_a['zipcode2']=='blank'): ?>
     					<p class="error">*郵便番号が未入力です</p>
     				<?php endif; ?>
                </td>
        </tr>
        <tr>
          	<th scope="row"><span>*</span>都道府県</th>
          		<td><select name="todohuken" id="todohuken">
            		<option value="お選びください">お選びください</option>
            		<option value="北海道">北海道</option>
            		<option value="青森県">青森県</option>
            		<option value="岩手県">岩手県</option>
            		<option value="宮城県">宮城県</option>
            		<option value="秋田県">秋田県</option>
            		<option value="山形県">山形県</option>
            		<option value="福島県">福島県</option>
            		<option value="茨城県">茨城県</option>
            		<option value="栃木県">栃木県</option>
            		<option value="群馬県">群馬県</option>
            		<option value="埼玉県">埼玉県</option>
            		<option value="千葉県">千葉県</option>
            		<option value="東京都">東京都</option>
            		<option value="神奈川県">神奈川県</option>
            		<option value="新潟県">新潟県</option>
            		<option value="富山県">富山県</option>
            		<option value="石川県">石川県</option>
            		<option value="福井県">福井県</option>
            		<option value="山梨県">山梨県</option>
            		<option value="長野県">長野県</option>
            		<option value="岐阜県">岐阜県</option>
            		<option value="静岡県">静岡県</option>
            		<option value="愛知県">愛知県</option>
            		<option value="三重県">三重県</option>
            		<option value="滋賀県">滋賀県</option>
            		<option value="京都府">京都府</option>
            		<option value="大阪府">大阪府</option>
            		<option value="兵庫県">兵庫県</option>
            		<option value="奈良県">奈良県</option>
            		<option value="和歌山県">和歌山県</option>
            		<option value="鳥取県">鳥取県</option>
            		<option value="島根県">島根県</option>
            		<option value="岡山県">岡山県</option>
            		<option value="広島県">広島県</option>
            		<option value="山口県">山口県</option>
            		<option value="徳島県">徳島県</option>
            		<option value="香川県">香川県</option>
            		<option value="愛媛県">愛媛県</option>
            		<option value="高知県">高知県</option>
            		<option value="福岡県">福岡県</option>
            		<option value="佐賀県">佐賀県</option>
            		<option value="長崎県">長崎県</option>
            		<option value="熊本県">熊本県</option>
            		<option value="大分県">大分県</option>
            		<option value="宮崎県">宮崎県</option>
            		<option value="鹿児島県">鹿児島県</option>
            		<option value="沖縄県">沖縄県</option>
                </select>
                    <?php if($error_a['todohuken']=='blank'): ?>
     					<p class="error">*都道府県をお選びください</p>
     				<?php endif; ?>
                    </td>
          </tr>
          <tr>
          		<th scope="row"><span>*</span>市区郡</th>
          		<td><input name="shikugun" type="text" id="ad1" size="45" 
                		   value="<?php echo htmlspecialchars($_POST['shikugun'],ENT_QUOTES,'UTF-8'); ?>" />
                    <?php if($error_a['shikugun']=='blank'): ?>
     					<p class="error">*市区郡が未入力です</p>
     				<?php endif; ?>
                </td>
          </tr>
          <tr>
          		<th scope="row"><span>*</span>番地</th>
          		<td><input name="banchi" type="text" id="ad2" size="45" 
                		   value="<?php echo htmlspecialchars($_POST['banchi'],ENT_QUOTES,'UTF-8'); ?>" />
                    <?php if($error_a['banchi']=='blank'): ?>
     					<p class="error">*番地が未入力です</p>
     				<?php endif; ?>
                </td>
          </tr>
          <tr>
          		<th scope="row">建物名</th>
          		<td><input name="tatemono" type="text" id="ad3" size="45" 
                		   value="<?php echo htmlspecialchars($_POST['tatemono'],ENT_QUOTES,'UTF-8'); ?>" /></td>
          </tr>
          <tr>
          		<th scope="row"><span>*</span>電話番号</th>
          		<td><input name="tel" type="text" id="tel" size="30" 
                		   value="<?php echo htmlspecialchars($_POST['tel'],ENT_QUOTES,'UTF-8'); ?>" />
                    <?php if($error_a['tel']=='blank'): ?>
     					<p class="error">*電話番号が未入力です</p>
     				<?php endif; ?>
                </td>
          </tr>
    </table>
		<div class="sc">
        	<?php if($error_d['pay']=='blank'): ?>
    			<p class="error">*お支払方法を選んでください</p>
    		<?php endif; ?>
			<p>お支払方法</p>
        	<p><label><input type="radio" name="pay" value="代金引換" id="pay" 
            	      <?php if($pay=='代金引換'){echo"checked";}?>/>&nbsp;代金引換</label></p>
        	<p><label><input type="radio" name="pay" value="銀行振込" id="pay" 
            		  <?php if($pay=='銀行振込'){echo"checked";}?>/>&nbsp;銀行振込</label></p>
        </div>
        
        <div class="btnArea">
		  <p><a href="cartinfo_2.php?input=2<?php echo SID; ?>"><img src="images/modoru2.gif" width="130" height="30" alt="戻る" /></a></p>
       	  <p><input name="test" type="image" id="test" src="images/naiyoukakunin.gif" alt="内容確認へ" />
          	 <input type="hidden" name="btn04" value="1" /></p>
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
