<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ParecerAmostraTest extends TestCase
{
    public function testAmostraValidaDoRelatorio(): void
    {
        $resultado = analisarAmostra('Amostra Biofiltro A1', '7.0', '2.0');
        $this->assertSame('POTÁVEL', $resultado['status_ph']);
        $this->assertSame('POTÁVEL', $resultado['parecer']);
    }

    public function testAmostraForaDaReferencia(): void
    {
        $this->assertSame('NÃO POTÁVEL', analisarAmostra('A1', '5', '2')['parecer']);
        $this->assertSame('INCONFORMIDADE', analisarAmostra('A1', '5', '2')['status']);
        $this->assertSame('NÃO POTÁVEL', analisarAmostra('A1', '7', '6')['parecer']);
    }

    public function testPhNegativoDoRelatorio(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O pH deve conter um valor válido entre 0 e 14');
        analisarAmostra('A1', '-2.0', '2');
    }

    public function testPhVazioDoRelatorio(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O pH deve conter um valor válido entre 0 e 14');
        analisarAmostra('A1', '', '2');
    }

    public function testIdentificadorObrigatorio(): void
    {
        $this->expectException(InvalidArgumentException::class);
        analisarAmostra('', '7', '2');
    }

    public function testLimitesDoParecer(): void
    {
        $this->assertSame('POTÁVEL', analisarAmostra('A1', '6', '5')['parecer']);
        $this->assertSame('POTÁVEL', analisarAmostra('A1', '9,5', '0')['parecer']);
    }
}
