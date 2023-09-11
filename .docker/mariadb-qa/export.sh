set -e

. /home/.env

FILE="/home/export-${MYSQL_DATABASE}-qa-$(date '+%Y-%m-%d').sql"
mysqldump -u "${MYSQL_USER}" -p"${MYSQL_PASSWORD}" "${MYSQL_DATABASE}" > "${FILE}"
aws s3 cp "${FILE}" s3://"${AWS_S3_BUCKET}"
