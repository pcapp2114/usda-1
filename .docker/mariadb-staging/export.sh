set -e

. /home/.env

FILE="/home/export-${MYSQL_DATABASE}-staging-$(date '+%Y-%m-%d').sql"
mysqldump -u "${MYSQL_USER}" -p"${MYSQL_PASSWORD}" "${MYSQL_DATABASE}" > "${FILE}"
aws s3 cp "${FILE}" s3://"${AWS_S3_BUCKET}"
mysql -u "${MYSQL_USER}" -p"${MYSQL_PASSWORD}" DROP DATABASE "${MYSQL_DATABASE}";
mysql -u "${MYSQL_USER}" -p"${MYSQL_PASSWORD}" CREATE DATABASE "${MYSQL_DATABASE}";


FILE="/home/export-${MYSQL_DATABASE}-qa-$(date '+%Y-%m-%d').sql"
aws s3 cp s3://"${AWS_S3_BUCKET}" "${FILE}"
mysqldump -u "${MYSQL_USER}" -p"${MYSQL_PASSWORD}" "${MYSQL_DATABASE}" < "${FILE}"
