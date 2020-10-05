<?php

include "top.php";

$limit=" limit 10 ";

if(isset($_SESSION["isadmin"]) && ($_SESSION["isadmin"]==1))  
{
	if(isMobile())
		$limit=" limit 100 ";
	else $limit=" limit 2000 ";

	if(isset($_REQUEST["del"]))  
	{   
		//del ip
		$id= intval($_REQUEST["id"]);
		$q="update blackip set end=now(),status='deleting' where id=?";
		$stmt=$mysqli->prepare($q);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->close();
	}

	if(isset($_REQUEST["add_do"])) 
	{  
		//add new
		$prefix = $_REQUEST["prefix"];
		$len = $_REQUEST["len"];
		$next_hop = $_REQUEST["next_hop"];
		$other= $_REQUEST["other"];
		$day = intval($_REQUEST["day"]);
		$msg = $_REQUEST["msg"];

		if(0) 
		{
			$q="select count(*) from mylist where inet_aton(ip) = (inet_aton(?) & inet_aton(mask))";
			$stmt=$mysqli->prepare($q);
			$stmt->bind_param("s",$prefix);
			$stmt->execute();
			$stmt->bind_result($count);
			$stmt->fetch();
			$stmt->close();
		}

		$count=1;
		
		if($count==0)  
			echo "Error: IP ".$prefix." not in mylist, check your input<p>";
		else 
		{
			$q = "insert into blackip (status,prefix,len,next_hop,other,start,end,msg) values ('adding',?,?,?,?,now(),date_add(now(),interval ? day),?)";
			$stmt=$mysqli->prepare($q);
			$stmt->bind_param("sissis",$prefix,$len,$next_hop,$other,$day,$msg);
			$stmt->execute();
			sleep(2);
		}
	}

	if(isset($_REQUEST["modi"])) 
	{  
		$id=$_REQUEST["id"];
		@$s=$_REQUEST["s"];
		$q="select prefix,len, end, msg from blackip where id=?";
		$stmt=$mysqli->prepare($q);
		$stmt->bind_param("s",$id);
		$stmt->execute();
		$stmt->bind_result($prefix, $len, $end, $msg);
		$stmt->fetch();

		// Printing form
		echo "<form action=index.php METHOD=get>";
		echo "<input name=modi_do type=hidden>";
		echo "<input name=id type=hidden value=\"$id\">";
		echo "<input name=s type=hidden value=\"$s\">";
		echo "<table><tr><td>IP: </td><td>$prefix</td><td>len: </td><td>$len</td></tr>";
		echo "<tr><td>expire date: </td><td><input name=end value=\"$end\"></td><td>news: </td><td><input name=msg size=100 value=\"$msg\"></td></tr>";
		echo "</table><input name=modi_do type=submit value='modify'>";
		echo "</form><p>";

		$stmt->close();
	}

	if(isset($_REQUEST["modi_do"])) 
	{ 
		$id= $_REQUEST["id"];
		$end = $_REQUEST["end"];
		$msg = $_REQUEST["msg"];
		$q="update blackip set end=?, msg = ? where id =?";
		$stmt=$mysqli->prepare($q);
		$stmt->bind_param("sss", $end, $msg, $id);
		$stmt->execute();
		$stmt->close();
	}
}

$q="select count(*) from blackip where status='added'";
$result = $mysqli->query($q);
$r=$result->fetch_array();
echo "Effective route: ".$r[0].", ";

$q="select count(*) from blackip where status='adding' or status='deleting'";
$result = $mysqli->query($q);
$r=$result->fetch_array();
echo "Update routing: ".$r[0]." ";

if( isset($_SESSION["isadmin"]) && ($_SESSION["isadmin"]==1))  
{
	if(isset($_REQUEST["add"])) 
	{ 
		// add
		?>
			<p>
			<form action=index.php>
				Add routing: <br>
				Prefix: <input name=prefix><br>
				preflen: <input name=len value=32 size=3><br>
				next_hop: <input name=next_hop value="<?php echo $routerip;?>"/><br>
				Effective days: <input name=day value=10 size=2><br>
				other: <input name=other> example: local-preference 65000 community [100:100]<br>
				news: <input name=msg><br>
				<input type=submit name=add_do value="Add to">
			</form>
		<?php
	}
	else {
		echo "<a href=index.php?add>add</a>";
	}
} 

echo "<p>";
@$s=$_REQUEST["s"];
$q="select id,prefix,len,next_hop,other,start,end,msg from blackip where status='added' order by inet_aton(prefix)".$limit;
if($s=="s")
	$q="select id,prefix,len,next_hop,other,start,end,msg from blackip where status='added' order by start desc".$limit;
else if($s=="e")
	$q="select id,prefix,len,next_hop,other,start,end,msg from blackip where status='added' order by end desc".$limit;
else if($s=="n")
	$q="select id,prefix,len,next_hop,other,start,end,msg from blackip where status='added' order by next_hop".$limit;
else if($s=="m")
	$q="select id,prefix,len,next_hop,other,start,end,msg from blackip where status='added' order by msg".$limit;
$result = $mysqli->query($q);
echo "<table border=1 cellspacing=0>";
echo "<tr><th>Serial number</th><th><a href=index.php>IP</a></th><th><a href=index.php?s=n>next_hop</a></th><th>other</th><th><a href=index.php?s=s>start</a></th><th><a href=index.php?s=e>end</a></th>";
echo "<th><a href=index.php?s=m>MSG</a></th>";
if( isset($_SESSION["isadmin"]) && ($_SESSION["isadmin"]==1))
	echo "<th>cmd</th>";
echo "</tr>\n";
$count=0;
while($r=$result->fetch_array()) {
	$count++;
	echo "<tr><td align=center>";
	echo $count;
	echo "</td><td>";
	echo "<a href=search.php?str=$r[1] target=_blank>$r[1]</a>/$r[2]";
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
		if( isset($_SESSION["isadmin"]) && ($_SESSION["isadmin"]==1)) 
		{
			echo "<td><a href=index.php?del&id=$r[0]&s=$s  onclick=\"return confirm('delete $r[1]/$r[2] ?');\">delete</a>&nbsp;";
			echo "<a href=index.php?modi&id=$r[0]&s=$s>modify</a>";
			echo "</td>";
        };
	echo "</tr>\n";
}
echo "</table>";

include "bottom.php";

?>