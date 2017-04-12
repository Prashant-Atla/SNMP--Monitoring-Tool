#!/usr/bin/perl
use DBI;
use Net::SNMP;
use Net::SNMP::Interfaces;
use Data::Dumper;
use RRD::Simple();
use FindBin qw($Bin);
#connect to mysql server:
my $db_conf_path = substr($Bin,0,rindex($Bin,'/')+1)."db.conf";
open FILE, "$db_conf_path" or die $!;
my @data = <FILE>;
my @host = split('"', $data[0]);
my @port = split('"', $data[1]);
my @database = split('"', $data[2]);
my @username = split('"', $data[3]);
my @password = split('"', $data[4]);
#####assiging to an individual variable####################
$dsn = "dbi:mysql:$database[1];$host[1];$port[1]";
########perl DBI connect
$dbh = DBI->connect($dsn,$username[1],$password[1]) or die "unable to connect: $DBI::errstr\n";
########prepare the query
$query1 = $dbh->prepare("select * from DEVICES");
$query1->execute;


while (@row = $query1->fetchrow_array)
 {
    $id=$row[0];
    $ip=$row[1];
    $port=$row[2];
    $community=$row[3];
##poll
 $session = Net::SNMP->session( -hostname => $ip, -port => $port, -community => $community, -timeout => 1);
$c=0;
$loop=0;
$speed=0;
$oper=0;
@updatestr=();
@in=();
@out=();
@ind=();
@name=();
@interfacelist=();
$valueifname=0;
@filter=();
@sort=();
     my $interfaces = Net::SNMP::Interfaces->new(Hostname => $ip, Port => $port, Community => $community);

if(!defined $interfaces)
   {
print"unresponsive\n";
next;
}
else
 {
 @a=$interfaces->if_indices();
 @interfacelist=sort{$a<=> $b} @a;
 }   
foreach $c(@interfacelist)
	{
$result = $session->get_request("1.3.6.1.2.1.2.2.1.3."."$c","1.3.6.1.2.1.2.2.1.5."."$c","1.3.6.1.2.1.2.2.1.8."."$c");
	$loop=$result->{"1.3.6.1.2.1.2.2.1.3."."$c"};
	$speed=$result->{"1.3.6.1.2.1.2.2.1.5."."$c"};
	$oper=$result->{"1.3.6.1.2.1.2.2.1.8."."$c"};
	if($loop!=24 && $speed>0 && $oper==1)
   		{
    		push(@filter,$c);

   		}
 }
foreach $b(@filter){
	$inoctet = "1.3.6.1.2.1.2.2.1.10."."$b";
	$outoctet = "1.3.6.1.2.1.2.2.1.16."."$b";
	$system1=$session->get_request($inoctet);
	$system2=$session->get_request($outoctet);
	$valuesin = $system1->{$inoctet};

	$valuesout = $system2->{$outoctet};
	$ifname = "1.3.6.1.2.1.2.2.1.2."."$b";
	#$ifname="1.3.6.1.2.1.31.1.1.1.1."."$b";
	$result1=$session->get_request($ifname);
	$valueifname = $result1->{$ifname};

     	push(@inlot,$valuesin);
     	push(@outlot,$valuesout);
     	push(@ind,$b);
	$nameif = $b."-"."$valueifname";
	push(@name,$nameif);
		}
@sort = sort@ind;
print "how is it @inlot\n";

$i=join(",",@sort);

$ifn=join(",",@name);
##rrd
$time= time();
$rrdfile = "$Bin/rrdfile/$ip-$community.rrd";
$rrd = RRD::Simple->new(file => $rrdfile, cf =>[ qw(AVERAGE)], default_dstype =>"COUNTER", on_missing_ds =>"add");
############# rrd update and create #############
if(! -e "$Bin/rrdfile/$ip-$community.rrd")
{
$rrd->create("$Bin/rrdfile/$ip-$community.rrd","mrtg",testDS=>"COUNTER");
}
$count=0;
foreach $l(@sort)
{

push(@updatestr,"in$l" => "$inlot[$count]","out$l" => "$outlot[$count]");
print "how is it now jijoso @updatestr\n"; 
$count++;
}
$rrd->update($rrdfile,@updatestr);
############ done ###############################
#info to table
$table2 = "CREATE TABLE IF NOT EXISTS interfaceinfo (
id int(255) NOT NULL AUTO_INCREMENT,
ip varchar(40),
port varchar(40),
community varchar(40),
interfaceindex varchar(255),
interfacename varchar(255),
PRIMARY KEY (id) )
ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
$query3 = $dbh->prepare($table2);
$query3->execute;
$know2 = $dbh->prepare("SELECT * FROM interfaceinfo WHERE ip='$ip' && community= '$community'");
$know2->execute;
my@row2=$know2->fetchrow_array();
if($row2[1] eq "$ip" && $row2[3] eq "$community")
{
 $update2=$dbh->prepare("UPDATE interfaceinfo SET interfaceindex = '$i', interfacename= '$ifn' WHERE ip= '$ip' && community = '$community' ");
    $update2->execute; 
}

else
{
$insert1 = $dbh->prepare("INSERT INTO interfaceinfo (ip, port, community, interfaceindex, interfacename) VALUES ( '$ip','$port','$community', '$i' , '$ifn') ");
 $insert1->execute;
}
  } 
