<?php

function rebuildArray($data) 
{
    $data = $_POST;

	$temp_array = array_values($data);

		$count = count($temp_array[0]) ;
		for($j = 0; $j<$count; $j++) {
			$i = 0;
		foreach($data as $key => $value) {
    		$data[$key] = $temp_array[$i][$j];
    		$i++;
		}

		$gegevens[] = $data;

	}

	return $gegevens;
}

function rebuildExcelClassDataArray($exceldata, $excelheaders)
{ 
	//foreach($excelheaders as $excelheader) {
	//	$excelheader = strtolower($excelheader);
	//	$temparray[$excelheader] = '';
  	//}
  	$temparray = [
  		'leerling_id' => '',
  		'klas' => '',
  		'voornaam' => '',
  		'tussenvoegsel' => '',
  		'achternaam' => '',
  		'emailadres' => '' ,
  	];

  	$leerlingendata = [];  	
  	$count = count($exceldata);
  	
  	for($i=0; $i<$count; $i++) {
  		$leerlingendata[$i] = $temparray;
  	}

	for ($i=0; $i<$count; $i++) {
  		foreach ($leerlingendata as $leerling) {
			$j = 0;
			foreach ($leerling as $key => $value) {
  				if (!isset($exceldata[$i][$j])) {
  					$leerlingendata[$i][$key] = '';
  					$j++;
      			} else {
      				$value = $exceldata[$i][$j];
      				$leerlingendata[$i][$key] = $value;
      				$j++;
	      		}								
			}
		}
	}
	return $leerlingendata;
}

function checkArrayForEmptyValues($array) 
{
	foreach($array as $data) {
		foreach($data as $key => $value) {
			if($key != "tussenvoegsel") {
				if(empty($value)) {
					return false;
				}
			}
		}
	}
	return true;
}

function addKlasFilter($gegevens) 
{
    foreach($gegevens as $values => $keys) {
	    $gegevens[$values]["klas"] = filter_var($gegevens[$values]["klas"], FILTER_SANITIZE_STRING);
	    $gegevens[$values]["examenjaar"] = filter_var(trim($gegevens[$values]["examenjaar"]), FILTER_SANITIZE_NUMBER_INT);
	    $gegevens[$values]["niveau"] = filter_var(trim($gegevens[$values]["niveau"]), FILTER_SANITIZE_STRING);
	    $gegevens[$values]["docent_afk"] = filter_var(trim($gegevens[$values]["docent_afk"]), FILTER_SANITIZE_STRING);
	}
	return $gegevens;
}

function updateKlasFilter($gegevens) 
{
    $gegevens["klas"] = filter_var($gegevens["klas"], FILTER_SANITIZE_STRING);
    $gegevens["examenjaar"] = filter_var(trim($gegevens["examenjaar"]), FILTER_SANITIZE_NUMBER_INT);
    $gegevens["docent_afk"] = filter_var(trim($gegevens["docent_afk"]), FILTER_SANITIZE_STRING);
	return $gegevens;
}

function addLeerlingFilter($gegevens) 
{
	foreach($gegevens as $values => $keys) {
	    $gegevens[$values]["voornaam"] = filter_var($gegevens[$values]["voornaam"], FILTER_SANITIZE_STRING);
	    $gegevens[$values]["achternaam"] = filter_var(trim($gegevens[$values]["achternaam"]), FILTER_SANITIZE_STRING);
	    $gegevens[$values]["tussenvoegsel"] = filter_var($gegevens[$values]["tussenvoegsel"], FILTER_SANITIZE_STRING);//tussenvoegsel mag spatie bevatten
	    $gegevens[$values]["leerling_id"] = filter_var(trim($gegevens[$values]["leerling_id"]), FILTER_SANITIZE_STRING);
	    $gegevens[$values]["emailadres"] = filter_var(trim($gegevens[$values]["emailadres"]), FILTER_VALIDATE_EMAIL);
	}
	return $gegevens;
}