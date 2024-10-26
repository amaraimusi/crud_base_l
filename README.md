# crud_base_l
CrudBase Laravel10版

@since 2024-7-12

@auther amaraimusi

@license MIT


[開発環境構築手順](doc/README_Environment2.md "開発環境構築手順")

[フロントエンドのCrudBase開発時における、gulpのコンパイルについて](doc/README_crudbase_gulp.md "フロントエンドのCrudBase開発時における、gulpのコンパイルについて")

[本番環境構築手順](doc/README_Product.md "本番環境構築手順")


## 本番環境構築

SSHでログイン 例↓

```
ssh -l hogehoge hogehoge.sakura.ne.jp
```

本番環境にLaravel10をインストール

```
cd www/
mkdir crud_base_l
cd crud_base_l
php -r "readfile('https://getcomposer.org/installer');" | php
php composer.phar create-project "laravel/laravel=10.*" dev
cd dev
php -r "readfile('https://getcomposer.org/installer');" | php
php composer.phar require laravel/ui


```


01_upload.shを実行してソースコードをアップロードする



本番環境用の設定ファイル、crud_base_config_p.phpと.env_p.phpを作成し、以下のshellを実行します。

```
02_u_cb_config.sh
04_upload_env.sh
```

## CRON用のバッチコマンド

有名猫画面のデータを復元するコマンド

```
php artisan app:monitor-batch
```

上記のコマンドをさくらサーバーに設定する場合

```
cd www/mng/crud_base_l/dev; php artisan app:monitor-batch
```

### venderのCrudBaseを更新するコマンド

```
php composer.phar update amaraimusi/CrudBase
```



## ER図

とりあえずメッセージボードまわりのみ。 draw.ioにて記述しています。


![ER図](doc/crud_base_l.drawio.svg "ER図")

 [ER図:draw.io用xml](doc/crud_base_l.drawio.xml)


