# Qualidade da água — ODS 6

Projeto escolar em PHP para comparar pH, turbidez, cloro residual livre, dureza e temperatura antes e depois de um biofiltro.

## Algoritmos e estrutura

- `models/EnsaioFiltro.php`: comparação das medições e classificação por limites inclusivos.
- `controllers/`: processamento do formulário.
- `views/`: apresentação dos campos e resultados.
- `public/`: entrada do site e CSS.
- `tests/`: uma classe PHPUnit para cada algoritmo.

A redução percentual de turbidez e dureza usa `((antes − depois) / antes) × 100`. Valor inicial zero gera erro no cálculo da eficiência; resultado negativo indica aumento. `eficiencia` contém o percentual e `status` é `EFICIENTE` quando há redução maior que zero, regra escolar adotada porque o relatório não define um limiar mínimo. pH, cloro e temperatura são comparados sem percentual de remoção.

São 24 testes PHPUnit: classificação, validações, eficiência, limites e integração do parecer da amostra. Os oito cenários do relatório são cobertos, incluindo as mensagens de erro esperadas. A cobertura exige Xdebug ou PCOV; o relatório HTML fica em `coverage/index.html`. O percentual deve ser conferido no relatório gerado.

## Critérios e limites do parecer escolar

O identificador da amostra, pH e turbidez antes/depois são obrigatórios; os outros parâmetros são opcionais. O pH deve estar entre 0 e 14, conforme a regra do relatório. A turbidez não pode ser negativa e deve ser maior que zero na entrada para calcular a eficiência.

O algoritmo `analisarAmostra` retorna `status_ph`, `status_turbidez` e `parecer`. Usa pH de 6 a 9,5 como faixa escolar explicitamente adotada, pois o relatório não fornece os limites de classificação; essa faixa não é apresentada como exigência da Portaria 888/2021. Para turbidez usa até 5 NTU, referência organoléptica de distribuição do Anexo 11 da Portaria, não meta de saída do biofiltro. O teste genérico de classificação continua usando uma faixa fictícia de 10 a 20.

“POTÁVEL” e “NÃO POTÁVEL” são os rótulos exigidos pelo relatório para o parecer escolar de pH e turbidez. Eles não certificam segurança para consumo: análises microbiológicas e outros parâmetros não são avaliados. A interface apresenta essa limitação junto do resultado. Cloro, dureza e temperatura continuam sendo comparados, sem parecer de potabilidade.

Fonte da turbidez: [Portaria GM/MS 888/2021](https://bvsms.saude.gov.br/bvs/saudelegis/gm/2021/prt0888_24_05_2021_rep.html).

O experimento se relaciona à melhoria da qualidade da água proposta pela [meta 6.3 da ODS 6](https://brasil.un.org/pt-br/sdgs/6). Reduzir um valor não significa necessariamente melhorar a água.
