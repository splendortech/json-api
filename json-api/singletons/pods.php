<?php

class JSON_API_Pods {
  
  public function get_pods($podname,$params=null) {
	  $output = array();
      $podr = new Pod($podname);
	  $podr->findRecords($params);
	  $i = 0;
	  while ($podr->fetchRecord())
	  {
		  
	   $output[$i++] = $podr->data;
	  }
	  if($i==0) return false;
	  else  
	  return $output;
  
  }
  
  public function get_pods_by_filter($podname,$fieldName,$start,$end,$pg,$params){
	
	$params = loadDefaultParams();
	
    $where = filterByField($fieldName,$start,$end);
	$params['where'] .= $where;
	return get_pods($podname,$params);
  }
  
  
  protected function filterByField($fieldName,$start, $end=null) {
    $output = '';
	if(is_null($end)){
	    $output = ' '.$fieldName.' >= '.$start;
	}else {
		$output = $fieldName.' BETWEEN '.$start.' AND '.$end;
	}
    return $output;
  }
  
 protected function loadDefaultParams() {
   $params = array();
   
  
 	$params['page_var'] = 'pg'; // custom page variable (Pods 1.12.4+)
	$params['search'] = false; // disable search
	$params['limit'] = 10;
 
  
   return $params;
 }
  
}

?>
