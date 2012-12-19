<?php
/*
Controller name: Publicidade
Controller description: Oh Garçom/IBTI Publicidade 
*/
	 

class JSON_API_Publicidade_Controller {
  	
	public function get_lista_publicidade() {
	 global $json_api;	  
	 extract($json_api->query->get(array('')));
	 
	 $output = array();
	 
	 	 
	 return $output;
	}
	
} 
	

?>