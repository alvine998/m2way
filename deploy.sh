#!/usr/bin/env bash
set -euo pipefail

COLOR="${1:-}"
if [[ "$COLOR" != "blue" && "$COLOR" != "green" ]]; then
    echo "Usage: ./deploy.sh <blue|green>"
    exit 1
fi

if [[ "$COLOR" == "blue" ]]; then
    OTHER="green"
else
    OTHER="blue"
fi

echo "==> Deploying to [$COLOR] environment ..."

echo "==> Building production image for [$COLOR] ..."
docker compose build app-"$COLOR"

echo "==> Starting [$COLOR] container ..."
docker compose up -d app-"$COLOR"

echo "==> Waiting for [$COLOR] to be ready ..."
sleep 5

echo "==> Running migrations ..."
docker compose exec -T app-"$COLOR" php artisan migrate --force

echo "==> Switching nginx traffic to [$COLOR] ..."
cp docker/nginx/conf.d/upstream-"$COLOR".conf docker/nginx/conf.d/active-upstream.conf
docker compose exec nginx nginx -s reload

echo "==> Traffic switched to [$COLOR]!"

if docker compose ps --format json app-"$OTHER" 2>/dev/null | grep -q running; then
    echo "==> Stopping previous [$OTHER] container ..."
    docker compose stop app-"$OTHER"
fi

echo "==> Deployment to [$COLOR] complete!"
