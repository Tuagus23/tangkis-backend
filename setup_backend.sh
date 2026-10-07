#!/usr/bin/env bash
set -euo pipefail
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
TEMP="$ROOT/.laravel-base"
command -v php >/dev/null || { echo 'PHP 8.2+ tidak ditemukan di PATH.'; exit 1; }
command -v composer >/dev/null || { echo 'Composer tidak ditemukan di PATH.'; exit 1; }
rm -rf "$TEMP"
composer create-project laravel/laravel:^12.0 "$TEMP" --no-interaction --prefer-dist
( cd "$TEMP" && php artisan install:api --no-interaction )
cp -R "$TEMP"/. "$ROOT"/
cp -R "$ROOT/overlay/app/." "$ROOT/app/"
cp -R "$ROOT/overlay/database/." "$ROOT/database/"
cp -R "$ROOT/overlay/routes/." "$ROOT/routes/"
cp -R "$ROOT/overlay/tests/." "$ROOT/tests/"
rm -rf "$TEMP"
cat > "$ROOT/.env.tangkis.example" <<'EOT'
APP_NAME=Tangkis
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
LOG_CHANNEL=stderr
LOG_LEVEL=debug
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tangkis
DB_USERNAME=root
DB_PASSWORD=
EOT
echo 'Setup selesai. Jalankan: php artisan key:generate && php artisan migrate && php artisan serve'
