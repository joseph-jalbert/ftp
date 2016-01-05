#!/usr/bin/env bash

if hash terminus 2>/dev/null; then

    terminus auth login $PANTHEON_USER --password=$PANTHEON_PASSWORD

    if [ $1 = "master" ]; then
        terminus site deploy --site=$PANTHEON_SITE_NAME --env=live --note="Updating"
        #only install it do not activate it it https://pantheon.io/docs/articles/wordpress/installing-redis-on-wordpress/#install-drop-in-plugin
        terminus wp plugin install wp-redis --site=$PANTHEON_SITE_NAME --env=live
        terminus wp plugin deactivate wp-redis --site=$PANTHEON_SITE_NAME --env=live
        terminus wp rewrite flush  --site=$PANTHEON_SITE_NAME --env=live
    else
        terminus site deploy --site=$PANTHEON_SITE_NAME --env=test --note="Updating"
    fi

else
    echo 'Please install terminus see https://github.com/pantheon-systems/cli'
fi

