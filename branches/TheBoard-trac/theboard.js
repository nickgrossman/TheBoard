

$(document).ready(applyBehaviors);
$(document).ready(stripeSectionRows);

var TP = {};
TP.cell;
TP.dragged;

function applyBehaviors() {

  $("table .person").draggable({revert: 'invalid'});
  
  $('.people .person').draggable({helper: 'clone', revert: 'invalid'});
  
  $('.person').dblclick(togglePersonHighlight);
  
  $("#startdate").datepicker({ 
    dateFormat: 'yy-mm-dd',
    onSelect: function() {this.parentNode.submit();}
  });
    
  $("td:not(.project-name)").droppable({
    drop: entryDrop,
    hoverClass: 'hover'
  });
  
  $('td.project-name').dblclick(toggleProjectHighlight);
    
  /*$('tbody').tableDnD({
      onDrop: function(table, row) {
        var order = $.tableDnD.serialize();
        updateOrder(order);
      }
  });
  
  $("tr").hover(function() {
      $(this.cells[0]).addClass('draggable');
  }, function() {
      $(this.cells[0]).removeClass('draggable');
  });*/

  
  $(".trash").droppable({
    drop: trashDrop,
    accept: 'table .person',
    activeClass: 'target'
  });

  $("th").droppable({
    drop: trashDrop,
    accept: 'table .person',
    activeClass: 'target'
  });  
  
  $("td.project-name").droppable({
    drop: trashDrop,
    accept: 'table .person',
    activeClass: 'target'
  }); 
  
  /*$('table .person').selectable({
    selecting: function() {console.log('selected')}
  });*/
  
  //$('td').click(showBatchDialog);
  
  //$('.batch-dialog .cancel').click(hideBatchDialog);
  
  $('#show-admin').click(toggleAdminPanel);
  $('#show-log').click(function(e){e.preventDefault();$('#log').toggle('medium')});
}

function stripeSectionRows() {

  $('tr').each(function(i,val) {
    if (i != 0) { // skip the header row
 
      if (i == 1) {
        // this is first row.  make it odd
        $(this).addClass('odd');
      }
      
      if (i != 1 && $(this).prev().hasClass(this.className)){
        // these are part of the same section.  make the classnames match.

        if ($(this).prev().hasClass('odd')) {
          $(this).addClass('odd');
        } else {
          $(this).addClass('even');
        }
        
      } else {
        // this is a new section
          
        var sectionName = $(this).attr('tb:component'); 
        $($(this).children("td")[0]).prepend('<span class="section-name">' + sectionName + '</span>');
        
        $(this).addClass('firstInSection');
        
        if ($(this).prev().hasClass('odd')) {
          $(this).addClass('even')
        } else {
          $(this).addClass('odd');
        }
      }
    }
  }); 
}

function entryDrop(e,ui) {
  var personId = ui.draggable.context.getAttribute('tb:person_id'); 
  var startDate = this.getAttribute('tb:date');
  var entryId = ui.draggable.context.getAttribute('tb:entry_id'); 
  var projectId = this.getAttribute('tb:project_id');
  TP.cell = this;
  TP.dragged = ui.draggable.context;
  
  if (!entryId){
    /* this is a new entry */
    $.post('./', 
      {
      'action': 'create', 
      'person_id': personId,
      'startdate': startDate,
      'project_id': projectId
      },
      entryResponse);
  } else {
    /* this is an update */
    $.post('./', 
      {
      'action': 'update', 
      'entry_id': entryId,
      'startdate': startDate,
      'project_id': projectId
      }, 
      entryResponse);
  }
}

function entryResponse(data, textStatus) {
  var cell = $(TP.cell);
  var cellContent = data;
  var dragged = $(TP.dragged);
  
  /* don't hide items from source list */
  if (!dragged.hasClass('source')) dragged.hide();
  cell.empty();
  cell.append(cellContent);
  
  applyBehaviors();
  refreshLog();
}

function trashDrop(e, ui) {
  ui.draggable.hide();
  $.post('./', {'action': 'delete', 'entry_id': ui.draggable.context.id}, trashResponse);
}

function trashResponse(data, textStatus) {
  refreshLog();
}

function updateOrder(order) {
   $.post('./', 
      {
      'action': 'reorder',
      'new_order': order
      }, 
      orderResponse);
}

function orderResponse(data, textStatus) {
}

function refreshLog(e, ui) {
  $.get('./', {'action': 'list_latest_changes'}, function(data, textStatus){
    $('#log ul').replaceWith(data);
  });
}


function togglePersonHighlight(e) {
  var person_id = e.currentTarget.getAttribute('tb:person_id');
  
  if (!$(e.currentTarget).hasClass('highlighted')) {
    $('.person').addClass('lowlighted');
    $('.person.person-' + person_id).removeClass('lowlighted');
    $(e.currentTarget).addClass('highlighted');
  } else {
    $('.person').removeClass('lowlighted');
    $(e.currentTarget).removeClass('highlighted');
  }
}

function toggleProjectHighlight(e) {
  var project_id = e.currentTarget.parentNode.getAttribute('tb:project_id');
  
  if (!$(e.currentTarget).hasClass('highlighted')) {
    $('.person').addClass('lowlighted');
    $('td').addClass('lowlighted');
    $('td.project-' + project_id).removeClass('lowlighted');
    $('td.project-' + project_id + ' .person').removeClass('lowlighted');
    $(e.currentTarget).addClass('highlighted');
    $(e.currentTarget).removeClass('lowlighted');
  } else {
    $('.person').removeClass('lowlighted');
    $('td').removeClass('lowlighted');
    $(e.currentTarget).removeClass('highlighted');
  }
}

function toggleAdminPanel(e) {
  e.preventDefault();
  $('#admin:hidden').show('fast');
}

/*
#
# Batch Dialog
#
*/
function showBatchDialog(e) {
  var cell = $(e.currentTarget);
  var dialog = cell.find('.batch-dialog');
  
  if (cell.find('.person').length > 0) {
    dialog.fadeIn();
  }
}

function hideBatchDialog(e) {
  e.preventDefault();
  e.stopPropagation();
  var dialog = $(e.currentTarget).parent();
  dialog.fadeOut();
}

