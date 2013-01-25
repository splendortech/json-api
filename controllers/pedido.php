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
	    	
			$lista = split(",", $id);	 
  		$i = 0;
	  
	  	//Armazena na variável $join_id a string para pegar todos os itens passados pela URL. Ela vai ser jogada no Join.
			while($i < sizeof($lista)){
		  		if($i == 0){	  	 
		  	  		$join_id = $lista[0];		  							
					}else{		 	
		      	$join_id = $join_id .' OR wp_postmeta.meta_value = ' .$lista[$i];	  
					}
					$i++;		
			}       
	  
	  	$params_item = array();	
	    
	  	if(sizeof($lista) > 1){	  	
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
	  	$cardapio = pods('itens');
	  	$cardapio->find($params_item);
	  
	 	 $remove= array("$", ",00");
	   	
	  	/*while($cardapio->fetch()){
	   		$preco = $preco + str_replace($remove, "", $cardapio->display('preco'));
	  	}  	*/
	  	
	  	while($cardapio->fetch()){
						$valores[$cardapio->display('codigo_do_item')] = $cardapio->display('preco'); 																		
			}	  	
			
			$i = 0;
									
			while($i <= sizeof($lista)){													
				$soma += str_replace($remove, "", $valores[$lista[$i]]);			
				$i++;					
			}						
			
		  $preco = "$" .$soma .",00";			
	   
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
  	}
  	
  	return $output; 
	}
	
	public function add_item() {
		global $json_api;	  
	 	extract($json_api->query->get(array('id_pedido', 'id_item')));
	 
		if(is_null($id_pedido)){
	    	$output=array('erro'=>'identificação do pedido não informada.\n');	
	  	}elseif(is_null($id_item)){	  	
	    	$output=array('erro'=>'identificação do item não informado.\n');
					  	
      }else{      		
	  		$pedido = pods('pedidos', $id_pedido);	 	 
			 	$params= array(
			 		'where'=> 't.ID = '. $id_pedido
				);	 	 
			 	$pedido->find($params);				
											
				while($pedido->fetch()){
			 		$output = array(
						'itens_do_pedido'=> $pedido->display('itens_do_pedido'),						
						'preco'          => $pedido->display('preco')
					);						
					$verifica = array(
						'post_status'    => $pedido->display('post_status')
					);	
			 	}	 
											
				if($verifica['post_status']!= 'publish' ){
					$output = array('erro'=> 'Objeto nao encontrado\n');					
				}
					
				else{							
				 	$lista = split(",", $id_item);									
					$cardapio = pods('itens');							
					
					//Armazena na variável $join_id a string para pegar todos os itens passados pela URL. Ela vai ser jogada no Join.
					while($i < sizeof($lista)){
				  		if($i == 0){	  	 
				  	  		$join_id = $lista[0];		  							
							}else{										 	
				      	$join_id = $join_id .' OR wp_postmeta.meta_value = ' .$lista[$i];														
							}
							$i++;		
					}
						    
			  	$params_item = array();	
			    
			  	if(sizeof($lista) > 1){	  	
			    	$params_item = array(
			    		'join'=> 'INNER JOIN wp_postmeta ON wp_postmeta.post_id = t.ID 
			                  	AND (wp_postmeta.meta_key = "codigo_do_item" 
			                    AND (wp_postmeta.meta_value = ' .$join_id .'))'	     
			    	);		
			  	}else{
		        	$params_item = array(
			      		'join'=> 'INNER JOIN wp_postmeta ON wp_postmeta.post_id = t.ID 
			                      AND (wp_postmeta.meta_key = "codigo_do_item" 
			                      AND wp_postmeta.meta_value = '.$id_item.')'	     
			    	);
					}			
					
					$cardapio->find($params_item);			
				  
					
					while($cardapio->fetch()){
						$valores[$cardapio->display('codigo_do_item')] = $cardapio->display('preco'); 																		
					}							
						  
					$remove= array("$", ",00");						
					$i = 0;
									
					while($i <= sizeof($lista)){													
						$soma += str_replace($remove, "", $valores[$lista[$i]]);			
						$i++;					
					}						
				
		   		$soma += str_replace($remove, "", $output['preco']);							
		   		$output['preco'] = "$" .$soma .",00";			
					
					if($output['itens_do_pedido'] == null){
						$output['itens_do_pedido'] = implode(",", $lista);	
					}else{
						$output['itens_do_pedido'] = $output['itens_do_pedido'] ."," .implode(",", $lista);	
					}
					
	        $pedido->save($output, null, $id_pedido);		
	        unset($output['itens_do_pedido']);							
	      }        			
			} 	
			
			return $output;
	}	
	
	public function get_pedido() {
  		global $json_api;	  
  		extract($json_api->query->get(array('id_pedido', 'e', 'm')));
	  
	  	function verifica_total($itens){
	  		if($itens == null){
	  			return 0;
	  		}else{	  	
	 				$total = split(",", $itens);		
					$total = sizeof($total);
					return $total;
				}		
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
	    			'join' => 'INNER JOIN wp_postmeta ON (t.id = wp_postmeta.post_id AND t.id = ' .$id_pedido .') 
                       AND (wp_postmeta.meta_key = "mesa" AND wp_postmeta.meta_value = '.$m.')', 	       
					);	 
	    		$pedido->find($params);
	 
	    		while($pedido->fetch()){
	      		$output[]= array(	 	 	    
	 	    			'estado_do_pedido' => $pedido->display('estado_do_pedido'),	
	 	    			'total'            => verifica_total($pedido->display('itens_do_pedido')),										  			  
		    			'preco'            => $pedido->display('preco'),
		    			'lista'            => $pedido->display('itens_do_pedido'),			
		  			);
	    	  }		    
	  	  }	  
	  	  return $output;		
	}	
	
	public function remove_item() {
		global $json_api;	  
		extract($json_api->query->get(array('id_pedido','e','m')));		 
	 
	  if(is_null($id_pedido)){
	    	$output=array('erro'=>'identificação do pedido não informada.\n');	
	  	}elseif(is_null($e)){	  	
	    	$output=array('erro'=>'identificação do estabelecimento não informada.\n');	 
	  	}elseif(is_null($m)){	  		
      		$output=array('erro'=>'identificação da mesa não informada.\n');
      }else{
      		
	  		$pedido = pods('pedidos', $id_pedido);	 	 
			 	$params= array(
			 		'join'=> 'INNER JOIN wp_postmeta ON wp_postmeta.post_id = t.ID 
			 						    AND (t.ID = ' .$id_pedido. ')
			                AND (wp_postmeta.meta_key = "mesa" 			                    
			                AND wp_postmeta.meta_value = '.$m.')'
				);	 	 
			 	$pedido->find($params);			
				
											
				while($pedido->fetch()){
			 		$output = array(
						'itens_do_pedido'=> $pedido->display('itens_do_pedido'),
						'preco'          => $pedido->display('preco')
					);						
					$verifica = array(
						'post_status'    => $pedido->display('post_status')
					);	
			 	}	 
				
				if($verifica['post_status']!= 'publish' ){
						$output = array('erro'=> 'Objeto nao encontrado\n');										
				}else{						
					if($output['itens_do_pedido']	== null){
					$output=array('erro'=> 'O pedido referido não possui nenhum item');
						
					}else{							
					 	$lista = split(",", $output['itens_do_pedido']);					
						$item = array_pop($lista);			
						
						$cardapio = pods('itens');
		
						$params_item = array(
			      	'join'=> 'INNER JOIN wp_postmeta ON wp_postmeta.post_id = t.ID 
			                    AND (wp_postmeta.meta_key = "codigo_do_item" 			                    
			                    AND wp_postmeta.meta_value = '.$item.')'													
		        );	 
						
						$cardapio->find($params_item);			
					
						while($cardapio->fetch()){					
							$valor = $cardapio->display('preco');					
						}		  
					 
						$remove= array("$", ",00");	   	 	
			   		$valor = str_replace($remove, "", $valor);
						$output['preco'] = str_replace($remove, "", $output['preco']);				
						$output['preco'] = $output['preco'] - $valor; 
						$output['preco'] = "$" .$output['preco'] .",00";				
						$output['itens_do_pedido'] = implode(",", $lista);								
		        $pedido->save($output, null, $id_pedido);				
		      }    
		    }    		
        return $output;						
			} 	 	
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
			  'join' =>  'INNER JOIN wp_postmeta ON t.ID = wp_postmeta.post_id 
			              AND t.ID = ' .$id_pedido .' 
			              AND wp_postmeta.meta_key = "mesa" 
			              AND wp_postmeta.meta_value = ' .$m		 		
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