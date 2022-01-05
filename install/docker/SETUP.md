# Docker 安装 betterlife

## 设置权限

  - 由于是通过volume映射到容器内，容器内文件夹的权限由宿主机文件夹的权限决定，因此需要在宿主机本地执行以下指令赋予读写权限。
  - 也可在容器运行起来后，进入容器内应用的文件位置执行以下指令赋予读写权限。
  - 根路径下运行

    ```
      sudo mkdir -p upload
      sudo chmod -R 777 upload
      sudo mkdir -p log
      sudo chmod -R 777 log
    ```

## 复制容器文件到本地

  - 该操作为可选项，根据自己的需求决定: 
    - 复制安装的composer包文件到本地: docker cp orm:/var/www/html/orm/install/vendor/ $(pwd)/install/

## 本地开发调试应用

  - 使用调试工具: Xdebug
  - 使用开发工具: Visual Studio Code
  - 修改配置文件: install/docker/Dockerfile
    - 取消调试注释部分: debug with xdebug 
  - 在 Visual Studio Code 里开启调试模式
  
## 上传到Docker Hub

### 创建镜像

- 制作[ skygreen2001/orm ]镜像提交到Docker Hub
  - 只需要制作一个镜像，Online Redis Manager和apache服务器使用一个镜像。
  - 本功能用于制作演示Online Redis Manager的 Docker镜像，提交到Docker Hub，公开对外的Dockerfile文件及环境搭建。
  - 创建Online Redis Manager镜像提交到Docker Hub

    ```
      docker build -t orm --target=orm -f install/docker/hub/lemp/Dockerfile .
      docker tag orm skygreen2021/orm
      docker login -u [YOUR-USER-NAME]
      docker push skygreen2021/orm
    ```

    - hub上查询提交的镜像: https://hub.docker.com  -> 搜索:  skygreen2021/orm
    - 如果本地已经存在，需要更新镜像: docker pull skygreen2021/orm
    - 运行推送的hub: docker run -dp 80:80 --name orm skygreen2021/orm
