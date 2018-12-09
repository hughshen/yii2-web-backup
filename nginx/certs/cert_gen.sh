#!/bin/bash

CURRENTPATH=`pwd`
SCRIPTDIR=$(cd `dirname $0` && pwd)
PROJECTDIR=$(cd ${SCRIPTDIR}/../../ && pwd)

source ${PROJECTDIR}/.env

mv ${PROJECTDIR}/nginx/conf.d/default.conf ${PROJECTDIR}/nginx/conf.d/default.conf.bak
cp -n ${PROJECTDIR}/nginx/conf.d/default.https.conf.example ${PROJECTDIR}/nginx/conf.d/default.https.conf
sed -i -e "s/main_host/$HTTPS_MAIN_HOST/g" ${PROJECTDIR}/nginx/conf.d/default.https.conf
sed -i -e "s/main_server_name/$HTTPS_SERVER_NAME/g" ${PROJECTDIR}/nginx/conf.d/default.https.conf

cd ${SCRIPTDIR}/ssl

# Create a Let's Encrypt account private key
openssl genrsa 4096 > account.key
# Generate a domain private key
openssl genrsa 4096 > domain.key

openssl req -new -sha256 -key domain.key -subj "/" -reqexts SAN -config <(cat /etc/ssl/openssl.cnf <(printf "[SAN]\nsubjectAltName=$SSL_DNS")) > domain.csr

# Download the acme-tiny script
wget -O acme_tiny.py https://raw.githubusercontent.com/diafygi/acme-tiny/master/acme_tiny.py
# Get a signed certificate
python acme_tiny.py --account-key ./account.key --csr ./domain.csr --acme-dir ${SCRIPTDIR}/challenges/ > ./signed.crt
# Download the intermediate certificate
wget -O - https://letsencrypt.org/certs/lets-encrypt-x3-cross-signed.pem > intermediate.pem
# Merge signed.crt and intermediate.pem
cat signed.crt intermediate.pem > chained.pem
# Download the root certificate and merge the intermediate certificate for OCSP Stapling
wget -O - https://letsencrypt.org/certs/isrgrootx1.pem > root.pem
cat intermediate.pem root.pem > full_chained.pem

openssl dhparam -out dhparams.pem 2048

openssl rand 48 > session_ticket.key

docker restart ${NGINX_NAME}

cd $CURRENTPATH
exit 0
