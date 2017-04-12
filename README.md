
# SNMP--Monitoring-Tool
MRTG
#####
TO run install and to configure mrtg run the mrtgconf.pl in the folder.
perl mrtgconf.pl
##########################################################################
the tool will read the values in the table 'DEVICES' and will display the bit rate for selected device.
##########################################################################
pre- requisities
#################

properly install

	mysql-server
	apache2
	snmpd	
	snmp
	php5

with required permissions

the directory sould have required permission ie et2536-prat15
##############

the following are the components need to be installed 

sudo apt-get update
sudo apt-get install apache2
sudo apt-get insatll snmp
sudo apt-get insatll snmpd
sudo apt-get install libdbi-perl
sudo apt-get install libpango1.0-dev 
sudo apt-get install  libxml2-dev
sudo apt-get install libsnmp-perl 
sudo apt-get install libsnmp-dev 
sudo apt-get install libnet-snmp-perl
sudo apt-get install rrdtool
sudo apt-get install rrdtool-dbg
sudo apt-get install php5-rrd
sudo apt-get install php5-snmp
sudo perl -MCPAN -e 'install Net::SNMP'
sudo perl -MCPAN -e 'install Net::SNMP::Interfaces'
sudo perl -MCPAN -e 'install RRD::Simple'
###############################################3
Instructions
																										
================================================================================================================================

1.To Configure the mrtg tool run the mrtgconf file which is in the same folder. This should be run with necesary permissions(sudo)

					sudo perl mrtgconf.pl
*MRTG does not accept if the multiple devices have the same interfaces. even though they have different ip address and community.



	 Save and exit. Start the daemon by
	 
	 			 sudo service cron start

3. Wait for atleast 15 to 20 minutes  for values to get updated.

4. From your browser go to the folder assignment1

5. The list of available devices which are curently being monitored is displayed.

6.the list of all devices with interfaces are displayed click on each individual graph to get the montly daily and yearly graph.



