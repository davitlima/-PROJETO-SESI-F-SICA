<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// Faixa fictícia de 10 a 20: exemplo de teste, não padrão de potabilidade.
final class ClassificacaoMedicaoTest extends TestCase
{
    public function testValorDentroDaFaixa(): void
    {
        $this->assertSame('dentro da referência', classificarMedicao(15, 10, 20));
    }

    public function testValorAbaixoDaFaixa(): void
    {
        $this->assertSame('fora da referência', classificarMedicao(9, 10, 20));
    }

    public function testValorAcimaDaFaixa(): void
    {
        $this->assertSame('fora da referência', classificarMedicao(21, 10, 20));
    }

    public function testLimitesInclusivos(): void
    {
        $this->assertSame('dentro da referência', classificarMedicao(10, 10, 20));
        $this->assertSame('dentro da referência', classificarMedicao(20, 10, 20));
    }

    public function testSemReferencia(): void
    {
        $this->assertSame('sem referência', classificarMedicao(15));
    }

    public function testRejeitaFaixaInvertida(): void
    {
        $this->expectException(InvalidArgumentException::class);
        classificarMedicao(15, 20, 10);
    }
}
