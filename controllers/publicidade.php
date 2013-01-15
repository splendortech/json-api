<?php
/*
Controller name: Publicidade
Controller description: Oh Garçom/IBTI Publicidade 
*/	 

class JSON_API_Publicidade_Controller {
  	
	public function get_lista_publicidade() {
	  global $json_api;	 
	  extract($json_api->query->get(array('')));
	  	 	  
	  function get_img($id){ 		  
  	    $sql_img = "
			  SELECT DISTINCT `m`.`meta_value`
			  FROM `wp_postmeta` AS m
			  WHERE `m`.`post_id` = ( 
			    SELECT DISTINCT `m`.`meta_value`	        
			    FROM `wp_posts` AS `t`
			    INNER JOIN `wp_postmeta` AS `m`
			    ON `t`.`ID` = `m`.`post_id` 
			    WHERE (`m`.`meta_key` = '_thumbnail_id') AND (`t`.`id` = ".$id.")
			  ) AND `meta_key` = '_wp_attached_file'
             ";	 
				 
	    return $sql_img;		  
      }	
			  
	  $publicidade = pods('publicidade');		  
	  $data = date("Y-m-d");	
	 	  	  
	  $sql = "
		      SELECT DISTINCT `t`.*, `m`.*		        
	          FROM `wp_posts` AS `t`
	          INNER JOIN `wp_postmeta` AS `m`
	          ON `t`.`ID` = `m`.`post_id`
	          WHERE 
	              (`m`.`meta_key` = 'data_fim' AND
	              STR_TO_DATE(`m`.`meta_value`, '%Y-%m-%d') >= '".$data."') AND
	              (`t`.`post_type` = 'publicidade') AND
	              (`post_status` = 'publish') 	                    
	          ORDER BY `menu_order`, `post_title`,`post_date` 	         
     		 ";
	 
	  $publicidade->find(null, 0, null, $sql);	 	     
	  $output = array(); 	 		 
	  
	  while($publicidade->fetch()){
	  	$img = pods('publicidade');
	  	$img->find(null, 0, null, get_img($publicidade->display('ID'))); 	
	  	
	  	$output[] = array('title'           => $publicidade->display('post_title'),
						  'textoDescritivo' => $publicidade->display('post_content'),							  			  
						  'imagem'          => $img        ->display('meta_value'),
						  'data-incicio'	=> $publicidade->display('data_inicio'),	
						  'data-fim'        => $publicidade->display('data_fim')
		);	
		$img = null;			
	  }  
	  
	  $count = $publicidade->total();	 
	  array_unshift($output, $count); 
	  	
	  return $output;	
	}
} 
?>