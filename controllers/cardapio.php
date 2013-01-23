<?php
/*
Controller name: Cardápio
Controller description: Oh Garçom/IBTI Cardápio 
*/
	 

class JSON_API_Cardapio_Controller {
  	
	
	public function get_lista_categoria() {
		global $json_api;	  
		extract($json_api->query->get(array('e')));
		 
		$categorias= pods( 'categorias_dos_itens');
		$params = array(
						'orderby' => 't.term_id DESC'
		);
		$categorias->find($params);
		$output=array();
		
		while ( $categorias->fetch() ){
			
   			$output[]= 	array('Id'=>$categorias->display( 'term_id'),
   							'Nome'=>$categorias->display( 'name'),
   							'Slug'=>$categorias->display( 'slug'),
			);
		}
 	 $output=array_merge(array('Total'=>$categorias->total()),$output);
	 return $output;
	}
	
	public function get_item() {
		global $json_api;	  
		extract($json_api->query->get(array('id','code','slug')));
		if(is_null($id)){
			$output=array('erro'=>'ID do item não informado.');
		}elseif(is_null($code)){
			$output=array('erro'=>'Codigo do item não informado.');	
		}elseif(is_null($slug)){
			$output=array('erro'=>'Slug do item não informado.');
		}else{
			
			$itens= pods( 'itens');
			$params = array(
							'orderby' => 't.id DESC',
							'where'=>"(t.post_name like '%".$slug."%')
							and(t.id=".$id.')'
			);
			
			$itens->find($params);
			$output=array();
			
			while ( $itens->fetch() ){
				if($itens->display( 'codigo_do_item')==$code){
		   			$output= array('Id'=>$itens->display( 'ID'),
		   							'Codigo'=>$itens->display( 'codigo_do_item'),
		   							'Preço'=>$itens->display( 'preco'),
		   							'Foto'=>$itens->display( 'foto_principal'),
		   							'Calorias'=>$itens->display( 'calorias'),
		   							'Layout'=>$itens->display( 'layout_da_tela'),
		   							'Impressora'=>$itens->display( 'impressora'),
		   							'Adicionais'=>$itens->display( 'adicionais_'),
		   							'Retiraveis'=>$itens->display( 'retiraveis'),
					);
				}else{
					$output=array('erro'=>'Codigo do item não encontrado.');	
				}
			}
		}
	 return $output;
	}
	
	public function get_lista_item() {
		global $json_api;
		extract($json_api->query->get(array('e','c')));
		
	 	$params_itens = array('join'=>'INNER JOIN `wp_term_relationships` ON `wp_term_relationships`.`object_id` = t.id AND wp_term_relationships.term_taxonomy_id='.$c);
		$itens= pods('itens');
		$itens->find($params_itens);
		
		$params_cat = array('where'=>'term_taxonomy_id='.$c);
		$categoria= pods('categorias_dos_itens');
		$categoria->find($params_cat);
		$output=array();
		
		while ( $itens->fetch() ){
			
   			$output[]= 	array('Id'=>$itens->display( 'ID'),
   							'Codigo'=>$itens->display( 'codigo_do_item'),
   							'Preço'=>$itens->display( 'preco'),
   							'Foto'=>$itens->display( 'foto_principal'),
   							'Calorias'=>$itens->display( 'calorias'),
   							'Layout'=>$itens->display( 'layout_da_tela'),
   							'Impressora'=>$itens->display( 'impressora'),
   							'Adicionais'=>$itens->display( 'adicionais_'),
   							'Retiraveis'=>$itens->display( 'retiraveis'),
   							'Categoria'=>$categoria->display( 'name'),
			);
		}
		$output=array_merge(array('Total'=>$itens->total()),$output);
		
		return $output;
		
	}
	
	public function search_itens() {
		global $json_api;
		extract($json_api->query->get(array('e','k','p')));
		if(is_null($k)){
			$output=array('erro_1'=>'Palavra chave não informada da situação não informada.');
		}elseif(is_null($p)){
			$output=array('erro_2'=>'Preço não informada da situação não informada.');
		}else{
			$itens= pods( 'itens');
			$params = array(
							'orderby' => 't.id DESC',
							'where'=>"t.post_content like '%".$k."%'"
							//'join'=>"INNER JOIN `wp_postmeta` ON `wp_postmeta`.`post_id` = t.ID AND (meta_key='preco') AND ((wp_postmeta.meta_valuea>".($p-1).') AND (wp_postmeta.meta_value<'.($p+1).'))'
							//esquece join faz a verificação no while precisa converter o valor do preço
			);
			
			$itens->find($params);
			$output=array();
			$total=0;
			$result=array();
			
			while ( $itens->fetch() ){
				$preco=0+str_replace('$','',$itens->display( 'preco'));
				$p = (is_null($p)) ? $preco : $p;
				if(($preco<$p+1)&&($preco>$p-1)){
					$total=$total+1;
		   			$result[]=array('Codigo'=>$itens->display( 'codigo_do_item'),
		   							'Preço'=>$itens->display( 'preco'),
		   							'Foto'=>$itens->display( 'foto_principal'),
		   							'Calorias'=>$itens->display( 'calorias'),
		   							'Layout'=>$itens->display( 'layout_da_tela'),
		   							'Impressora'=>$itens->display( 'impressora'),
		   							'Adicionais'=>$itens->display( 'adicionais_'),
		   							'Retiraveis'=>$itens->display( 'retiraveis'),
		   							'Categoria'=>$itens->display( 'categorias_'),
					);					
				}
			}
			$output=array_merge(array('Total'=>$total),$result);
		}
		
	
		return $output;
	}
	
} 
?>