<?php
/*
Controller name: Estabelecimento
Controller description: Oh Garçom/IBTI Estabelecimento 
*/
	 

class JSON_API_Estabelecimento_Controller {
  	
	
	public function get_estabelecimento() {
	 global $json_api;	  
	 extract($json_api->query->get(array('')));
	 
	 $output = array();
	 
	 	 
	 return $output;
	}
	
	public function get_estado_estabelecimento() {
	 global $json_api;	  
	 extract($json_api->query->get(array('')));
	 
	 $output = array();
	 
	 	 
	 return $output;
	}
	
	public function search_estabelecimento() {
	 global $json_api;	  
	 extract($json_api->query->get(array('')));
	 
	 $output = array();
	 
	 	 
	 return $output;
	}
} 
	

?>