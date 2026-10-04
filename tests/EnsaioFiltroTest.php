<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class EnsaioFiltroTest extends TestCase
{
    public function testAceitaVirgulaDecimal(): void
    {
        $this->assertSame(0.5, compararMedicoes('7,5', '7')['reducao']);
    }

    public function testPhSemPercentualDeRemocao(): void
    {
        $this->assertNull(compararMedicoes('7', '6', -PHP_FLOAT_MAX, false)['percentual']);
    }

    public function testCalculaEficienciaDeSessentaPorCento(): void
    {
        $resultado = compararMedicoes('10', '4');
        $this->assertSame(6.0, $resultado['reducao']);
        $this->assertSame(60.0, $resultado['percentual']);
    }

    public function testAumentoGeraEficienciaNegativa(): void
    {
        $this->assertSame(-50.0, compararMedicoes('10', '15')['percentual']);
    }

    public function testValoresIguaisGeramZeroPorCento(): void
    {
        $this->assertSame(0.0, compararMedicoes('10', '10')['percentual']);
    }

    public function testValorFinalZeroGeraCemPorCento(): void
    {
        $this->assertSame(100.0, compararMedicoes('10', '0')['percentual']);
    }

    public function testValorInicialZeroNaoDividePorZero(): void
    {
        $this->assertNull(compararMedicoes('0', '4')['percentual']);
    }

    public function testAceitaMinimoFisicoDaTemperatura(): void
    {
        $this->assertSame(0.0, compararMedicoes('-273.15', '-273.15', -273.15, false)['reducao']);
    }

    public function testRejeitaTexto(): void
    {
        $this->expectException(InvalidArgumentException::class);
        compararMedicoes('abc', '4');
    }

    public function testRejeitaValorNegativo(): void
    {
        $this->expectException(InvalidArgumentException::class);
        compararMedicoes('-1', '4');
    }

    public function testRejeitaMedicaoIncompleta(): void
    {
        $this->expectException(InvalidArgumentException::class);
        compararMedicoes('10', '');
    }

    public function testRejeitaTemperaturaAbaixoDoMinimoFisico(): void
    {
        $this->expectException(InvalidArgumentException::class);
        compararMedicoes('-274', '20', -273.15, false);
    }
}
