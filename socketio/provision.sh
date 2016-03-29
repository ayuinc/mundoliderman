# Install build essentials
yum install gcc gcc-c++ make openssl-devel

# Install NodeJS repository
rpm -Uvh https://rpm.nodesource.com/pub_4.x/el/7/x86_64/nodesource-release-el7-1.noarch.rpm

# Install NodeJS
yum install nodejs

chmod -R 777 /var/www/mundoliderman/public_html/socketio

ln -s /var/www/mundoliderman/public_html/socketio /opt

cd /opt/socketio

npm install

cp /var/www/mundoliderman/public_html/socketio/socketio.service /etc/systemd/system/

systemctl enable socketio
sysmtemctl restart socketio

