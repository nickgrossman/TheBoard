<form action="" method="post">
OLD BOARD ID: <input name="old_board" type="text" />

<br /><br />
NEW NEST ID: <input name="new_nest" type="text" />

<br /><br />
<input type="submit" />
</form>

<?php
  
  if (isset($_POST)) {
    
    // move the current project holding the desired number to 9x
    mysql_query('update projects set project_id = 9'.$_POST['new_nest'].' where project_id = '.$_POST['new_nest'].'');
    
    // renumber the desired project to its new number
    mysql_query('update projects set project_id = '.$_POST['new_nest'].' where project_id = '.$_POST['old_board'].'');
    
    // move any entries associated with the old # to 9x
    mysql_query('update entries set project_id = 9'.$_POST['new_nest'].' where project_id = '.$_POST['new_nest'].'');
    
    // move any people associated with the new project to the new number
    mysql_query('update entries set project_id = '.$_POST['new_nest'].' where project_id = '.$_POST['old_board'].'');
    
  }
?>