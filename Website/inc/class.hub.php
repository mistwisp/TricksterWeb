<?php
	session_start();
	error_reporting( E_ERROR | E_PARSE );
	include( "class/connection.class.php" );
	include( "class/site.class.php" );
	include( "class/ranking.class.php" );
	include( "class/news.class.php" );
	include( "class/user.class.php" );
	include( "class/admin.class.php" );
	include( "config.php" );
	//====================================================================================================================
	$dbinfo     	  = [ "dbserv" => $dbserv, "dbname" => $dbname, 		  "dbuser" => $dbuser, "dbpass" => $dbpass ];
	$dbinfo_acc 	  = [ "dbserv" => $dbserv, "dbname" => $dbname_acc, 	  "dbuser" => $dbuser, "dbpass" => $dbpass ];
	$dbinfo_trickster = [ "dbserv" => $dbserv, "dbname" => $dbname_trickster, "dbuser" => $dbuser, "dbpass" => $dbpass ];
	//====================================================================================================================
	$siteclass 		 = new Site( $dbinfo );
	$rankclass 		 = new Ranking ( $dbinfo );
	$newsclass 		 = new News ( $dbinfo );
	$userclass 		 = new User( $dbinfo );
	$adminclass 	 = new Admin( $dbinfo );
	$dbconnacc 		 = new Connection( $dbinfo_acc );
	$dbconntrickster = new Connection( $dbinfo_trickster );