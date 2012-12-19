<?php
/*
Controller name: User
Controller description: User Functions via Json
*/
	define(FORGOT_PWD,'1');

class JSON_API_User_Controller {
	
  
    public function login() {
    	//login de usuario
      global $json_api;
	  $output = array();
	  
	  extract($json_api->query->get(array('u','p','t')));
	 // $p = $json_api->introspector->decryptStr($p);
	  //echo $p;
	  //t = type of login
	  /* code facebook login
	  */
	   $u = str_replace(';','@',$u);
	  if(filter_var($u, FILTER_VALIDATE_EMAIL)) {
       
	   
		$user = get_user_by( 'email', $u );
	    
		$u = $user->user_login;
		$id = $user->ID;
		$metamap =  get_user_meta($id);
 		//print_r($metamap);
     }
	  	if(isset($u) && isset($p)) { 
			$creds = array();
			$creds['user_login'] = $u;
			$creds['user_password'] = $p;
			$creds['remember'] = true;
			$user = wp_signon( $creds, false );
			if ( is_wp_error($user) ) {
			   $json_api->error("error in login: ".$user->get_error_message()." ");
		 
			}else {
			   $output['userid']  =$user->ID;
			$output['username']  =$user->user_login;
			$output['email'] = $user->user_email;
			
			 /* data.username / data.email / data.receive_udpates / data.general_interests / data.zipcode / data.birthdate  */

		   			 
 			/*
	
			$output['receive_updates'] = $metamap[receive_updates][0];
			$output['general_interests'] =  	$this->decode_interests($metamap[general_interests][0]);	
			$output['zipcode'] = $metamap[zipcode][0];
			$output['birthdate'] = $metamap[birthdate][0];
			$output['taggedTkts'] = $metamap[tagged_tickets][0];
			 */
				 return $output;

			}
   } else {  
      if(!$u)  {
	    //register user because it doesn't exist
		  
	  }
      
       $json_api->error("error in login, please check your username and password"); 
	 }
	 
	 return $output;
  }
 
 public function register() {
          global $json_api;
	  $output = array();
	  
	  extract($json_api->query->get(array('u')));
	 
	   $u = str_replace(';','@',$u);
	   $new_user = $this->signup($u);
	   if($new_user) { 
	   
	     $output = $new_user;
		 
		 //TODO send welcome email 
	      return $output;
		}else {
		       $json_api->error("Error in signup. User already exists"); 

		}
 }
 
 private function signup($email) {
	 $new_user = array();
   if ( email_exists($email) ) {
      return false;
   }else {
	   $random_password = wp_generate_password( $length=4, $include_standard_special_chars=false );
    	$user_id = wp_create_user( $email, $random_password, $email );
		
		
		   $new_user['userid']  = $user_id;
			$new_user['username']  =$email;
			$new_user['email'] = $email;
			$new_user['receive_updates'] = 1;
			
   		return $new_user;
   }
   
 }
 private function decode_interests($data) {
	 $output = '';
   $str = $data;
   $str = str_replace('}','',$str);
   $dar = split('{',$str);
   $def = $dar[0];
   $items = $dar[1];
   $itar = split('";',$items);
   foreach($itar as $unit) {
     $res = split(':"',$unit);
	 $output .= $res[1].',';
   }
   $output = str_replace(',,','',$output);
   return  $output;
 }
 
 private function encode_interests($data) {
   $output = 'a:';
   
   $dar = split(',',$data);
    $output .= sizeof($dar).':{';
   for($j=0;$j<sizeof($dar);$j++) {
      $output.='i:'.$j.';s:1:"'.$dar[$j].'";';
   }
   $output .='}';
   echo $output;
 return $output;
  }
 
  
 
 public function saveProfile() {
	 global $json_api;
	  $output =  array();
	
	  extract($json_api->query->get(array('id','u','e','r','g','z','b','p')));
	  
	
	   		$metamap =  get_user_meta($id);
			$user = get_user_by( 'id', $id);
	
	   if($user) { 
	    
		$updates = array();
		if(isset($u)) 	update_user_meta( $id, 'first_name', $u,$user->first_name);
		if(isset($e)) update_user_meta( $id, 'user_email', $e,$user->user_email);
		if(isset($r)) $updates['receive_updates'] = $r;
		if(isset($g)) $updates['general_interests'] = $g;
		if(isset($z)) $updates['zipcode'] = $z;
		if(isset($b)) $updates['birthdate'] = $b;
		if(isset($p)) wp_update_user( array ('ID' => $id, 'user_pass' => $p) ) ;

		
		
		$api = new PodAPI();
 
	 $params = array('datatype' => 'user', 'tbl_row_id' => $id, 'columns' => $updates); 
	 $params = pods_sanitize($params);
    $api->save_pod_item($params);
 
	   } else   $json_api->error("error - user doesn't exist"); 
 
 
 
	
	   		$metamap =  get_user_meta($id);
			$user = get_user_by( 'id', $id);
			
 	 $output['userid']  =$user->ID;
			$output['username']  =$user->first_name;
			$output['email'] = $user->user_email;;
			$output['receive_updates'] = $metamap[receive_updates][0];
			$output['general_interests'] =  	$this->decode_interests($metamap[general_interests][0]);	
			$output['zipcode'] = $metamap[zipcode][0];
			$output['birthdate'] = $metamap[birthdate][0];
			$output['taggedTkts'] = $metamap[tagged_tickets][0];
			
	return $output;		
 }
 
 
 public function resetPassword() {
  global $json_api;
	  $output = array();  
	  extract($json_api->query->get(array('h')));
     $id = $h[0];
	 $hash = substr($h,1);
 	 
	 $user = get_user_by( 'id', $id );
		if($user) {
		$u = $user->user_login;
	}else  $json_api->error("error - email is not of a known ticket dumper"); 
	  
 }

public function forgotPassword() {
   global $json_api;
	  $output = array();
	  
	  extract($json_api->query->get(array('u')));
	  
 $u = str_replace(';','@',$u);
	  if(filter_var($u, FILTER_VALIDATE_EMAIL)) {
       
	   
		$user = get_user_by( 'email', $u );
		if($user) {
		$u = $user->user_login;
		$id = $user->ID;
	   
	   
	   //TODO : generate hash to verify and attach to email template _link
	   
	   $hash_link = '';
	   if($this->sendEmail('Password Recovery TicketDump',$u,FORGOT_PWD,$hash_link))
	   		return $output;
		else  $json_api->error("error - we had some issues sending you an email try again in a few minutes"); 
	
		} else   $json_api->error("error - email is not of a known ticket dumper"); 

}else {
	       $json_api->error("error - email  is not valid"); 
}

}


public function deactivateAccount() {
//TODO


}

private function sendEmail($title, $to, $template,$extra) {

$email = '';
 switch($template) {
  case FORGOT_PWD:
     $email .= $this->get_email_top($title);
	 $email .= " To recover your Ticket Dump password <a href='".$extra."' target='_blank'> click on this link. </a>
	 ";
	 $email.= $this->get_email_footer();
  break; 
  default:
   
  break;
 }
 
 //smtp php send email
 
 return true;
 
}

private function generate_email_header($to) {

}


private function get_email_top($title) {

}

private function get_email_footer() {
 
}

}

?>
