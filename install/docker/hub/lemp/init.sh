#!/bin/bash

log () {
    printf "[%(%Y-%m-%d %T)T] %s\n" -1 "$*"
}
# echo () {
#     log "$@"
# }

# ignore hidden files
if [ -z "$(ls /var/www)" ]
then
    log "empty www directory, create index.php"
    nginx_v=`nginx -v 2>&1`
    mysql_v=`mysql -V`
    cat <<EOT > /var/www/index.php
<?php
phpinfo();
EOT
elif [ -f "/var/www/public/index.php" ]
then
    log "set doc root dir=/var/www/public"
    sed 's@root /var/www;@root /var/www/public;@g' -i /etc/nginx/conf.d/default.conf
else
    log "normal php www dir containing files, skip"
fi
php-fpm -F &
# pid_php=$!
nginx &
# pid_nginx=$!

# no pgrep && ps
while [ 1 ]
do
    sleep 2
    SERVICE="nginx"
    if ! pidof "$SERVICE" >/dev/null
    then
        log "$SERVICE stopped. restart it"
        "$SERVICE" &
        # send mail ?
    fi
    SERVICE="php-fpm"
    if ! pidof "$SERVICE" >/dev/null
    then
        log "$SERVICE stopped. restart it"
        "$SERVICE" -F &
        # send mail ?
    fi
done