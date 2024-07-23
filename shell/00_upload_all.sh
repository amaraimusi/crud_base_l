#!/bin/sh
echo 'すべてソースコードをアップ'

rsync -auvz --exclude='.env' ../dev amaraimusi@amaraimusi.sakura.ne.jp:www/mng/crud_base_l/

rsync -auvz  ../dev/.env_p amaraimusi@amaraimusi.sakura.ne.jp:www/mng/crud_base_l/dev/.env

echo "------------ アップロード完了"
#cmd /k