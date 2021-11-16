<?php
extract($_GET);
require "db_config_taller.php";
$pagina--;
$offset = $pagina*$registrosporpagina;
$buscar = (trim($buscar) == "" || trim($buscar) == "null") ? "" : trim($buscar);
$query = "SELECT COUNT(*) AS totales FROM moto_aa_servicios s LEFT JOIN moto_aa_vehiculos v ON s.vid = v.id LEFT JOIN moto_aa_mecanicos m ON s.mecanico_id = m.id WHERE folio LIKE '".$buscar."%' OR modelo LIKE '".$buscar."%' OR serie LIKE '".$buscar."%'";
$resulta = $mysqli->query($query);
$tots = $resulta->fetch_assoc();
$total = $tots['totales'];
$query = "SELECT s.id, fecha, folio, modelo, serie, trabajo, corto FROM moto_aa_servicios s LEFT JOIN moto_aa_vehiculos v ON s.vid = v.id LEFT JOIN moto_aa_mecanicos m ON s.mecanico_id = m.id WHERE folio LIKE '".$buscar."%' OR modelo LIKE '".$buscar."%' OR serie LIKE '".$buscar."%' ORDER BY folio DESC LIMIT ".$offset.",".$registrosporpagina;
$res = $mysqli->query($query);
$salida = array();
$renglon = array();
$temporal = array();
while($row = $res->fetch_assoc()){
    $temporal['id'] = $row['id'];
    $temporal['fecha'] = $row['fecha'];
    $temporal['folio'] = $row['folio'];
    $temporal['unidad'] = $row['modelo'];
    $temporal['serie'] = $row['serie'];  
    $temporal['trabajo'] = $row['trabajo'];
    $temporal['mecanico'] = $row['corto'];  
    $renglon[] = $temporal;
}
$salida['datos'] = $renglon;
$salida['totales'] = $total;
echo json_encode($salida);
?>