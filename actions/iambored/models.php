<?php
if (!defined('LCMS')) exit;

class Activity extends Database {

	public function __construct() {
		parent::__construct();
	}

	public function getActivities($time,$weather,$location,$profile) {

		// read in time
		$time = time();
		// set how far in the future to find events
		$futurelimit = time() + (60 * 60 * 2);

		// season?

		// read in weather
		// based on keywords: sunny,cloudy,rainy,stormy,snowy
		$weather = 'sunny';

		// read in locations
		// ($location should be coordinates pair)

		// read in user info
		// should be array of ($age,$interests,$likes)

	}


}

 
?>