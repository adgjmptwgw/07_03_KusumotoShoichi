<?php

// 送信確認
// var_dump($_POST);
// exit();

// 項目入力のチェック
// 値が存在しない or 空で送信されてきた場合はNGにする
if (
  !isset($_POST['name']) || $_POST['name'] == '' ||
  !isset($_POST['password']) || $_POST['password'] == ''
) {
  // 項目が入力されていない場合はここでエラーを出力し，以降の処理を中止する
  echo json_encode(["error_msg" => "no input"]);
  exit();
}

// POSTから送られてきた、nameのデータを変数に入れる
$name = $_POST['name'];
$password = $_POST['password'];

// DB接続の設定 ※ここはtryに代入する文を定義しているだけ
// DB名は`gsacf_x00_00`にする
$dbn = 'mysql:dbname=gsacf_l03_03;charset=utf8;port=3306;host=localhost';
$user = 'root';
$pwd = '';

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
$sql = 'INSERT INTO login_table(id, name, password) 
VALUES(NULL, :name, :password)';

// SQL準備&実行
$stmt = $pdo->prepare($sql); // sql文をstmtへ渡す処理
//bindValue(ある文字を任意の文字に書き換えるもの)で、変な文字が入ってきたら無効化して$sqlに戻す。
$stmt->bindValue(':name', $name, PDO::PARAM_STR); 
$stmt->bindValue(':password', $password, PDO::PARAM_STR);
$status = $stmt->execute(); //  $statusの中にエラーが起きたのか、正常であったか全ての情報が入ってくる。execute()で実行。

// データ登録処理後
if ($status == false) { //47行目で実行した内容がエラーであった場合、以下実行
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();  //errorInfo()という関数を実行する。
  // $errorの中には配列としてたくさんの言語でエラーメッセージがあるが、人間の言語で分かるのが2番目の配列である為、[2]をと記述する
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  header("Location:score_input.php");
  exit();
}
