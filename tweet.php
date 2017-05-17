<?php
session_start();

ini_set( 'display_errors', 1 );

require_once (__DIR__ ."/credentials.php");
require_once (__DIR__ ."/util.php");
require (__DIR__ ."/twitteroauth-master/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['summary_access_token']['oauth_token'], $_SESSION['summary_access_token']['oauth_token_secret']);

$tweet = es($_POST['data']);
//var_dump ($tweet);
$param = ["status" => $tweet];
$connection->post("statuses/update",  $param);
echo "<p>ツイートしました。</p>";
echo "<a href='index.php'>はじめのページへ</a>";

//セッション変数を全て解除
$_SESSION = array();
 
//セッションクッキーの削除
if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
}
 
//セッションを破棄する
session_destroy();
 