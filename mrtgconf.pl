#!/usr/bin/perl
use DBI;
use FindBin '$Bin';
#connect to mysql server
my $db_conf_path = substr($Bin,0,rindex($Bin,'/')+1)."db.conf";
#print"yybdsbkfnskn $db_conf_path\n";
open FILE, "$db_conf_path" or die $!;
my @data = <FILE>;
my @host = split('"',$data[0]);
my @port = split('"',$data[1]);
my @database = split('"',$data[2]);
my @username = split('"',$data[3]);
my @password = split('"',$data[4]);
###assign to individual variables########
$dsn = "dbi:mysql:$database[1];$host[1];$port[1]";
###perl dbi connect
$dbh = DBI->connect($dsn,$username[1],$password[1]) or die "unable to connect: $DBI::errstr\n";
###prepare the query
$query1 = $dbh->prepare("select * from DEVICES");
$query1->execute;
#mrtg#
system("sudo apt-get -y install mrtg");
system("sudo updatedb && locate mrtg");
system("sudo mkdir /var/www/mrtg");
system("chmod -R 777 /etc/apache2/apache2.conf");
while (@row = $query1->fetchrow_array())
{
$values="$row[3]"."@"."$row[1]:$row[2]";

push(@values,$values);
}
$doc='/etc/apache2/apache2.conf';
open($file,$doc)or die "could not open file'$doc'$!";
$doc_contents = do{local $/; <$file>};
if($doc_contents =~m/Alias/)
{
print"";
}
else
{$doc1 = '/etc/apache2/apache2.conf';
open($file1,">>",$doc1) or die "could not open '$file1'$!";
print $file1 "Alias /mrtg /var/www/mrtg/
<Directory /var/www/mrtg/>
        	Options None
        	AllowOverride None
        	Require all granted
		Allow from all
	</Directory> 
	ServerName locahost:80 \n";	
	
close $file1;
}
close $file;
system('sudo cfgmaker --global "WorkDir: /var/www/mrtg" --global "Options[_]: growright" --global "RunAsDaemon: Yes" --global "Interval: 5"'."@values > /var/www/mrtg/mrtg.cfg");
system("sudo indexmaker /var/www/mrtg/mrtg.cfg > /var/www/mrtg/index.html");
system("sudo service apache2 restart");
system("sudo env LANG=C /usr/bin/mrtg /var/www/mrtg/mrtg.cfg --logging /var/log/mrtg.log");
system("sudo chmod -R 0777 /var/www/mrtg"); 
