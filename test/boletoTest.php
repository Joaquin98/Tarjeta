<?php


namespace Poli\Tarjeta;


class BoletoTest extends \PHPUnit_Framework_TestCase {

  protected $tarjeta,$colectivoA,$colectivoB,$medio,$bici;	

  public function setup(){
			$this->tarjeta = new Tarjeta(23);
      $this->medio = new Medio();
		  $this->colectivoA = new Colectivo("144 Negro", "Rosario Bus");
  		$this->colectivoB = new Colectivo("135", "Rosario Bus");
      $this->biciA = new Bicicleta("323");
      $this->biciB = new Bicicleta("111");
  }	


  public function testPagarViaje() {
  	$this->tarjeta->recargar(272);
    $this->assertEquals($this->tarjeta->pagar($this->colectivoA, "2016/06/30 22:50")->getTipo(),"Normal", "Cuando recargo y pago un colectivo deberia devolver un boleto de viaje Normal");
  	$this->assertEquals($this->tarjeta->saldo(), 312, "Cuando recargo 272 y pago un colectivo deberia tener finalmente 312");
  }


  public function testPagarViajeSinSaldo() {
  	$this->assertEquals($this->tarjeta->pagar($this->colectivoA, "2016/06/30 22:50")->getTipo(),"Plus", "Cuando no recargo y pago un colectivo deberia devolver un boleto de viaje plus");
    $this->assertEquals($this->tarjeta->saldo(),-8, "Cuando no recargo y pago el saldo deberia ser -8");
  }

  public function testMedio() {
    $this->medio->recargar(272);
    $this->medio->pagar($this->colectivoA, "2016/06/28 10:50");
    $this->assertEquals($this->medio->pagar($this->colectivoB, "2016/06/30 23:58")->getTipo(),"Medio", "Cuando recargo y pago un colectivo con el medio deberia devolver un boleto de viaje Medio");
    $this->assertEquals($this->medio->saldo(), 312, "Si tengo 312 y pago un colectivo sin transbordo deberia tener finalmente 312");
 
  }

  public function testBoleto() {
    $this->tarjeta->recargar(272);
    $aux=$this->tarjeta->pagar($this->colectivoA, "2016/06/30 22:50");
    $this->assertEquals($aux->getTipo(),"Normal", "Cuando recargo y pago un colectivo deberia devolver un boleto de viaje Normal");
    $this->assertEquals($aux->getCosto(),8, "El costo del boleto debe ser 8");
    $this->assertEquals($aux->getLinea(),"144 Negro", "La linea es 144");
    $this->assertEquals($aux->getFecha(),"2016/06/30 22:50", "La fecha es 2016/06/30 22:50");
    $this->assertEquals($this->tarjeta->saldo(), 312, "Cuando recargo 272 y pago un colectivo deberia tener finalmente 312");
  }
 

}

?>
