<?php
/*
Controller name: Opções
Controller description: Oh Garçom/IBTI Opções 
*/ 

class JSON_API_Opcoes_Controller {
  	
	public function get_lista_opcoes() {
	 global $json_api;	  
	 extract($json_api->query->get(array('')));
	 
	 $output = array();
	 	 
	 return $output;
	}	
} 	
?>