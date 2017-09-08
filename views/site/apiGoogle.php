<?php
$annotation = $language->annotateText($twittsEnUnaLinea);
if(count($categorias) > 1){
    foreach($categorias as $categoria){
        $elementos = $annotation->entitiesByType($categoria);
        if($elementos){
            foreach ($elementos as $elemento) {
                echo $categoria . ": " . $elemento['name'];
                echo "<br>";        
                echo "Menciones: " . count($elemento['mentions']);
                echo "<hr>";
            }
        }
    }
}
else{
    $elementos = $annotation->entitiesByType($categorias[0]);
    if($elementos){
        foreach ($elementos as $elemento){
            echo $categorias[0] . ": " . $elemento['name'];
            echo "<br>";        
            echo "Menciones: " . count($elemento['mentions']);
            echo "<hr>";
        }
    }
}
?>
