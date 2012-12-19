<?php
/*
Controller name: Cardápio
Controller description: Oh Garçom/IBTI Cardápio 
*/
	 

class JSON_API_Cardapio_Controller {
  	
	
	public function get_lista_categoria() {
	 global $json_api;	  
	 extract($json_api->query->get(array('')));
	 $output = array();
	 	 
	 return $output;
	}
	
	public function get_item() {
	 global $json_api;	  
	 extract($json_api->query->get(array('')));
	 
	 $output = array();
	 
	 	 
	 return $output;
	}
	
	public function get_lista_item() {
	 global $json_api;	  
	 extract($json_api->query->get(array('')));
	 
	 $output = array();
	 
	 	 
	 return $output;
	}
	
	public function search_itens() {
	 global $json_api;	  
	 extract($json_api->query->get(array('')));
	 
	 $output = array();
	 
	 	 
	 return $output;
	}
	
	
} 
	

?>