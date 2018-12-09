#!/bin/bash

CURRENTPATH=`pwd`
SCRIPTDIR=$(cd `dirname $0` && pwd)
PROJECTDIR=$(cd ${SCRIPTDIR}/../ && pwd)

cd $PROJECTDIR
source ${PROJECTDIR}/.env

# Init and symlink
docker exec ${PHP_NAME} php init --env=${INIT_ENV} --overwrite=n
# Data migration
docker exec ${PHP_NAME} php yii migrate --interactive=0

cd $CURRENTPATH;
exit 0
