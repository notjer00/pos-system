#!/bin/sh
# Entrypoint script to substitute $PORT into nginx config and start nginx

# Default to 8000 if PORT not set
PORT="${PORT:-8000}"

# Replace the port in nginx config
sed -i "s/listen 8000;/listen ${PORT};/g" /etc/nginx/http.d/default.conf
sed -i "s/listen \[::\]:8000;/listen [::]:${PORT};/g" /etc/nginx/http.d/default.conf

# Start nginx
exec nginx -g "daemon off;"