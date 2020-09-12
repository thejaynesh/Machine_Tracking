# MachineTracking
Digital Machine Information Tracking

# To deploy the project
<ul>
  <li>Install LAMP/WAMP server and GIT on machine</li>
  <li>Goto htdocs folder inside LAMP's root directory. In Ubuntu usually at /opt/lampp/htdocs</li>
  <li>Run git clone command</li>
  <li>Run git fetch --all</li>
  <li>Turn on Apache server and Mysql server from LAMPP.(usually at sudo ./opt/lampp/manager-linux-x64.run, followed by switching on with GUI)</li>
  <li>Open php my admin on browser</li>
  <li>create database named computers and import sql file in it</li>
  <li>In pdo.php file replace username and password with system configured password</li>
  <li>On browser use URL localhost/MachineTracking to access the site. On other PC use <localip>/MachineTracking. (If you changed the name of folder created after git clone, replace MachineTracking with your folder name)</li>
</ul>
