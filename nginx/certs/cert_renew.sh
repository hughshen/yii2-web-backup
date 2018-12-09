#!/bin/bash

CURRENTPATH=`pwd`
SCRIPTDIR=$(cd `dirname $0` && pwd)
PROJECTDIR=$(cd ${SCRIPTDIR}/../../ && pwd)

cd ${SCRIPTDIR}/ssl

python acme_tiny.py --account-key account.key --csr domain.csr --acme-dir ${SCRIPTDIR}/challenges/ > signed.crt || exit
wget -O - https://letsencrypt.org/certs/lets-encrypt-x3-cross-signed.pem > intermediate.pem
cat signed.crt intermediate.pem > chained.pem

cd $PROJECTDIR
source ${PROJECTDIR}/.env

docker restart ${NGINX_NAME}

cd $CURRENTPATH
exit 0
