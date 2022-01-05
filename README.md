# Online Redis Manager

符合中国开发者思维方式的在线Redis管理工具的框架，设计初衷快捷、简单、实用。
主要采用betterlife框架、betterlife.front框架中[web/vuejs]模块快速开发而成。
底层前端主要使用了Vuejs、iView框架；后端使用了PhpRedis、PhpSpreadsheet框架。

## 帮助文档

- [帮助文档](help/HELP.md)

## 下载源码

下载地址: https://github.com/skygreen2001/RedisManager

* **Git安装**

  - 下载Git
    - Git SCM  : https://git-scm.com/downloads
    - Bitbucket: https://www.atlassian.com/git/tutorials/install-git
    - Git大全   : https://gitee.com/all-about-git

  - 下载RedisManager

      ```
      > git clone https://github.com/skygreen2001/RedisManager.git
      或
      > git clone https://github.com.cnpmjs.org/skygreen2001/RedisManager
      或
      > git clone https://gitee.com/skygreen2015/RedisManager
      ```

* **Docker安装**

  - [下载 Docker](https://docs.docker.com/get-docker/)
  - 下载RedisManager

    ```
    > docker run -ti --rm -v ${HOME}:/root -v $(pwd):/git alpine/git clone https://github.com/skygreen2001/RedisManager.git
    或
    > docker run -ti --rm -v ${HOME}:/root -v $(pwd):/git alpine/git clone https://github.com.cnpmjs.org/skygreen2001/RedisManager
    或
    > docker run -ti --rm -v ${HOME}:/root -v $(pwd):/git alpine/git clone https://gitee.com/skygreen2015/RedisManager
    ```

## 通常安装

### 运行环境安装

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

  - [宝塔](https://www.bt.cn/)

  - [PhpStud](https://www.xp.cn/)

  - 本地运行PHP server: php -S localhost:8000)

### 其它安装

  - [安装 PhpRedis和PHP第三方库](install/README.md)
    - 安装 PhpRedis  
    - 安装PHP第三方库主要是用composer

## Docker安装

如果开发者熟悉Docker或者希望尝试通过Docker搭建开发环境(无需考虑因为操作系统，无法完整搭建应用运行环境，如在Mac操作系统下，因为权限问题无法安装php的zip或者redis，Mac Monterey版本后不再默认安装PHP), 可使用Docker安装

* **安装Docker**

  - [Get Docker](https://docs.docker.com/get-docker/)

* **Docker帮助文档**

  - [帮助文档](install/docker/README.md)

### Docker Hub下载运行

- 直接从Docker Hub拉取orm镜像并运行
  - Docker Hub上查询镜像: https://hub.docker.com  -> 搜索: skygreen2021/orm
  - 执行以下命令即可
  
    ```
      docker run -dp 80:80 --name orm -t skygreen2021/orm
    ```

  - 停止应用     : docker stop orm
  - 删除所有的容器: docker rm orm
  - 删除生成的镜像: docker rmi skygreen2021/orm


### 本地Docker 运行应用

  - 根路径下运行以下指令执行操作
  - 创建运行: docker-compose up -d
  - 运行应用: docker-compose start
  - 停止应用: docker-compose stop
  - 进入应用: docker exec -it orm /bin/bash

  - 删除所有的容器: docker-compose down
  - 删除生成的镜像: docker rmi orm_nginx orm redis

* **其它**

  - [其它说明](install/docker/SETUP.md)

## 云部署

* [阿里云](https://market.aliyun.com/developer)
* [Heroku](https://devcenter.heroku.com/categories/php)
* [vagrant](https://app.vagrantup.com/laravel/boxes/homestead-7): https://segmentfault.com/a/1190000000264347

## 框架目录定义

  - core   : 框架核心支持文件
    - core/config      : 配置文件[各个功能模块]
    - core/include     : 常用的函数库
  - install: 安装目录
  - api    : ajax请求服务端服务支持[手机或Web前端ajax请求返回json数据]
  - www    : redis在线编辑器前端页面
  - log    : 日志目录，每天一个调试测试日志文件放在这里
  - upload : 后台上传下载文件(如excel)放置目录 

## 服务器配置模式

  - 在 www/js/main.js 里配置 isConfigLocal
    - isConfigLocal: true, 意味着服务器配置都持久化存储在本地
    - isConfigLocal: false, 意味着服务器配置都持久化存储在服务器上，建议内部本地使用，可只需配置一次。
    - 默认存储在本地浏览器localStorage里，确保使用工具千人千面。


## 目录权限设置

  - 如果是Linux、Mac 系统，需要设置以下目录权限为完全可读写。
    - log     
    - upload 
    - 执行命令如下:
      > sudo mkdir log/ upload/
      > sudo chmod -R 0777 log/ upload/

## 开发工具

* [Visual Studio Code](https://code.visualstudio.com/)
* [Atom](https://atom.io)
* [Atom IDE](https://ide.atom.io/)
* [Sublime](http://www.sublimetext.com)

    

## 参考资料

* **Betterlife**
  > https://threejs.org/docs/index.html#manual/en/introduction/How-to-run-things-locally
* **Betterlife.Front**
  > https://github.com/skygreen2001/betterlife.front/tree/master/web/vuejs
* **Redis**
  > https://redis.io
* **本地运行服务器**
  > https://threejs.org/docs/index.html#manual/en/introduction/How-to-run-things-locally
* **安装Composer**
  > http://www.phpcomposer.com/

* **Docker 安装 Redis 管理器**
  > docker hub phpRedisAdmin: https://hub.docker.com/r/erikdubbelboer/phpredisadmin
  > phpRedisAdmin: https://dubbelboer.com/phpRedisAdmin
