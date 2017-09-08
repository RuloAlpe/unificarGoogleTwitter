<?php
$annotation = $language->annotateText($twittsEnUnaLinea);
$personas = $annotation->entitiesByType('PERSON');
$organizaciones = $annotation->entitiesByType('ORGANIZATION');
$localidades = $annotation->entitiesByType('LOCATION');
var_dump($personas);
exit();

if($personas){
    foreach($personas as $persona){
        echo "Nombre:" . $persona['name'];
        echo "<br>";        
        echo "Menciones: " . count($persona['mentions']);
        echo "<hr>";
    }
}

if($organizaciones){
    foreach($organizaciones as $organizacion){
        echo "Organizacion:" . $organizacion['name'];
        echo "<br>";                
        echo "Menciones: " . count($organizacion['mentions']);
        echo "<hr>";
    }
}

if($localidades){
    foreach($localidades as $localidad){
        echo "Localidad:" . $localidad['name'];
        echo "<br>";                
        echo "Menciones: " . count($localidad['mentions']);
        echo "<hr>";
    }
}
?>

