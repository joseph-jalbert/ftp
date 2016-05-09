#!/usr/bin/env bash

SITE='forthepeople'
LOG_FILE='/home/vagrant/root-install.log'

trap ctrl_c INT
ctrl_c() {
  tput bold >&3; tput setaf 1 >&3; echo -e '\nCancelled by user' >&3; echo -e '\nCancelled by user'; tput sgr0 >&3; if [ -n "$!" ]; then kill $!; fi; exit 1
}

log2file() {
  exec 3>&1 4>&2
  trap 'exec 2>&4 1>&3' 0 1 2 3
  exec 1>$LOG_FILE 2>&1
}

log2file

echo "---- update apt-get ----" >&3
apt-get update

echo "---- setup database ----" >&3
debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'

echo "---- install libraries for php5.5 ----" >&3
apt-get install -y vim curl python-software-properties

echo "---- add apt-repository for nginx, php5.5 and latest nodejs ----" >&3
add-apt-repository ppa:rtcamp/nginx -y
add-apt-repository -y ppa:ondrej/php5
curl -sL https://deb.nodesource.com/setup | sudo bash -


echo "---- update apt-get stuffs ----" >&3
apt-get update -y && apt-get upgrade -y && apt-get autoremove -y

echo "---- install libraries ----" >&3
apt-get install -y mysql-server
apt-get install -y git
apt-get install -y php5-common
apt-get install -y php5-mysqlnd
apt-get install -y php5-xmlrpc
apt-get install -y php5-curl
apt-get install -y php5-gd
apt-get install -y php5-imagick
apt-get install -y php5-cli
apt-get install -y php-pear
apt-get install -y php5-dev
apt-get install -y php5-imap
apt-get install -y php5-mcrypt
apt-get install -y nodejs
apt-get install -y nodejs-legacy
apt-get install -y npm
apt-get install -y curl
apt-get install -y nginx-custom
apt-get install -y php5-redis
apt-get install -y redis-server
apt-get install -y nginx
apt-get install -y php5-fpm

mkdir -p /var/log/xdebug

echo "---- installing and configuring Xdebug ----" >&3
apt-get install -y php5-xdebug
cat << EOF | tee -a /etc/php5/fpm/conf.d/xdebug.ini
xdebug.cli_color=1
xdebug.show_local_vars=1
xdebug.default_enable=1
xdebug.remote_enable=1
xdebug.remote_handler=dbgp
xdebug.remote_host=localhost
xdebug.remote_port=9000
xdebug.remote_autostart=0
xdebug.ide_key='VAGRANT'
xdebug.remote_connect_back=on
xdebug.remote_log="/var/log/xdebug/xdebug.log"
EOF


echo "---- download composer ----" >&3
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

echo "---- setup root directory ----" >&3
mkdir -p /vagrant/
rm -rf /var/www/html
mkdir -p /var/www/html
ln -fs /vagrant/web /var/www/html/web


echo "---- turn on error reporting ----" >&3
sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php5/fpm/php.ini
sed -i "s/display_errors = .*/display_errors = On/" /etc/php5/fpm/php.ini

echo "---- raising upload limits ----" >&3
sed -i "s/upload_max_filesize =.*/upload_max_filesize = 500M/"  /etc/php5/fpm/php.ini
sed -i "s/post_max_size =.*/post_max_size = 500M/"  /etc/php5/fpm/php.ini


echo "---- set nginx configurations ----" >&3

rm /etc/nginx/sites-available/default
rm /etc/nginx/sites-enabled/default
wget https://gist.githubusercontent.com/matgargano/969fb4ea16c1abaad66fdb781750b3aa/raw/4979ede17d852128d523ec7fe1742e77f17b29cb/default.conf -O /etc/nginx/sites-available/default
sudo ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

sed -i "s/{{DOMAIN}}/$SITE.dev/" /etc/nginx/sites-available/default
sed -i 's/index /index index.php' /etc/nginx/sites-available/default


echo "---- restart php and nginx ----" >&3
service nginx restart
service php5-fpm restart

echo "---- create database ----" >&3
mysqladmin -uroot -proot create "$SITE"
cd /vagrant

echo "---- install wp-cli ----" >&3
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
mv wp-cli.phar /usr/local/bin/wp
chmod u+rwx /usr/local/bin/wp

echo "---- install nodejs, npm & grunt ----" >&3

apt-get install -y nodejs
npm install -g grunt-cli
echo "---- create .htaccess ----" >&3

touch /vagrant/.htaccess
chown www-data .htaccess

echo "---- done with root stuff, logged to $LOG_FILE ----" >&3