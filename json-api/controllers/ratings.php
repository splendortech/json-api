<?php
/*
Controller name: Ratings
Controller description: Ticket Dump Ratings Api
*/

class JSON_API_Ratings_Controller {
	 
  
 public function get_ratings_list() {
   
		   global $json_api;
		     
			  $output = array();
			  extract($json_api->query->get(array('u','pg')));
				$u = str_replace(';','@',$u);
			  if(filter_var($u, FILTER_VALIDATE_EMAIL)) {
					$user = get_user_by( 'email', $u );
				
				  //SELECT ratings WHERE USER =$u
				  
				  
				  
				  
			  }else   $json_api->error("user doesn't exist. This is not a TicketDumper buddy..."); 

 }
   
   
  public function get_user_rating() {
   
   		   global $json_api;
	
			  $output = array();
			  extract($json_api->query->get(array('u','id')));
			  if(isset($u)) { 
					$u = str_replace(';','@',$u);
					  if(filter_var($u, FILTER_VALIDATE_EMAIL)) {
							$user = get_user_by( 'email', $u );
								$id = $user->ID;
							$metamap =  get_user_meta($id);
						   }else   $json_api->error("user doesn't exist. This is not a TicketDumper buddy..."); 

			  }else {
 				 $metamap =  get_user_meta($id);
			  }//SELECT metadata user_rating WHERE USER =$u
				 
				 $output['user_rating'] = $metamap[user_rating][0]; 
				$output['user_id']= $id;
   				return $output;
   }
   
   public function create_rating() {
       global $json_api;
	
			  $output = array();
			  extract($json_api->query->get(array('u','id','t','r','d')));
			  /*
			   user
			   user_id
			   ticket
			   rating
			   description
			  */
			  if(isset($u)) { 
					$u = str_replace(';','@',$u);
					  if(filter_var($u, FILTER_VALIDATE_EMAIL)) {
							$user = get_user_by( 'email', $u );
								$id = $user->ID;
							$metamap =  get_user_meta($id);
					   }else   $json_api->error("user doesn't exist. This is not a TicketDumper buddy..."); 
			  }
			  
			  //CREATE RATING WITH PARAMETERS
			  
			  //UPDATE USER METADATA
			  		// 1. SELECT ROW COUNT OF RATINGS WITH user = $id
						$count = 1;
			  		// 2. GET USER META ( RATING) 
						$curr_rating = $metamap[user_rating][0]; 
					// 3. ADD NEW RATING $R AND DIVIDE BY ROW COUNT
						
						$new_rating = $curr_rating + $r;
						$new_rating /= $count;
					// 4. UPDATE USER META
					
					$output['user_rating'] = $new_rating;
					$output['user_id'] = $id;
			  return $output;

   }
   
   public function edit_rating() {
	   
	    global $json_api;
	
			  $output = array();
			  extract($json_api->query->get(array('id', 'r','d')));
			  /*
			   rating id
			   rating value
			   description
			   */
			  
   } 
   
   public function remove_rating() {
    global $json_api;
	
			  $output = array();
			  extract($json_api->query->get(array('id')));
			  
			  // 1. SELECT USER FROM RATING
			  
			  // 2. GET USER META USER_RATING
			  
			  //  3. GET RATING VALUE
			  
			  // 4. GET ROW COUNT OF RATINGS FOR USER
			  
			  // 5. UPDATE META USER_RATING ->CURR_RATING - RATING / COUNT-1
			  
			  // 6. DELETE RATING 
			  
			  return $output;
			  
   }
}

?>
