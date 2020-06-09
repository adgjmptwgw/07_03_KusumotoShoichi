<?php
// 送信データのチェック
// var_dump($_GET);
// exit();

// 関数ファイルの読み込み
include("functions.php");
// DB接続
$pdo = connect_to_db();

// idの受け取り
$id = $_GET['id'];
// var_dump($_GET);
// exit();

// $sql = 'SELECT * FROM score_table WHERE id=:id';

// データ取得SQL作成
$sql = '';
$sql = 'SELECT * FROM score_table WHERE id=:id';
// SQL準備&実行
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
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  // 正常にSQLが実行された場合は指定の11レコードを取得
  // fetch()関数でSQLで取得したレコードを取得できる
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DB連携型scoreリスト（編集画面）</title>
</head>

<body>
  <form action="score_update.php" method="POST">
    <fieldset>
      <legend>DB連携型scoreリスト（編集画面）</legend>
      <a href="score_read.php">一覧画面</a>      
      <div>
        score: <input type="text" name="score" value="<?= $record["score"] ?>">
      </div>
      <div>
        dete: <input type="date" name="dete" value="<?= $record["dete"] ?>">
      </div>
      <div>
        <input type="hidden" name="id" value="<?= $record['id'] ?>">
      </div>
      <div>
        <button>submit</button>
      </div>

    </fieldset>
  </form>

</body>

</html>