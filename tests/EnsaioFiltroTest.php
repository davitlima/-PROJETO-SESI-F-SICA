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

    public function testCalculaEficienciaDoRelatorio(): void
    {
        $resultado = compararMedicoes('10', '2');
        $this->assertSame(8.0, $resultado['reducao']);
        $this->assertSame(80.0, $resultado['eficiencia']);
        $this->assertSame('EFICIENTE', $resultado['status']);
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
        $this->expectException(InvalidArgumentException::class);
        compararMedicoes('0', '4');
    }

    public function testAceitaMinimoFisicoDaTemperatura(): void
    {
        $this->assertSame(0.0, compararMedicoes('-273.15', '-273.15', -273.15, false)['reducao']);
    }

    public function testRejeitaTurbidezDeSaidaNegativa(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('As medições devem conter valores válidos');
        compararMedicoes('10', '-2');
    }

    public function testRejeitaValorNegativo(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('As medições devem conter valores válidos');
        compararMedicoes('-10', '2');
    }

    public function testRejeitaMedicaoIncompleta(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('As medições devem conter valores válidos');
        compararMedicoes('10', '');
    }

    public function testRejeitaTurbidezDeEntradaVazia(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('As medições devem conter valores válidos');
        compararMedicoes('', '2');
    }
}
