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
//商品情報を保持したままで、１時間以内操作しないと商品情報を削除
if(isset($_SESSION[item]) && $_SESSION['time_g']+10 > time()){
	$_SESSION['time_g'] = time();
}else{
	unset($_SESSION['item']);
    unset($_SESSION['item_sum']);
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
<script type="text/javascript">
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
</script>
<!-- InstanceEndEditable -->
<!-- InstanceParam name="id" type="text" value="index_page" -->
</head>

<body id="index_page" onload="MM_preloadImages('images/cart_b_ov.gif','images/new_uesr_b_ov.gif','images/c_image1ov.jpg','images/c_image2ov.jpg','images/c_image3ov.jpg','images/c_image4ov.jpg',,'images/image_1ov.jpg','images/image_2ov.jpg','images/image_3ov.jpg')">
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
    	<li><a href="inquiry.php" id="inquiry">お問合わせ</a></li>
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
                  	<a href="newuser.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image20','','images/new_uesr_b_ov.gif',1)">
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
<!--MainVisual開始-->
<h2>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="610" height="250" id="FlashID" title="beanslifecoffee">
<param name="movie" value="mv.swf" />
<param name="quality" value="high" />
<param name="wmode" value="opaque" />
<param name="swfversion" value="6.0.65.0" />
<!-- このパラメータータグにより、Flash Player 6.0 または 6.5 以降を使用して、Flash Player の最新バージョンをダウンロードするようメッセージが表示されます。
	 ユーザーにメッセージを表示させないようにする場合はパラメータータグを削除します。 -->
<param name="expressinstall" value="Scripts/expressInstall.swf" />
<!-- 次のオブジェクトタグは IE 以外のブラウザーで使用するためのものです。IE では IECC を使用して非表示にします。 -->
<!--[if !IE]>-->
<object type="application/x-shockwave-flash" data="mv.swf" width="610" height="250">
<!--<![endif]-->
<param name="quality" value="high" />
<param name="wmode" value="opaque" />
<param name="swfversion" value="6.0.65.0" />
<param name="expressinstall" value="Scripts/expressInstall.swf" />
<!-- ブラウザーには、Flash Player 6.0 以前のバージョンを使用して次の代替コンテンツが表示されます。 -->
<h6>このページのコンテンツには、Adobe Flash Player の最新バージョンが必要です。</h6>
<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Adobe Flash Player を取得" width="112" height="33" /></a></p>
<!--[if !IE]>-->
</object>
<!--<![endif]-->
</object>
</h2>
<!--MainVisual終了-->
<!--MainContents開始-->
<h3 class="midashi"><img src="images/midashi_r.gif" width="619" height="49" alt="お勧め商品Recommendcoffeebeans" /></h3>
<div id="m_contents">
<div class="main_list">
<div class="list_1">
<h4 class="list_image"><a href="goods_b.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image24','','images/c_image1ov.jpg',1)"> <img src="images/c_image1.jpg" alt="モカブレンド" name="Image24" width="100" height="100" border="0" id="Image24" /></a></h4>
<div class="text">
<p class="title"><span class="list_color">商品名：</span><a href="goods_b.php">モカブレンド</a></p>
<p class="nedan">￥560(100g)</p>
<p class="comment"><span class="list_color">comment...</span><br />
&nbsp;甘い香りと酸味のある味で<br />
&nbsp;人気です。 </p>
</div>
</div>
<div class="list_2">
<h4 class="list_image"><a href="goods_2.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image25','','images/c_image2ov.jpg',1)"> <img src="images/c_image2.jpg" alt="オリジナルブレンド" name="Image25" width="100" height="100" border="0" id="Image25" /></a></h4>
<div class="text">
<p class="title"><span class="list_color">商品名：</span><a href="goods_2.php">ハワイコナ</a></p>
<p class="nedan">￥600(100g) </p>
<p class="comment"><span class="list_color">comment...</span><br />
&nbsp;独特の酸味と甘い香りが<br />
&nbsp;非常に癖になります。</p>
</div>
</div>
</div>
<div class="main_list">
<div class="list_1">
<h4 class="list_image"><a href="goods.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image26','','images/c_image3ov.jpg',1)"> <img src="images/c_image3.jpg" alt="ブルーマウンテン" name="Image26" width="100" height="100" border="0" id="Image26" /></a></h4>
<div class="text">
<p class="title"><span class="list_color">商品名：</span><a href="goods.php">ブルーマウンテン</a></p>
<p class="nedan">￥1,800(100g) </p>
<p class="comment"><span class="list_color">comment...</span><br />
&nbsp;香り・味・コクのバランスが<br />
&nbsp;非常に良い逸品です。 </p>
</div>
</div>
<div class="list_2">
<h4 class="list_image"><a href="goods_2.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image27','','images/c_image4ov.jpg',1)"> <img src="images/c_image4.jpg" alt="マンデリン" name="Image27" width="100" height="100" border="0" id="Image27" /></a></h4>
<div class="text">
<p class="title"><span class="list_color">商品名：</span><a href="goods_2.php">マンデリン</a></p>
<p class="nedan">￥600(100g)</p>
<p class="comment"><span class="list_color">comment...</span><br />
&nbsp;酸味が少なく、ほろ苦い<br />
&nbsp;大人の味です。<br />
</p>
</div>
</div>
</div>
</div>
<!--MainContents終了-->
<!--SubContents開始-->
<h3 class="midashi"><img src="images/midashi_i.gif" width="619" height="49" alt="新着・更新情報Information" /></h3>
<div id="s_c">
<dl>
<dt>2012/05/01</dt>
<dd>サイトをリニューアルしました。</dd>
<dt>2012/04/28</dt>
<dd><a href="goods_b.php">コーヒー豆の商品を追加しました。</a></dd>
<dt>2012/02/01</dt>
<dd>メンバーログイン機能を追加しました。</dd>
<dt>2012/01/05</dt>
<dd>HP開設に伴い、コーヒー豆のネット販売をはじめました。 </dd>
</dl>
</div>
<!--SubContents終了-->
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
