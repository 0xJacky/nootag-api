# nootag-api
无派科技（深圳）有限公司官网后端

### 开发环境部署
1. 安装依赖
```
composer install
```

2. 复制 `.env.example` 为 `.env` 进入 `.env` 配置

3. 执行命令
```
php artisan migrate

php artisan tinker
User::create(['name'=>'YOUR_NAME', 'email'=>'YOUR_EMAIL', 'password'=>bcrypt('YOUR_PWD')]);
exit

php artisan passport:keys
php artisan key:generate
php artisan passport:client --password --name='nootag-api'
```

4. 保存生成的 secret 用于前端部署
