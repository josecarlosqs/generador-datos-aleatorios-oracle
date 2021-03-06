<?php
class Badatos extends SQLite3
{
    public function __construct()
    {
        $this->open('badatos.db');
    }
}


class Generador{
	public function __construct(){
    }
	public function generarCa($car){
		$l="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890=)({}^*|[]#@ ";
		$cad="";
		for($i=0;$i<$car;$i++){
			$cad .=$l[rand(0,strlen($l)-1)];
		}
		return $cad;

	}

	public function numAle($de,$a,$cif){
		$num = ''.rand($de, $a);
		$fin='';
		for($i=0;$i<$cif-strlen($num);$i++){
			$fin .= '0';
		}
		$fin .= $num;
		return $fin;
	}

	public function generarDoc($digitos){
		$num = "";
		for($i=0;$i<$digitos;$i++){
			switch($i){
				case 0:
				$v = rand(0,7);
				break;
				case 1:
				$v = rand(0,2);
				break;
				default:
				$v = rand(0,9);
				break;
			}
			$num .= $v;
		}
		return $num;
	}

}

class Procesador{
	public function __construct(){
    }
	public function deslatinizar($txt){
		$txt = str_replace('Á', 'A', $txt);
		$txt = str_replace('É', 'E', $txt);
		$txt = str_replace('Í', 'I', $txt);
		$txt = str_replace('Ó', 'O', $txt);
		$txt = str_replace('Ú', 'U', $txt);
		$txt = str_replace('Ñ', 'N', $txt);
		return $txt;
	}

	public function genNick($n, $a1, $a2){
		return strtolower($this->deslatinizar($n))[0].strtolower($this->deslatinizar($a1)).strtolower($this->deslatinizar($a2))[0].rand(10,99);
	}

	public function comprobarExistencia($cual, $de){
		$f=0;
		if(count($de)>1){
			for($i=0;$i<count($de);$i++){
				if($de[$i] == $cual){
					$f=1;
					break;
				}
			}
		}
		return $f;
	}

	public function modificarFecha($fecha, $dias){
		$nuevafecha = strtotime ($dias.' days',strtotime($fecha));
		return date ('Y-m-d',$nuevafecha);
	}
}

?>