<?php

namespace Poli\Tarjeta;


class Tarjeta implements Int_Tarjeta{
	protected $saldo,$porcentaje, $ultimafecha=0,$ultimabicipaga=0,$pasajesPlus=0,$tiempomaxtransbordo=3600, $viajes,$trans=0,$id;
	protected $lunes, $dias = array(0 => "Lunes" , 1 => "Martes" , 2 => "Miercoles", 3 => "Jueves", 4 => "Viernes", 5 => "Sabado", 6 => "Domingo");

	public function __construct ($id){
		$this->saldo = 0;
		$this->porcentaje = 1;
		$this->lunes = strtotime("2016/01/04 00:00");
		$this->id = $id;
	}

	public function pagar(Transporte $transporte, $fecha_y_hora){ 

	//Para pagar Colectivos.

	if($transporte->getTipo()==1){ 

		$aux1 = strtotime($fecha_y_hora);
		$aux2 = strtotime($this->ultimafecha);

		$dia = $aux1 - $this->lunes;
		$a = $dia % 86400;
		$dia = $dia - $a;
		$dia = ($dia/86400) % 7;

		if(($dia==5 && $a>50400 && $a<79200) || ($dia==6 && $a>21600 && $a<79200) || $a<21600 || $a>79200 ){$this->tiempomaxtransbordo=5400;} else {$this->tiempomaxtransbordo=3600;}

		if($this->ultimafecha == 0 || ($aux1-$aux2>$this->tiempomaxtransbordo) || $this->viajes[$this->ultimafecha]->getTransporte()->getId() == $transporte->getid() || $this->trans==1){
			$costo = ($transporte->getCosto()*$this->porcentaje);
			$this->trans=0;
		} else {

			$costo = ($transporte->getCostoTrans()*$this->porcentaje);
			$this->trans=1;

			if($costo>$this->saldo){
				$costo = ($transporte->getCosto()*$this->porcentaje);
				$this->trans=0;
			}

		}


		if($this->pasajesPlus<2){
			if($costo>$this->saldo){
				$this->pasajesPlus++;
			}
	
			$this->saldo -= $costo;
			$this->viajes[$fecha_y_hora] = new Viaje($fecha_y_hora,$transporte,$costo);
			$this->ultimafecha = $fecha_y_hora;


			if($this->pasajesPlus!=0){
				$boleto = new Boleto($fecha_y_hora,2,$costo,$this->saldo,$transporte->getId(),$this->id);
			} else if ($this->porcentaje==0.5) {
				$boleto = new Boleto($fecha_y_hora,3,$costo,$this->saldo,$transporte->getId(),$this->id);
			} else {
				$boleto = new Boleto($fecha_y_hora,1,$costo,$this->saldo,$transporte->getId(),$this->id);
			}

			// Se pudo pagar.
			return $boleto;
		} else {
			// No se pudo pagar.
			return 0;
		}


		} 


	//Para pagar Bicis.

	if($transporte->getTipo()==2){ 

		$aux1 = strtotime($fecha_y_hora);
		$aux2 = strtotime($this->ultimabicipaga);
		
		if($this->ultimabicipaga == 0 || ($aux1-$aux2>86400)){
			$costo = $transporte->getCosto();
			$this->saldo -= $costo;
			$this->ultimabicipaga = $fecha_y_hora;

		} else {
			$costo = 0;
		}

		$this->viajes[$fecha_y_hora] = new Viaje($fecha_y_hora,$transporte,$costo);
		return 1;
		} 

	}


	public function recargar($monto){
		if($monto>=500){
			$monto+=140;
		} else if($monto>=272){
			$monto+=48;
		}
		$this->saldo+=$monto;
		$this->pasajesPlus=0;
	}

	public function saldo(){
		return $this->saldo;
	}

	public function viajesRealizados(){
		return $this->viajes;
	}
}


?>
