<?php

declare(strict_types=1);

require __DIR__ . '/../models/EnsaioFiltro.php';

function verificar(bool $condicao, string $nome): void
{
    if (!$condicao) {
        throw new RuntimeException('Falhou: ' . $nome);
    }
    echo "OK: $nome\n";
}

// Faixa fictícia de 10 a 20, usada apenas para testar o algoritmo.
// Estes números não são padrões de potabilidade de nenhum parâmetro.
echo "\nCLASSIFICAÇÃO — FAIXA FICTÍCIA DE TESTE\n";
verificar(classificarMedicao(15, 10, 20) === 'dentro da referência', 'valor dentro da faixa');
verificar(classificarMedicao(9, 10, 20) === 'fora da referência', 'valor abaixo da faixa');
verificar(classificarMedicao(21, 10, 20) === 'fora da referência', 'valor acima da faixa');
verificar(classificarMedicao(10, 10, 20) === 'dentro da referência'
    && classificarMedicao(20, 10, 20) === 'dentro da referência', 'limites mínimo e máximo inclusivos');
verificar(classificarMedicao(15) === 'sem referência', 'sem faixa: não emite classificação');
try {
    classificarMedicao(15, 20, 10);
    throw new RuntimeException('Deveria rejeitar limites invertidos.');
} catch (InvalidArgumentException) {
    echo "OK: rejeita faixa com mínimo maior que máximo\n";
}

// Casos felizes: entradas válidas e comparação normal.
echo "\nCASOS FELIZES\n";
verificar(compararMedicoes('7,5', '7')['reducao'] === 0.5, 'aceita vírgula decimal');
verificar(compararMedicoes('7', '6', -PHP_FLOAT_MAX, false)['percentual'] === null, 'pH sem percentual de remoção');

// Eficiência de redução = ((antes - depois) / antes) * 100.
echo "\nCÁLCULO DE EFICIÊNCIA\n";
$resultado = compararMedicoes('10', '4');
verificar($resultado['reducao'] === 6.0 && $resultado['percentual'] === 60.0, '10 para 4: eficiência de 60%');
verificar(compararMedicoes('10', '15')['percentual'] === -50.0, '10 para 15: eficiência negativa de -50%');

// Casos de borda: zero, valores iguais e o limite físico permitido.
echo "\nCASOS DE BORDA\n";
verificar(compararMedicoes('10', '10')['percentual'] === 0.0, 'valores iguais: eficiência de 0%');
verificar(compararMedicoes('10', '0')['percentual'] === 100.0, 'valor final zero: eficiência de 100%');
verificar(compararMedicoes('0', '4')['percentual'] === null, 'valor inicial zero: não divide por zero');
verificar(compararMedicoes('-273.15', '-273.15', -273.15, false)['reducao'] === 0.0, 'aceita exatamente o mínimo físico da temperatura');

// Casos de erro: entradas inválidas devem ser rejeitadas.
echo "\nCASOS DE ERRO\n";
try {
    compararMedicoes('abc', '4');
    throw new RuntimeException('Deveria rejeitar texto.');
} catch (InvalidArgumentException) {
    echo "OK: rejeita texto\n";
}
try {
    compararMedicoes('-1', '4');
    throw new RuntimeException('Deveria rejeitar valor negativo.');
} catch (InvalidArgumentException) {
    echo "OK: rejeita valor negativo\n";
}


try {
    compararMedicoes('10', '');
    throw new RuntimeException('Deveria exigir as duas medições.');
} catch (InvalidArgumentException) {
    echo "OK: rejeita medição incompleta\n";
}
try {
    compararMedicoes('-274', '20', -273.15, false);
    throw new RuntimeException('Deveria rejeitar temperatura abaixo do zero absoluto.');
} catch (InvalidArgumentException) {
    echo "OK: rejeita temperatura fisicamente inválida\n";
}
