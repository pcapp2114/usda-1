set -e

. /home/.env

FILE="/home/export-${MYSQL_DATABASE}-$(date '+%Y-%m-%d').sql"
RESOURCE="/${AWS_S3_BUCKET}/${FILE}"
CONTENT_TYPE="application/x-mysqldumpp"
DATE=$(date -R)
STRING_TO_SIGN="PUT\n\n${CONTENT_TYPE}\n${DATE}\n${RESOURCE}"
SIGNATURE=$(echo -en "${STRING_TO_SIGN}" | openssl sha1 -hmac "${AWS_SECRET_ACCESS_KEY}" -binary | base64)

#mysqldump -u ${MYSQL_USER} -p${MYSQL_PASSWORD} ${MYSQL_DATABASE} > /home/${MYSQL_DATABASE}-$(date '+%Y-%m-%d').sql
echo "mysqldump -u ${MYSQL_USER} -p${MYSQL_PASSWORD} ${MYSQL_DATABASE}" >> "${FILE}"

curl -X PUT -T "${FILE}" \
  -H "Host: ${AWS_S3_BUCKET}.s3.amazonaws.com" \
  -H "Date: ${DATE}" \
  -H "Content-Type: ${CONTENT_TYPE}" \
  -H "Authorization: AWS ${AWS_ACCESS_KEY_ID}:${SIGNATURE}" \
  "https://${AWS_S3_BUCKET}.s3.amazonaws.com/${FILE}"
