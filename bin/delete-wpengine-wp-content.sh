#!/usr/bin/env bash

set -x #echo on

USER_STAGING='forthepeopledr-staging-dep'
USER_PRODUCTION='forthepeopledr-production-dep'
HOST='sftp://45.33.17.22'
PORT='2222'
PASSWORD_PRODUCTION='QD38CTqhFojsqi'
PASSWORD_STAGING='4AL8DeuwwisRDo'


if [ $1 = "master" ]; then
   USER=$USER_PRODUCTION
   PASSWORD=$PASSWORD_PRODUCTION
else
    USER=$USER_STAGING
    PASSWORD=$PASSWORD_STAGING
fi

echo "DEPLOYING USING USERNAMEhol $USER"


lftp -u $USER,$PASSWORD $HOST -p $PORT  << EOF
rm -r wp-content/plugins
rm -r wp-content/themes
quit
EOF