<?php
/*
Controller name: Solicitações
Controller description: Oh Garçom/IBTI Solicitações 
*/
	 

class JSON_API_Solicitacoes_Controller {
  	
	public function create_solicitacao() {
	 global $json_api;	  
	 extract($json_api->query->get(array('')));
	 
	 $output = array();
	 
	 	 
	 return $output;
	}
	
	
	public function get_lista_solicitacao() {
	 global $json_api;	  
	 extract($json_api->query->get(array('')));
	 
	 $output = array();
	 
	 	 
	 return $output;
	}
	
	
	
} 
	

?>