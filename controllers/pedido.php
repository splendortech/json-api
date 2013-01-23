<?php
/*
Controller name: Pedido
Controller description: Oh Garçom/IBTI Pedido 
*/	 

class JSON_API_Pedido_Controller {
      	
	public function create_pedido() {
		global $json_api;	  
		extract($json_api->query->get(array('e', 'm', 'id')));	 
	 
	 	if(is_null($id)){
	   		$output=array('erro'=>'identificação do estabelecimento não informado');	 
	 	}elseif(is_null($m)){
	   		$output=array('erro'=>'numero da mesa não informado.\n');	 
	 	}elseif(is_null($id)){
	   		$output=array('erro'=>'identificação do item não informada');
	 	}else{
	    	
	  		$array_id = split(",", $id);	 
	  		$i = 0;
	  
	  		//Armazena na variável $join_id a string para pegar todos os itens passados pela URL. Ela vai ser jogada no Join.
	  		while($i < sizeof($array_id)){
	  		if($i == 0){	  	 
	  	  		$join_id = $array_id[0];		  							
			}else{		 	
	      		$join_id = $join_id .' OR wp_postmeta.meta_value = ' .$array_id[$i];	  
			}
			$i++;		
	  	}       
	  
	  	$params_item = array();	
	    
	  	if(sizeof($array_id) > 1){	  	
	    	$params_item = array(
	    		'join'=> 'INNER JOIN wp_postmeta ON wp_postmeta.post_id = t.ID 
	                      AND (wp_postmeta.meta_key = "codigo_do_item" 
	                      AND (wp_postmeta.meta_value = ' .$join_id .'))'	     
	    	);		
	  	}else{
        	$params_item = array(
	      		'join'=> 'INNER JOIN wp_postmeta ON wp_postmeta.post_id = t.ID 
	                      AND (wp_postmeta.meta_key = "codigo_do_item" 
	                      AND wp_postmeta.meta_value = '.$id.')'	     
	    	);
	  	} 
	  	$item = pods('itens');
	  	$item->find($params_item);
	  
	 	 $remove= array("$", ",00");
	   	
	  	while($item->fetch()){
	   		$preco = $preco + str_replace($remove, "", $item->display('preco'));
	  	}  	  	
	   
	  	$fields = array(
	    	'e'               => $e,
			'mesa'            => $m,
	   	 	'itens_do_pedido' => $id,	
	    	'preco'           => $preco,          
	    	'post_status'     => 'publish'        		
	 	 );		
	   					
	  	$pedido = pods('pedidos');
	  	$new_id = $pedido->save($fields);
	  	$output=array('id'=>$new_id,'sucesso'=>'Pedido cadastrado.');     	
	 
	  	return $output; 	    
      }
    }
	
	public function add_item() {
		global $json_api;	  
	 	extract($json_api->query->get(array('id_pedido', 'id_item')));
	 
		if(is_null($id_pedido)){
			$output=array('erro'=>'identificação do pedido não foi informada');
		}
	 	else{
	  		$pedido = pods('pedidos', $id_pedido);
	   		$pedido->find();
		 
	   		$output[] = array(
	    		'lista' => $pedido->display('itens_do_pedido')
	  		);	   
	 	}
	
	 	return $output;		 
	}
	
	public function get_pedido() {
  		global $json_api;	  
  		extract($json_api->query->get(array('id_pedido', 'e', 'm')));
	  
	  	function verifica_total($itens){	  	
	 		$total = split(",", $itens);		
			$total = sizeof($total);
			return $total;		
	 	 } 
	  
	  	if(is_null($id_pedido)){
	    	$output=array('erro'=>'identificação do pedido não informada.\n');	 
	  	}elseif(is_null($e)){
	    	$output=array('erro'=>'identificação do estabelecimento não informada.\n');	 
	  	}elseif(is_null($m)){
      		$output=array('erro'=>'identificação da mesa não informada.\n');
      	}else{	   
	   		$output = array(); 
	    	$pedido = pods('pedidos');
		
	    	$params = array(
	    		'join' => 'INNER JOIN wp_postmeta ON (wp_postmeta.post_id = t.ID) 
	                       AND (wp_postmeta.meta_key = "mesa" AND wp_postmeta.meta_value = '.$m.')', 
	               	    
	    	'	where'=> 't.ID = '.$id_pedido	   
			);	 
	    	$pedido->find($params);
	 
	    	while($pedido->fetch()){
	      		$output[]= array(	 	 	    
	 	    		'estado_do_pedido' => $pedido->display('estado_do_pedido'),	
	 	    		'total'            => verifica_total($pedido->display('itens_do_pedido')),										  			  
		    		'preco'            => $pedido->display('preco'),
		    		'lista'  => $pedido->display('itens_do_pedido'),			
		  		);
	    	}		    
	  	}	  
	  	return $output;
	}	
	
	public function remove_item() {
		global $json_api;	  
		extract($json_api->query->get(array('id_pedido','e','m')));		 
	 
	 	$pedido = pods('pedidos', $id_pedido);	 	 
	 	$params= array(
	 		'where'=> 't.ID = '. $id_pedido
		);	 	 
	 	$pedido->find($params);
	
		while($pedido->fetch()){
	 		$output= array(
			'lista'=> $pedido->display('itens_do_pedido')
			);		
	 	}	 
	 	$lista = split(",", $output['lista']);
	 	 	 
	 	return "";
	}	
	
	public function confirm_pedido() {
		global $json_api;	  
	  	extract($json_api->query->get(array('id_pedido', 'e', 'm')));
	 
	 	if(is_null($id_pedido)){
	    	$output=array('erro'=>'identificação do pedido não informada');	 
	  	}elseif(is_null ($e)){
	    	$output = array('erro'=> 'identificacao do estabelecimento não informada');
	  	}elseif(is_null ($m)){
		    $output = array('erro'=> 'identificacao da mesa não informada');	    	 
	  	}else{	 	
	    	$pedido = pods('pedidos', $id_pedido);
	  			  		 
	    	$data = array(
	        	'estado_do_pedido' => '0'
	    	);		
	 
	    	$new_id = $pedido->save($data);
		
			$params = array(
		 		'where'=> 't.ID = ' .$id_pedido
			);
		
	    	$pedido->find($params);
	 
	    	while($pedido->fetch()){
	      		$output[]= array('estado_do_pedido' => $pedido->display('estado_do_pedido'));
			}	 
	  }
	  	
	  return $output;	 
    }	
} 
?>