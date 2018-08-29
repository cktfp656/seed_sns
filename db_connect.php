<?php  
// データベースに必要な文字列
$dsn = 'mysql:dbname=seed_sns;host=localhost';
$user = 'root';
$password = '';
// データベース（DB)を使える形にしている
$dbh = new PDO($dsn,$user,$password);
// 日本語の文字化けを防ぐ
$dbh->query('SET NAMES utf8');
?>