#!/usr/bin/env bash

if hash terminus 2>/dev/null; then

    terminus auth login $PANTHEON_USER --password=$PANTHEON_PASSWORD

    if [ $1 = "master" ]; then

        #only install it do not activate it it https://pantheon.io/docs/articles/wordpress/installing-redis-on-wordpress/#install-drop-in-plugin
        terminus site set-connection-mode --mode=sftp --site=$PANTHEON_SITE_NAME
        terminus wp plugin install wp-redis --site=$PANTHEON_SITE_NAME --env=dev
        terminus site set-connection-mode --mode=git --site=$PANTHEON_SITE_NAME
        terminus wp rewrite flush  --site=$PANTHEON_SITE_NAME --env=live
        terminus site deploy --site=$PANTHEON_SITE_NAME --env=live --note="Updating"
        terminus wp plugin deactivate wp-redis --site=$PANTHEON_SITE_NAME --env=live
    else
        terminus site deploy --site=$PANTHEON_SITE_NAME --env=test --note="Updating"
    fi

else
    echo 'Please install terminus see https://github.com/pantheon-systems/cli'
fi

