<?php

// 送信データのチェック
// var_dump($_GET);
// exit();

// 関数ファイルの読み込み
include('functions.php');

// 送信データ受け取り
$id = $_GET['id'];

// DB接続
$pdo = connect_to_db();

// DELETE文を作成&実行。delete処理は（DELETE FROM テーブル名;）を使う。WHERE で id だけを取り出す。
// ※WHEREを付け忘れると全てのデータが消滅するので注意！！！！！！！
$sql = "DELETE FROM user WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();



// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は一覧ページファイルに移動し，一覧ページの処理を実行する
  header('Location:login_read.php');

}
