<?php 
session_start();

ini_set( 'display_errors', 1 );

require (__DIR__ ."/credentials.php");
require (__DIR__ ."/util.php");
require (__DIR__ ."/twitteroauth-master/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;

//header("Content-type: text/html; charset=utf-8");
//print_r($_SESSION);
if(!isset($_SESSION['summary_access_token'])){
	echo "<p>あなたの最近のツイート、120文字に要約します。（※要ログイン）</p>";
	echo "<a href=\"login.php\">Twitterでログイン</a>";
	echo "<p><a href=\"https://www.goo.ne.jp/\">";
	echo "<img src=\"https://u.xgoo.jp/img/sgoo.png\" alt=\"supported by goo\" title=\"supported by goo\">";
	echo "</a></p>";
}else{
	
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['summary_access_token']['oauth_token'], $_SESSION['summary_access_token']['oauth_token_secret']);
	$user_timelines = $connection->get("statuses/user_timeline", array("user_id" => $_SESSION['summary_id'],"count" => 30,"exclude_replies" => true,"include_rts" => false));
	//session_write_close();
	foreach ($user_timelines as $user_timeline) {
		$text .= $user_timeline->text;
	}
	//echo "<br/>";
	//$text = preg_replace('/[a-zA-Z -\/:-@\[-`\{-\~]/','',$text);
	$text = preg_replace("/(?:[\x{3041}-\x{3096}\x{30A1}-\x{30FA}\x{31F0}-\x{31FF}\x{FF66}-\x{FF6F}\x{FF71}-\x{FF9D}][\x{3099}\x{309A}]?|[\x{30FC}a-zA-Z0-9\x{FF10}-\x{FF19}\x{3001}\x{3002}\n\r]|[\p{Han}][\x{E0100}-\x{E01EF}\x{FE00}-\x{FE02}]?)+$/u","",$text);
	$text = preg_replace("/#/","",$text);
	//var_dump($text);
	$summary = getSummary($text);
	//var_dump($obj);
	echo "<p>あなたのツイート要約しました。</p>";
	echo "<p><b>".$summary."</b></p>";
	//echo "<form name =\"form\" method=\"POST\" action=\"".$_SERVER['PHP_SELF']."\">";
	$tweet = $summary." #ツイート要約したろか";
	echo "<textarea rows=\"3\" cols=\"100\" id=\"textarea\">";
	echo $tweet;
	echo "</textarea>";
	echo "<p><input type=\"button\" id=\"submit\" value=\"ツイッターに投稿\"></p>";
	echo "<p><a href=\"#\" id=\"logout\">ログアウト</a></p>";
}

function getSummary ($text) {
	// gooAPI
	$url = "https://labs.goo.ne.jp/api/shortsum";
	// ポストするデータ
	$post_data = [
		"app_id" => GOO_API,
    	"review_list" => array($text),
	];
  $headers = [
        'Content-Type: application/json; charset=UTF-8',
    ];
	// セッションを初期化
	$conn = curl_init();
	// サーバ証明書の検証は行わない。
  curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, false);
	//POSTで送信
	curl_setopt($conn, CURLOPT_CUSTOMREQUEST, 'POST');
	// curl_execの実行結果を文字列として取得できるように設定
  curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
	//url指定
  curl_setopt($conn, CURLOPT_URL,  $url);
    //ヘッダー追加オプション
  curl_setopt($conn, CURLOPT_HTTPHEADER, $headers);
    //ポストするデータの追加
  curl_setopt($conn, CURLOPT_POSTFIELDS, json_encode($post_data));
	// 実行
	$res = curl_exec($conn);
	if (!curl_errno($conn)) {
  switch ($http_code = curl_getinfo($conn, CURLINFO_HTTP_CODE)) {
    case 200:  # OK
      break;
    default:
      echo 'Unexpected HTTP code: ', $http_code, "</br>";
			echo "もう一度、最初からやるとうまくいくかもしれません・・・。</br>";
  }
}
	// close
	curl_close($conn);

	$res = mb_convert_encoding($res,'UTF-8');
	$obj = json_decode($res, false);
	$summary = $obj->summary;
 
	return $summary;
}
