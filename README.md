## 概要
- LineソーシャルログインやLine Message APIの調査 
- Laravel9 PostgreSQL15 を試してみる

## 環境構築
### コンテナ起動
```sh
# PHP-ApacheコンテナにLaravel9をインストールする
docker-compose run php composer create-project --prefer-dist laravel/laravel . "9.*"
docker-compose up -d

docker ps
CONTAINER ID   IMAGE                          COMMAND                  CREATED         STATUS         PORTS                     NAMES
dce0e801aea2   laravel9-posgre-line-app_php   "docker-php-entrypoi…"   3 minutes ago   Up 3 minutes   0.0.0.0:8081->80/tcp      laravel9-posgre-line-app
69c88b698e54   postgres:15                    "docker-entrypoint.s…"   3 minutes ago   Up 3 minutes   0.0.0.0:54320->5432/tcp   laravel9-posgre-line-app-postgres
```

http://localhost:8081/ にアクセスしてLaravelのトップページが表示されることを確認する

### DB疎通確認
.envを編集する
```
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=main
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

LaravelとDBの疎通確認する
```sh
docker exec -it laravel9-posgre-line-app bash
# 疎通確認
php artisan tinker
> DB::select('select 1');
= [
    {#3685
      +"?column?": 1,
    },
  ]

> exit
```