FROM composer as composer
COPY composer.json /app/
RUN set -x ; cd /app && \
    composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/ && \
    composer install \
                --ignore-platform-reqs \
                --no-interaction \
                --no-plugins \
                --no-scripts \
                --prefer-dist


FROM novice/lemp as orm

LABEL maintainer="skygreen2001" \
      email="skygreen2001@gmail.com"\
      version="1.0.0" \
      description="a framework designed to easy read and rapid develop for chinese delevoper"

ARG BB_PATH=/var/www/

COPY . ${BB_PATH}
COPY --from=composer /app/install/vendor/ ${BB_PATH}install/vendor/

RUN cd ${BB_PATH} && \
    mkdir -p upload && \
    chmod -R 777 upload && \
    mkdir -p log && \
    chmod -R 777 log

#设置alpine系统时区
ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime && echo ${TZ} > /etc/timezone

COPY install/docker/hub/lemp/init.sh /
COPY install/docker/hub/lemp/nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR ${BB_PATH}

EXPOSE 80
