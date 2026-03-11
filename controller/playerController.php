<?php

use PlayerRepository;

class playerController {
    private PlayerRepository $repository;

    public function __construct() {
        $this->repository = new PlayerRepository();
    }

    public function registerPlayer():void {
        echo "\n" . str_repeat("-",20). PHP_EOL;
        echo "🛡️  CADASTRO DE HERÓI 🛡️" . PHP_EOL;

        echo "Digite seu nome de Jogador: ". PHP_EOL;
        $name = trim(fgets(STDIN));

        echo "Escolha sua Vocação:". PHP_EOL;
        echo "[1] Warrior (Guerreiro)" . PHP_EOL;
        echo "[2] Wizard (Mago)" . PHP_EOL;

        $classOption = trim(fgets(STDIN));

        // 1. Agora sim, busca os existentes
        $playersExistentes = $this->repository->getAll();

        // 2. Verifica se o nome já existe (agora $name existe!)
        foreach ($playersExistentes as $p){
            if($p->getName() == $name){
                echo "\n❌ Erro: Já existe um player com este nome!". $p->getName() . PHP_EOL;
                sleep(2);
                return;
            }
        }
        
        // 3. Cria o novo objeto (agora $opcaoClasse existe!)
        $vocation = ($classOption === '1') ? new Warrior() : new Wizard();
        $newPlayer = new Player(null,$name, 0);
        $newPlayer->setVocation($vocation);

        // 4. Salva
        $playersExistentes[] = $newPlayer;
        $this->repository->save($playersExistentes);

        echo "\n✅ Personagem '{$name}' salvo com sucesso!" . PHP_EOL;
        echo "Pressione ENTER para continuar...";
        fgets(STDIN);

        // Compatibilidade Windows/Linux para limpar tela
        PHP_OS_FAMILY === 'Windows' ? system('cls') : system('clear');
    }

    public function listRanking(): void {
        echo "\n" . str_repeat("=", 50) . PHP_EOL; // Aumentei o traço para caber o título
        echo "🏆 RANKING GLOBAL DE FEITICEIROS 🏆" . PHP_EOL;
        echo str_repeat("=", 50) . PHP_EOL;
        
        $players = $this->repository->getAll();

        if (empty($players)) {
            echo "Nenhum feiticeiro registrado ainda..." . PHP_EOL;
        } else {
            usort($players, function($a, $b) {
                return $b->getRank() <=> $a->getRank();
            });

            // 1. Adicionamos "Título" no cabeçalho
            echo sprintf("%-15s | %-10s | %-10s | %-15s\n", "Nome", "Classe", "Vitórias", "Título");
            echo str_repeat("-", 60) . PHP_EOL;

            foreach ($players as $p) {
                $vocationName = $p->getVocation() ? $p->getVocation()->getClassName() : "---";
                
                // 2. Chamamos o método getRankTitle() do objeto Player
                echo sprintf(
                    "%-15s | %-10s | %-10d | %-15s\n", 
                    $p->getName(), 
                    $vocationName, 
                    $p->getRank(),
                    $p->getRankTitle() // <--- Aqui está a mágica
                );
            }
        }

        echo "\n" . str_repeat("=", 50) . PHP_EOL;
        echo "Pressione ENTER para voltar ao menu...";
        fgets(STDIN);
        PHP_OS_FAMILY === 'Windows' ? system('cls') : system('clear');
    }
}