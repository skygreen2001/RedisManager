#!/bin/sh

until nc -z -w 1 orm 9000 || nc -z -w 1 127.0.0.1 9000 ; do
  echo "orm PHP is unavailable - sleeping"
  sleep 2
done

echo "orm PHP is up - executing command"

exec nginx -g 'daemon off;'