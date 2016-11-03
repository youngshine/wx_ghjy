<?php
header("Content-type:text/html; Charset=utf-8");

$xml_uri = 'db/county.xml';
//$current = array();
//$forecast = array();
$country = '中国'; //$_REQUEST['country'];
$state = $_REQUEST['state'];
$city = $_REQUEST['city'];

$ret = array();

$reader = new XMLReader();
$reader->open($xml_uri);

// 1.全部省份
if($state == null || empty($state)){
	while ($reader->read()) {
	    if ($reader->name == "State" && $reader->nodeType == XMLReader::ELEMENT){ 	
		  	$value = $reader->getAttribute('Name');
			array_push($ret,$value);
		}
	}		
}

// 2.某个省份下属地区
if(!empty($state) && empty($city)){
	while ($reader->read()) {
		if ($reader->name == "State" && $reader->nodeType == XMLReader::ELEMENT){ 	
			$value = $reader->getAttribute('Name');
			if($value == $state){
				while($reader->read() && $reader->getAttribute('Name') != $state ) {
				    if ($reader->name == "City" && $reader->nodeType == XMLReader::ELEMENT){
					  	$value = $reader->getAttribute('Name');
					  	//echo $value;
						array_push($ret,$value);
				    }
				}
			}	
		}
	}  	
}
// 3.某个省份下属某个地区的所有县区
if(!empty($state) && !empty($city)){
	while ($reader->read()) {
		if ($reader->name == "State" && $reader->nodeType == XMLReader::ELEMENT){ 	
			$value = $reader->getAttribute('Name');
			if($value == $state){
				while($reader->read() && $reader->getAttribute('Name') != $state ) {
				    if ($reader->name == "City" && $reader->nodeType == XMLReader::ELEMENT){
					  	$value = $reader->getAttribute('Name');
					  	if($value == $city){
							while($reader->read() && $reader->getAttribute('Name') != $city ) {
							    if ($reader->name == "Region" && $reader->nodeType == XMLReader::ELEMENT){
								  	$value = $reader->getAttribute('Name');
								  	//echo $value;
									array_push($ret,$value);
							    }
							}
					  	}
				    }
				}
			}	
		}
	}  	
}

$reader->close();

echo json_encode($ret);


/*
while ($reader->read()) {
  //if ($reader->nodeType == XMLReader::TEXT)
  if ($reader->name == "State" && $reader->nodeType == XMLReader::ELEMENT){ 	
	$value = $reader->getAttribute('Name');
	if($value == $state){
		while($reader->read() && $reader->getAttribute('Name') != $state ) {
		    if ($reader->name == "City" && $reader->nodeType == XMLReader::ELEMENT){
			  	$value = $reader->getAttribute('Name');
			  	//echo $value;
				array_push($ret,$value);
		    }
		}
	}	
  }
*/ 
  /*
  //get current data
  if ($reader->name == "current_conditions" && $reader->nodeType == XMLReader::ELEMENT) {
    while($reader->read() && $reader->name != "current_conditions") {
      $name = $reader->name;
      $value = $reader->getAttribute('data');
      $current[$name] = $value;
    }
  }

  //get forecast data
  if ($reader->name == "forecast_conditions" && $reader->nodeType == XMLReader::ELEMENT) {
    $sub_forecast = array();
    while($reader->read() && $reader->name != "forecast_conditions") {
      $name = $reader->name;
      $value = $reader->getAttribute('data');
      $sub_forecast[$name] = $value;
    }
    $forecast[] = $sub_forecast;
  } */
//}
//$reader->close();

//print_r($ret);

//echo json_encode($ret);
/*
echo json_encode(array(
	"fileName" => 'abc'
)); */

?>