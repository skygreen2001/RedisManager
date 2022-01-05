# Betterlife Docker README

## 开发初步
  - [Get Started with Docker](https://www.docker.com/get-started)
  - [Get started with Docker Desktop for Mac](https://docs.docker.com/docker-for-mac/)
  - [Reference documentation](https://docs.docker.com/reference/)
  - [Docker — 从入门到实践](https://yeasy.gitbooks.io/docker_practice/content/)
  - [Get started](https://docs.docker.com/get-started/)
  - [Docker for Beginners](https://github.com/docker/labs/tree/master/beginner/)
  - [Samples](https://docs.docker.com/samples/)
  - [Docker Hub](https://hub.docker.com/?overlay=onboarding)
  - [DaoCloud 镜像市场](https://hub.daocloud.io/)
  - 搜索镜像[PHP]: docker search php
  - 第一个docker应用: docker run hello-world
  - 开始启动nginx: docker run --detach --publish=80:80 --name=webserver nginx
  - 指定端口:docker run --name static-site -e AUTHOR="skygreen2001" -d -p 8888:80 dockersamples/static-site
  - 开始启动ubuntu系统: docker run -it ubuntu bash
  - 打开容器命令行工具 : docker exec -it brave_payne[容器名称] /bin/bash
                      docker exec -it brave_payne[容器名称] /bin/sh
  - 使用vi: apt-get update && apt-get install vim
  - 使用ifconfig和ping: apt-get update && apt install net-tools && apt install iputils-ping
  - 查看IP信息: docker inspect brave_payne[容器名称] | grep "IPAddress"
  - 从容器拷贝文件到宿主机: docker cp 容器名:容器中要拷贝的文件名及其路径 要拷贝到宿主机里面对应的路径
    - docker cp 17dbf5447e99:/usr/share/nginx/html/test.php /Users/master/Downloads/

  - 从宿主机拷贝文件到容器: docker cp 宿主机中要拷贝的文件名及其路径 容器名:要拷贝到容器里面对应的路径
    - docker cp /Users/master/Downloads/test.php 17dbf5447e99:/usr/share/nginx/html/

  - [Image-building best practices](https://docs.docker.com/get-started/09_image_best/)

## Centos 安装 Docker
  - 安装lsof: yum install lsof (查看端口是否被占用)
  - Install Docker Engine on CentOS: https://docs.docker.com/engine/install/centos/
  - Configure Docker to start on boot: https://docs.docker.com/engine/install/linux-postinstall/
  - Play with Docker (PWD): https://labs.play-with-docker.com

## Ubuntu 安装 Docker
  - Install Docker Engine on Ubuntu: https://docs.docker.com/engine/install/ubuntu/
  - Configure Docker to start on boot: https://docs.docker.com/engine/install/linux-postinstall/
  - Play with Docker (PWD): https://labs.play-with-docker.com

## 常用指令
  - docker --help
  - 列出镜像: docker images
  - 删除1到多个镜像: docker rmi IMAGE_ID1 IMAGE_ID2
  - 查看最新前3个的container: docker ps -n 3
  - 查看所有的container(包括停止的): docker ps -a
  - 容器取名: docker container rename ef7b5043e9b4 bb
  - Docker stats for containers: docker stats
  - 开始一个container: docker start $CONTAINER_ID
    - 开始container: docker start $CONTAINER_NAME(docker run 需指定名称 --name $CONTAINER_NAME)
  - 关闭1到多个container: docker stop $CONTAINER_ID
  	- 关闭container: docker stop $CONTAINER_NAME(docker run 需指定名称 --name $CONTAINER_NAME)
  - 删除1到多个container: docker rm $CONTAINER_ID1 $CONTAINER_ID2
  - 清理所有处于终止状态的容器: docker container prune
  - 删除所有容器的命令: docker rm -f `docker ps -a | awk '{print $1}' | grep [0-9a-z]`
  - 删除所有镜像的命令: docker rmi -f `docker images | awk '{print $3}' | grep [0-9a-z]`
                    - docker rmi -f $(docker images | awk '/^<none>/ { print $3 }')
  - 查看日志: docker logs -f [OPTIONS] CONTAINER
  - 查看文件变动: docker diff CONTAINER_ID 

  - 创建镜像并上传
    - 安全检查: docker scan getting-started [image-name]
    - 检查创建中每层大小: docker image history getting-started [image-name]
    - 创建镜像: docker build -t getting-started [image-name] .
    - 运行创建的镜像: docker run -dp 3000:3000 getting-started [image-name]
    - 登录到docker hub: docker login -u [YOUR-USER-NAME]
    - 标记需提交的镜像:docker tag getting-started [image-name] YOUR-USER-NAME/getting-started [image-name]
    - 提交到hub: docker push YOUR-USER-NAME/getting-started [image-name]
    - hub上查询提交的镜像: https://hub.docker.com  -> 搜索:  YOUR-USER-NAME/getting-started
    - 如果本地已经存在，需要更新镜像: docker pull YOUR-USER-NAME/getting-started [image-name]
    - 运行推送的hub: docker run -dp 3000:3000 YOUR-USER-NAME/getting-started [image-name]

  - 创建一个数据卷: docker volume create my-vol
  - 查看所有的数据卷: docker volume ls
  - 查看数据卷信息: docker volume inspect my-vol
  - 删除数据卷: docker volume rm my-vol
  - 挂载数据卷: docker run -d -P --name web --mount source=my-vol,target=/usr/share/nginx/html nginx:alpine
               docker run -dp 82:80 -v my-vol:/usr/share/nginx/html nginx:alpine
  - 挂载主机目录: docker run -d -P --name web --mount type=bind,source=/Library/WebServer/Documents/,target=/usr/share/nginx/html nginx:alpine

  - 创建网络: docker network create todo-app
  - 网络列表: docker network list

  - docker-compose
    - Build or rebuild services: docker-compose build
    - 查看验证文件配置: docker-compose config
    - 后台启动: docker-compose up -d
    - List current running Containers: docker-compose ps
    - Close all running Containers: docker-compose stop
    - 删除Delete all existing Containers: docker-compose down
    - Compose查看日志: docker-compose logs -f

## Docker Compose
  - [Overview of Docker Compose](https://docs.docker.com/compose/)
  - [Docker Compose](https://www.runoob.com/docker/docker-compose.html)
  - [awesome compose](https://github.com/docker/awesome-compose)
  - [LNMP - Docker 多容器间协作互连](https://github.com/twang2218/docker-lnmp)
  - [使用 docker-compose 构建你的项目](https://juejin.cn/post/6844904038627033095)

## 在IDE中使用Docker
  - [Docker in Visual Studio Code](https://code.visualstudio.com/docs/containers/overview)
    - 所有Docker的指令操作: opening the Command Palette [⇧⌘P] and using Docker: 
    - Docker commands | Docker Explorer | Docker Compose
    - Docker Explorer -> HELP AND FEEDBACK -> Open Docker Extension Walkthrough
  - [VSCode - Developing inside a Container](https://code.visualstudio.com/docs/remote/containers)
  - [VSCode - Create a development container](https://code.visualstudio.com/docs/remote/create-dev-container)
  - [VSCode - Remote development in Containers](https://code.visualstudio.com/docs/remote/containers-tutorial)
    - VSCode安装extension: Remote - Containers
  - [IDEA - Docker](https://www.jetbrains.com/help/idea/docker.html)
  - [IDEA - 使用IDEA的Docker插件快速实现Docker镜像构建和部署](https://segmentfault.com/a/1190000022026960)

## Docker开发
  - [Language-specific guides](https://docs.docker.com/language/)
  - [Develop with Docker](https://docs.docker.com/develop/)

## PHP
  - 安装PHP运行环境
    - [实战多阶段构建 Laravel 镜像](https://yeasy.gitbook.io/docker_practice/image/multistage-builds/laravel)
    - [使用 Docker Compose 搭建了一套 LNMP 环境](https://github.com/khs1994-docker/lnmp)
    - [使用Docker部署LNMP+Redis环境](https://github.com/voocel/docker-lnmp)
    - [docker-php-ext-install.md](https://gist.github.com/giansalex/2776a4206666d940d014792ab4700d80)
    - [Environment variables in Compose](https://docs.docker.com/compose/environment-variables/)
    - [My Simple Approach to using Docker and PHP](https://bitpress.io/simple-approach-using-docker-with-php/)
    - [laravel-demo](https://github.com/khs1994-docker/laravel-demo)
    
  - 安装PHP开发环境  
    - [Laradock is a full PHP development environment based on Docker](https://laradock.io/)
    - [PHPDocker](https://phpdocker.io/)
    - [DevDock](https://github.com/iMacken/DevDock)
    - [Docker Development Environment for PHP Apps](https://rodrigodelimavieira.com/docker-development-environment-for-php-apps-cju2vko5r000csms18kzo1tgl)
    - [Setting up PHP, PHP-FPM and NGINX for local development on DockerA primer on PHP on Docker under Windows 10](https://www.pascallandau.com/blog/php-php-fpm-and-nginx-on-docker-in-windows-10/)

  - lamp
    - [docker-lamp](https://hub.docker.com/r/mattrayner/lamp)
      - [github](https://github.com/mattrayner/docker-lamp)
  - lemp | lnmp
    - [novice](https://hub.docker.com/r/novice/lemp)
      - [github](https://github.com/novice79/lemp)
    - [adhocore](https://hub.docker.com/r/adhocore/lemp) 
      - [github](https://github.com/adhocore/docker-lemp)
    - [lnmp](https://hub.docker.com/r/2233466866/lnmp)
    - [宝塔Linux面板](https://hub.docker.com/r/btpanel/btpanel)
    
## Kubernetes 初步
  - 使用 Docker Desktop 可以很方便的启用 Kubernetes: https://yeasy.gitbook.io/docker_practice/setup/docker-desktop
  - [Docker Desktop for Mac/Windows 开启 Kubernetes](https://github.com/AliyunContainerService/k8s-for-docker-desktop)

