<?php 
$error_array = array();

if (empty($_POST['email'])){
  $error_array["email_error"] = "メールアドレスを入力してください";
}
if (empty($_POST['password'])){
  $error_array["password_error"] = "パスワードを入力してください";
}

if (isset($_POST["submitButton"])){
  if (empty($error_array["email_error"]) && empty($error_array["password_error"])){
    $email = $_POST['email']; 
    $password = $_POST['password']; 

    //DB接続 
    try {
      $pdo = new PDO('mysql:host=localhost;dbname=auth_php', "root", "");
      $sql = "SELECT count(*) FROM `users` where email=? and password=?;";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($email, $password));//ここで?に指定
      $result = $stmt->fetch();
      //DBの接続を閉じる
      $stmt = null;
      $pdo = null;
      
      if ($result[0] != 0){
        header('Location: home.php');
        
      } else {
        $error_msg = "メールアドレスかパスワードが誤りです";
      }
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
  <title>ログイン</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <form name="login_form" method="POST" >
    <div class="login_form_top">
      <h1>ログイン画面</h1>
      <p>メールアドレス、パスワードをご入力の上、「ログイン」ボタンをクリックしてください。</p>
    </div>
    <div class="login_form_btm">
      <?php 
        if(isset($error_array["email_error"]) || isset($error_array["password_error"])){
          echo '<span style="color:#FF0000;">入力漏れがあります</span>';
        } 
        elseif(isset($error_msg)){
          echo '<span style="color:#FF0000;">メールアドレスかパスワードが誤りです</span>';
        }
        ?></br>
      <input type="email" name="email" placeholder="メールアドレスを入力してください"><br>
      <input type="password" name="password" placeholder="パスワードを入力してください">
    </div>
    <button type="submit" name="submitButton">ログイン</button>
  </form>
  <div style="text-align:center">
    <br><a href="signup.php">新規登録はこちら</a>
  </div>
</body>
</html>