<?php
session_start();
//設定を読み込む

require_once (__DIR__ ."/credentials.php");
//ライブラリを読み込む
require (__DIR__ ."/twitteroauth-master/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;

//oauth_tokenとoauth_verifierを取得
if($_SESSION['oauth_token'] == $_GET['oauth_token'] and $_GET['oauth_verifier']){
	
	//Twitterからアクセストークンを取得する
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
	$access_token = $connection->oauth('oauth/access_token', array('oauth_verifier' => $_GET['oauth_verifier'], 'oauth_token'=> $_GET['oauth_token']));

	//取得したアクセストークンでユーザ情報を取得
	$user_connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
	$user_info = $user_connection->get('account/verify_credentials');	

	// ユーザ情報の展開
	//var_dump($user_info);
	
	//適当にユーザ情報を取得
	$id = $user_info->id;
	//$name = $user_info->name;
	//$screen_name = $user_info->screen_name;
	//$profile_image_url_https = $user_info->profile_image_url_https;
	//$text = $user_info->status->text;
	
	//各値をセッションに入れる
	$_SESSION['summary_access_token'] = $access_token;
	$_SESSION['id'] = $id;
	//$_SESSION['name'] = $name;
	//$_SESSION['screen_name'] = $screen_name;
	//$_SESSION['text'] = $text;
	//$_SESSION['profile_image_url_https'] = $profile_image_url_https;
	
	header('Location: index.php');
	exit();
}else{
	header('Location: index.php');
	exit();
}
