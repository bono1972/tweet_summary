<?php
session_start();
//設定を読み込む
ini_set( 'display_errors', 1 );

require_once (__DIR__ ."/credentials.php");
//ライブラリを読み込む
require (__DIR__ ."/twitteroauth-master/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;

//TwitterOAuthのインスタンスを生成し、Twitterからリクエストトークンを取得する
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$request_token = $connection->oauth("oauth/request_token", array("oauth_callback" => CALLBACK));

//リクエストトークンはcallback.phpでも利用するのでセッションに保存する
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

// Twitterの認証画面へリダイレクト
$url = $connection->url("oauth/authorize", array("oauth_token" => $request_token['oauth_token']));
header('Location: ' . $url);