# 安装 PhpRedis和PHP第三方库

主要目标是安装 PhpRedis和PHP第三方库。

## 推荐方式

### Docker方式

* **安装Docker**

  - [Get Docker](https://docs.docker.com/get-docker/)

* **Docker帮助文档**

  - [说明](docker/README.md)

* **安装PHP第三方库**

  如果开发者熟悉Docker，可使用Docker安装PHP第三方库，就无需在本地安装composer了。

  在Docker容器内安装好UEditor，用修改过的UEditor备份文件(已先复制到容器内)覆盖，再将其复制到本地指定的文件路径下。

  以下操作均在根路径下命令行中执行:

  - 安装目录: 根路径
  - 创建镜像
    - docker build -t orm/composer --target=composer -f install/docker/Dockerfile .
  - 运行容器应用 
    - docker run -dit --name=orm_composer orm/composer
  - 复制容器文件到本地: 
    - 复制安装好的PHP第三方库文件到本地: docker cp orm_composer:/app/vendor/ $(pwd)/install/
  - 删除容器和镜像
    - docker stop orm_composer && docker rm orm_composer && docker rmi orm/composer

## 本地方式

### 安装 PhpRedis

  - 安装PhpRedis: https://github.com/phpredis/phpredis/blob/develop/INSTALL.markdown
    - 国内安装: https://github.com.cnpmjs.org/phpredis/phpredis/blob/develop/INSTALL.markdown
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

### 安装PHP第三方库

* **安装 composer**

  - 安装composer: http://docs.phpcomposer.com/00-intro.html
    - 升级composer: composer self-update
    - 升级后回滚老版本: composer self-update --rollback 
    - [阿里云 Composer 全量镜像](https://developer.aliyun.com/composer)

* **安装目录下运行**

  - 根路径下运行composer

    ```
    > composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
    > composer install --ignore-platform-reqs --no-interaction --no-plugins --no-scripts --prefer-dist
    ```

## FAQ

### PHP 7 需知

  - Since PHP 7 is not in the official Ubuntu PPAs,使用Composer install 会提示错误: Call to undefined function: simplexml_load_string(),解决办法在服务器上执行以下指令

    ```
    > sudo apt-get install php7.0-xml
    > sudo service php7.0-fpm restart
    ```

## 参考资料

* **安装Composer**
  > http://www.phpcomposer.com/