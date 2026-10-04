# Qualidade da água — ODS 6

Projeto escolar em PHP para comparar pH, turbidez, cloro residual livre, dureza e temperatura antes e depois de um biofiltro.

## Instalação e execução

Requisitos: PHP 8.4 ou superior e Composer. No Laravel Herd, selecione PHP 8.4 e use `public/` como pasta pública. Alternativamente:

```bash
composer install
php -S localhost:8080 -t public
```

Acesse http://localhost:8080. A interface usa PHP, HTML e CSS, sem JavaScript.

## Algoritmos e estrutura

- `models/EnsaioFiltro.php`: comparação das medições e classificação por limites inclusivos.
- `controllers/`: processamento do formulário.
- `views/`: apresentação dos campos e resultados.
- `public/`: entrada do site e CSS.
- `tests/`: uma classe PHPUnit para cada algoritmo.

A redução percentual de turbidez e dureza usa `((antes − depois) / antes) × 100`. Valor inicial zero impede o percentual; resultado negativo indica aumento. pH, cloro e temperatura são comparados sem percentual de remoção.

## Testes

```bash
composer test
composer coverage
```

São 18 testes PHPUnit: seis de classificação e doze de cálculos, eficiência, casos felizes, borda e erro. A cobertura exige Xdebug ou PCOV; o relatório HTML fica em `coverage/index.html`. O percentual deve ser conferido no relatório gerado.

A classificação utiliza uma faixa fictícia de 10 a 20 somente nos testes, pois a atividade não forneceu referências. Sem faixa, o algoritmo retorna “sem referência”. A interface não aplica os limites fictícios e não determina potabilidade.

O experimento se relaciona à melhoria da qualidade da água proposta pela [meta 6.3 da ODS 6](https://brasil.un.org/pt-br/sdgs/6). Reduzir um valor não significa necessariamente melhorar a água.
