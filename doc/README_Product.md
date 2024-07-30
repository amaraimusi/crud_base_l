# 本番環境構築手順 v2.0


① サーバーにデータベースを作成しておく。

データベース名→amaraimusi_crud_base_l

backupディレクトリにsqlファイルがいくつかあるので最新のsqlファイルをインポートする。

② サーバー側のphpバージョンを合わせる

サーバーのphpバージョンを8.1にする。

③ ソースコードをサーバーにアップする

00_upload_all.shを実行する

④ シンボリックリンクを作成する

サーバーへログインし、シンボリックリンクを作成する

```
ssh -l amaraimusi amaraimusi.sakura.ne.jp
cd www
ln -s  /home/amaraimusi/www/mng/crud_base_l/dev/public crud_base_l
```

⑤ 確認

以下のURLにアクセスし画面が表示されれば成功

https://amaraimusi.sakura.ne.jp/crud_base_l