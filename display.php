<?php 
require_once('header.php'); 
require_once('display.inc.php'); 
?>

<div id="intro">
  <p>Drag a tile onto the board to <b>add it</b>. Drag a tile to the edge of the board to <b>delete it</b>. <br /> Double-click a tile to see all other tiles like it.  Double-click a project to see only its tiles.</p>
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
    <?php foreach ($projects as $key=>$project) : ?>
    <tr id="project-<?php echo $project['project_id'] ?>" tb:project_id="<?php echo $project['project_id'] ?>">
      <td class="project-name">
        <?php echo $project['project_name']; ?>
        <?php the_project_link($project); ?>
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
      <th style="width: 40px"><a href="<?php echo HOME; ?>?project_id=<?php echo $project['project_id'] ?>">edit</a></td>
    </tr>
    <?php endforeach; ?> 
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

<h2>Latest Changes</h2>
<div id="log">  
  <?php list_latest_changes($changes); ?>
</div>

<div id="admin">
<h2>Admin</h2>

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
    <?php endforeach; ?>
  </select>
  <input type="submit" value="Go" />
  </form>
</div>

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
