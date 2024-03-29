# アプリケーション名
「DETERMINATION」

# アプリケーション概要
自分で決断できない優柔不断な人の背中を押してくれるSNSです。

# 利用方法
アカウント未登録の場合は、Gmailを用いたメール認証によりアカウントを登録を行う。<br>
自身のアカウントでログインすると、マイページと投稿閲覧ページを閲覧できる。<br>
<br>
マイページ:<br>
物事の判断に迷ったとき、自身が今悩んでいる事柄と二択の選択肢を入力すると、いずれかの選択肢にランダムで決定し、投稿することができる。<br>
自分の投稿の削除、自分の投稿への「いいね」数の確認も行うことができる。<br>
<br>
![スクリーンショット (44)](https://user-images.githubusercontent.com/89728838/163028146-9a00b430-97bd-4869-9751-d5382379e115.png)<br>
<br>
投稿閲覧ページ:<br>
他者の投稿を閲覧し、「いいね」をすることができる。<br>
<br>
![スクリーンショット (45)](https://user-images.githubusercontent.com/89728838/163028362-0d3906a9-08be-4fd6-af1c-b98e946dffc0.png)<br>

# このアプリケーションを作ったきっかけと目指した課題解決
物事の決断において、「どう決めるか」よりも「そもそも決められるか」ということの方が幸福度への寄与が高い、という心理学的な事実があることを知り、そこから着想を得ました。<br>
他者からの「いいね」を通じて、決断する勇気を抱かせることを狙いとしています。

# 洗い出した要件定義
優先順位(高:3 中:2 低:1) | 機能 | 目的 | 詳細 | ストーリー(ユースケース)
-|-|-|-|-
3 | DB設計 | アプリ作成の全体を把握する必要テーブルを洗い出す | 必要テーブル pre_users/users/posts/likes | 
3 | ログイン機能 | 利用ユーザーを特定する | ログイン画面がこのアプリケーションを使う上で最初に表示される画面となる | サービスを使う際、登録したユーザー名とパスワードを入力してログイン
3 | アカウント作成機能 | アカウント未登録の場合のアカウント作成 |  Gmailアドレスを入力し、指定されたアドレスに届いたURLから新規ユーザー登録を行う(メール認証) | 初めてサービスを使う場合に新規登録を行う
3 | お悩み投稿機能 | マイページから自分の悩みを投稿する | 悩みの内容、選択肢A,Bを入力すると投稿できる | 自分で決断できない事柄があるときに投稿機能を使う 
3 | 投稿削除機能 | マイページから投稿を削除する | マイページに表示された投稿番号を入力して削除 | 間違えて投稿した場合や、お悩みが解決した場合に投稿を削除する
3 | 投稿表示機能(全ユーザー) | サービス利用者による全投稿を表示する | 投稿者名、悩みと選択肢、決定した選択肢を表示 |
3 | 投稿表示機能(自分の投稿) | 自分の投稿をマイページに表示 | 投稿番号、悩みと選択肢、決定した選択肢、いいねの数を表示 | いいねの数を確認する場合や削除したい投稿の番号を確認する
2 | いいね機能 | 他ユーザーの投稿にいいねをつける | 1投稿ごとのいいねできる回数は1ユーザーにつき1回まで。いいねをしてない投稿には「いいね」、いいね済みの投稿には「いいね解除」と表示される | 他ユーザーの投稿が良いと思った場合にいいねボタンを押す
2 | ログアウト機能 | ログイン状態の解除 | マイページ上の「ログアウト」ボタンを押すと、ユーザー名を保持したセッションを削除してからログイン画面に遷移する(ブラウザバックをすると「ログアウトされています」と表示される) | 
1 | メール認証 | ユーザー名とパスワードに加え、メールアドレスも要求することでセキュリティを強化する | PHPMailerを用いて、指定されたアドレスに新規アカウント登録を行うURLを送信する。一度使用されたURLは二度と使えないようにpre_usersテーブルにURLのトークンを保存しておく | 
