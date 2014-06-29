<?php 
require_once('header.php'); 
require_once('display.inc.php'); 
?>

<div id="intro">
  <p><b>Welcome to The Board</b>: project staffing @ CivicWorks. See <a href="https://thenest.openplans.org">The Nest</a> for all projects.</p>
  <p>Drag a tile onto the board to <b>add it</b>. Drag a tile to the edge of the board to <b>delete it</b>.
</div>

<div id="datepicker">
  Show 10 weeks starting: 
  <form action="<?php echo HOME ?>" method="get">
  <input type="text" name="startdate" id="startdate"
    value="<?php if (isset($_GET['startdate'])) echo $_GET['startdate']; ?>"/>
  <?php if (isset($_GET['startdate'])) : ?> (<a href="<?php echo HOME; ?>">clear</a>)<?php endif; ?>
  <noscript>
  <input type="submit" value="Go" />
  </noscript>
  </form>
</div>

<ul class="people">
  <?php foreach ($people as $key => $person) : ?>
    <li class="person source person-<?php echo $person['person_role']?> person-<?php echo $person['person_id']; ?>" tb:person_id="<?php echo $person['person_id']?>" title="<?php echo $person['person_long_name']; ?>"><?php echo $person['person_name']; ?></li>
  <?php endforeach; ?>
</ul>

<table>
  <thead>
    <tr>
    <th></th>
    <?php foreach ($mondays as $key => $monday) : ?>
    <th class="<?php echo week_class($monday) ?>"><?php echo $monday; ?></th>
    <?php endforeach; ?>
    <th></th>
    </tr>
  </thead>
  <tbody>
    
    <?php display_projects_table($active_projects); ?>
        
    </tbody>
    <tfoot>
    <tr>
    <th></th>
    <?php foreach ($mondays as $key => $monday) : ?>
    <th class="<?php echo week_class($monday) ?>"><?php echo $monday; ?></th>
    <?php endforeach; ?>
    <th></th>
    </tr>
    </tfoot> 
</table>

<ul class="people">
  <?php foreach ($people as $key => $person) : ?>
    <li class="person source person-<?php echo $person['person_role']?> person-<?php echo $entry['person_id']; ?>" tb:person_id="<?php echo $person['person_id']?>" title="<?php echo $person['person_long_name']; ?>"><?php echo $person['person_name']; ?></li>
  <?php endforeach; ?>
</ul>

<hr />

<a id="show-log" href="#">Activity Log</a>
<div id="log" style="display: none">
<h2>Latest Changes</h2>
<?php list_latest_changes($changes); ?>
</div>

<br />

<a id="show-admin" href="#">Admin</a>
<div id="admin" style="display: none">
<h2>Admin</h2>
<!--
<div class="edit-widget">
  <h3>Add a new project:</h3>
  <form action="<?php HOME; ?>" method="post">
  <input type="hidden" name="action" value="add_project" />
  <label for="project_name">Name: </label><input type="text" name="project_name" /><br />
  <label for="project_url">URL: </label><input type="text" name="project_url" /><br />
    <input type="submit" value="Go" />
  </form>
</div>

<div class="edit-widget">
<h3>Hide a project:</h3>
  <form action="<?php HOME; ?>" method="POST">
  <input type="hidden" name="action" value="park_project" />
  <select name="project_id">
    <option>-- Choose --</option>
    <?php foreach ($projects as $key=>$project) : ?>
    <option value="<?php echo $project['project_id']?>"><?php echo $project['project_name']; ?></option>    
    <?php endforeach; ?>
  </select>
  <input type="submit" value="Go" />
  </form>
</div>

<div class="edit-widget">
<h3>Show a project:</h3>
  <form action="<?php HOME; ?>" method="POST">
  <input type="hidden" name="action" value="unpark_project" />
  <select name="project_id">
    <option>-- Choose --</option>
    <?php foreach ($parked_projects as $key=>$project) : ?>
    <option value="<?php echo $project['project_id']?>"><?php echo $project['project_name']; ?></option>    
    <?php endforeach; ?>xn
  </select>
  <input type="submit" value="Go" />
  </form>
</div>
-->

<!--
<div class="edit-widget">
<h3>Edit a project:</h3>
  <form action="<?php HOME; ?>" method="GET">
  <select name="project_id">
    <option>-- Choose --</option>
    <?php foreach ($projects as $key=>$project) : ?>
    <option value="<?php echo $project['project_id']?>"><?php echo $project['project_name']; ?></option>    
    <?php endforeach; ?>
  </select>
  <input type="submit" value="Go" />
  </form>
</div>
-->

<div class="edit-widget">
  <h3>Add a new person:</h3>
  <form action="<?php HOME; ?>" method="post">
  <input type="hidden" name="action" value="add_person" />
  <label for="person_name">Short Name: </label><input type="text" name="person_name" /> <br />
  <label for="person_long_name">Long Name: </label><input type="text" name="person_long_name" /> <br />
  Role: <select name="person_role">
          <option value="">-- Choose --</option>
          <option value="dev">Dev</option>
          <option value="dzn">Dzn</option>
        </select><br />
    <input type="submit" value="Go" />
  </form>
</div>

<div class="edit-widget">
<h3>Edit a person:</h3>
  <form action="<?php HOME; ?>" method="GET">
  <select name="person_id">
    <option>-- Choose --</option>
    <?php foreach ($people as $key=>$person) : ?>
    <option value="<?php echo $person['person_id']?>"><?php if ($person['person_long_name'] != '') { echo $person['person_long_name']; } else { echo $person['person_name']; } ?></option>    
    <?php endforeach; ?>
  </select>
  <input type="submit" value="Go" />
  </form>
</div>

</div><!-- end #admin -->

<?php require_once('footer.php'); ?>
