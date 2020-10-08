# ExaBGP WEB

## 简单的ExaBGP WEB客户端

需要发送的路由数据存放在mysql数据库中。

新增加的路由，status 为 adding，需要删除的路由，将status改为 deleting。

blackip-exbgp.php 程序轮询数据库，如果有需要删除的，发送withdraw命令；如果有新增的，发送announce route命令。

web目录为简单的管理界面，其中login.php是登录程序，简单修改即可使用。

参考资料：

[使用ExaBGP发送BGP路由信息和清洗DDoS流量](https://github.com/bg6cq/ITTS/blob/master/security/bgp/exabgp/README.md)


## Simple ExaBGP WEB Client

The routing data that needs to be sent is stored in the mysql database.

For the newly added route, the status is adding. For the route to be deleted, change the status to deleting.

The blackip-exbgp.php program polls the database. If there are any items to be deleted, send the withdraw command; if there are new ones, send the announce route command.

The web directory is a simple management interface, and login.php is the login program, which can be used after simple modification.

Reference materials:

[Use ExaBGP to send BGP routing information and clean DDoS traffic](https://github.com/bg6cq/ITTS/blob/master/security/bgp/exabgp/README.md)
[Dapphp\Radius - A pure PHP RADIUS client based on the SysCo/al implementation](https://github.com/dapphp/radius)

## Instalation

Exabgp service installation is not covered here, so you'll have to search it on GitHub OK?!

Process described bellow was done using a Debian9 server, but fell free to update it for other distro.

### Install Apache Web Server
```
root@debian:/home/# apt update
root@debian:/home/# apt install apache2
```
Apache should be OK and available at http://[your_server_ip]

### Install MariaDB 
```
root@debian:/home/# apt install mariadb-server
root@debian:/home/# mysql_secure_installation  (this will prompt you some questions, like root password, remove anonymous users, etc, so make your choices)
```
Acess MariaDB database, create database blackip and blackip-exabgp user (you can change blackip-exabgp name if you want)

```
root@debian:/home/# mysql -u root -p

MariaDB [(none)]> create database blackip;
Query OK, 1 row affected (0.001 sec)

MariaDB [(none)]> use blackip;
MariaDB [blackip]> GRANT ALL ON blackip.* TO 'blackip-exabgp'@'localhost' IDENTIFIED BY 'password_here' WITH GRANT OPTION;
MariaDB [blackip]> flush privileges;
MariaDB [blackip]> exit
```
Run blackip.sql script to fill blackip database
```
root@debian:/home/# mysql -u blackip-exabgp -p blackip < blackip.sql
```
We are done with MariaDB, now install PHP

### Install PHP
```
root@debian:/home/# apt install php libapache2-mod-php php-mysql
```

### Modify Apache to prefer PHP pages
```
root@debian:/home/# nano /etc/apache2/mods-enabled/dir.conf
```
This file should be like this
```
<IfModule mod_dir.c>
        DirectoryIndex index.php index.html index.cgi index.pl index.xhtml index.htm
</IfModule>
```

### Install ExaBGP WEB

Download repo and put its content at /var/www/ directory

Configure virtual host

```
root@debian:/etc/apache2/sites-enabled# nano blackip-exabgp.conf
```
It should be something like this
```
<VirtualHost *:80>
        
        DocumentRoot /var/www/html/blackip-exabgp

        ErrorLog ${APACHE_LOG_DIR}/blackip-exabgp-error.log
        CustomLog ${APACHE_LOG_DIR}/blackip-exabgp-access.log combined

</VirtualHost>
```
Restart Apache
```
sudo systemctl restart apache2
```
Configure exabgp web parameters like authentication method, database password, router IP, etc.
```
root@debian:/var/www/html/blackip-exabgp# nano config.php
```
Configure blackip-exabgp to run with exabgp.service by adding line bellow at process service-dynamic in exabgp.conf
```
run /usr/bin/php /var/www/html/blackip-exabgp/blackip-exabgp.php;
```
Restart exaBGP
```
root@debian:/var/www/html/blackip-exabgp# systemctl restart exabgp
```
That's it! ExaBGP WEB should be running and available at http://[your_server_ip]
