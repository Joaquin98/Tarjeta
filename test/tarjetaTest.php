<?php


namespace Poli\Tarjeta;


class TarjetaTest extends \PHPUnit_Framework_TestCase {

  protected $tarjeta,$colectivoA,$colectivoB,$medio;	

  public function setup(){
			$this->tarjeta = new Tarjeta();
      $this->medio = new Medio();
			$this->colectivoA = new Colectivo("144 Negro", "Rosario Bus");
  		$this->colectivoB = new Colectivo("135", "Rosario Bus");
  }	

  public function testCargaSaldo() {
    $this->tarjeta->recargar(272);
    $this->assertEquals($this->tarjeta->saldo(), 320, "Cuando cargo 272 deberia tener finalmente 320");
    $this->tarjeta = new Tarjeta();
    $this->tarjeta->recargar(505);
    $this->assertEquals($this->tarjeta->saldo(), 645, "Cuando cargo 505 deberia tener finalmente 645");
  }


  public function testPagarViaje() {
  	$this->tarjeta->recargar(272);
  	$this->tarjeta->pagar($this->colectivoA, "2016/06/30 22:50");
  	$this->assertEquals($this->tarjeta->saldo(), 312, "Cuando recargo 272 y pago un colectivo deberia tener finalmente 312");
  }


  public function testPagarViajeSinSaldo() {
  	$this->assertEquals($this->tarjeta->pagar($this->colectivoA, "2016/06/30 22:50"),0, "Cuando no recargo y pago un colectivo deberia devolver un 0");
    $this->assertEquals($this->tarjeta->saldo(),0, "Cuando no recargo y pago el saldo deberia ser 0");
  }

  public function testTransbordo() {
  	$this->tarjeta->recargar(272);
  	$this->tarjeta->pagar($this->colectivoA, "2016/06/30 22:54");
  	$this->tarjeta->pagar($this->colectivoB, "2016/06/30 23:50");
  	$this->assertEquals($this->tarjeta->saldo(), 309.36, "Si tengo 312 y pago un colectivo con transbordo deberia tener finalmente 309.36");
  }

  public function testNoTransbordo() {
  	$this->tarjeta->recargar(272);
  	$this->tarjeta->pagar($this->colectivoA, "2016/06/28 10:50");
   	$this->tarjeta->pagar($this->colectivoB, "2016/06/30 23:58");
  	$this->assertEquals($this->tarjeta->saldo(), 304, "Si tengo 312 y pago un colectivo sin transbordo deberia tener finalmente 304");
 
  }

  public function testNoTransbordoMismoColectivo() {
  	$this->tarjeta->recargar(272);
  	$this->tarjeta->pagar($this->colectivoA, "2016/06/30 22:50");
  	$this->tarjeta->pagar($this->colectivoA, "2016/06/30 22:54");
  	$this->assertEquals($this->tarjeta->saldo(), 304, "Si tengo 312 y pago un colectivo sin transbordo ya que es el mismo deberia tener finalmente 304");
  }


    public function testmedioTransbordo() {
    $this->medio->recargar(272);
    $this->medio->pagar($this->colectivoA, "2016/06/30 22:54");
    $this->medio->pagar($this->colectivoB, "2016/06/30 23:50");
    $this->assertEquals($this->medio->saldo(), 310.32, "Si tengo 312 y pago un colectivo con transbordo deberia tener finalmente 310.68");
  }

  public function testmedioNoTransbordo() {
    $this->medio->recargar(272);
    $this->medio->pagar($this->colectivoA, "2016/06/28 10:50");
    $this->medio->pagar($this->colectivoB, "2016/06/30 23:58");
    $this->assertEquals($this->medio->saldo(), 308, "Si tengo 312 y pago un colectivo sin transbordo deberia tener finalmente 308");
 
  }

}

?>
