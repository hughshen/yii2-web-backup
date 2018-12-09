#!/bin/bash

CURRENTPATH=`pwd`
SCRIPTDIR=$(cd `dirname $0` && pwd)
PROJECTDIR=$(cd ${SCRIPTDIR}/../ && pwd)

# echo $PROJECTDIR
cd $PROJECTDIR

cp -n ${PROJECTDIR}/.env.example ${PROJECTDIR}/.env
source ${PROJECTDIR}/.env

cp -n ${PROJECTDIR}/nginx/conf.d/default.conf.example ${PROJECTDIR}/nginx/conf.d/default.conf
sed -i -e "s/main_server_name/$DEFAULT_SERVER_NAME/g" ${PROJECTDIR}/nginx/conf.d/default.conf

docker pull composer

# Global require
docker run --rm --interactive --tty \
    --volume $PROJECTDIR/www/.composer:/tmp \
    composer global require "fxp/composer-asset-plugin:^1.2.0"

# Require
docker run --rm --interactive --tty \
    --volume $PROJECTDIR/www:/app \
    --volume $PROJECTDIR/www/.composer:/tmp \
    composer install

# Optimizing Composer Autoloader
docker run --rm --interactive --tty \
    --volume $PROJECTDIR/www:/app \
    --volume $PROJECTDIR/www/.composer:/tmp \
    composer dumpautoload -o

# docker-compose build
# docker-compose up -d

cd $CURRENTPATH
exit 0
