# 安装说明

## 推荐方式

* [推荐方式安装](../README.md)

## 手动方式

* **安装Smarty | PHPExcel | UEditor**

  - 下载Github库
    - Smarty        : https://github.com/smarty-php/smarty
    - PhpSpreadsheet: https://github.com/PHPOffice/PhpSpreadsheet

  - 放置 Smarty 库, PhpExcel库
    - 放置 Smarty 库 -> install/vendor/smarty/smarty/ 目录下
    - 放置 PhpSpreadsheet 库 -> install/vendor/phpoffice/phpspreadsheet/ 目录下

  - 下载 在线编辑器: [UEditor](http://ueditor.baidu.com/website/download.html)
    - 下载 在线编辑器: [UEditor] [1.4.3.3 PHP 版本] UTF-8版
    - 解压 下载文件 到目录 utf8-php
    - 先备份misc/js/onlineditor/ueditor 目录下的文件 到 misc/js/onlineditor/ueditor_bak 下
    - 复制粘贴 utf8-php/ -> misc/js/onlineditor/ueditor 目录下
    - 复制粘贴 misc/js/onlineditor/ueditor_bak -> misc/js/onlineditor/ueditor 即可.

    [说明]
    > UEditor下载版本为:[1.4.3.3 PHP 版本] UTF-8版

## FAQ

### PHP 7 需知

  - Since PHP 7 is not in the official Ubuntu PPAs,使用Composer install 会提示错误: Call to undefined function: simplexml_load_string(),解决办法在服务器上执行以下指令

    ```
    > sudo apt-get install php7.0-xml
    > sudo service php7.0-fpm restart
    ```

  - Composer require phpoffice/phpspreadsheet doesn't work

    ```
    > sudo apt install php-xml
    > sudo apt install php7.0-gd
    > sudo apt install php7.0-mbstring
    > sudo apt install php-zip
    ```
