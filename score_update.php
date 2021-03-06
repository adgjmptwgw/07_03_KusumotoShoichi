<?php

// 送信データのチェック
// var_dump($_POST);
// exit();

// 関数ファイルの読み込み
include('functions.php');

// 送信データ受け取り
$score = $_POST['score'];
$date = $_POST['date'];
$id = $_POST['id'];

// DB接続
$pdo = connect_to_db();

// UPDATE文を作成&実行
$sql = "UPDATE score_table SET score=:score, date=:date, updated_at=sysdate() WHERE id=:id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);
$stmt->bindValue(':score', $score, PDO::PARAM_STR);
$stmt->bindValue(':date', $date, PDO::PARAM_INT);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は一覧ページファイルに移動し，一覧ページの処理を実行する
  header("Location:score_read.php");

}
