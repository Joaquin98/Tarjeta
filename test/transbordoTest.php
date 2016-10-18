<?php


namespace Poli\Tarjeta;


class TransbordoTest extends \PHPUnit_Framework_TestCase {

  protected $tarjeta,$colectivoA,$colectivoB,$medio,$bici;	

  public function setup(){
	  $this->tarjeta = new Tarjeta(23);
      $this->medio = new Medio();
	  $this->colectivoA = new Colectivo("144 Negro", "Rosario Bus");
  	  $this->colectivoB = new Colectivo("135", "Rosario Bus");
      $this->biciA = new Bicicleta("323");
      $this->biciB = new Bicicleta("111");
  }	

  // Tomo 2 colectivos diferentes en menos de una hora.
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

  // Tomo el mismo colectivo en menos de una hora, no es Transbordo.
  public function testNoTransbordoMismoColectivo() {
  	$this->tarjeta->recargar(272);
  	$this->tarjeta->pagar($this->colectivoA, "2016/06/30 22:50");
  	$this->tarjeta->pagar($this->colectivoA, "2016/06/30 22:54");
  	$this->assertEquals($this->tarjeta->saldo(), 304, "Si tengo 312 y pago un colectivo sin transbordo ya que es el mismo deberia tener finalmente 304");
  }

  // Pago Transbordo con el Medio Boleto.
  public function testMedioTransbordo() {
    $this->medio->recargar(272);
    $this->medio->pagar($this->colectivoA, "2016/06/30 22:54");
    $this->medio->pagar($this->colectivoB, "2016/06/30 23:50");
    $this->assertEquals($this->medio->saldo(), 314.68, "Si tengo 312 y pago un colectivo con transbordo deberia tener finalmente 314.68");
  }


  public function testMedioNoTransbordo() {
    $this->medio->recargar(272);
    $this->medio->pagar($this->colectivoA, "2016/06/28 10:50");
    $this->medio->pagar($this->colectivoB, "2016/06/30 23:58");
    $this->assertEquals($this->medio->saldo(), 312, "Si tengo 312 y pago un colectivo sin transbordo deberia tener finalmente 312");
  }


  // Tomo 3 colectivos en menos de una hora, solo uno de ellos es Transbordo.
  public function testUnTransbordoPorViaje(){
    $this->tarjeta->recargar(272);
    $this->tarjeta->pagar($this->colectivoA, "2016/06/30 22:54");
    $this->tarjeta->pagar($this->colectivoB, "2016/06/30 23:00");
    $this->tarjeta->pagar($this->colectivoA, "2016/06/30 23:10");
    $this->assertEquals($this->tarjeta->saldo(), 301.36, "Si tengo 312 y pago un colectivo con transbordo y luego otro deberia tener finalmente 301.36");
  }

  // Pruebo transbordo antes de las 6.
  public function testTransbordo90Madrugada() {
    $this->tarjeta->recargar(272);
    $this->tarjeta->pagar($this->colectivoA, "2016/10/18 4:10");
    $this->tarjeta->pagar($this->colectivoB, "2016/10/18 5:20");
    $this->assertEquals($this->tarjeta->saldo(), 309.36, "Si tengo 312 y pago un colectivo con transbordo deberia tener finalmente 309.36");
  }

  // Pruebo transbordo despues de las 22.
  public function testTransbordo90Noche() {
    $this->tarjeta->recargar(272);
    $this->tarjeta->pagar($this->colectivoA, "2016/10/18 21:10");
    $this->tarjeta->pagar($this->colectivoB, "2016/10/18 22:20");
    $this->assertEquals($this->tarjeta->saldo(), 309.36, "Si tengo 312 y pago un colectivo con transbordo deberia tener finalmente 309.36");
  }

  // Pruebo que el tiempo max de transbordo un dia de semana de 6 a 22 no es 90 min.
  public function testnoTransbordo90DiaSemana() {
    $this->tarjeta->recargar(272);
    $this->tarjeta->pagar($this->colectivoA, "2016/10/18 20:10");
    $this->tarjeta->pagar($this->colectivoB, "2016/10/18 21:20");
    $this->assertEquals($this->tarjeta->saldo(), 304, "Si tengo 312 y pago un colectivo con transbordo deberia tener finalmente 309.36");
  }

  // Tiempo max sabado de 14 a 22 es 90 min
  public function testTransbordo90Sabado() {
    $this->tarjeta->recargar(272);
    $this->tarjeta->pagar($this->colectivoA, "2016/10/29 14:10");
    $this->tarjeta->pagar($this->colectivoB, "2016/10/29 15:20");
    $this->assertEquals($this->tarjeta->saldo(), 309.36, "Si tengo 312 y pago un colectivo con transbordo deberia tener finalmente 309.36");
  }


  // Tiempo max sabado de 6 a 22 es 90 min
  public function testTransbordo90DomingoyFeriado() {
    $this->tarjeta->recargar(272);
    $this->tarjeta->pagar($this->colectivoA, "2016/10/30 14:10");
    $this->tarjeta->pagar($this->colectivoB, "2016/10/30 15:20");
    $this->assertEquals($this->tarjeta->saldo(), 309.36, "Si tengo 312 y pago un colectivo con transbordo deberia tener finalmente 309.36");
  }

  // Tiempo max sabado de 6 a 14 es 60 min
  public function testNoTransbordo90Sabado() {
    $this->tarjeta->recargar(272);
    $this->tarjeta->pagar($this->colectivoA, "2016/10/29 8:10");
    $this->tarjeta->pagar($this->colectivoB, "2016/10/29 9:20");
    $this->assertEquals($this->tarjeta->saldo(), 304, "Si tengo 312 y pago un colectivo con transbordo deberia tener finalmente 309.36");
  }

}

?>
