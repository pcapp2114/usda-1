set -e

mysqldump -u ${MYSQL_USER} -p${MYSQL_PASSWORD} ${MYSQL_DATABASE} > /home/${MYSQL_DATABASE}-$(date '+%Y-%m-%d')
