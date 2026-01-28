#!/usr/bin/env bash
set -Eeuo pipefail

# ------------------------
# Config
# ------------------------
BACKUP_DIR="/mnt/private-content/backups"
PREFIX="dbdump-dev"
DATETIME="$(date +'%Y%m%d-%H%M%S')"
SQLFILE="${BACKUP_DIR}/${PREFIX}-${DATETIME}.sql"
LOGFILE="${BACKUP_DIR}/${PREFIX}-${DATETIME}.log"
LATEST_LOG="${BACKUP_DIR}/${PREFIX}-latest.log"

DRUPAL_ROOT="/var/www/html/web"
DRUSH="drush"

# TLS CA for mariadb-dump (adjust if you later pin a specific CA)
DUMP_EXTRA="--ssl-ca=/etc/ssl/certs/ca-certificates.crt"

# ------------------------
# Logging / error handling
# ------------------------
mkdir -p "$BACKUP_DIR"

# Log stdout+stderr to file AND console
exec > >(tee -a "$LOGFILE") 2>&1
ln -sf "$LOGFILE" "$LATEST_LOG"

timestamp() { date -Is; }

# Print the command that is about to run
log_cmd() {
  echo "[$(timestamp)] RUN: $*"
}

# Run a command, logging it first
run() {
  log_cmd "$@"
  "$@"
}

# Trap errors: show failing command + line + exit code
on_error() {
  local exit_code=$?
  local line_no=${BASH_LINENO[0]}
  echo "[$(timestamp)] ERROR: command failed (exit=$exit_code) at line $line_no"
  echo "[$(timestamp)] ERROR: last command: ${BASH_COMMAND}"
  echo "[$(timestamp)] Log file: $LOGFILE"
  exit "$exit_code"
}
trap on_error ERR

echo "[$(timestamp)] Starting nightly maintenance + DB dump"
echo "[$(timestamp)] Drupal root: $DRUPAL_ROOT"
echo "[$(timestamp)] SQL file:    $SQLFILE"
echo "[$(timestamp)] Log file:    $LOGFILE"

cd "$DRUPAL_ROOT"

# ------------------------
# Sanity checks
# ------------------------
run command -v "$DRUSH"

# ------------------------
# Clear caches
# ------------------------
run "$DRUSH" cr

# ------------------------
# Truncate tables
# ------------------------
truncate_tables=(
	cachetags
	cache_access_policy
  cache_bootstrap
  cache_config
  cache_container
  cache_data
  cache_default
  cache_discovery
  cache_dynamic_page_cache
  cache_entity
  cache_feeds_download
  cache_file_mdm
  cache_flexible_permissions
  cache_group_permission
  cache_menu
  cache_page
  cache_render
  cache_signal
  cache_toolbar
  cache_ultimate_cron_logger
  watchdog
)

for t in "${truncate_tables[@]}"; do
  run "$DRUSH" sql:query "TRUNCATE \`$t\`;"
done

# ------------------------
# Dump DB (plain .sql so we can sed it)
# ------------------------
run "$DRUSH" sql:dump --gzip=0 --result-file="$SQLFILE" --extra-dump="$DUMP_EXTRA"

# Ownership/permissions
run chown www-data:www-data "$SQLFILE"
run chmod 640 "$SQLFILE"

# ------------------------
# Normalize SQL
# ------------------------
run sed -i 's/utf8mb4_0900_ai_ci/utf8_general_ci/g' "$SQLFILE"
run sed -i 's/CHARSET=utf8mb4/CHARSET=utf8/g' "$SQLFILE"

# ------------------------
# Compress
# ------------------------
run gzip -f "$SQLFILE"
run chown www-data:www-data "${SQLFILE}.gz"
run chmod 640 "${SQLFILE}.gz"

echo "[$(timestamp)] SUCCESS: Created ${SQLFILE}.gz"
echo "[$(timestamp)] Done."
