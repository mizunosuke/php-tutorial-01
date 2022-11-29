<?php
ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL ); 

// //pdoインスタンスとステートメントの初期化
$pdo = null;
$stmt = null;

//-----ファイル処理関連-----
//アップロードされたファイルを初期化
$up_file  = "";
//ファイルの状態を初期化
$up_ok = false;

//アップロードされたファイルを一時的な名前で保存
$tmp_file = isset($_FILES["imagefile"]["tmp_name"]) ? $_FILES["imagefile"]["tmp_name"] : "";
//元のファイル名で保存するように元ファイル名を取得
$origin_file = isset($_FILES["imagefile"]["name"]) ? $_FILES["imagefile"]["name"] : "";


//ファイルが存在かつアップロードされたものだった場合
if($tmp_file !== "" && is_uploaded_file($tmp_file)){
    //拡張子を取り出し$extに代入
    $split = explode(".",$origin_file); 
    $ext = end($split);

    //拡張子があるかつ拡張子名がファイル名でない場合
    if($ext != "" && $ext != $origin_file){
        $up_file = "./img/". date("Ymd_His.") . mt_rand(1000,9999) . ".$ext";
        //move_uploaded_fileで絶対パス($tmp_file)から相対パス($up_file)に保存先を変更
        $up_ok = move_uploaded_file($tmp_file, $up_file);
    }
}


//----db登録処理----
$file = $up_file;
$kind = $_POST["kind"];
$size = $_POST["size"];
$location = $_POST["location"];
$lure = $_POST["lure"];
$weather = $_POST["weather"];

date_default_timezone_set('Asia/Tokyo');
$date = date("Y-m-d H:i:s");

//dbと接続
try {
    $pdo = new PDO('mysql:host=localhost;dbname=fish_sns02;charset=utf8', 'root', '',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    print "エラー!: " . $e->getMessage() . "<br/>";
    die();
}


//入力したデータをDBに追加
$stmt = $pdo->prepare("INSERT INTO `postData` (`filename`, `kind`, `size`, `location`, `lure`, `weather`, `date`) VALUES (:filename, :kind, :size, :location, :lure, :weather, :date);");

//prepareメソッドのクエリ文の引数と取得したデータを紐づける
$stmt->bindParam(':filename', $file);
$stmt->bindParam(':kind', $kind);
$stmt->bindParam(':size', $size);
$stmt->bindParam(':location', $location);
$stmt->bindParam(':lure', $lure);
$stmt->bindParam(':weather', $weather);
$stmt->bindParam(':date', $date);
//SQL文の実行
$stmt->execute();

//dbを閉じる
$pdo = null;

header("Location:home.php");

?>

