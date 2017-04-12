<html>
<head>
<title>Probe Based Operations</title>
<link href="style.css" rel="stylesheet">
</head>

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


$ip=$_POST['ip'];
echo "ip is $ip\n";
$port=$_POST['port'];
echo"port is $port\n";

$community=$_POST['community'];
echo"community is $community\n";

$index=$_POST['interfaceindex'];

echo "index is $index\n";
#$ip = "192.168.184.25";
#$community = "testanm1";
#$index = "11";
echo "<center><h3>Traffic Analysis For $index -- $ip:$community</h3>";

$opts_d = array("--start", "-1d", "--vertical-label=bytes per second",
                 "DEF:inoctets=$path/rrdfile/$ip"."-"."$community.rrd:in$index:AVERAGE",
		 "DEF:outoctets=$path/rrdfile/$ip"."-"."$community.rrd:out$index:AVERAGE",

 		"AREA:inoctets#00FF00:In traffic",
		 "LINE1:outoctets#0000FF:Out traffic",
		 "--dynamic-labels",
			 "--title=Daily graph",
	  		 "--color=BACK#CCCCCC",      
		    	 "--color=CANVAS#CCFFFF",    
		    	 "--color=SHADEB#9999CC",
		"COMMENT:\\n",
		          "GPRINT:inoctets:LAST:Current In \: %6.2lf %SBps",
		         "COMMENT:  ", 
			 "GPRINT:outoctets:LAST:Current Out \: %6.2lf %SBps",
		         "COMMENT:\\n",       
			 "GPRINT:inoctets:MAX:Maximum In \: %6.2lf %SBps",
		         "COMMENT:  ",
			 "GPRINT:outoctets:MAX:Maximum Out \: %6.2lf %SBps",
		         "COMMENT:\\n", 
			 "GPRINT:inoctets:AVERAGE:Average In \: %6.2lf %SBps",
		         "COMMENT:  ",
			 "GPRINT:outoctets:AVERAGE:Average Out \: %6.2lf %SBps",
		         "COMMENT:\\n",

		);
		$ret_d = rrd_graph("$path/rrdfile/day$index.png", $opts_d);
		if( !$ret_d )
  		{
    		$err = rrd_error();
    		echo "rrd_graph() ERROR: $err\n";
  		}

$opts_w = array( "--start", "-1w", "--vertical-label=bytes per second",
                 "DEF:inoctets=$path/rrdfile/$ip"."-"."$community.rrd:in$index:AVERAGE",
		 "DEF:outoctets=$path/rrdfile/$ip"."-"."$community.rrd:out$index:AVERAGE",
		 
                 "AREA:inoctets#00FF00:In traffic",
		 "LINE1:outoctets#0000FF:Out traffic",
		 "--dynamic-labels",
			 "--title=Weekly graph",
	  		 "--color=BACK#CCCCCC",      
		    	 "--color=CANVAS#CCFFFF",    
		    	 "--color=SHADEB#9999CC",
		"COMMENT:\\n",
		          "GPRINT:inoctets:LAST:Current In \: %6.2lf %SBps",
		         "COMMENT:  ", 
			 "GPRINT:outoctets:LAST:Current Out \: %6.2lf %SBps",
		         "COMMENT:\\n",       
			 "GPRINT:inoctets:MAX:Maximum In \: %6.2lf %SBps",
		         "COMMENT:  ",
			 "GPRINT:outoctets:MAX:Maximum Out \: %6.2lf %SBps",
		         "COMMENT:\\n", 
			 "GPRINT:inoctets:AVERAGE:Average In \: %6.2lf %SBps",
		         "COMMENT:  ",
			 "GPRINT:outoctets:AVERAGE:Average Out \: %6.2lf %SBps",
		         "COMMENT:\\n",
		);
		$ret_w = rrd_graph("$path/rrdfile/week$index.png", $opts_w);
		if( !$ret_w )
  		{
    		$err = rrd_error();
    		echo "rrd_graph() ERROR: $err\n";
  		}


	$opts_m = array( "--start", "-1m", "--vertical-label=bytes per second",
                 "DEF:inoctets=$path/rrdfile/$ip"."-"."$community.rrd:in$index:AVERAGE",
		 "DEF:outoctets=$path/rrdfile/$ip"."-"."$community.rrd:out$index:AVERAGE",
		 
                 "AREA:inoctets#00FF00:In traffic",
		 "LINE1:outoctets#0000FF:Out traffic",
		 "--dynamic-labels",
			 "--title=Monthly graph",
	  		 "--color=BACK#CCCCCC",      
		    	 "--color=CANVAS#CCFFFF",    
		    	 "--color=SHADEB#9999CC",
		"COMMENT:\\n",
		          "GPRINT:inoctets:LAST:Current In \: %6.2lf %SBps",
		         "COMMENT:  ", 
			 "GPRINT:outoctets:LAST:Current Out \: %6.2lf %SBps",
		         "COMMENT:\\n",       
			 "GPRINT:inoctets:MAX:Maximum In \: %6.2lf %SBps",
		         "COMMENT:  ",
			 "GPRINT:outoctets:MAX:Maximum Out \: %6.2lf %SBps",
		         "COMMENT:\\n", 
			 "GPRINT:inoctets:AVERAGE:Average In \: %6.2lf %SBps",
		         "COMMENT:  ",
			 "GPRINT:outoctets:AVERAGE:Average Out \: %6.2lf %SBps",
		         "COMMENT:\\n",
		);
		$ret_m = rrd_graph("$path/rrdfile/month$index.png", $opts_m);
		if( !$ret_m )
  		{
    		$err = rrd_error();
    		echo "rrd_graph() ERROR: $err\n";
  		}


	$opts_y = array( "--start", "-1y", "--vertical-label=bytes per second",
                 "DEF:inoctets=$path/rrdfile/$ip"."-"."$community.rrd:in$index:AVERAGE",
		 "DEF:outoctets=$path/rrdfile/$ip"."-"."$community.rrd:out$index:AVERAGE",
		 
                 "AREA:inoctets#00FF00:In traffic",
		 "LINE1:outoctets#0000FF:Out traffic",
		 "--dynamic-labels",
			 "--title=Yearly graph",
	  		 "--color=BACK#CCCCCC",      
		    	 "--color=CANVAS#CCFFFF",    
		    	 "--color=SHADEB#9999CC",
		"COMMENT:\\n",
		          "GPRINT:inoctets:LAST:Current In \: %6.2lf %SBps",
		         "COMMENT:  ", 
			 "GPRINT:outoctets:LAST:Current Out \: %6.2lf %SBps",
		         "COMMENT:\\n",       
			 "GPRINT:inoctets:MAX:Maximum In \: %6.2lf %SBps",
		         "COMMENT:  ",
			 "GPRINT:outoctets:MAX:Maximum Out \: %6.2lf %SBps",
		         "COMMENT:\\n", 
			 "GPRINT:inoctets:AVERAGE:Average In \: %6.2lf %SBps",
		         "COMMENT:  ",
			 "GPRINT:outoctets:AVERAGE:Average Out \: %6.2lf %SBps",
		         "COMMENT:\\n",
		);
		$ret_y = rrd_graph("$path/rrdfile/year$index.png", $opts_y);
		if( !$ret_y )
  		{
    		$err = rrd_error();
    		echo "rrd_graph() ERROR: $err\n";
  		}

echo "<img src=image.php?graphs=$path/rrdfile/day$index.png>";
echo "<img src=image.php?graphs=$path/rrdfile/week$index.png>";
echo "<img src=image.php?graphs=$path/rrdfile/month$index.png>";
echo "<img src=image.php?graphs=$path/rrdfile/year$index.png>";

?>

</form>
</div>
</body>
</html> 



