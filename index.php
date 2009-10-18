<?php 

#
# Load up files
#
require_once('config.php');
require_once('setup.php');
require_once('functions.php'); 


#
# Route URLS
#
if ($_POST['action'] == 'create') {
  $person_id = $_REQUEST['person_id'];
  $project_id = $_REQUEST['project_id'];
  $startdate = $_REQUEST['startdate'];
  
  create_entry($person_id, $project_id, $startdate);

} elseif ($_POST['action'] == 'update') {
  $entry_id = $_REQUEST['entry_id'];
  $project_id = $_REQUEST['project_id'];
  $startdate = $_REQUEST['startdate'];
  
  update_entry($entry_id, $project_id, $startdate);

} elseif ($_POST['action'] == 'delete') {
  $entry_id = $_REQUEST['entry_id'];
  
  delete_entry($entry_id);

} elseif ($_POST['action'] == 'reorder') {
  $new_order = $_REQUEST['new_order'];
  reorder_entries($new_order);

} elseif ($_POST['action'] == 'add_project') {
  $project_name = $_REQUEST['project_name'];
  $project_url = $_REQUEST['project_url'];
  add_project($project_name, $project_url);

} elseif ($_POST['action'] == 'park_project') {
  $project_id = $_REQUEST['project_id'];
  park_project($project_id);

} elseif ($_POST['action'] == 'unpark_project') {
  $project_id = $_REQUEST['project_id'];
  unpark_project($project_id);

} elseif ($_GET['project_id']) {
  $project_id = $_REQUEST['project_id'];
  require_once('edit_project.php');
  
} elseif ($_POST['action'] ==  'edit_project') {
  $project_id = $_REQUEST['project_id'];
  $new_name = $_POST['project_name'];
  $new_url = $_POST['project_url'];
  $new_parked = $_POST['parked'];
  
  edit_project($project_id, $new_name, $new_url, $new_parked);
  
} elseif ($_GET['person_id']) {
  $person_id = $_REQUEST['person_id'];
  require_once('edit_person.php');
  
} elseif ($_POST['action'] == 'add_person') {
  $person_name = $_REQUEST['person_name'];
  $person_long_name = $_REQUEST['person_long_name'];
  $person_role = $_REQUEST['person_role'];
  add_person($person_name, $person_long_name, $person_role);

} elseif ($_POST['action'] ==  'edit_person') {
  $person_id = $_REQUEST['person_id'];
  $new_name = $_POST['person_name'];
  $new_long_name = $_POST['person_long_name'];
  $new_role = $_POST['person_role'];
  
  edit_person($person_id, $new_name, $new_long_name, $new_role);
  
} elseif ($_GET['action'] == 'list_latest_changes') {
  list_latest_changes(get_changes());
} else {
  require_once('display.php');
}



/* close MySQL connection */
mysql_close();












?>