<?php

include "top.php";

?>
<p>
本系统使用ExaBGP程序，把DDoS攻击的IP信息通过BGP协议发送给上游路由器引流，<br>
将DDoS流量引流到一台Linux服务器，流量经过Linux服务器过滤后再送给下游路由器，<br>
从而完成DDoS流量清洗工作。
</p>

<p>
This system uses the ExaBGP program to send the IP information of the DDoS attack to the upstream router through the BGP protocol for drainage,<br>
DDoS traffic is directed to a Linux server, and the traffic is filtered by the Linux server before being sent to the downstream router,<br>
So as to complete the DDoS traffic cleaning work.
</p>

相关资料：(Relevant information)<br>
<a href=https://github.com/Exa-Networks/exabgp target=_blank>https://github.com/Exa-Networks/exabgp</a><br>
<a href=https://github.com/bg6cq/blackip-exabgp target=_blank>https://github.com/bg6cq/blackip-exabgp</a><br>
<a href=https://github.com/bg6cq/ITTS/blob/master/security/bgp/exabgp/README.md target=_blank>https://github.com/bg6cq/ITTS/blob/master/security/bgp/exabgp/README.md</a><p>

<p>
下图是工作原理示意: (The following figure shows the working principle) 
</p>
<img src=DDoS.png width=800>
