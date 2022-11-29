<?php
//session開始
session_start();
//errorメッセージを入れるための配列を用意
$error = array();

//----dbとの接続処理〜usertableのデータ取得まで----
try {
    //dbとの接続
    $pdo = new PDO('mysql:host=localhost;dbname=userData;charset=utf8', 'root', ''[
        // カラム名をキーとする連想配列で取得する
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // SQL実行失敗時には例外をスローしてくれる
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
        PDO::ATTR_EMULATE_PREPARES =>false
    ]);
} catch (PDOException $e) {
    print "エラー!: " . $e->getMessage() . "<br/>";
    die();
}

//----各バリデーション----
//POSTデータが空じゃない場合
if(!empty($_POST)) {
//emailが入力されているか
    if($_POST["email"] == "" || filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
        $error[] = "メールアドレスが入力されていないか、正しい形式になっていません";
    }

    //住所が入力されているかどうか
    if($_POST["address"] == ""){
        $error[] = "住所を入力してください";
    }

    //usernameが入力されているかどうか.30文字以内になっているかどうか
    if($_POST["username"] == ""){
        $error[] = "ユーザーネームを入力してください";
    } elseif(mb_strlen($_POST["username"]) > 30) {
        $error[] = "ユーザーネームは30字以内で設定してください";
    }

    //passwordが半角英数字を含む8文字以上で設定されているか
    if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]{8,}$/',$_POST["password"])){
        $error[] = "パスワードは大文字、小文字、数字をそれぞれ含む8文字以上で設定してください";
    }


    //username,emailにそれぞれ重複がないかを調べる
    if(!isset($error)){
        $query = $dbh->prepare('SELECT * FROM user WHERE email = :email');
        $query->execute(array(':email' => $email));

        $result = $query->fetch(PDO::FETCH_ASSOC);
        if(count($result) > 0){
            $error[] = "このメールアドレスは既に使用されています";
        }
    }

    if(!isset($error)){
        $query = $dbh->prepare('SELECT * FROM user WHERE username = :username');
        $query->execute(array(':username' => $username));

        $result = $query->fetch(PDO::FETCH_ASSOC);
        if(count($result) > 0){
            $error[] = "このユーザーネームは既に使用されています";
        }
    }
    


    //error配列の中身の有無によって確認画面に進むか決める
    if(count($error) === 0) {
        //新規登録する処理
        //POSTメソッドで渡されたデータをsession変数に入れる
        $_SESSION[] = $_POST
    } else {
        //エラー文の表示
        
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <div>
            <h2>Sign Up</h2>
        </div>
        <form action="register_output.php" method="POST">
            <label for="">
                input your e-mail
                <input type="text" name="email">
            </label>
            <label for="">
                input your password
                <input type="password" name="password">
            </label>
            <label for="">
                input your username
                <input type="text" name="username">
            </label>
            <label for="">
                input your address
                <input type="text" name="address">
            </label>
            <input type="submit" value="登録確認画面へ">
        </form>
    </div>
</body>
</html>