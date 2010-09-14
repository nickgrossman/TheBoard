<?php

/* Get Projects */
$projects = get_projects('unparked');
$parked_projects = get_projects('parked');
$all_projects = get_projects('all');

/* Setup Calendar */
if(isset($_GET['startdate'])) {
  $mondays = get_mondays($_GET['startdate']);
} else {
  $lastweek = date("Y-m-d", mktime(0,0,0,date('m'), date('d')-7, date('Y') ));
  /* get calendar starting with previous week */
  $mondays = get_mondays($lastweek);
}

/* Get People */
$people = get_people();

/* Get recent changes */
$changes = get_changes('50');
	
?>