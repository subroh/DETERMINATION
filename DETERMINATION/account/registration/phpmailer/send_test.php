<?php
    require 'src/Exception.php';
    require 'src/PHPMailer.php';
    require 'src/SMTP.php';

    session_start();

    $mail = new PHPMailer\PHPMailer\PHPMailer();

    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = 'smtp.gmail.com';
    $mail->Username = 'xxxxxxxxxx@gmail.com';
    $mail->Password = '********';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->CharSet = "UTF-8";
    $mail->Encoding = "base64";
    $mail->setFrom('xxxxxxxxxx@gmail.com','DETERMINATION事務局');
    $mail->addAddress($_SESSION['gmail_address'], '仮ユーザー様');
    $mail->Subject = 'DETERMINATION投稿サイト　本登録のご案内';
    $mail->isHTML(true);

	$urltoken = hash('sha256',uniqid(rand(),1));
	$url = "新規アカウント登録ページ(DETERMINATION/account/registration/user_registration.php)のURL"."?urltoken=".$urltoken;
	$loginurl = 'ログインページ(DETERMINATION/account/login.php)のURL';
    $body = <<< EOM
    DETERMINATION投稿サイト事務局です<br>
    以下のURLから当サイトへのユーザー本登録をお願いいたします<br>
    {$url}<br>
    <br>
    ログインはこちらから<br>
    {$loginurl}
    EOM;

    $mail->Body  = $body;
    // メール送信の実行
    if(!$mail->send()) {
    	echo 'メッセージは送られませんでした！';
    	echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else { //メール送信成功時の処理
        //urlに使用したランダム文字列をテーブルに保存
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        $sql = "CREATE TABLE IF NOT EXISTS pre_usertable"
            ."("
                . "id INT AUTO_INCREMENT PRIMARY KEY,"
                . "urltoken text"
            .");";
        $pdo->query($sql);

        $sql = "INSERT INTO pre_usertable (urltoken) VALUES (:urltoken)";
        $stmt = $pdo -> prepare($sql);
        $urltoken = 'urltoken=' . $urltoken;
        $stmt -> bindParam(':urltoken', $urltoken, PDO::PARAM_STR);
        $stmt -> execute();

    	echo '指定したメールアドレス(' . $_SESSION['gmail_address'] . ')に本登録の案内を送信しました<br>';
    	echo 'メール内のURLから本登録をして下さい<br>';
    }
