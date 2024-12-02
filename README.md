# SMS Subject management system
# 后端
课题管理系统 v1.0.0 beta
## 一、环境及工具
### 1.1 环境
-  1. PHP-8.3.13 NTS
-  2. Windows 11 / Linux
-  3. redis_version:3.0.504
-  4. MariaDB 10.9 - 11
-  5. Webman 1.6.2 /workerman 4.X
-  6. PHP8-Redis扩展 （windows下的扩展，需要编译）
-  7. NPM/PNPM/YARN [需要安装nodejs库]
-  8. composer 2.6.3 截至2024.12.2
### 1.2 工具
-  1.Hoppscotch API测试工具 
-  2.VSCodium/vscode  开发IDE
-  3.DBeaver 数据库管理工具，速度有点慢
-  4.PhpStorm 2024.2.4 比较吃内存
-  5.Sublime Text 轻量级的开发神器。
-  6.vim Linux系统的合作伙伴，确实优秀。
-  7.WindTerm/MobaXterm SSH远程工具，开源。
-  8.Git Bash 开发必备。
-  9.豆包/通义，编程字典。
## 二、克隆到本地
`git clone https://github.com/zzllbj/sms-php.git`
## 三、安装依赖
`composer install `
## 四、运行项目
-  1.windows `php windows.php`
-  2.linux `php start.php start -d`
## 常见问题
### 安装依赖库后无法运行
-  有可能是你的环境配置不正确或者根据报错信息逐项解决
### 安装后没有数据库
-  可以在DB里找到3.x的数据库
### 安装后提示redis扩展库没有找到的
-  需要安装php_redis.dll到php目录下的ext目录下，同时在php.ini打开此扩展
-  如果需要PHP8.3的redis动态库可以自己编译，也可以跟我要。
## 更新日期 2024-12-2 By 学无止境