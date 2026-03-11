# Projeto---Arena-De-Combate-RPG-Jujutsu-Kaisen-

# 🛡️ Arena de Combate RPG

![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-777BB4?logo=php&style=flat-square)
![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)
![Status](https://img.shields.io/badge/status-em%20desenvolvimento-orange?style=flat-square)

Um simulador de batalha por turnos desenvolvido em **PHP Moderno**, focado na aplicação prática de **Programação Orientada a Objetos (POO)**. O projeto apresenta um duelo interativo via terminal com artes ASCII e sistema de progressão.

---

## 🎮 O Jogo

O sistema permite que dois jogadores escolham heróis de diferentes vocações para um duelo até a morte. Cada ação é calculada com base nos atributos de cada classe, incluindo chances de acerto crítico e absorção de dano por defesa.

### Principais Funcionalidades
- **Seleção Dinâmica:** Escolha heróis através de IDs únicos carregados do banco de dados.
- **Vocações Únicas:** - ⚔️ **Warrior:** Alta defesa e ataques físicos consistentes.
  - 🔮 **Wizard:** Alto dano explosivo, porém dependente de mana.
- **Combate por Menu:** Escolha entre ataques básicos ou habilidades especiais a cada turno.
- **Persistência JSON:** Jogadores, ranks e atributos são salvos e carregados de um arquivo `.json`.
- **Ranking Global:** Sistema que ordena os melhores jogadores e atribui títulos honorários.

---

## 🛠️ Arquitetura e Padrões

O projeto utiliza uma estrutura desacoplada para facilitar a manutenção:

- **Modelos (Models):** Uso de `Abstract Classes` e `Herança` para definir o comportamento das vocações.
- **Repositórios (Repository):** Camada responsável por ler e escrever os dados no arquivo JSON.
- **Controladores (Controllers):** Gerenciam o fluxo da batalha e o cadastro de usuários.
- **Polimorfismo:** O motor de batalha processa qualquer vocação que estenda a classe base `Vocation`.



---

## 🚀 Como Jogar

1. **Requisitos:** PHP 8.1 ou superior.
2. **Instalação:**
   ```bash
   git clone [https://github.com/seu-usuario/arena-rpg-php.git](https://github.com/seu-usuario/arena-rpg-php.git)
   cd arena-rpg-php
3. **Inicie:**
   ```bash
    php views/index.php
## ✒️ Autor
Desenvolvido com ❤️ por Wagner como projeto de aprofundamento em lógica de programação e arquitetura de sistemas com PHP.
