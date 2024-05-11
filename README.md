# <center>仿移动端微信后端接口</center>

### 项目介绍
采用框架lumen+workerman，目前只是写了注册登录，会一直持续更新！！！

### 项目运行

导入数据库cover_wechat.sql
改好env配置

```shell
git clone https://github.com/Juenfy/cover-wechat-backend.git
```

```shell
cd cover-wechat-backend
```

```shell
composer install
```

启动workerman服务

windows直接点击运行start_for_win.bat

linux运行
```shell
php artisan workerman start --d
```

运行项目
```shell
php -S localhost:8000 -t public
```

Then enjoy！！！
