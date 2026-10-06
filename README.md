# Qualidade da água — ODS 6

Projeto escolar em PHP para comparar pH, turbidez, cloro residual livre, dureza e temperatura antes e depois de um biofiltro.

## Algoritmos e estrutura

- `models/EnsaioFiltro.php`: comparação das medições e classificação por limites inclusivos.
- `controllers/`: processamento do formulário.
- `views/`: apresentação dos campos e resultados.
- `public/`: entrada do site e CSS.
- `tests/`: uma classe PHPUnit para cada algoritmo.

A redução percentual de turbidez e dureza usa `((antes − depois) / antes) × 100`. Valor inicial zero gera erro no cálculo da eficiência; resultado negativo indica aumento. `eficiencia` contém o percentual e `status` é `EFICIENTE` quando há redução maior que zero, regra escolar adotada porque o relatório não define um limiar mínimo. pH, cloro e temperatura são comparados sem percentual de remoção.

São 24 testes PHPUnit: classificação, validações, eficiência, limites e integração do parecer da amostra. Os oito cenários do relatório são cobertos, incluindo as mensagens de erro esperadas. o relatório HTML fica em `coverage/index.html`. O percentual deve ser conferido no relatório gerado.

## Critérios e limites do parecer escolar

O identificador da amostra, pH e turbidez antes/depois são obrigatórios; os outros parâmetros são opcionais. O pH deve estar entre 0 e 14, conforme a regra do relatório. A turbidez não pode ser negativa e deve ser maior que zero na entrada para calcular a eficiência.

O algoritmo `analisarAmostra` retorna `status_ph`, `status_turbidez` e `parecer`. Usa pH de 6 a 9,5 como faixa escolar explicitamente adotada, pois o relatório não fornece os limites de classificação; essa faixa não é apresentada como exigência da Portaria 888/2021. Para turbidez usa até 5 NTU, referência organoléptica de distribuição do Anexo 11 da Portaria, não meta de saída do biofiltro. O teste genérico de classificação continua usando uma faixa fictícia de 10 a 20.

“POTÁVEL” e “NÃO POTÁVEL” são os rótulos exigidos pelo relatório para o parecer escolar de pH e turbidez. Eles não certificam segurança para consumo: análises microbiológicas e outros parâmetros não são avaliados. A interface apresenta essa limitação junto do resultado. Cloro, dureza e temperatura continuam sendo comparados, sem parecer de potabilidade.

Fonte da turbidez: [Portaria GM/MS 888/2021](https://bvsms.saude.gov.br/bvs/saudelegis/gm/2021/prt0888_24_05_2021_rep.html).

O experimento se relaciona à melhoria da qualidade da água proposta pela [meta 6.3 da ODS 6](https://brasil.un.org/pt-br/sdgs/6). Reduzir um valor não significa necessariamente melhorar a água.


## 📌 Algoritmos e Estrutura

- `models/EnsaioFiltro.php`: comparação das medições e classificação por limites inclusivos.
- `controllers/`: processamento do formulário.
- `views/`: apresentação dos campos e resultados.
- `public/`: entrada do site e CSS.
- `tests/`: uma classe PHPUnit para cada algoritmo.

---

## 🧮 Lógica de Cálculo e Eficiência



- **Divisão por zero:** Valor inicial igual a zero gera erro no cálculo da eficiência.
- **Aumento de parâmetro:** Um resultado negativo indica aumento da concentração do parâmetro analisado.
- **Status:** A variável `eficiencia` contém o valor percentual e o `status` é classificado como `EFICIENTE` quando há redução maior que zero (regra escolar adotada, visto que o relatório não define um limiar mínimo).
- **Sem percentual:** pH, cloro e temperatura são comparados sem cálculo de percentual de remoção.

---

## 🧪 Suíte de Testes e Cobertura (PHPUnit)

São **24 testes PHPUnit** cobrindo classificação, validações, eficiência, limites e integração do parecer da amostra.

- Todos os oito cenários descritos no relatório são cobertos, incluindo o tratamento de mensagens de erro esperadas.
- O relatório em HTML de cobertura do código fica disponível em `coverage/index.html`. O percentual de cobertura deve ser conferido no relatório gerado.

---

## 📋 Critérios e Limites do Parecer Escolar

- **Campos Obrigatórios:** O identificador da amostra, pH e turbidez (antes e depois) são obrigatórios; os restantes parâmetros são opcionais.
- **Validações:** O pH deve estar obrigatoriamente entre 0 e 14. A turbidez não pode ser negativa e deve ser maior que zero na entrada para permitir o cálculo da eficiência.
- **Faixas de Referência:**
  - **pH (6,0 a 9,5):** Faixa escolar explicitamente adotada por ausência de limites explícitos de classificação no relatório (não apresentada como exigência direta da Portaria).
  - **Turbidez (até 5,0 NTU):** Referência organoléptica de distribuição extraída do Anexo 11 da Portaria GM/MS nº 888/2021 (não se trata de meta estrita de saída do biofiltro). O teste genérico de classificação utiliza uma faixa fictícia de 10 a 20.
- **Rótulos de Parecer:** `"POTÁVEL"` e `"NÃO POTÁVEL"` são os rótulos exigidos pelo relatório escolar. **Importante:** Estes rótulos não certificam segurança absoluta para consumo humano, visto que análises microbiológicas e outros contaminantes químicos não são avaliados. A interface apresenta esta limitação junto ao resultado. Cloro, dureza e temperatura são comparados sem emitir parecer de potabilidade.

Fonte de referência: [Portaria GM/MS nº 888/2021](https://bvsms.saude.gov.br/bvs/saudelegis/gm/2021/prt0888_24_05_2021_rep.html).



## Declaração de Uso de Inteligência Artificial


- Natureza do Uso : A IA não desenvolveu o projeto autonomamente, atuando exclusivamente como um 
assistente de consulta técnica para refatoração e tira-dúvidas em trechos.

- Trechos Específicos com Apoio da IA:
  - Tratamento de Exceção Matemática (`models/EnsaioFiltro.php`):Consulta sobre a forma mais limpa em PHP para disparar exceção ao evitar divisão por zero no cálculo da taxa de remoção:
    
    if ($antes == 0) {
        throw new InvalidArgumentException("O valor inicial não pode ser zero para o cálculo de eficiência.");
    }
  - Sintaxe de Asserção do PHPUnit (`tests/EnsaioFiltroTest.php`): Apoio na estruturação sintática dos testes de limite exato (borda), acelerando a escrita das asserções no PHPUnit 11:
 
    // Apoio na sintaxe do PHPUnit para testes de limite exato
    $this->assertEquals('POTÁVEL', $resultado['parecer']);


