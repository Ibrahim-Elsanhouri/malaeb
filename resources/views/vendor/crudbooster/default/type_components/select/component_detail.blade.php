<?php 	
	if($form['datatable']) {		
		$datatable = explode(',', $form['datatable']);
		$table = $datatable[0];
		$field = $datatable[1];	
		echo CRUDBooster::first($table,['id'=>$value])->$field;
	}
	if($form['dataquery']) {
		$dataquery = $form['dataquery'];
		$query = DB::select(DB::raw($dataquery));	
		if($query) {
			foreach($query as $q) {
				if($q->value == $value) {
					echo $q->label;
					break;
				}
			}
		}					
	}

	if($form['dataenum'] && is_numeric($value)) {
		
		$val = ( $value != 1 ) ? 'لا' : 'نعم';
		echo "<span class='badge'>$val</span> ";
	}elseif($form['dataenum']){
		$v= explode(';', $form['dataenum']);
		foreach ($v as $key => $value_a) {
			$vv = explode('|', $value_a);
				// debug($value);die;
			if ( $value == $vv[0] ){
				$new_value = $vv[1];
				echo $new_value;
				break;
			}

			}
	}
?>