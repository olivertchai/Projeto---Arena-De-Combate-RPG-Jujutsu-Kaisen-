<?php 
require_once 'vocation.php';
class Warrior extends Vocation {
    public function __construct() {
        parent::__construct("Guerreiro", 1200, 40, 50);
    }

    // IMPLEMENTAÇÃO DO MÉTODO ABSTRATO
    public function getBaseDamage(): int {
        // O dano base do guerreiro é consistente
        return rand(80, 110);
    }

    public function swordSlash() {
        echo "\n⚔️ Você desfere um golpe de espada pesado!";
        return $this->calculateAttack(100);
    }

    public function shieldBash() {
        if ($this->magicalEnergy >= 10) {
            $this->magicalEnergy -= 10;
            echo "\n🛡️ Golpe com o escudo! (Custo: 10 MP)";
            return $this->calculateAttack(150);
        }
        echo "\n❌ Energia insuficiente para o Golpe de Escudo!";
        return ['damage' => 0, 'critical' => false];
    }
}