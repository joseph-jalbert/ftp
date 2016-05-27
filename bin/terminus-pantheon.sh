#!/usr/bin/env bash

if hash terminus 2>/dev/null; then

    terminus auth login $PANTHEON_USER --password=$PANTHEON_PASSWORD

    if [ $1 = "git" ]; then

        terminus site set-connection-mode --mode=git --site=$PANTHEON_SITE_NAME

    elif [ $1 = "master" ]; then

        #only install wp-redis do not activate it it https://pantheon.io/docs/articles/wordpress/installing-redis-on-wordpress/#install-drop-in-plugin

        terminus site set-connection-mode --mode=sftp --site=$PANTHEON_SITE_NAME --env=dev
        terminus wp plugin delete wp-redis --site=$PANTHEON_SITE_NAME --env=dev
        terminus wp plugin install wp-redis --site=$PANTHEON_SITE_NAME --env=dev
        sleep 5 #does this need a short timeout to register that the code has been updated?
        terminus site code commit --site=$PANTHEON_SITE_NAME --env=dev --message='add wp-redis to codebase'
        terminus site deploy --site=$PANTHEON_SITE_NAME --env=test --note="Updating"
        terminus site deploy --site=$PANTHEON_SITE_NAME --env=live --note="Updating from test"
        terminus wp plugin deactivate wp-redis --site=$PANTHEON_SITE_NAME --env=live
        terminus wp rewrite flush  --site=$PANTHEON_SITE_NAME --env=live
        terminus site set-connection-mode --mode=git --site=$PANTHEON_SITE_NAME

    else
        terminus site deploy --site=$PANTHEON_SITE_NAME --env=test --note="Updating"
    fi

else
    echo 'Please install terminus see https://github.com/pantheon-systems/cli'
fi

