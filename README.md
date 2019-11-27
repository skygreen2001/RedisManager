# Online Redis Manager

符合中国开发者思维方式的在线Redis管理工具的框架，设计初衷快捷、简单、实用。
主要采用betterlife框架、betterlife.front框架中[web/vuejs]模块快速开发而成。
底层前端主要使用了Vuejs、iView框架；后端使用了PhpRedis、PhpSpreadsheet框架。

## 安装说明

* **通过Github官网下载**

  官网地址: https://github.com/skygreen2001/RedisManager

  ```
  > git clone https://github.com/skygreen2001/RedisManager.git
  > git clone git@github.com:skygreen2001/RedisManager.git
  ```

## 运行环境安装

  以下工具任选一种即可

  - [ampps](http://www.ampps.com)

    可以直接在它上面下载安装(Wamp|Lamp|Mamp)

  - [Wamp](http://www.wampserver.com/en/)

    Windows下的Apache + Mysql + PHP
    [PhpStudy]: http://www.phpstudy.net/

  - [Lamp](https://lamp.sh/)

    LAMP指的Linux、Apache，MySQL和PHP的第一个字母
    [安装详细说明]: http://blog.csdn.net/skygreen_2001/article/details/19912159

  - [Mamp](http://www.mamp.info/en/)

    Mac环境下搭建 Apache/Nginx、MySQL、Perl/PHP/Python 平台。

  - [Xampp](https://www.apachefriends.org/zh_cn/index.html)

    XAMPP是完全免费且易于安装的Apache发行版，其中包含MariaDB、PHP和Perl。

  - 本地运行PHP server: php -S localhost:8000)

* **安装 PhpRedis**

  - 安装PhpRedis: https://github.com/phpredis/phpredis/blob/develop/INSTALL.markdown
  - Mac安装PhpRedis
    - Mac安装Pecl: https://pear.php.net/manual/en/installation.getting.php
    - sudo pecl channel-update https://pecl.php.net/channel.xml
    - sudo pecl update-channels
    - pecl search redis
    - sudo pecl install redis
    - Mac开启关闭SIP（系统完整性保护）
      - 重启MAC，按住cmd+R直到屏幕上出现苹果的标志和进度条，进入Recovery模式
      - 在屏幕最上方的工具栏找到实用工具（左数第3个），打开终端，输入：
        - csrutil disable
      - 重启mac
    - sudo vi /php.ini
      - extension=redis.so
    - 重启Apache: sudo apachectl restart

* **安装 composer**

  安装composer: http://docs.phpcomposer.com/00-intro.html

  - 安装目录: 在根路径/install 目录下
  - 运行composer

    ```
    > composer install
    ```
    [Mac电脑用户]: sudo composer install

## 云部署

* [阿里云](https://market.aliyun.com/developer)
* [Heroku](https://devcenter.heroku.com/categories/php)
* [docker](https://docs.docker.com): https://segmentfault.com/a/1190000006802383
* [vagrant](https://app.vagrantup.com/laravel/boxes/homestead-7): https://segmentfault.com/a/1190000000264347

## 开发工具

* [Visual Studio Code](https://code.visualstudio.com/)
* [Atom](https://atom.io)
* [Atom IDE](https://ide.atom.io/)
* [Sublime](http://www.sublimetext.com)

## 框架目录定义

  - core   : 框架核心支持文件
    - core/config      : 配置文件[各个功能模块]
    - core/include     : 常用的函数库
  - install: 安装目录
  - api    : ajax请求服务端服务支持[手机或Web前端ajax请求返回json数据]
  - www: 自适应html5Web网页[内嵌在手机App里]
  - log    : 日志目录，每天一个调试测试日志文件放在这里
  - upload : 后台上传下载文件(如excel)放置目录 

## 目录权限设置

    - 如果是Linux、Mac 系统，需要设置以下目录权限为完全可读写。
      - log     
      - upload 
      - 执行命令如下:
        > sudo mkdir log/ upload/
        > sudo chmod -R 0777 log/ upload/
    
## 服务器配置模式

  - 在 www/js/main.js 里配置 isConfigLocal
    - isConfigLocal: true, 意味着服务器配置都持久化存储在本地
    - isConfigLocal: false, 意味着服务器配置都持久化存储在服务器上，建议内部本地使用，可只需配置一次。
    - 默认存储在本地浏览器localStorage里，确保使用工具千人千面。

## 参考资料

* **Betterlife**
  > https://threejs.org/docs/index.html#manual/en/introduction/How-to-run-things-locally
* **Betterlife.Front**
  > https://github.com/skygreen2001/betterlife.front/tree/master/web/vuejs
* **本地运行服务器**
  > https://threejs.org/docs/index.html#manual/en/introduction/How-to-run-things-locally
* **安装Composer**
  > http://www.phpcomposer.com/
* **下载PhpSpreadsheet**
  > https://github.com/PHPOffice/PhpSpreadsheet