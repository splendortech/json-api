<?php
/*
Controller name: Messages
Controller description: Manage Pods Messaging System
*/

class JSON_API_Message_Controller {
  
  var $podName = 'messages';
  
  protected function encode_users($f,$t) {
  
  }
 
 public function contact_tktdump() {
       global $json_api;
	
			  $output = array();
			  extract($json_api->query->get(array('u','id','m')));
			  if(isset($u)) { 
					$u = str_replace(';','@',$u);
					  if(filter_var($u, FILTER_VALIDATE_EMAIL)) {
							$user = get_user_by( 'email', $u );
								$id = $user->ID;
							$metamap =  get_user_meta($id);
						   }else   $json_api->error("user doesn't exist. This is not a TicketDumper buddy..."); 

			  }
			  
			  
 }
 
 
 public function send_message() {
          global $json_api;
	  $output = array();
	  
	  extract($json_api->query->get(array('f','t','m')));
	  //from , to , message
	  
	  //FROM
	  $f = str_replace(';','@',$f);
	  if(filter_var($f, FILTER_VALIDATE_EMAIL)) {      
		$from = get_user_by( 'email', $f );
 		}else  	  $json_api->error("error user get: ".$user->get_error_message()." ");

	//TO
  $t = str_replace(';','@',$t);
	  if(filter_var($f, FILTER_VALIDATE_EMAIL)) {      
		$to= get_user_by( 'email', $t );
 		}else  	  $json_api->error("error user get: ".$user->get_error_message()." ");

	$users_Str = encode_users($f,$t);
   
 }
   public function clear_messages() {
   
   }
   
   public function message_history_between_users() {
   
   }
   
  
}

?>
