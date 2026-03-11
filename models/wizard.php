<?php 
require_once 'vocation.php';
class Wizard extends Vocation {
    public function __construct() {
        parent::__construct("Mago", 700, 15, 400);
    }

    // IMPLEMENTAÇÃO DO MÉTODO ABSTRATO
    public function getBaseDamage(): int {
        // O mago tem um dano base menor que o guerreiro no corpo a corpo (cajado)
        return rand(50, 90);
    }

    public function magicMissile() {
        echo "\n🔮 Você lança mísseis mágicos azuis!";
        return $this->calculateAttack(80);
    }

    public function fireball() {
        if ($this->magicalEnergy >= 60) {
            $this->magicalEnergy -= 60;
            echo "\n🔥 BOLA DE FOGO EXPLOSIVA! (Custo: 60 MP)";
            return $this->calculateAttack(250);
        }
        echo "\n❌ Mana insuficiente para a Bola de Fogo!";
        return ['damage' => 0, 'critical' => false];
    }    
}