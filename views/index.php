<?php
require_once __DIR__ . '/../models/vocation.php';
require_once __DIR__ . '/../models/warrior.php';
require_once __DIR__ . '/../models/wizard.php';
require_once __DIR__ . '/../models/player.php';
require_once __DIR__ . '/../models/playerRepository.php';
require_once __DIR__ . '/../controller/playerController.php';
require_once __DIR__ . '/../controller/battleController.php';

$battleController = new BattleController();
$playerController = new PlayerController();
function exibirMenu() {
    // Cores ANSI
    $azul  = "\033[1;34m";
    $roxo  = "\033[1;35m"; // Estilo Gojo/Vazio Infinito
    $reset = "\033[0m";
    $bold  = "\033[1m";

    // Limpa a tela (funciona em Linux/Mac e Windows moderno)
    system(command: 'clear'); // ou system('cls') no Windows

    echo "{$roxo}============================================={$reset}". PHP_EOL;
    echo "{$roxo}║{$reset}         {$bold}TERMINAL RPG{$reset}          {$roxo}║{$reset}". PHP_EOL;
    echo "{$roxo}============================================={$reset}". PHP_EOL;
    echo "{$roxo}║{$reset}                                         {$roxo}║{$reset}". PHP_EOL;
    echo "{$roxo}║{$reset}  {$azul}[1]{$reset} Criar Novo Player                 {$roxo}║{$reset}". PHP_EOL;
    echo "{$roxo}║{$reset}  {$azul}[2]{$reset} Ver Ranking Global                {$roxo}║{$reset}". PHP_EOL;
    echo "{$roxo}║{$reset}  {$azul}[3]{$reset} Começar Batalha                   {$roxo}║{$reset}". PHP_EOL;
    echo "{$roxo}║{$reset}  {$azul}[0]{$reset} Sair do Jogo                      {$roxo}║{$reset}". PHP_EOL;
    echo "{$roxo}║{$reset}                                         {$roxo}║{$reset}". PHP_EOL;
    echo "{$roxo}============================================={$reset}". PHP_EOL;
    echo " Escolha uma opção: ";
}

// Loop simples para o menu
while (true) {
    exibirMenu();
    $opcao = trim(fgets(STDIN));

    switch ($opcao) {
        case '1':
            echo "Iniciando criação de personagem..." .PHP_EOL;
            $playerController->registerPlayer();
            sleep(1);
            break;
        case '2':
            echo "Buscando os melhores feiticeiros...".PHP_EOL;
            $playerController->listRanking();
            sleep(1);
            break;
        case '3':
            echo "Entrando no menu de batalha...".PHP_EOL;
            $battleController->iniciarCenarioBatalha();
            sleep(1);
            break;
        case '0':
            echo "Até a próxima, feiticeiro!".PHP_EOL;
            exit;
        default:
            echo "Opção inválida!".PHP_EOL;
            sleep(1);
    }
}