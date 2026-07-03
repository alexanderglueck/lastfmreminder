# nginx front for the php-fpm app. Build AFTER lastfmreminder:latest — it copies the
# already-built public/ (including the Vite assets) straight out of that image, so
# there's no shared volume and no stale-assets problem.
FROM lastfmreminder:latest AS app

FROM nginx:alpine
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY --from=app /app/public /app/public
