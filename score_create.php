<?php

// 送信確認
// var_dump($_POST);
// exit();

// 項目入力のチェック
// もし、テキスト中の値が存在しないor空で送信されてきた場合はNGにする
if(
  !isset($_POST['score']) || $_POST['score']=='' ||
  !isset($_POST['date']) || $_POST['date']==''
){
  exit('テキストに内容を入力して下さい');
}


// 受け取ったデータを変数に入れる
$score = $_POST['score'];  
$date = $_POST['date'];

// DB接続の設定
// DB名は`gsacf_x00_00`にする
$dbn = 'mysql:dbname=gsacf_l03_03;charset=utf8;port=3306;host=localhost';
$user = 'root';  //user_ID
$pwd = '';  //password 

try {
  // ここでDB接続処理を実行する
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  // DB接続に失敗した場合はここでエラーを出力し，以降の処理を中止する
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}

// データ登録SQL作成 
// `created_at`と`updated_at`には実行時の`sysdate()`関数を用いて実行時の日時を入力する
//  ※バインド変数をいれる
// $sql = 'INSERT INTO score_table(id,score,date,created_at,)
// VALUES(NULL, :score, :date, sysdate())';
$sql = 'INSERT INTO score_table(id,score,date,created_at)
VALUES(NULL,:score, :date, sysdate())';
// var_dump($_POST);
// exit();

// SQL準備&実行  ※score,dateをバインド変数に変換する
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':score', $score, PDO::PARAM_STR);
$stmt->bindValue(':date', $date, PDO::PARAM_STR);

// SQLを実行
$status = $stmt->execute();


// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  //$error2は人間が分かる文字。$error1は謎の文字列。
  $error = $stmt->errorInfo();
  exit('sqlError:'.$error[2]);
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  header('Location: score_input.php');

}

?>
