<?php

declare(strict_types=1);

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
