$ErrorActionPreference = 'Stop'
$Root = Split-Path -Parent $MyInvocation.MyCommand.Path
$Temp = Join-Path $Root '.laravel-base'
$StarterReadme = Join-Path $Root 'README.md'
$StarterReadmeCopy = Join-Path $Root '.README.tangkis-source.md'
Copy-Item -Force $StarterReadme $StarterReadmeCopy

Write-Host "== Tangkis Backend setup ==" -ForegroundColor Cyan

if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    throw "PHP tidak ditemukan di PATH. Install PHP 8.2+ dan buka terminal baru."
}
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    throw "Composer tidak ditemukan di PATH. Install Composer lalu buka terminal baru."
}

$phpVersion = (& php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;").Trim()
Write-Host "PHP: $phpVersion"

if (Test-Path $Temp) {
    Remove-Item -Recurse -Force $Temp
}

Write-Host "Membuat Laravel 12..." -ForegroundColor Yellow
& composer create-project laravel/laravel:^12.0 $Temp --no-interaction --prefer-dist
if ($LASTEXITCODE -ne 0) { throw "composer create-project gagal." }

Push-Location $Temp
Write-Host "Memasang API routes..." -ForegroundColor Yellow
& php artisan install:api --no-interaction
if ($LASTEXITCODE -ne 0) { throw "php artisan install:api gagal." }
Pop-Location

Write-Host "Menyalin file Tangkis..." -ForegroundColor Yellow
Copy-Item -Recurse -Force (Join-Path $Temp '*') $Root

$Overlay = Join-Path $Root 'overlay'
Copy-Item -Recurse -Force (Join-Path $Overlay 'app') (Join-Path $Root 'app')
Copy-Item -Recurse -Force (Join-Path $Overlay 'database') (Join-Path $Root 'database')
Copy-Item -Recurse -Force (Join-Path $Overlay 'routes') (Join-Path $Root 'routes')
Copy-Item -Recurse -Force (Join-Path $Overlay 'tests') (Join-Path $Root 'tests')

# Restore the starter README after the Laravel skeleton copy.
Copy-Item -Force $StarterReadmeCopy (Join-Path $Root 'README.tangkis.md')
@'
# Tangkis Backend

Laravel 12 REST API for the Tangkis Android app. See `README.tangkis.md` for staging/deployment notes and `API.md` for the HTTP contract.
'@ | Set-Content -Encoding UTF8 (Join-Path $Root 'README.md')
Remove-Item -Force $StarterReadmeCopy

$EnvExample = @'
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

# Railway staging (set these in Railway Variables, not in Git):
# DB_HOST=${{MySQL.MYSQLHOST}}
# DB_PORT=${{MySQL.MYSQLPORT}}
# DB_DATABASE=${{MySQL.MYSQLDATABASE}}
# DB_USERNAME=${{MySQL.MYSQLUSER}}
# DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
'@
$EnvExample | Set-Content -Encoding UTF8 (Join-Path $Root '.env.tangkis.example')

Remove-Item -Recurse -Force $Temp
Write-Host "Setup selesai." -ForegroundColor Green
Write-Host "Langkah berikut: php artisan key:generate ; php artisan migrate ; php artisan serve"
