<?php

/* Get Projects */
//$projects = get_projects('unparked'); 
$active_projects = get_live_tickets('status!=closed&type=In Development&type=Live or Ongoing&type=Pending&group=component');

//$bizdev_projects = get_live_tickets('status!=closed&type=Active Biz Dev&group=component&groupdesc=1');

/*'component=Labs: Planning&component=Labs: Government&component=Labs: Other&component=For: OpenGeo&component=For: Livable Streets&component=For: TOPP&component=For: Gotham Schools&group=component&order=type'

$query_stub = 'status!=closed&type!=Back Burner&type!=Triage&type!=Active Biz Dev&order=type';
*/

/*
$planning = get_live_tickets('component=Labs: Planning');
$government = get_live_tickets('component=Labs: Government');
$other = get_live_tickets('component=Labs: Other');
$opengeo = get_live_tickets('component=For: OpenGeo');
$livablestreets = get_live_tickets('component=For: Livable Streets');
$topp = get_live_tickets('component=For: TOPP');
$gothamschools = get_live_tickets('component=For: Gotham Schools');
global $allprojects;
$allprojects = array_merge($transportation,$planning,$government,$other,$opengeo,$livablestreets,$topp,$gothamschools);

foreach($allprojects as $key => $value) {
  if($value == "") {
    unset($allprojects[$key]);
  }
}
$allprojects = array_values($allprojects); 
*/


//$parked_projects = get_projects('parked');
//$all_projects = get_projects('all');

/* Setup Calendar */
$mondays = setup_mondays();

/* Get People */
$people = get_people();

/* Get recent changes */
$changes = get_changes('50');
	
?>
