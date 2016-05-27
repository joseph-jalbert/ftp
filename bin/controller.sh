#!/usr/bin/env bash

terminus auth login $PANTHEON_USER --password=$PANTHEON_PASSWORD

if [ -n "${RUN_NIGHTLY_BUILD}" ]; then

        terminus site set-connection-mode --mode=sftp --site=$PANTHEON_SITE_NAME --env=dev
        terminus site clone-content --site=$PANTHEON_SITE_NAME --from-env=live --to-env=dev --yes
        terminus wp --site=$PANTHEON_SITE_NAME --env=dev plugin activate mm-wp-cli
        terminus wp --site=$PANTHEON_SITE_NAME --env=dev mm add_compatibility_plugin
        terminus wp --site=$PANTHEON_SITE_NAME --env=dev mm set_blacklists
        terminus wp plugin activate wp-migrate-db-pro wp-migrate-db-pro-media-files wp-migrate-db-pro-cli --site=$PANTHEON_SITE_NAME --env=dev
        #terminus wp migratedb push $DESTINATION_WPDBMIGRATE_PRO_STRING --media=compare-and-remove --site=$PANTHEON_SITE_NAME --env=dev
        terminus site set-connection-mode --mode=git --site=$PANTHEON_SITE_NAME

    elif [ $1 = "master" ]; then

        grunt deploy --wpEngineTarget=production --sourceRepoBranch=master

    elif [ $1 = "development" ]; then

        grunt deploy --sourceRepoBranch=development

fi