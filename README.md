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