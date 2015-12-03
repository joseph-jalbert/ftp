#!/usr/bin/env bash

if hash terminus 2>/dev/null; then

    terminus auth login $PANTHEON_USER --password=$PANTHEON_PASSWORD

    if [ $1 = "master" ]; then
        terminus site deploy --site=$PANTHEON_SITE_NAME --env=test --note="Updating"
        terminus site deploy --site=$PANTHEON_SITE_NAME --env=live --note="Updating"
    else
        terminus site deploy --site=$PANTHEON_SITE_NAME --env=test --note="Updating"
    fi

else
    echo 'Please install terminus see https://github.com/pantheon-systems/cli'
fi

terminus wp plugin install wp-redis --site=$PANTHEON_SITE_NAME --env=live

terminus wp plugin activate wp-redis --site=$PANTHEON_SITE_NAME --env=live



#if [ -n "${RUN_NIGHTLY_BUILD}" ]; then

   #terminus wp migratedb push https://dev-abogados.pantheon.io R9pP7iBxg2XqWJFNIzK1GUfpC9z7B0f0aFBBNSzg --site=$PANTHEON_SITE_NAME --env=live --media=compare-and-remove
   #terminus wp migratedb push https://test-abogados.pantheon.io TnQq+huu2LXUTKF3JGQcY9N4DJNo5/+TB6sTzZq2 --site=$PANTHEON_SITE_NAME --env=live --media=compare-and-remove
   #terminus wp migratedb push https://abogadosdr.wpengine.com YGgf9+4YRIVPP/4/WSXb2pYOD2vH9psrJb4ohCk9 --site=$PANTHEON_SITE_NAME --env=live --media=compare-and-remove
   #terminus wp migratedb push https://abogadosdr.staging.wpengine.com YB/hrl+1Nj4buEEaDhHq6SzD5R4k6oVxJLva6kcK --site=$PANTHEON_SITE_NAME --env=live --media=compare-and-remove


#fi

