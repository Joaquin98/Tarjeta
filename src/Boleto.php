<?php
	
namespace Poli\Tarjeta;


class Boleto {
	protected $fecha,$saldo,$tipo,$tipos = array(1 => "Normal" , 2 => "Plus" , 3 => "Medio"),$linea,$costo,$idTarjeta;

	public function __construct($fecha,$tipo,$costo,$saldo,$linea,$id){
		$this->fecha=$fecha;
		$this->tipo=$tipo;
		$this->costo=$costo;
		$this->saldo=$saldo;
		$this->linea=$linea;
		$this->idTarjeta=$id;
	}

	public function getCosto(){
		return $this->costo;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getLinea(){
		return $this->linea;
	}

	public function getId(){
		return $this->idTarjeta;
	}

	public function getSaldo(){
		return $this->saldo;
	}

	public function getTipo(){
		return $this->tipos[$this->tipo];
	}
}

?>