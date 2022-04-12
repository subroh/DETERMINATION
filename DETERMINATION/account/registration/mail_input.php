<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メール入力</title>
</head>
<body>
    <?php
        if (!empty($_POST['gmail_address'])) {
            session_start();
            $_SESSION['gmail_address'] = $_POST['gmail_address'];
            header("Location: ./phpmailer/send_test.php");
            exit;
        }
    ?>
    <form method="post" action="">新規ユーザー登録<br>(入力されたGmailアカウントに本登録の案内を送信します)<br>
        <input type="text" name="gmail_address" placeholder="Gmailアドレス"><br>
    	<input type="submit" name="submit">
    </form>
    <a href="../login.php">ログイン画面へ</a>
</body>
</html>