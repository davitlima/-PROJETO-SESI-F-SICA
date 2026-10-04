<?php

declare(strict_types=1);

function escapar(mixed $texto): string
{
    return htmlspecialchars(is_string($texto) ? $texto : '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function formatar(float $valor): string
{
    return number_format($valor, 2, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Água e filtro — ODS 6</title>
    <link rel="stylesheet" href="css/laboratorio.css">
</head>
<body>
<main>
    <h1>Comparação antes e depois do filtro</h1>
    <p>Informe pH e turbidez antes e depois. Cloro, dureza e temperatura são opcionais.</p>
    <form method="post" action="laboratorio.php">
        <label for="identificador">Identificador da amostra</label>
        <input id="identificador" name="identificador" maxlength="120" required value="<?= escapar($identificador) ?>">
        <?php foreach ($parametros as $chave => $parametro):
            $campos = is_array($medicoes[$chave] ?? null) ? $medicoes[$chave] : [];
        ?>
        <fieldset>
            <legend><?= escapar($parametro['nome']) ?><?= $parametro['unidade'] !== '' ? ' (' . escapar($parametro['unidade']) . ')' : '' ?></legend>
            <div class="medicoes">
                <div><label for="<?= $chave ?>-antes">Antes</label><input id="<?= $chave ?>-antes" name="medicoes[<?= $chave ?>][antes]" inputmode="decimal" value="<?= escapar($campos['antes'] ?? '') ?>"></div>
                <div><label for="<?= $chave ?>-depois">Depois</label><input id="<?= $chave ?>-depois" name="medicoes[<?= $chave ?>][depois]" inputmode="decimal" value="<?= escapar($campos['depois'] ?? '') ?>"></div>
            </div>
        </fieldset>
        <?php endforeach; ?>
        <button type="submit">Comparar medições</button>
    </form>
    <?php foreach ($erros as $erro): ?><p class="erro" role="alert"><?= escapar($erro) ?></p><?php endforeach; ?>
    <?php if ($resultados !== []): ?>
    <section aria-labelledby="resultado">
        <h2 id="resultado">Resultados — <?= escapar($identificador) ?></h2>
        <p><strong>Parecer escolar antes: <?= escapar($parecerAntes['parecer']) ?></strong></p>
        <p><strong>Parecer escolar depois: <?= escapar($parecerDepois['parecer']) ?></strong> — <?= escapar($parecerDepois['status']) ?></p>
        <p class="nota">“POTÁVEL” é o rótulo previsto no relatório para pH e turbidez dentro dos critérios escolares. Não comprova segurança para beber; faltam análises microbiológicas e outros parâmetros.</p>
        <p>Critérios adotados: pH de 6 a 9,5 (faixa escolar) e turbidez até 5 NTU (referência de distribuição). Não são uma certificação do biofiltro.</p>
        <?php foreach ($resultados as $chave => $resultado): ?>
        <div class="resultado">
            <h3><?= escapar($parametros[$chave]['nome']) ?></h3>
            <?php if (in_array($chave, ['ph', 'turbidez'], true)): ?>
                <p>Classificação antes: <?= escapar($parecerAntes['status_' . $chave]) ?> · depois: <?= escapar($parecerDepois['status_' . $chave]) ?></p>
            <?php endif; ?>
            <p><?= $resultado['reducao'] > 0 ? 'Diminuiu' : ($resultado['reducao'] < 0 ? 'Aumentou' : 'Sem alteração') ?><?= $resultado['reducao'] != 0 ? ': ' . formatar(abs($resultado['reducao'])) . ' ' . escapar($parametros[$chave]['unidade']) : '' ?>.</p>
            <?php if ($parametros[$chave]['percentual']): ?>
            <p>Eficiência: <strong><?= formatar($resultado['eficiencia']) . '%' ?></strong></p>
            <p>Status: <?= escapar($resultado['status']) ?></p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <p class="nota">Diminuir um valor não significa necessariamente melhorar a água. Esta comparação não determina potabilidade.</p>
    </section>
    <?php endif; ?>
    <footer>
        <p class="nota">EFICIENTE significa redução maior que zero, regra escolar adotada porque o relatório não define um percentual mínimo.</p>
        <p><a href="https://brasil.un.org/pt-br/sdgs/6" target="_blank" rel="noopener noreferrer">ODS 6 — Água potável e saneamento</a></p>
        <p class="nota">A meta 6.3 propõe melhorar a qualidade da água e reduzir a poluição. Aqui, acompanhamos as mudanças nas medições do experimento.</p>
    </footer>
</main>
</body>
</html>
