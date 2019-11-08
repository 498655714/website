<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>


## 关于 CMS后台管理系统(Frame基础版)(尚未开发完成)

本系统是基于laravel5.5,layui-v2.4.5 制作的CMS后台管理系统基础版,用于快速开发网站、博客、新闻等基础框架。框架包含以下扩展：
 
  1. 权限管理使用 laravel-permission 扩展实现,具体可以参考官方地址：https://github.com/spatie/laravel-permission
  2. 使用 laravel-lang:~3.0 作为汉化包，具体可以自行搜索帮助文档。
  
## 安装步骤

 ##### 1. 执行以下命令,将项目克隆到本地 
       git clone https://github.com/498655714/website.git
 ##### 2. 进入项目根目录后,安装依赖关系
       composer install
 ##### 3. 复制配置文件
       cp .env.example .env
 ##### 4. 创建新的应用程序密钥
       php artisan key:generate
 ##### 5. 设置数据库及邮件服务等 编辑.env文件 以下是实例
       
       DB_CONNECTION=mysql
       DB_HOST=127.0.0.1
       DB_PORT=3306
       DB_DATABASE=website
       DB_USERNAME=website
       DB_PASSWORD=你的密码
     
       MAIL_DRIVER=smtp      
       MAIL_HOST=服务器
       MAIL_PORT=端口
       MAIL_USERNAME=账号
       MAIL_PASSWORD=这里输入邮件服务器给的授权码
       MAIL_ENCRYPTION=ssl
       MAIL_FROM_ADDRESS=账号
       MAIL_FROM_NAME=署名
       
       DEL_PASS=123456     //用户点击删除时，会提示输入的密码，再次设置               

 ##### 6. 添加自动加载
     composer dump-autoload
     
 ##### 7. 运行数据库迁移
      php artisan migrate
      
 ##### 8. 运行数据填充
      php artisan db:seed      

 ## Laravel 框架  nginx rewrite配置

    location / { 
             index index.html index.htm index.php;
             if (!-e $request_filename){ 
                  rewrite ^/(.*)$ /index.php/$1 last; 
             } 
     }
