<?php
/*
Controller name: Solicitações
Controller description: Oh Garçom/IBTI Solicitações 
*/
	 

class JSON_API_Solicitacoes_Controller {
  	
	public function create_solicitacao() {
		global $json_api;	  
		extract($json_api->query->get(array('e','t','d')));
	 
		if(is_null($t))
			$t=0;
		if(is_null($d)){
			$output=array('erro'=>'mensagem da situação não informada.');
		}else{
			
			$fields = array(
        		'estado_solicitacao'=> $t,
        		'mensagem'=> $d,
			);
			$mypod = pods('solicitao');
			$new_id = $mypod->save( $fields );
			$output=array('id'=>$new_id,'sucesso'=>'Situação cadastrada.');
		} 
		return $output;
	}
	
	
	public function get_lista_solicitacao() {
		global $json_api;	  
		$t='0';
		extract($json_api->query->get(array('e','t')));
			 
		
		if(is_null($t))
		$t=0;	
		$fields = array(
			'orderby'=> 't.name DESC',
			'where'=>' t.estado_solicitacao ='.$t,
		);
				 
		$solicitacao = pods('solicitao');
		$solicitacao = $solicitacao->find($fields);
				
		while ( $solicitacao->fetch() ){
			
			
   			$output[]= 	array('Nome'=> $solicitacao->display( 'name'),
   							'Mensagem'=> chop(strip_tags($solicitacao->display( 'mensagem'))),
   							'Estado Solicitacao'=>$solicitacao->display( 'estado_solicitacao'),
   							'Action Call'=>$solicitacao->display( 'permalink'),
   							'Data create'=>$solicitacao->display( 'created')
					);
		}
 		return $output;
	}
	
	
	
} 
	

?>