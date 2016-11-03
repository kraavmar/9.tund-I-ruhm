<?php

	require("../../config.php");
	// functions.php
	//var_dump($GLOBALS);
	
	$database = "if16_marikraav";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
	
	//Iga klassiga tulevad need 2 rida juurde
	require("User.class.php");
	$User = new User($mysqli);
	
	require("Interest.class.php");
	$Interest = new Interest($mysqli);
	
	require("Car.class.php");
	$Car = new Car($mysqli);
	
	require("Helper.class.php");
	$Helper = new Helper($mysqli);

	
	// see fail, peab olema kõigil lehtedel kus 
	// tahan kasutada SESSION muutujat
	session_start();

?>