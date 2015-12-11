#!/usr/bin/env bash

set -x #echo on

if [ $1 = "master" ]; then
   USER=$WPENGINE_FTP_PRODUCTION_USER
   PASSWORD=$WPENGINE_FTP_PRODUCTION_PASSWORD
else
    USER=$WPENGINE_FTP_STAGING_USER
    PASSWORD=$WPENGINE_FTP_STAGING_PASSWORD
fi

echo "DEPLOYING USING USERNAME $USER"


lftp -u $USER,$PASSWORD sftp://$WPENGINE_FTP_SERVER -p $WPENGINE_FTP_PORT  << EOF
rm -r wp-content/plugins
rm -r wp-content/themes
quit
EOF