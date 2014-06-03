<?php
//
// Clase en PHP que genera los registros
// aleatorios para la base de datos.
require("misc.class.php");

$cantidad = 50;
$cantidadEmpleados = 8;
$cargoEmpleados = array(1,2,2,3,3,3,3,3);
if(count($cargoEmpleados) != $cantidadEmpleados){
	echo "Empleados no coinciden";
	exit;
}

// consts
$doms = array("hotmail.com", "outlook.com", "gmail.com", "usmp.pe", "yahoo.com");
$nCo = 0;
$bd = new Badatos();
$p = new Procesador();
$g = new Generador();

$sqDir = $bd->query("SELECT direccion FROM direcciones ORDER BY RANDOM()"); 
$sqApat = $bd->query("SELECT apellido FROM apellidos ORDER BY RANDOM()"); 
$sqAmat = $bd->query("SELECT apellido FROM apellidos ORDER BY RANDOM()"); 
$sqNom = $bd->query("SELECT nombre FROM nombres ORDER BY RANDOM()"); 

// arrays
$personas = array();
$clientes = array();
$empleados = array();
$docs = array();

$dR = array();
$aP = array();
$aM = array();
$nM = array();

// llenar arrays principales
while ($row = $sqDir->fetchArray()){ 
	array_push($dR, mb_strtoupper(sprintf($row[0], ''.rand(100,999)),'utf-8'));
} 
while ($row = $sqApat->fetchArray()){ 
	array_push($aP, mb_strtoupper($row[0], 'utf-8'));
} 
while ($row = $sqAmat->fetchArray()){ 
	array_push($aM, mb_strtoupper($row[0], 'utf-8'));
} 
while ($row = $sqNom->fetchArray()){ 
	array_push($nM, mb_strtoupper($row[0], 'utf-8'));
} 

// llenar arrays
for($i=0; $i<$cantidad; $i++){
	$qcdoc = rand(8,9);
	$doc = $g->generarDoc($qcdoc);
	array_push($docs, $doc);
	array_push($personas, array($doc,'ACTIVO',$nM[$i],$aP[$i],$aM[$i],$dR[$i],''.rand(1,2),''.rand(1,3)));
}


$txtPer = "-- Datos de la tabla PERSONA\n";
for($i=0; $i<$cantidad;$i++){
	$txtPer .= "INSERT INTO PERSONA VALUES('".$personas[$i][0]."','".$personas[$i][1]."','".$personas[$i][2]."','".$personas[$i][3]."','".$personas[$i][4]."','".$personas[$i][5]."','".$personas[$i][6]."','".$personas[$i][7]."');\n";
}

$txtContac = "-- Datos de la tabla CONTACTO\n";
for ($i=0; $i < $cantidad; $i++) { 
	$txtContac .= "INSERT INTO CONTACTO VALUES(".++$nCo.",'".$p->genNick($personas[$i][3],$personas[$i][4],$personas[$i][5])."@".$doms[rand(0,4)]."',1,'".$personas[$i][0]."');\n";

	if(round(rand(0,999))%2 == 0)
		$txtContac .="INSERT INTO CONTACTO VALUES(".++$nCo.",'".rand(3000000,7000000)."',2,'".$personas[$i][0]."');\n";
	if(round(rand(0,999))%3 == 0)
		$txtContac .="INSERT INTO CONTACTO VALUES(".++$nCo.",'".rand(900000000,999999999)."',3,'".$personas[$i][0]."');\n";
	if(round(rand(0,999))%5 == 0)
		$txtContac .="INSERT INTO CONTACTO VALUES(".++$nCo.",'".rand(3000000,7000000)."',4,'".$personas[$i][0]."');\n";
}

$txtClie = "-- Datos de la tabla CLIENTE\n";
$txtEmpl = "-- Datos de la tabla EMPLEADO\n";
$clavesEmpl = array_rand($personas,$cantidadEmpleados);
for ($i=0; $i < $cantidad; $i++) {
	$fecha = ''.rand(1999,2014).'-0'.rand(1,5).'-'.$g->numAle(1,28,2);
	if(in_array($i, $clavesEmpl)){
		$emple = --$cantidadEmpleados;
		array_push($empleados, array($docs[$clavesEmpl[$emple]],$cargoEmpleados[$emple],$fecha));
		$txtEmpl .= "INSERT INTO EMPLEADO VALUES('".$docs[$clavesEmpl[$emple]]."',".$cargoEmpleados[$emple].",TO_DATE('".$fecha."','YYYY-MM-DD'),null);\n";
	}else{
		array_push($clientes, array($docs[$i],$fecha));
		$txtClie .= "INSERT INTO CLIENTE VALUES('".$docs[$i]."',TO_DATE('".$fecha."','YYYY-MM-DD'),null);\n";
	}
}

$txtCrede = "-- Datos de la tabla CREDENCIAL\n";
for($i=0; $i<count($clientes);$i++){
	$txtCrede .= "INSERT INTO CREDENCIAL VALUES('".$clientes[$i][0]."','".$g->generarCa(10)."',TO_DATE('".$p->modificarFecha(date('Y-m-d'),'-'.rand(1,3))."','YYYY-MM-DD'));\n";
}

$txtVenta = "-- Datos de la tabla VENTA\n";
for($i=0; $i<count($clientes);$i++){
	$txtVenta .= "INSERT INTO VENTA VALUES(".($i+1).",TO_DATE('".$clientes[$i][1]."','YYYY-MM-DD'),'VENTA','".$empleados[rand(0,4)][0]."','".$clientes[$i][0]."');\n";
}

$txtVentaPaque = "-- Datos de la tabla VENTAPAQUETE\n";
for($i=0; $i<count($clientes);$i++){
	$txtVentaPaque .= "INSERT INTO VENTAPAQUETE VALUES (".rand(1,2).",".($i+1).");\n";
}

$txtSXP = "-- Datos de la tabla SXP\n";
for($i=1; $i<=5;$i++){
	for($j=1; $j<=8;$j++){
		$txtSXP .= "INSERT INTO SERVICIOXPAQUETE VALUES (".$i.",".$j.");\n";
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
<pre class="brush: sql">
-- Datos de la tabla TIPO_DOCUMENTO
INSERT INTO TIPO_DOCUMENTO VALUES(1,'DNI');
INSERT INTO TIPO_DOCUMENTO VALUES(2,'CARNET EXTRANJERIA');
INSERT INTO TIPO_DOCUMENTO VALUES(3,'RUC');

-- Datos de la tabla TIPO_PERSONA
INSERT INTO TIPO_PERSONA VALUES(1,'NATURAL');
INSERT INTO TIPO_PERSONA VALUES(2,'JURIDICA');

<?=$txtPer;?>

-- Datos de la tabla TIPO_CONTACTO
INSERT INTO TIPO_CONTACTO VALUES(1,'CORREO');
INSERT INTO TIPO_CONTACTO VALUES(2,'CASA');
INSERT INTO TIPO_CONTACTO VALUES(3,'CELULAR');
INSERT INTO TIPO_CONTACTO VALUES(4,'OFICINA');

<?=$txtContac;?>

-- Datos de la tabla CARGO
INSERT INTO CARGO VALUES(1,'ADMINISTRADOR',4000.00);
INSERT INTO CARGO VALUES(2,'SUPERVISOR',2000.00);
INSERT INTO CARGO VALUES(3,'VENDEDOR',800.00);

-- Datos de la tabla BENEFICIO
INSERT INTO BENEFICIO VALUES(1,'Beneficio correspondiente a clientes con mas de 1 año de pagos al dia.');
INSERT INTO BENEFICIO VALUES(2,'Beneficio correspondiente a clientes con mas de 5 años de pagos al dia.');
INSERT INTO BENEFICIO VALUES(3,'Beneficio correspondiente a clientes con mas de 10 años.');

<?=$txtClie;?>

<?=$txtEmpl;?>

<?=$txtCrede;?>

<?=$txtVenta;?>

-- Datos de la tabla PAQUETE
INSERT INTO PAQUETE VALUES (1,'Ideal para pequeñas empresas o para uso personal.','100GB','2.69');
INSERT INTO PAQUETE VALUES (2,'Mas capacidad para usuarios mas exigentes.','1TB','12.29');
INSERT INTO PAQUETE VALUES (3,'PROMO: Mas capacidad para usuarios mas exigentes.','1TB','10.99');
INSERT INTO PAQUETE VALUES (4,'PROMO: Mas capacidad para usuarios mas exigentes.','1TB','9.99');
INSERT INTO PAQUETE VALUES (5,'PROMO: Mas capacidad para usuarios mas exigentes.','1TB','9.00');

<?=$txtVentaPaque;?>

-- Datos de la tabla SERVICIO
INSERT INTO SERVICIO VALUES (1,'DRIVE');
INSERT INTO SERVICIO VALUES (2,'DOCS');
INSERT INTO SERVICIO VALUES (3,'HOJAS DE CALCULO');
INSERT INTO SERVICIO VALUES (4,'DIAPOSITIVAS');
INSERT INTO SERVICIO VALUES (5,'GMAIL');
INSERT INTO SERVICIO VALUES (6,'SITES');
INSERT INTO SERVICIO VALUES (7,'CALENDAR');
INSERT INTO SERVICIO VALUES (8,'GOOGLE PLUS');

<?=$txtSXP;?>
</pre>
</body>
</html>