<?php 
session_start();
// セッションの中身を空の配列で上書きする
 $_SESSION =array();
// セッション情報の破棄
 session_destroy();
// ログイン後の画面に戻る

 header("Location: index.php");
 exit();

// ポイント

// ログイン後の画面（index.php)にログインチェックの気のお王を実力する
// ログアウト処理後に、ログイン後の画面に行くことでしっかりログアウトしていることが確認できる




 ?>