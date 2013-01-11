<?php
/*
Controller name: Opções
Controller description: Oh Garçom/IBTI Opções 
*/ 

class JSON_API_Opcoes_Controller {
  	
	public function get_lista_opcoes() {
	 global $json_api;	  
	 extract($json_api->query->get(array('')));
	 
<<<<<<< HEAD
	 	$opcoes = pods( 'opcoes');
		$params = array(
					'orderby' => 't.id DESC'
				);
				
		$opcoes->find($params);
		$output=array();
		
		while ( $opcoes->fetch() ){
			
			$arquivo = $opcoes->display( 'icone');
			$extensao = pathinfo($arquivo, PATHINFO_EXTENSION);
			
   			$output[]= 	array('Nome'=> $opcoes->display( 'name'),
   							'Ordem'=> $opcoes->display( 'ordem'),
   							'Descricao'=>$opcoes->display( 'descricao'),
   							'icone'=>$opcoes->display( 'icone'),
   							'tipo_icone'=>$extensao,
   							'link'=>$opcoes->display( 'link_interno')
					);
		}
	
	 	return $output;
=======
	 $output = array();
	 	 
	 return $output;
>>>>>>> parent of 50fd9b9... Controller opcoes
	}	
} 	
?>