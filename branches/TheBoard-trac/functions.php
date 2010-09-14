<?php
#
# Find Monday - prints out the previous monday.
# Scott Hurring - scott at hurring dot com
#
function get_monday($startdate = null, $week_offset = 0) {
  if ($startdate) {
    $startdate = strtotime($startdate);
  } else {
    $startdate = time();
  }

  $dow = date("w", $startdate);
  //print "Today is dow $dow (Monday is 1)\n";
  
  // How many days ago was monday?
  $offset = ($dow -1);
  if ($offset <0) {
      $offset = 6;
  }
  //print "Offset = $offset\n";
    
  if ($week_offset > 0) {
    $offset = $offset - ($week_offset * 7);
  }

  $monday = date("Y-m-d", mktime(0,0,0,date('m', $startdate), date('d', $startdate)-$offset, date('Y', $startdate) ));
  
  //print "Previous monday is $monday";
  
  return $monday;
}


#
# Get mondays
#
function get_mondays($startdate = null) {
  $mondays = Array();
  
  for ($i = 0; $i < NUM_WEEKS; $i++) {
    array_push($mondays, get_monday($startdate, $i));
  }

  return $mondays;
}
function setup_mondays() {  
  if(isset($_GET['startdate'])) {
    $mondays = get_mondays($_GET['startdate']);
  } else {
    $lastweek = date("Y-m-d", mktime(0,0,0,date('m'), date('d')-7, date('Y') ));
    /* get calendar starting with previous week */
    $mondays = get_mondays($lastweek);
  }
  return $mondays;
}
 
#
# Is this week the current week?
# 
function week_class($monday) {
  $class = '';
  
  $this_monday = get_monday();
  
  if ($monday ==  $this_monday) {
    $class = 'currentweek';
  } elseif ($monday < $this_monday) {
    $class = 'past';
  }
  
  
  
  return $class;
}

#
# Get Projects
#
function get_projects($type = null) {

  if ($type == 'parked') {
    $query = "SELECT * FROM projects WHERE parked = 'true' ORDER BY sort_order ASC";
  } elseif ($type == 'unparked') {
    $query = "SELECT * FROM projects WHERE parked IS NULL OR parked != 'true' ORDER BY sort_order ASC";
  } elseif ($type == 'all') {
    $query = "SELECT * FROM projects ORDER BY sort_order ASC";    
  }
  
  $results = Array();
    if ($result = mysql_query($query)) {
  		while($row = mysql_fetch_array($result)) {
  		  array_push($results, $row);
  		}
  		  
    } else {
  		die("<p>could not get projects because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
  
  return $results;
}

#
# Get One Project
#
function get_project($project_id) {

  $query = "SELECT * FROM projects WHERE project_id = $project_id  LIMIT 1";
  
    $record = Array();
    if ($result = mysql_query($query)) {
  		while($row = mysql_fetch_row($result)) {
  		  $record['project_id'] = $row[0];
  		  $record['project_name'] = $row[1];
  		  $record['project_url'] = $row[4];
  		  $record['parked'] = $row[3];
  		}
  		  
    } else {
  		die("<p>could not get project because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
  
  return $record;
}


#
# Edit Project
#
function edit_project($project_id, $new_name, $new_url, $new_parked) {
    $query = "UPDATE projects SET project_name = '$new_name', project_url = '$new_url', parked = '$new_parked' WHERE project_id = $project_id";

  if ($result = mysql_query($query)) {
  	header("Location: " . HOME . "?project_id=".$project_id."&updated=true");
  } else {
  	die("<p>could not update item because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
}


#
# Add Project
#
function add_project($project_name, $project_url = '') {
  $project_name = addslashes($project_name);
  
  $query = "INSERT INTO projects (project_name, project_url) VALUES ('$project_name', '$project_url')";
    
  if ($result = mysql_query($query)) {
  	header("Location: " . HOME . "");
  } else {
  	die("<p>could not add project because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
}

#
# Park Project
#
function park_project($project_id) {
  
  $query = "UPDATE projects SET parked = 'true' WHERE project_id = $project_id";
    
  if ($result = mysql_query($query)) {
  	header("Location: " . HOME . "");
  } else {
  	die("<p>could not park project because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
}


#
# Unpark project
#
function unpark_project($project_id) {
  
  $query = "UPDATE projects SET parked = 'false' WHERE project_id = $project_id";
    
  if ($result = mysql_query($query)) {
  	header("Location: " . HOME . "");
  } else {
  	die("<p>could not park project because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
}


#
# Display project name (and link)
#
function the_project_link($project) {
  $link = '';
  
  if ($project['project_url']) {
    $link = '<a class="project-link" href="'. $project['project_url'].'" target="_blank">#</a>';
  }
  
  echo $link;
}

#
# Get Entries 
#
function get_entries($project_id, $startdate) {
  if ($startdate == '') $startdate = get_monday();
  
  /* get future dates */
  $query = "SELECT e.entry_id, e.startdate, p.person_name, p.person_long_name, p.person_role, p.person_id, e.project_id FROM entries e, people p WHERE e.startdate = '$startdate' AND e.project_id = $project_id AND p.person_id = e.person_id ORDER BY person_name ASC";

  $results = Array();
    if ($result = mysql_query($query)) {
  		while($row = mysql_fetch_array($result)) {
  		  array_push($results, $row);
  		}
  		  
    } else {
  		die("<p>could not delete item because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
  
  return $results;
}

#
# Get One Entry
#
function get_entry($entry_id) {

  $query = "SELECT * FROM entries WHERE entry_id = $entry_id  LIMIT 1";
  
    $record = Array();
    if ($result = mysql_query($query)) {
  		while($row = mysql_fetch_row($result)) {
  		  $record['entry_id'] = $row[0];
  		  $record['startdate'] = $row[1];
  		  $record['project_id'] = $row[2];
  		  $record['person_id'] = $row[3];
  		}
  		  
    } else {
  		die("<p>could not get project because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
  
  return $record;
}


#
# Get People
#
function get_people() {
  $query = "SELECT * FROM people ORDER BY person_name ASC";
  $results = Array();
    if ($result = mysql_query($query)) {
  		while($row = mysql_fetch_array($result)) {
  		  array_push($results, $row);
  		}
  		  
    } else {
  		die("<p>could not get people because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
  
  return $results;
}



#
# Display people
#
function display_people($project_id, $startdate) {
  $entries = get_entries($project_id, $startdate);
  
  if (sizeOf($entries) > 0) {
    foreach ($entries as $key=>$entry) {
    ?>
      <div id="<?php echo $entry['entry_id'] ?>" tb:entry_id="<?php echo $entry['entry_id'] ?>" class="person person-<?php echo $entry['person_role']; ?> person-<?php echo $entry['person_id']; ?>" tb:person_id="<?php echo $entry['person_id']; ?>" title="<?php echo $entry['person_long_name']; ?>"><?php echo $entry['person_name']; ?></div>
    <?php
    }
  }
}


#
# Get One Person
#
function get_person($person_id) {

  $query = "SELECT * FROM people WHERE person_id = $person_id  LIMIT 1";
  
    $record = Array();
    if ($result = mysql_query($query)) {
  		while($row = mysql_fetch_row($result)) {
  		  $record['person_id'] = $row[0];
  		  $record['person_name'] = $row[1];
  		  $record['person_role'] = $row[2];
  		}
  		  
    } else {
  		die("<p>could not get project because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
  
  return $record;
}


#
# Edit a Person
#
function edit_person($person_id, $new_name, $new_long_name, $new_role) {
    $query = "UPDATE people SET person_name = '$new_name', person_long_name = '$new_long_name', person_role = '$new_role' WHERE person_id = $person_id";

  if ($result = mysql_query($query)) {
  	header("Location: " . HOME . "?person_id=".$person_id."&updated=true");
  } else {
  	die("<p>could not update item because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
}


#
# Add Project
#
function add_person($person_name, $person_long_name = '', $person_role = '') {
  $person_name = addslashes($person_name);
  $person_long_name = addslashes($person_long_name);
  
  $query = "INSERT INTO people (person_name, person_long_name, person_role) VALUES ('$person_name',  '$person_long_name', '$person_role')";
  if ($result = mysql_query($query)) {
  	header("Location: " . HOME . "");
  } else {
  	die("<p>could not add person because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
}



#
# Create an entry
#
function create_entry($person_id, $project_id, $startdate) {
  $query = "INSERT INTO entries (person_id, project_id, startdate) VALUES ($person_id, $project_id, '$startdate')";

  if ($result = mysql_query($query)) {
  	display_people($project_id, $startdate);
  	log_change(CURRENT_USER, 'added', $person_id, 'to', $project_id, $startdate);
  } else {
  	die("<p>could not insert item because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
}


#
# Update Entry
#
function update_entry($entry_id, $new_project_id, $new_startdate) {
    $entry = get_entry($entry_id);
    $query = "UPDATE entries SET project_id = $new_project_id, startdate = '$new_startdate' WHERE entry_id = $entry_id";

  if ($result = mysql_query($query)) {
  	display_people($new_project_id, $new_startdate);
  	log_change(CURRENT_USER, 'removed', $entry['person_id'], 'from', $entry['project_id'], $entry['startdate']);
  	log_change(CURRENT_USER, 'added', $entry['person_id'], "to", $new_project_id, $new_startdate);
  } else {
  	die("<p>could not update item because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
}


#
# Delete
#
function delete_entry($entry_id) {
  $entry = get_entry($entry_id);
  $query = "DELETE FROM entries WHERE entry_id = $entry_id";
    
  if ($result = mysql_query($query)) {
  	echo $query;
  	log_change(CURRENT_USER, 'removed', $entry['person_id'], 'from', $entry['project_id'], $entry['startdate']);
  } else {
  	die("<p>could not delete item because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
}


#
# Reorder
#
function reorder_entries($raw_new_order) {
  
  /* XXX hack */
  $tmp = str_replace('&', '', $raw_new_order);
  $new_order = explode ('[]=',$tmp);
  unset($new_order[0]);
  /* this returns something like:
    [0] => first item ID
    [1] => second item ID
    etc.
  */
  
  /* XXX another hack -- looping through repeated queries */
  foreach ($new_order as $key => $project_id) {
    $query = "UPDATE projects SET sort_order = $key WHERE project_id = $project_id";
    if ($result = mysql_query($query)) {
    } else {
    	die("<p>could not delete item because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
    }
  }
}

#
# Get changes
#
function get_changes($num_changes = 20) {
  $query = "SELECT * FROM changes ORDER BY change_id DESC LIMIT $num_changes";
  $results = Array();
    if ($result = mysql_query($query)) {
  		while($row = mysql_fetch_array($result)) {
  		  array_push($results, $row);
  		}
  		  
    } else {
  		die("<p>could not get changes because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
  $changes = array();
  
  foreach($results as $key => $record) {
    $change = array();
    $change['timestamp'] = date('n/j/Y', strtotime($record['timestamp']));
    $change['instigator'] = $record['instigator']; 
    $change['verb'] = $record['verb'];
    $person = get_person($record['person_id']);
    $change['person'] = $person['person_name'];
    $change['preposition'] = $record['preposition'];
    $project = get_project($record['project_id']);
    $change['project'] = $project['project_name'];
    $change['week'] = $record['week'];
    array_push($changes, $change);
  }
  
  return $changes;
}

#
# Add a change to the log
#
function log_change($instigator, $verb, $person_id, $preposition, $project_id, $week) {
  $instigator = addslashes($instigator);
  $query = "INSERT INTO changes (timestamp, instigator, verb, person_id, preposition, project_id, week) VALUES (NOW(), '$instigator', '$verb', $person_id, '$preposition', $project_id, '$week')";

  if ($result = mysql_query($query)) {
  	#echo "added record " . mysql_insert_id();
  } else {
  	die("<p>could not insert item because:<br>"  .mysql_error().  "<br>the query was $query.</p>");
  }
}

function list_latest_changes($changes) {
?>
<ul>
    <?php foreach ($changes as $key => $change) : ?>
      <li class="<?php echo $change['verb'];?>"><strong><?php echo $change['person']; ?></strong> <?php echo $change['verb']; ?> <?php echo $change['preposition']; ?> <strong><?php echo $change['project']; ?> / <?php echo $change['week']; ?></strong> <span class="meta">(by <?php echo $change['instigator']; ?> on <?php echo $change['timestamp']; ?>)</span></li>
    <?php endforeach; ?>
  </ul>
<?php
}

function display_projects_table($projects) {
  $mondays = setup_mondays();	
  
  foreach ($projects as $key=>$project) :
  
  ?>
    <tr id="project-<?php echo $project['project_id'] ?>" tb:project_id="<?php echo $project['project_id'] ?>" class="<?php echo $project['component_class'];?>" tb:component="<?php echo $project['component_full'];?>">
      <td class="project-name">
        <?php //echo $project['project_name']; ?>
        <?php //the_project_link($project); ?>
        <a class="<?php echo $project['priority']?>" href="http://thenest.topplabs.org/ticket/<?php echo $project['project_id']?>"><?php echo $project['project_name']?></a>
      </td>
      <?php foreach ($mondays as $key => $monday) : ?>
        <td class="project-<?php echo $project['project_id']?> <?php echo week_class($monday) ?>" id="cell-<?php echo $project['project_id']?>-<?php echo $monday?>" tb:project_id="<?php echo $project['project_id']?>" tb:date="<?php echo $monday; ?>">
          <?php echo display_people($project['project_id'], $monday) ?>
          <div class="batch-dialog" style="display:none">
            <a href="#" class="copy">cp</a>  
            <a href="#" class="move">mv</a> 
            <a href="#" class="delete">rm</a>              
            <a href="#" class="cancel">x</a>
          </div>
        </td>
      <?php endforeach; ?>
      <th style="width: 40px">
        
        </th>
    </tr>
    <?php 
    endforeach;
}

?>