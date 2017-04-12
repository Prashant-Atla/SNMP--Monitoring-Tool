<html>
<h1> ASSIGNMENT 1 </h1>
<style>
table { 

color: #333; /* Lighten up font color */
font-family: Helvetica, Arial, sans-serif; /* Nicer font */
width: 640px; 
height:200px;
border-collapse: 
collapse; border-spacing: 1; 
}
td, th { border: 1px solid #CCC; height: 30px; } /* Make cells a bit taller */

th {
background: #F3F3F3; /* Light grey background */
font-weight: bold; /* Make sure they're bold */
}

p{
font-family:"Times new Roman";
font-size:20px;
}
tr {
background: #ffffff; /* Lighter grey background */
text-align: center; /* Center our text */
}
h1{
text-align:center;
color:#4a78b5;
font-size:20px
}

</style>
<!--sends the value--->
<body>

<?php

$path=getcwd();
$conf=substr($path,0,strripos($path,'/')+1)."db.conf";
$doc= file("$conf");
$read = fopen($conf,"r");
while (!feof($read))
{
$line=fgets($read);
for($i=0;$i<=4;$i++)
{
$credentials=explode('"',$doc[$i]);
$values[$i]= "$credentials[1]";
}
$host = $values[0];
$port = $values[1];
$database = $values[2];
$username = $values[3];
$password = $values[4];
}
$con =  mysql_connect("$host:$port","$username","$password");
if(!$con)
  {
  die ('could not connect: .'. mysql_error());
  }
#echo "connected to mysql server<br>";
mysql_select_db("$database",$con)
or die("could not select"); 

$result=mysql_query("SELECT * FROM interfaceinfo") or die(mysql_error());
echo '<table  solid black  align="center">
<tr>
<th>ID</th>
<th>IP</th>
<th>PORT</th>
<th>COMMUNITY</th>
<th>interface index</th>
<th>interface name</th>
</tr>';
while($rowdata=mysql_fetch_array($result))
{
echo "<tr>";
echo "<td>".$rowdata['id']."</td>";
echo "<td>". $rowdata['ip']. "</td>";
echo "<td> " . $rowdata['port']."</td>";
echo "<td>" . $rowdata['community']."</td>";
echo" <td>".$rowdata['interfaceindex']."</td>";
echo"<td>".$rowdata['interfacename']."</td>";
echo"</tr>";
}
echo"</table>";

?>

<div style = "position: middle;" >
<form action="graph1.php" method="post">

	<h1 >Enter device credentials with index to view the graph</h1>
	<input type="text" name="ip" placeholder="The ip address"><br>
	<input type="text" name="port" placeholder="port"><br>
	<input type="text" name="community" placeholder="community"><br>
	<input type="text" name="interfaceindex" placeholder="index"><br>
	<input type="submit" name="submit" value="ENTER">

</div>
</form>
</body>
</html>
