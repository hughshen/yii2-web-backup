#!/bin/bash

CURRENTPATH=`pwd`
SCRIPTDIR=$(cd `dirname $0` && pwd)
PROJECTDIR=$(cd ${SCRIPTDIR}/../ && pwd)

source ${PROJECTDIR}/.env

CURRENTDATE=`date +%Y%m%d`

# MySQL
# Backup
docker exec ${DBDRIVE_NAME} /usr/bin/mysqldump -u ${DBDRIVE_USER} --password=${DBDRIVE_PASS} ${DBDRIVE_DB} | gzip > ${PROJECTDIR}/mysql/backup/${CURRENTDATE}.sql.gz
# Restore
# cat backup.sql | docker exec -i MYSQL_CONTAINER /usr/bin/mysql -u root --password=your_password DATABASE

# Postgres
# Backup
# docker exec ${PGSQL_NAME} pg_dump -U ${PGSQL_USER} -d ${PGSQL_DB} -f /backup/${CURRENTDATE}.sql
# docker exec ${PGSQL_NAME} gzip /backup/${CURRENTDATE}.sql

# Restore
# docker exec ${PGSQL_NAME} psql -U ${PGSQL_USER} -d ${PGSQL_DB} -f /backup/web.sql

# 0 3 * * * /path/to/backup.sh >/dev/null 2>&1
