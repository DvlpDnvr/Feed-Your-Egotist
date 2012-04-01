<?
	// CONNECTION VARS
	$root = 'http://www.feedyouregotist.com';
	
	// DB SETTINGS
	DEFINE('DBUSER','drewdahl_ego'); // DB USER NAME
	DEFINE('DBPASS','dvlpdnvr'); // DB PASSWORD
	DEFINE('DB','drewdahl_ego'); // DB
	
	// CONNECT TO DB
	$con = mysql_connect("localhost",DBUSER,DBPASS);
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	mysql_select_db(DB, $con);
?>