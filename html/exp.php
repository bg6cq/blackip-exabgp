<?php

include "top.php";

$limit=" limit 10 ";
if( isset($_SESSION["isadmin"]) && ($_SESSION["isadmin"]==1))  {
	if(isMobile())
		$limit=" limit 100 ";
	else $limit=" limit 2000 ";
}

$q="select count(*) from blackip where status='deleted'";
$result = $mysqli->query($q);
$r=$result->fetch_array();
echo "Withdrawn route: ".$r[0]."<p>";

@$s=$_REQUEST["s"];
$q="select id,prefix,len,next_hop,other,start,end,msg from blackip where status='deleted' order by inet_aton(prefix)".$limit;
if($s=="s")
	$q="select id,prefix,len,next_hop,other,start,end,msg from blackip where status='deleted' order by start desc".$limit;
else if($s=="e")
	$q="select id,prefix,len,next_hop,other,start,end,msg from blackip where status='deleted' order by end desc".$limit;
else if($s=="n")
	$q="select id,prefix,len,next_hop,other,start,end,msg from blackip where status='deleted' order by next_hop".$limit;
else if($s=="m")
	$q="select id,prefix,len,next_hop,other,start,end,msg from blackip where status='deleted' order by msg".$limit;
$result = $mysqli->query($q);
echo "<table class='stripped full-width'>";
echo "<tr><th>Serial number</th><th><a href=exp.php>IP</a></th><th><a href=exp.php?s=n>next_hop</a></th><th>other</th><th><a href=exp.php?s=s>start</a></th><th><a href=exp.php?s=e>end</a></th>";
echo "<th><a href=exp.php?s=m>MSG</a></th>";
echo "</tr>\n";
$count=0;
while($r=$result->fetch_array()) {
	$count++;
	echo "<tr><td align=center>";
	echo $count;
	echo "</td><td>";
	echo "$r[1]/$r[2]";
	echo "</td><td>";
	echo $r[3];
	echo "</td><td>";
	echo $r[4];
	echo "</td><td>";
	echo $r[5];
	echo "</td><td>";
	echo $r[6];
	echo "</td><td>";
	echo $r[7];
	echo "</td>";
	echo "</tr>\n";
}
echo "</table>";

?>


