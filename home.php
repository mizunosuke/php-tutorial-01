<?php
//----csv関連の処理----
//あとで！つける
if(!empty($_GET["input"])){

    //dbとの接続
    $pdo = null;
    $stmt = null;

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=fish_sns02;charset=utf8', 'root', '',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) {
        print "エラー!: " . $e->getMessage() . "<br/>";
        die();
    }

    //SQLの発行/データの取得
    $sql = "SELECT * FROM postData";
    $stmt = $pdo->prepare($sql);
    //sql文を実行
    $stmt->execute();
    
    //fileを保存した時間をファイル名に含む
    $time = time();
    $filename = 'data/postData'. $time.'.csv';

    //touch関数でfileが存在するか確認・なければ生成(touch関数はファイルの最終更新日をセットする関数);
    if(!touch($filename)) {
        echo 'すでにファイルが存在します';
        exit;
    }else{
        //fileを書き込み専用でopen
        if($fp = fopen($filename,'w')) {
            //stmt->fecthでdbの全データを取得
            while($rec = $stmt->fetch(PDO::FETCH_ASSOC)){
                //生成したファイルにcsv形式でデータを追加(文字コードをshift-jisに変換)
                $rec = mb_convert_encoding($rec, "SJIS", "UTF-8");
                fputcsv($fp, $rec);
            }
            if(!fclose($fp)) {
                echo 'ファイルを閉じるのに失敗しました';
                exit;
            }
        }else{
            echo 'ファイルの書き込みに失敗しました';
            exit;
        }
    }
    //dbを閉じる
    $pdo = null;
}


//----データベースから全データを配列として受け取る
$pdo = null;
$stmt = null;

try {
    //dbとの接続
    $options = array(
        // SQL実行失敗時には例外をスローしてくれる
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // カラム名をキーとする連想配列で取得する
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
        // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,);

    $pdo = new PDO('mysql:host=localhost;dbname=fish_sns02;charset=utf8', 'root', '',$options);

    //クエリ文の作成
    $sql = "SELECT * FROM postData";
    //stmt変数にクエリ結果を代入
    $stmt = $pdo -> query($sql);
    //$resultに取得したデータを代入
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // if(!empty($result)){
    //     echo '<pre>';
    //     var_dump($result);
    //     echo '</pre>';
    // }else{
    //     echo '結果なし';
    // }

    //dbを閉じる
    $pdo = null;
} catch(Exception $e) {
    error_log('エラーが発生しました：' .$e->getMessage());
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <header>
        <div class="logo">logo</div>
        <div class="title">
            <h1>釣果投稿SNS</h1>
        </div>
        <!-- できればサイドバーメニューの作成 -->
    </header>
    <div class="nav">
            <a href="register.php">新規登録</a>
            <a href="login.php">ログイン</a>
            <a href="mypage.php">mypage</a>
            <a href="postpage.php">釣果を投稿する</a>
    </div>

    <div class="selected_post">
        <div class="favo_title"><h2>人気投稿ランキング</h2></div>
        <div class="posted_list">
            <!-- この中にいいねの多い投稿をDBから引っ張り順に表示する -->
            <?php foreach($result as $data) :?>
                <div class="list">
                    <img src=<?= $data["filename"]?> alt="">
                    <p>魚種名 : <?=$data["kind"]?></p>
                    <p>サイズ : <?=$data["size"]?></p>
                    <p>釣行場所 : <?=$data["location"]?></p>
                    <p>ルアー : <?=$data["lure"]?></p>
                    <p>天気 : <?=$data["weather"]?></p>
                    <p>投稿日時 : <?=$data["date"]?></p>
               </div>
            <?php endforeach ;?>    
        </div>
    </div>

    <div class="favorite_post">
        <div><h2>自分のフォローした地域の投稿</h2></div>
        <div>
            <!-- この中に自分のフォローした地域のタグがついた投稿を入れる -->
        </div>
    </div>

    <footer>
        <form action="home.php" method="GET">
            <input type="submit" value="CSVをダウンロードする" name="input">
        </form>
        <p>@copyright 2022</p>
    </footer>

</body>
</html>