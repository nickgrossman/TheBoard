<?php 

/*
#
# Gets tickets from the nest
#
*/

include('./lib/xmlrpc.inc');

/*** client side ***/
global $c;
$c = new xmlrpc_client(TRAC_CONN);

// tell the client to return raw xml as response value
$c->return_type = 'phpvals';


function get_live_tickets($query) {
  
  global $c;
   
  // native php support 
  /*$r = $c->send(xmlrpc_encode_request('ticket.query', 'status!=closed&type!=Back Burner&type!=Triage&type!=Active Biz Dev&order=type&'.$query));*/
  
  // using the package
  $m = new xmlrpcmsg("ticket.query", array(new xmlrpcval($query)));
  $r = $c->send($m);

  /*if ($r->faultCode())
    // HTTP transport error
    echo 'Got error '.$r->faultCode();
  else
  {
    // HTTP request OK, but XML returned from server not parsed yet
    //$v = xmlrpc_decode($r->value());
    //$v = $message->parseResponse($r->value());
    
    // check if we got a valid xmlrpc response from server
    if ($v === NULL)
      echo 'Got invalid response';
    else
    // check if server sent a fault response
    if (xmlrpc_is_fault($v))
      echo 'Got xmlrpc fault '.$v['faultCode'];
    else
      echo '';
    
  }
  */
  
  $ticket_data = $r->value();
  $tickets;
 	
  foreach ($ticket_data as $key => $t) {
    $m = new xmlrpcmsg("ticket.get", array(new xmlrpcval($t)));
    //$r = $c->send(xmlrpc_encode_request('ticket.get', $t));
    $r = $c->send($m);
    //$v = xmlrpc_decode($r->value());
    $v = $r->value();
    $tickets[$key]['project_id'] = $v[0];
    $tickets[$key]['project_name'] = $v[3]['summary'];
    $tickets[$key]['priority'] = $v[3]['priority'];
    $tickets[$key]['component_full'] = $v[3]['component'];
    $trimmed_component = str_replace(':', '', $v[3]['component']);
    $trimmed_component = str_replace(' ', '', $trimmed_component);
    $trimmed_component = str_replace(' ', '', $trimmed_component);
    $tickets[$key]['component_class'] = strtolower($trimmed_component);
  }
    
  if (sizeof($tickets) > 0)
    return $tickets;
  else
    return array('','');
} 


?>
