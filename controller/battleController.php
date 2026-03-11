<?php

require_once __DIR__ . '/../models/vocation.php';
require_once __DIR__ . '/../models/warrior.php';
require_once __DIR__ . '/../models/wizard.php';
require_once __DIR__ . '/../models/player.php';
require_once __DIR__ . '/../models/playerRepository.php';
require_once __DIR__ . '/../controller/playerController.php';

class BattleController {
    private PlayerRepository $repository;

    public function __construct() {
        $this->repository = new PlayerRepository();
    }

    public function iniciarCenarioBatalha(): void {
        $players = $this->repository->getAll();

        if (count($players) < 2) {
            echo "\n⚠️ Erro: São necessários pelo menos 2 players cadastrados para um duelo!" . PHP_EOL;
            echo "Pressione ENTER para voltar...";
            fgets(STDIN);
            return;
        }

        // 1. Mostrar os lutadores disponíveis
        echo "\n" . str_repeat("=", 30) . PHP_EOL;
        echo "⚔️  ARENA DE SELEÇÃO  ⚔️" . PHP_EOL;
        echo str_repeat("=", 30) . PHP_EOL;

        foreach ($players as $p) {
            echo "[{$p->getId()}] {$p->getName()} ({$p->getVocation()->getClassName()}) - Rank: {$p->getRank()}" . PHP_EOL;
        }

        // 2. Seleção dos duelistas
        echo "\nEscolha o ID do Player 1: ";
        $id1 = (int)trim(fgets(STDIN));

        echo "Escolha o ID do Player 2: ";
        $id2 = (int)trim(fgets(STDIN));

        // 3. Buscar os objetos selecionados
        $player1 = $this->buscarPlayerPorId($players, $id1);
        $player2 = $this->buscarPlayerPorId($players, $id2);

        if (!$player1 || !$player2 || $id1 === $id2) {
            echo "\n❌ Seleção inválida! Certifique-se de escolher IDs diferentes e existentes." . PHP_EOL;
            sleep(2);
            return;
        }

        // 4. Iniciar o loop de combate (Próximo passo)
        $this->executarLuta($player1, $player2);
    }

    private function buscarPlayerPorId(array $players, int $id): ?Player {
        foreach ($players as $p) {
            if ($p->getId() === $id) return $p;
        }
        return null;
    }

    private function executarLuta(Player $p1, Player $p2): void {
        $v1 = $p1->getVocation();
        $v2 = $p2->getVocation();

        echo "\n⚔️  A BATALHA COMEÇOU: {$p1->getName()} VS {$p2->getName()} ⚔️\n";

        while ($v1->isAlive() && $v2->isAlive()) {
            // --- TURNO DO PLAYER 1 ---
            // Passamos: atacante, defensor, e os dois originais para a arena
            $this->processarTurno($p1, $p2, $p1, $p2);
            if (!$v2->isAlive()) break;

            // --- TURNO DO PLAYER 2 ---
            // Passamos: atacante, defensor, e os dois originais para a arena
            $this->processarTurno($p2, $p1, $p1, $p2);
        }

        // --- FINALIZAÇÃO E RANKING ---
        $vencedor = $v1->isAlive() ? $p1 : $p2;
        echo "\n🏆 O VENCEDOR É: " . $vencedor->getName() . " 🏆" . PHP_EOL;

        // Aumentar o Rank do vencedor em 1
        $vencedor->setRank($vencedor->getRank() + 1);

        // SALVAR NO JSON (Buscamos todos para atualizar apenas o que mudou)
        $todosOsPlayers = $this->repository->getAll();
        foreach ($todosOsPlayers as $index => $p) {
            if ($p->getName() === $vencedor->getName()) {
                // Atualiza o rank no objeto que veio do repositório
                $todosOsPlayers[$index]->setRank($p->getRank() + 1);
            }
        }
        $this->repository->save($todosOsPlayers);

        echo "\n✅ Ranking atualizado! Pressione ENTER para voltar ao menu...";
        fgets(STDIN);
    }
    private function renderizarArena(Player $p1, Player $p2): void {
        system('clear'); // Limpa a tela (use 'cls' se for Windows)

        $v1 = $p1->getVocation();
        $v2 = $p2->getVocation();

        // 1. Cabeçalho com Nomes (Ajuste de espaçamento para alinhar com o ASCII)
        echo "\n" . str_repeat(" ", 5) . "PLAYER 1: " . str_pad($p1->getName(), 20);
        echo str_repeat(" ", 15) . "PLAYER 2: " . $p2->getName() . "\n";
        
        // 2. A Arte ASCII (Sua arte fornecida)
        // Dica: Usamos a sintaxe heredoc (<<<EOT) para manter a formatação do bloco
        $ascii = <<<EOT
    ⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣆⠻⡟⠛⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢠⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸
    ⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡿⠿⣿⣿⣿⣿⣿⣿⣿⣿⣦⠹⣄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣈⣙⣲⡦⣄⣀⢀⣀⣠⠀⢀⠀⠀⠀⠀⠀⠀⠀⠀⢸
    ⣿⣿⣿⣿⡿⠛⠋⠉⠉⠁⢀⠀⠸⣿⣿⣿⣿⣿⣿⣿⣿⣧⡘⣆⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢙⣲⣦⡬⢻⠟⣻⣧⠖⠋⠀⠀⠀⠀⠀⠀⠀⠀⢸
    ⡿⠏⠀⠀⠀⠀⠀⢰⡄⠘⡞⡆⠀⢹⣿⣿⣿⣿⣿⣿⣿⣿⣷⡌⢦⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠤⢾⡿⠟⠏⣰⣿⠞⠋⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸
    ⡇⠀⠀⠀⠀⠀⠀⠀⠱⣄⠉⡝⢀⣾⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣌⢣⡀⠀⠐⠒⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠋⣠⣴⣾⢧⣿⢷⣤⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸
    ⡇⠀⠀⠀⠀⠀⠀⠀⠀⠈⠃⠁⣾⡍⠿⣿⢿⣿⣿⣿⣿⣿⣿⣿⣿⣆⠹⡄⠀⠀⠈⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠛⠿⡿⢻⣿⠴⣿⡆⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸
    ⡇⠀⢀⣤⣤⠤⠤⣤⡀⠀⠀⢸⣿⠇⠀⠈⠳⢽⡿⣿⣿⣿⣿⣿⣿⣿⣧⠈⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣴⣿⣿⣿⠀⣿⣧⠀⠀⠀⢀⣠⠶⠆⠀⠀⠀⢸
    ⡇⠀⢀⣤⣤⣴⣄⠀⠙⠦⠀⣿⣿⠀⠀⠀⠀⠀⠙⢮⣿⣿⣿⣿⠿⢿⡿⣣⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣴⣿⣿⣿⣿⣿⢦⣙⣿⠀⠀⢰⠿⠁⠀⡴⠖⠃⠀⢸
    ⡇⠠⣉⣙⣻⣩⣽⠿⠃⠀⢀⣩⣟⣀⠀⠀⠀⠀⠀⠀⠉⢹⣿⣧⡀⣠⣿⠉⠳⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣴⣿⣿⣿⣿⣿⣿⣿⡄⠟⠁⠀⠀⠈⢠⣄⢸⠁⠀⠀⠀⢸
    ⡇⠀⠀⠀⠀⠀⠀⠀⠒⠛⠛⠉⠙⣯⡻⣦⡀⠀⠀⣠⣶⣿⠉⣻⣿⡷⣯⠀⠀⢻⡄⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⣴⣿⣿⣿⣿⣿⣿⣿⣿⣿⣧⢠⠀⠀⠀⠀⠀⠈⠙⠓⠦⠤⠤⢾
    ⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣤⣤⣾⣿⣿⣿⣆⣼⣿⡿⣻⣿⡇⢈⣿⣼⡆⢰⠀⢹⣆⠀⠀⠀⠀⣀⣤⣶⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣏⣇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸
    ⡇⠀⠀⠘⢢⣤⣀⣀⣀⣀⣴⣹⡿⣿⣿⣿⣿⣿⣿⣿⣼⣿⠡⢿⡄⢨⣿⠃⠘⣧⠘⡟⢧⡀⢴⣾⣭⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡾⣄⡀⠀⠀⠀⠀⠀⠀⠀⠀⣽
    ⣷⣤⣀⡀⠀⠀⠉⠿⢿⣿⢿⣿⠻⣼⣿⣿⣿⣿⣿⣿⣿⣿⣄⠀⣿⣆⠏⢳⡀⠸⣧⢷⠈⢳⡈⢻⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣗⢿⣿⡷⠶⠶⠦⠀⠀⢀⣴⣿
    ⣷⣆⣈⠉⠛⠶⢶⠀⣦⡏⣾⣿⣿⡉⠻⣿⣿⣿⣿⣿⣿⣿⣿⣷⣿⣟⠀⠀⠀⠀⠈⠘⡆⠀⠙⣄⠻⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣼⡇⠀⠀⠀⣀⣤⣼⣿⣿⣿
    ⣿⣿⣿⣿⣿⣶⣶⣶⣶⣷⣿⣿⣿⣿⣆⡀⠙⢮⠻⣿⣿⣿⣿⣿⣿⣿⣆⠀⠀⠀⠀⠀⣉⡴⠞⠉⡆⠹⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡿⢿⣩⣿⣿⣿⢻
    ⣿⣿⣿⣿⣿⣿⣿⣿⣿⡿⢿⠿⠛⠋⠉⢷⣄⠈⢿⣮⡻⣿⣿⣿⣿⣿⣿⣿⣦⡀⠀⣠⣴⣶⣾⣷⣷⣦⠙⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡿⠞⣉⣤⡾⠃⠀⣿
    ⣿⣿⣿⣿⣿⣿⣿⣿⣿⣄⠂⠀⠀⠀⠀⢀⣹⣆⠈⢻⣿⣎⠻⣿⣿⣿⣿⣿⣿⣿⣶⣿⣿⡿⠿⢛⣉⣭⣷⡈⢿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⢛⡛⢿⣿⠿⣋⠴⣾⣿⣿⣇⠀⣼⣿
    ⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣷⣶⣷⣶⣿⣿⠏⠹⣆⠘⣿⣿⢳⢹⣿⣿⣿⣿⣿⣿⣿⣯⣥⣶⣿⣿⡿⠿⣿⢷⡄⢿⣿⣿⣿⣿⡿⢋⣥⡤⢴⣟⠉⠙⠿⣿⣿⢶⣿⠏⠀⢉⣿⣿⣿
    ⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣇⣀⣤⣴⣿⣆⢻⣿⠘⠸⣿⣿⣿⣿⣿⣿⣿⣿⡿⠛⠉⠀⠀⠀⠸⡌⣿⣄⢻⣿⣿⢛⣴⣟⠀⠀⠠⣿⣦⡀⠀⠈⣻⣿⡟⢛⣶⣿⣿⣿⣿
    EOT;
        echo $ascii . "\n";

        // 3. Barra de Status de Vida e Mana abaixo do ASCII
        echo str_repeat("=", 85) . "\n";
        echo " [P1] " . str_pad($p1->getName(), 15) . " ❤️ HP: " . str_pad($v1->getHealth(), 4) . " ✨ MP: " . $v1->getMagicalEnergy() . "\n";
        echo " [P2] " . str_pad($p2->getName(), 15) . " ❤️ HP: " . str_pad($v2->getHealth(), 4) . " ✨ MP: " . $v2->getMagicalEnergy() . "\n";
        echo str_repeat("=", 85) . "\n";
    }
    private function processarTurno(Player $atacante, Player $defensor, Player $p1, Player $p2): void {
        $this->renderizarArena($p1, $p2);

        $vAtacante = $atacante->getVocation();
        $vDefensor = $defensor->getVocation();

        // 1. INICIALIZAMOS A VARIÁVEL AQUI (Isso mata o erro do Intelephense)
        $resultadoAtaque = ['damage' => 0, 'critical' => false];

        echo "\n👉 VEZ DE: {$atacante->getName()}\n";
        echo "Escolha sua ação:\n";
        
        if ($vAtacante instanceof Warrior) {
            echo "[1] Sword Slash\n[2] Shield Bash (10 MP)\nOpção: ";
            $op = trim(fgets(STDIN));
            $resultadoAtaque = ($op === '2') ? $vAtacante->shieldBash() : $vAtacante->swordSlash();
        } 
        else if ($vAtacante instanceof Wizard) {
            echo "[1] Magic Missile\n[2] Fireball (60 MP)\nOpção: ";
            $op = trim(fgets(STDIN));
            $resultadoAtaque = ($op === '2') ? $vAtacante->fireball() : $vAtacante->magicMissile();
        }

        // Agora o PHP tem certeza que $resultadoAtaque existe!
        if ($resultadoAtaque['damage'] > 0) {
            $res = $vDefensor->receiveDamage($resultadoAtaque['damage']);
            // ... restante do código de exibição
        }
}
}