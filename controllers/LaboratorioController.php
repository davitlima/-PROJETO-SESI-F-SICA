<?php

declare(strict_types=1);

require __DIR__ . '/../models/EnsaioFiltro.php';

$parametros = [
    'ph' => ['nome' => 'pH', 'unidade' => '', 'minimo' => 0, 'percentual' => false],
    'turbidez' => ['nome' => 'Turbidez', 'unidade' => 'NTU', 'minimo' => 0, 'percentual' => true],
    'cloro' => ['nome' => 'Cloro residual livre', 'unidade' => 'mg/L', 'minimo' => 0, 'percentual' => false],
    'dureza' => ['nome' => 'Dureza', 'unidade' => 'mg/L de CaCO₃', 'minimo' => 0, 'percentual' => true],
    'temperatura' => ['nome' => 'Temperatura', 'unidade' => '°C', 'minimo' => -273.15, 'percentual' => false],
];
$medicoes = is_array($_POST['medicoes'] ?? null) ? $_POST['medicoes'] : [];
$resultados = [];
$erros = [];
$identificador = is_string($_POST['identificador'] ?? null) ? trim($_POST['identificador']) : '';
$parecerAntes = null;
$parecerDepois = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($parametros as $chave => $parametro) {
        $campos = is_array($medicoes[$chave] ?? null) ? $medicoes[$chave] : [];
        $antes = is_string($campos['antes'] ?? null) ? trim($campos['antes']) : '';
        $depois = is_string($campos['depois'] ?? null) ? trim($campos['depois']) : '';
        if ($antes === '' && $depois === '' && !in_array($chave, ['ph', 'turbidez'], true)) {
            continue;
        }
        try {
            if ($chave === 'ph') { validarPh($antes); validarPh($depois); }
            $resultados[$chave] = compararMedicoes($antes, $depois, $parametro['minimo'], $parametro['percentual']);
        } catch (InvalidArgumentException $erro) {
            $erros[] = $parametro['nome'] . ': ' . $erro->getMessage();
        }
    }
    if ($erros === []) {
        try {
            $parecerAntes = analisarAmostra($identificador, $medicoes['ph']['antes'], $medicoes['turbidez']['antes']);
            $parecerDepois = analisarAmostra($identificador, $medicoes['ph']['depois'], $medicoes['turbidez']['depois']);
        } catch (InvalidArgumentException $erro) { $erros[] = $erro->getMessage(); }
    }
    if ($resultados === [] && $erros === []) {
        $erros[] = 'Preencha antes e depois de pelo menos um parâmetro.';
    }
    if ($erros !== []) {
        $resultados = [];
    }
}

require __DIR__ . '/../views/laboratorio.php';
