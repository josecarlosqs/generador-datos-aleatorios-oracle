<?php
require("misc.class.php");

$cantidad = 50;
$cantidadEmpleados = 8;
$resul="";


$bd = new Badatos();
$p = new Procesador();
$g = new Generador();

$dR = $bd->query("SELECT num, direccion FROM direcciones ORDER BY RANDOM()"); 
$aP = $bd->query("SELECT num, apellido FROM apellidos ORDER BY RANDOM()"); 
$aM = $bd->query("SELECT num, apellido FROM apellidos ORDER BY RANDOM()"); 
$nM = $bd->query("SELECT num, nombre FROM nombres ORDER BY RANDOM()"); 


$docu = array();
$direccion = array();
$apePat = array();
$apeMat = array();
$nom = array();

$clientes = array();
$empleados = array();
$fechaCli = array();

$servicios = array("drive","docs","hojas de calculo","diapositivas","gmail","sites","calendar","google plus");

for($i=0; $i<$cantidad; $i++){
	do{
		$f = $g->generarNum();
	}while(in_array($f, $docu));

	array_push($docu, $g->generarNum());
}

$k=0;
while ($row = $dR->fetchArray()){ 
	array_push($direccion, $row[1]);
	$k++;
	if($k == $cantidad){
		break;
	}
} 

$k=0;
while ($row = $aP->fetchArray()){ 
	array_push($apePat, mb_strtoupper($row[1], 'utf-8'));
	$k++;
	if($k == $cantidad){
		break;
	}
} 

$k=0;
while ($row = $aM->fetchArray()){
	array_push($apeMat, mb_strtoupper($row[1], 'utf-8'));
	$k++;
	if($k == $cantidad){
		break;
	}
} 

$k=0;
while ($row = $nM->fetchArray()){
	array_push($nom, mb_strtoupper($row[1], 'utf-8'));
	$k++;
	if($k == $cantidad){
		break;
	}
}

$resul .= "-- Datos de la tabla TIPO_DOCUMENTO
INSERT INTO TIPO_DOCUMENTO VALUES(1,'DNI');
INSERT INTO TIPO_DOCUMENTO VALUES(2,'CARNET EXTRANJERIA');
INSERT INTO TIPO_DOCUMENTO VALUES(3,'RUC');

-- Datos de la tabla TIPO_CONTACTO
INSERT INTO TIPO_CONTACTO VALUES(1,'CORREO');
INSERT INTO TIPO_CONTACTO VALUES(2,'CASA');
INSERT INTO TIPO_CONTACTO VALUES(3,'CELULAR');
INSERT INTO TIPO_CONTACTO VALUES(4,'OFICINA');

-- Datos de la tabla TIPO_PERSONA
INSERT INTO TIPO_PERSONA VALUES(1,'NATURAL');
INSERT INTO TIPO_PERSONA VALUES(2,'JURIDICA');

-- Datos de la tabla CARGO
INSERT INTO CARGO VALUES(1,'ADMINISTRADOR',4000);
INSERT INTO CARGO VALUES(2,'SUPERVISOR',2000);
INSERT INTO CARGO VALUES(3,'VENDEDOR',800);

-- Datos de la tabla BENEFICIO
INSERT INTO BENEFICIO VALUES(1,'Beneficio correspondiente a clientes con mas de 1 año de pagos al dia.');
INSERT INTO BENEFICIO VALUES(2,'Beneficio correspondiente a clientes con mas de 5 años de pagos al dia.');
INSERT INTO BENEFICIO VALUES(3,'Beneficio correspondiente a clientes con mas de 10 años.');
";

$resul .= "-- Datos de la tabla DOCUMENTO\n";
for($i=0; $i<$cantidad;$i++){
	$resul .= "INSERT INTO DOCUMENTO VALUES(".$docu[$i][0].",".$docu[$i][1].");\n";
}

$resul .= "\n\n";
$resul .= "-- Datos de la tabla PERSONA\n";
for($i=0; $i<$cantidad;$i++){
	$resul .= "INSERT INTO PERSONA VALUES(".$docu[$i][0].",'".$nom[$i]."','".$apePat[$i]."','".$apeMat[$i]."','".mb_strtoupper(sprintf($direccion[$i], ''.rand(100,999)),'utf-8')."',1);\n";
}

$resul .= "\n\n";
$resul .= "-- Datos de la tabla CONTACTO\n";
$doms = array("hotmail.com", "outlook.com", "gmail.com", "usmp.pe", "yahoo.com");
for ($i=0; $i < $cantidad; $i++) { 
	$resul .= "INSERT INTO CONTACTO VALUES(".$docu[$i][0].",1,'".$p->genNick($nom[$i],$apePat[$i],$apeMat[$i])."@".$doms[rand(0,4)]."');\n";

	if(round(rand(0,999))%2 == 0)
		$resul .= "INSERT INTO CONTACTO VALUES(".$docu[$i][0].",2,'".rand(3000000,7000000)."');\n";
	if(round(rand(0,999))%3 == 0)
		$resul .= "INSERT INTO CONTACTO VALUES(".$docu[$i][0].",3,'".rand(900000000,999999999)."');\n";
	if(round(rand(0,999))%5 == 0)
		$resul .= "INSERT INTO CONTACTO VALUES(".$docu[$i][0].",4,'".rand(3000000,7000000)."');\n";
}

$resul .= "\n\n";
$resul .= "-- Datos de la tabla EMPLEADO\n";

for ($i=0; $i < $cantidadEmpleados; $i++) { 
	$n = rand(0,$cantidad-1-$i);
	switch($i){
		case 0:
			$resul .= "INSERT INTO EMPLEADO VALUES(".$docu[$n][0].",1);\n";
		break;

		case 1:
			$resul .= "INSERT INTO EMPLEADO VALUES(".$docu[$n][0].",2);\n";
		break;
		
		case 2:
			$resul .= "INSERT INTO EMPLEADO VALUES(".$docu[$n][0].",2);\n";
		break;

		default:
			$resul .= "INSERT INTO EMPLEADO VALUES(".$docu[$n][0].",3);\n";
		break;
	}
	array_push($empleados, $docu[$n]);
}

$resul .= "\n\n";
$resul .= "-- Datos de la tabla CLIENTE\n";

for($i=0; $i<$cantidad;$i++){
	if(!in_array($docu[$i], $empleados)){
		$fecha = ''.rand(1999,2014).'-0'.rand(1,5).'-'.$g->numAle(1,30,2);
		$ben = 'null';
		if(rand(0,7)%7==0){
			$ben=''.rand(1,3);
		}
		$resul .= "INSERT INTO CLIENTE VALUES(".$docu[$i][0].",".$ben.",TO_DATE('".$fecha."', 'YYYY-MM-DD'));\n";
		array_push($clientes, $docu[$i]);
		array_push($fechaCli, $fecha);
	}
}


$resul .= "\n\n";
$resul .= "-- Datos de la tabla CREDENCIAL\n";

for($i=0; $i<count($clientes); $i++){
	$nuevafecha = strtotime ( '+'.rand(1,5).' days' , strtotime ( $fechaCli[$i] ) ) ;
	$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
	$resul .= "INSERT INTO CREDENCIAL VALUES(".$clientes[$i][0].",'".$g->generarCa(15)."', TO_DATE('".$nuevafecha."','DD/MM/YYYY'));\n";
}

$resul .= "\n\n";
$resul .= "-- Datos de la tabla VENTA\n";

for($i=0; $i<count($clientes); $i++){
	$estados = "PV";
	$resul .= "INSERT INTO VENTA VALUES(".($i+1).", ".$clientes[$i][0].",TO_DATE('".$fechaCli[$i]."', 'YYYY-MM-DD'), '".$estados[rand(0,1)]."');\n";
}

$resul .= "\n\n";
$resul .= "-- Datos de la tabla PEDIDO_BACKUP\n";

$numpedi = 1;
$estPed="PE";
$tiPed="TP";
for($i=0; $i<count($clientes); $i++){
	if(rand(0,200) % 7 == 0){
		$nuevafecha = strtotime ( '+'.rand(1,7).' days' , strtotime ("2014-05-26") ) ;
		$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
		$resul .= "INSERT INTO PEDIDO_BACKUP VALUES(".$numpedi.", ".$i.",TO_DATE('2014-05-26', 'YYYY-MM-DD'),TO_DATE('".$nuevafecha."', 'YYYY-MM-DD'), '".rand(200,2000)."', '".$estPed[rand(0,1)]."', '".$tiPed[rand(0,1)]."');\n";
		$numpedi++;
	}
}

$resul .= "\n\n";
$resul .= "-- Datos de la tabla SERVICIO\n";
 for($i=0; $i<7;$i++){
 	for($j=1;$j<=3;$j++){
 		$resul .= "INSERT INTO SERVICIO VALUES (".$i.",".$j.",'".ucwords($servicios[$i])."');\n";
 	}
 }

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Generador de datos aleatorios</title>
	<script type="text/javascript" src="js/shCore.js"></script>
	<script type="text/javascript" src="js/shBrushSql.js"></script>
	<link type="text/css" rel="stylesheet" href="styles/shCoreDefault.css"/>
	<script type="text/javascript">SyntaxHighlighter.all();</script>
</head>
<body style="background: white; font-family: Helvetica">
	<pre class="brush: sql"><?=$resul;?></pre>
</body>
</html>