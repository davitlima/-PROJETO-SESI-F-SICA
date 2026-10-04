<?php

declare(strict_types=1);

function classificarMedicao(float $valor, ?float $minimo = null, ?float $maximo = null): string
{
    if (!is_finite($valor) || ($minimo !== null && !is_finite($minimo))
        || ($maximo !== null && !is_finite($maximo))
        || ($minimo !== null && $maximo !== null && $minimo > $maximo)) {
        throw new InvalidArgumentException('Medição ou faixa de referência inválida.');
    }
    if ($minimo === null && $maximo === null) {
        return 'sem referência';
    }
    return ($minimo !== null && $valor < $minimo) || ($maximo !== null && $valor > $maximo)
        ? 'fora da referência' : 'dentro da referência';
}

function compararMedicoes(string $antes, string $depois, float $minimo = 0, bool $calcularPercentual = true): array
{
    $valores = [];
    foreach ([$antes, $depois] as $entrada) {
        $entrada = str_replace(',', '.', trim($entrada));
        if (!is_numeric($entrada) || !is_finite((float) $entrada) || (float) $entrada < $minimo) {
            throw new InvalidArgumentException('As medições devem conter valores válidos');
        }
        $valores[] = (float) $entrada;
    }
    [$inicial, $final] = $valores;
    $reducao = $inicial - $final;
    if ($calcularPercentual && $inicial == 0) {
        throw new InvalidArgumentException('A medição de entrada deve ser maior que zero para calcular a eficiência');
    }
    $percentual = $calcularPercentual ? ($reducao / $inicial) * 100 : null;
    if ($percentual !== null && !is_finite($percentual)) {
        throw new InvalidArgumentException('Os valores são grandes demais para calcular o percentual.');
    }
    return ['reducao' => $reducao, 'percentual' => $percentual, 'eficiencia' => $percentual,
        'status' => $percentual === null ? 'NÃO APLICÁVEL' : ($percentual > 0 ? 'EFICIENTE' : 'NÃO EFICIENTE')];
}

function validarPh(string $entrada): float
{
    $numero = str_replace(',', '.', trim($entrada));
    if (!is_numeric($numero) || !is_finite((float) $numero) || (float) $numero < 0 || (float) $numero > 14) {
        throw new InvalidArgumentException('O pH deve conter um valor válido entre 0 e 14');
    }
    return (float) $numero;
}

function analisarAmostra(string $identificador, string $ph, string $turbidez): array
{
    if (trim($identificador) === '' || strlen($identificador) > 120) {
        throw new InvalidArgumentException('Informe o identificador da amostra com até 120 caracteres');
    }
    $valorPh = validarPh($ph);
    // Valida turbidez sem calcular percentual: zero é uma medição válida.
    compararMedicoes($turbidez, $turbidez, 0, false);
    $valorTurbidez = (float) str_replace(',', '.', trim($turbidez));
    // Critério escolar de pH 6–9,5; não é atribuído à Portaria 888/2021.
    $statusPh = classificarMedicao($valorPh, 6, 9.5) === 'dentro da referência' ? 'POTÁVEL' : 'NÃO POTÁVEL';
    // 5 NTU: referência organoléptica para distribuição, não meta pós-filtração.
    $statusTurbidez = classificarMedicao($valorTurbidez, 0, 5) === 'dentro da referência' ? 'POTÁVEL' : 'NÃO POTÁVEL';
    $parecer = $statusPh === 'POTÁVEL' && $statusTurbidez === 'POTÁVEL' ? 'POTÁVEL' : 'NÃO POTÁVEL';
    return ['identificador' => trim($identificador), 'status_ph' => $statusPh,
        'status_turbidez' => $statusTurbidez,
        'parecer' => $parecer, 'status' => $parecer === 'POTÁVEL' ? 'CONFORME' : 'INCONFORMIDADE'];
}
