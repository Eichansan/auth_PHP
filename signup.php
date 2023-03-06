<?php 
date_default_timezone_set("Asia/Tokyo");

$error_array = array();

if (empty($_POST['fullname'])){
  $error_array["name_error"] = "名前を入力してください";
}
if (empty($_POST['phone'])){
  $error_array["phone_error"] = "電話番号を入力してください";
}
if (empty($_POST['email'])){
  $error_array["email_error"] = "メールアドレスを入力してください";
}
if (empty($_POST['password'])){
  $error_array["password_error"] = "パスワードを入力してください";
}
if (empty($_POST['conf_pass'])){
  $error_array["conf_error"] = "確認用パスワードを入力してください";
}

if (isset($_POST["submitButton"]) && $_POST['password']==$_POST['conf_pass']){
  if (empty($error_array["name_error"]) && empty($error_array["phone_error"]) && empty($error_array["email_error"]) && empty($error_array["password_error"]) && empty($error_array["conf_error"])){

    $fullname = $_POST['fullname']; 
    $phone = $_POST['phone']; 
    $email = $_POST['email']; 
    $password = $_POST['password']; 
    $conf_pass = $_POST['conf_pass']; 
    $created_at = date("Y-m-d H:i:s");

    try {
      //DB接続 
      $pdo = new PDO('mysql:host=localhost;dbname=auth_php', "root", "");
      $sql = "INSERT INTO `users`(fullname, phone, email, password, created_at) values(?, ?, ?, ?, ?);";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($fullname, $phone, $email, password_hash($password, PASSWORD_DEFAULT), $created_at));//ここで?に指定
      //DBの接続を閉じる
      $stmt = null;
      $pdo = null;
      
      header('Location: home.php');
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
  }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>新規登録</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <form name="login_form" method="POST" >
    <div class="login_form_top">
      <h1>新規登録画面</h1>
      <p>各情報をご入力の上、「新規登録」ボタンをクリックしてください。</p>
    </div>
    <div class="login_form_btm">
      <?php 
        if(isset($_POST["submitButton"]) && (isset($error_array["name_error"]) || isset($error_array["phone_error"]) || isset($error_array["email_error"]) || isset($error_array["password_error"]) || isset($error_array["conf_error"]))){
          echo '<span style="color:#FF0000;">入力漏れがあります</span>';
        } elseif(isset($_POST["submitButton"]) && $_POST['password']!=$_POST['conf_pass']){
          echo '<span style="color:#FF0000;">パスワードと確認用パスワードが異なります</span>';
        }
        ?></br>
      <input type="text" name="fullname" placeholder="名前を入力してください"><br>
      <input type="tel" name="phone" placeholder="電話番号を入力してください"><br>
      <input type="email" name="email" placeholder="メールアドレスを入力してください"><br>
      <input type="password" name="password" placeholder="パスワードを入力してください"><br>
      <input type="password" name="conf_pass" placeholder="確認用パスワードを入力してください"><br>
    </div>
    <button type="submit" name="submitButton">新規登録</button>
  </form>
  <div style="text-align:center">
    <br><a href="index.php">ログインはこちら</a>
  </div>
</body>
</html>