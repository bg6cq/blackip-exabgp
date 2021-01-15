<?php

include "top.php";

$limit=" limit 10 ";

if(isset($_SESSION["isadmin"]) && ($_SESSION["isadmin"]==1))  
{
	if(isMobile())
		$limit=" limit 100 ";
	else $limit=" limit 2000 ";

	// Shown when the user clicks on modify hiperlink
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
		echo "<h3>Modify Route $prefix/$len</h3>";
		echo "<form action=database-interaction/modify_route.php METHOD=post>";
		echo "<input name=modi_do type=hidden>";
		echo "<input name=id type=hidden value=\"$id\">";
		echo "<input name=s type=hidden value=\"$s\">";
		echo "<table class='stripped'>";
		echo "<thead>";
		echo "<tr><th>Parameters</th><th style='text-align: center;'>Values</th></tr>";
		echo "</thead>";
		echo "<tbody>";
		echo "<tr><td>IP </td><td style='text-align: center;'>$prefix</td></tr>";
		echo "<tr><td>Prefix length </td><td style='text-align: center;'>$len</td></tr>";
		echo "<tr><td>Expire Date </td><td><input name=end value=\"$end\"></td></tr>";
		echo "<tr><td>Message </td><td><input name=msg size=100 value=\"$msg\"></td></tr>";
		echo "</tbody>";
		echo "</table><br/>";
		echo "<input name=modi_do type=submit value='modify'>";
		echo "</form>";

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
	// Add route case
	if(isset($_REQUEST["add"])) 
	{ 
		?>
			<p>
			<form action="/database-interaction/add_route.php" method="POST">
				<h3>Add Route</h3>
				<table class="stripped">
					<thead>
						<tr>
							<th>Options</th>
							<th>Inputs</th>
							<th>Examples</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Prefix</td>
							<td><input type="text" name="prefix" required></td>
							<td>192.168.0.25</td>
						</tr>
						<tr>
							<td>Prefix Length</td>
							<td><input type="text" name="len" value="32" maxlength="2" required></td>
							<td>32</td>
						</tr>
						<tr>
							<td>Next Hop</td>
							<td><input type="text" name="next_hop" value="<?php echo $routerip;?>" required/></td>
							<td><?php echo $routerip;?></td>
						</tr>
						<tr>
							<td>Effective Days</td>
							<td><input type="text" name="day" required></td>
							<td>7</td>
						</tr>
						<tr>
							<td>Other</td>
							<td><input type="text" name="other"></td>
							<td>local-preference 65000 community [100:100]</td>
						</tr>
						<tr>
							<td>Message</td>
							<td><input type="text" name=msg></td>
							<td>Intranet Server</td>
						</tr>
					</tbody>
				</table>
				<br/>
				<input type="submit" name="add_do" value="Add Route">
			</form>
		<?php
	}
	else {
		echo "<a href=index.php?add><button>Add route</button></a>";
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
echo "<table class='stripped full-width'>";
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
			echo "<td><a href=database-interaction/delete_route.php?del&id=$r[0]&s=$s  onclick=\"return confirm('delete $r[1]/$r[2] ?');\">delete</a>&nbsp;";
			echo "<a href=index.php?modi&id=$r[0]&s=$s>modify</a>";
			echo "</td>";
        };
	echo "</tr>\n";
}
echo "</table>";

include "bottom.php";

?>