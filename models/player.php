<?php
use Vocation;
class Player{
    private ?int $id;
    private string $name;
    private int $rank;
    private ?Vocation $vocation = null;

    public function __construct(?int $id = null, string $name, int $rank) {
        $this->id = $id;
        $this->name = $name;
        $this->rank = $rank;
    }

    // Getters
    public function getId(){ return $this->id; }
    public function getName() { return $this->name; } 
    public function getRank() { return $this->rank; }    
    public function getVocation(): ?Vocation { return $this->vocation; }

    // Setters
    public function setName($name) { $this->name = $name; }
    public function setRank($rank) { $this->rank = $rank; }
    public function setVocation(Vocation $vocation): void {
        $this->vocation = $vocation;
    }

    public function addWin(): void {
        $this->rank++;
    }

    public function showProfile(): void {
        $vocationName = $this->vocation ? $this->vocation->getClassName() : "Nenhuma";
        echo "--- PERFIL DO JOGADOR ---" . PHP_EOL;
        echo "\nNome: {$this->name}". PHP_EOL;
        echo "\nRank: " . $this->getRankTitle() . " ({$this->rank} vitórias)" . PHP_EOL;
        echo "\nClasse Atual: {$vocationName}" . PHP_EOL;
        echo "\n-------------------------\n". PHP_EOL;
    }

    // O "Rank" pode ser um título baseado nas vitórias
    public function getRankTitle(): string {
        if ($this->rank >= 15) return "Lenda Viva 🏆";
        if ($this->rank >= 10) return "Veterano ⚔️";
        if ($this->rank >= 5)  return "Combatente 🛡️";
        if ($this->rank >= 1)  return "Recruta 🌱";
        return "Novato 🐣";
    }
}

