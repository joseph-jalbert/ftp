#!/usr/bin/env bash

if [ "true" != "${RUN_NIGHTLY_BUILD}" ]; then
    git config --global push.default simple
    git config --global user.email circleci@forthepeople.com
    git config --global user.name "Circle CI"
    rm -rf node_modules
    npm install
    npm install -g grunt
    npm install -g grunt-cli
    sudo apt-get install lftp

fi

sudo curl https://github.com/pantheon-systems/cli/releases/download/0.9.3/terminus.phar -L -o /usr/local/bin/terminus
sudo chmod +x /usr/local/bin/terminus