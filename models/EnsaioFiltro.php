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
            throw new InvalidArgumentException('Informe duas medições numéricas válidas, respeitando o mínimo físico do parâmetro.');
        }
        $valores[] = (float) $entrada;
    }
    [$inicial, $final] = $valores;
    $reducao = $inicial - $final;
    $percentual = !$calcularPercentual || $inicial == 0 ? null : ($reducao / $inicial) * 100;
    if ($percentual !== null && !is_finite($percentual)) {
        throw new InvalidArgumentException('Os valores são grandes demais para calcular o percentual.');
    }
    return ['reducao' => $reducao, 'percentual' => $percentual];
}
