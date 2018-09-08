<!-- //ログインのチェック、ログインしているユーザーの判定、投稿一覧表示、ログアウト機能
 -->
<?php 
session_start();
require('db_connect.php');
echo'<br>';
echo'<br>';

//$_SESSION['id']がない時 == ログインしていない時
if(!isset($_SESSION['id'])){
    //ログインしていない時にlogin.phpに転移する
    header('Location: login.php');
    exit();// それ以降の処理を行わない
}

//つぶやき全件SELECT(Tweets内容だけ
    $sql = 'SELECT * FROM `members` WHERE`member_id`DESC';
// データ(レコード)全件をtweetsテーブルから、createdを元に降順で取得
// ログインしているユーザの情報を取得するsql文
        $sql = 'SELECT * FROM `members`WHERE`member_id`=?';
        $data = array($_SESSION['id']);
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
        // ログインしているユーザーの情報を$login_memberに代入
        $login_member = $stmt->fetch(PDO::FETCH_ASSOC);

        //呟きをDBに保存

        //呟きボタンが押された時
        if (!empty($_POST)){
            $sql ='INSERT INTO `comments`(`comment`, `member_id`, `reply_tweet_id`, `created`) VALUES (?,?,?,now())';
        //呟きを登録するためのInsert文を作成

        //SQL文実行
            $data = array($_POST['tweet'],$_SESSION['id'],-1);
            $stmt = $dbh->prepare($sql);
            $stmt->execute($data);

        //自分の画面へ移動する（データの再送信防止)
            header("Location: index.php");
        }
        $sql = 'SELECT`tweets`.*,`members`.`nick_name`,`members`.`picture_path` FROM`tweets` LEFT JOIN `members` ON `members`.`member_id`=`tweets`.`member_id` ORDER BY `tweets`.`created` DESC';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $tweets = array();
    while(true){
        $tweet = $stmt->fetch(PDO::FETCH_ASSOC);
        if($tweet == false){
            break;
        }
        $tweets[] = $tweet;
        }
        // var_dump($tweets);exit;

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SeedSNS</title>

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html"><span class="strong-title"><i class="fa fa-twitter-square"></i> Performing Arts</span></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="logout.php">ログアウト</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-4 content-margin-top">
                <legend>ようこそ <?php echo $login_member['nick_name']; ?> さん！</legend>

                <form method="post" action="" class="form-horizontal" role="form">

                    <!-- つぶやき -->
                    <div class="form-group">
                        <label class="col-sm-4 control-label">つぶやき</label>
                        <div class="col-sm-8">
                            <textarea name="tweet" cols="50" rows="5" class="form-control" placeholder="例：Hello World!"></textarea>
                        </div>
                    </div>
                    <ul class="paging">
                        <input type="submit" class="btn btn-info" value="つぶやく">
                            &nbsp;&nbsp;&nbsp;&nbsp;
                        <li><a href="index.html" class="btn btn-default">前</a></li>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                        <li><a href="index.html" class="btn btn-default">次</a></li>
                    </ul>
                </form>
            </div>

            <div class="col-md-8 content-margin-top">
                <?php foreach ($tweets as $tweet): ?>
                <div class="msg">
                        <img src="picture_path/<?php echo $tweet['picture_path']; ?>" width="48" height="48">
                        <p>
                            <?php echo $tweet['tweet']; ?><span class="name"> <?php echo$tweet['nick_name']; ?> </span>
                            [<a href="#">Re</a>]
                        </p>
                        <p class="day">
                            <a href="view.html">
                                <?php echo $tweet['created']; ?>
                            </a>
                            [<a href="#" style="color: #00994C;">編集</a>]
                            [<a href="#" style="color: #F33;">削除</a>]
                        </p>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/js/jquery-3.1.1.js"></script>
    <script src="assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
</body>
</html>
