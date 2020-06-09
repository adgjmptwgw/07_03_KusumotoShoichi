<?php
// 送信データのチェック
// var_dump($_GET);
// exit();

// 関数ファイルの読み込み
include("functions.php");
// DB接続
$pdo = connect_to_db();

// idの受け取り ※resd.phpからidが送られてくる
$id = $_GET['id'];
// var_dump($_GET);
// exit();

// データ取得SQL作成
$sql = '';
$sql = 'SELECT * FROM login_table WHERE id=:id'; //変更したい分のidだけを取得する
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
  //id一つを取り出すだけなので、foreachやwhileは必要ない。$stmt->fetch(PDO::FETCH_ASSOC);は一つだけ取り出す。
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
  <title>DB連携型loginリスト（編集画面）</title>
</head>

<body>
  <!-- 更新処理に飛ばす為、actionにlogin_update.phpを記述。 -->
  <form action="login_update.php" method="POST">
    <fieldset>
      <legend>DB連携型loginリスト（編集画面）</legend>
      <a href="login_read.php">一覧画面</a>
      <div>
        <!-- "?="は『?php echo="" ?』の略。$recordの中身はidであり、loginのidを配列で取り出す -->
        name: <input type="text" name="name" value="<?= $record["name"] ?>">
      </div>
      <div>
        password: <input type="date" name="password" value="<?= $record["password"] ?>">
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