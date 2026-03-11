<?php

abstract class Vocation {
    protected string $className;
    protected int $health;
    protected int $defensePercentage;
    protected int $magicalEnergy;
    

    public function __construct(string $className, int $health, int $defensePercentage, int $magicalEnergy) {
        $this->className = $className;
        $this->health = $health;
        $this->defensePercentage = $defensePercentage;
        $this->magicalEnergy = $magicalEnergy;
    }

    // Getters
    public function getClassName(): string {return $this->className;}
	public function getHealth(): int {return $this->health;}
	public function getDefensePercentage(): int {return $this->defensePercentage;}
	public function getMagicalEnergy(): int {return $this->magicalEnergy;}

    // Setters
	public function setClassName(string $className): void {$this->className = $className;}
	public function setHealth(int $health): void {$this->health = $health;}
	public function setDefensePercentage(int $defensePercentage): void {$this->defensePercentage = $defensePercentage;}
	public function setMagicalEnergy(int $magicalEnergy): void {$this->magicalEnergy = $magicalEnergy;}

	// 1. Criamos este método abstrato. 
    // Isso obriga Warrior e Wizard a dizerem qual o seu dano base.
    abstract public function getBaseDamage(): int;

    // 2. Este é o método que o Controller vai chamar.
    // Ele não pede argumentos, resolvendo o erro do Intelephense!
    public function dispararAtaquePadrao(): array {
        return $this->calculateAttack($this->getBaseDamage());
    }
    
    public function calculateAttack(int $baseDamage):array{
        $isCritical = rand(1,100) <= 15;
        $finalDamage = $isCritical ? $baseDamage *2 : $baseDamage;

        return[
            'damage' => (int)$finalDamage,
            'critical' => $isCritical
        ];
    }

    public function receiveDamage(int $incomingDamage): array {
        // Converte o int (ex: 30) para decimal (0.30)
        $reductionFactor = $this->defensePercentage / 100;
        
        // Calcula quanto do dano é absorvido
        $damageAbsorbed = $incomingDamage * $reductionFactor;
        
        // O que sobra é o dano real que afeta a vida
        $finalDamage = $incomingDamage - $damageAbsorbed;
        
        // Arredondamos para evitar números quebrados no HP
        $finalDamage = (int)round($finalDamage);
        $damageAbsorbed = (int)round($damageAbsorbed);

        // Aplica o dano à vida (garantindo que não fique menor que 0)
        $this->health = max(0, $this->health - $finalDamage);

        return [
            'original' => $incomingDamage,
            'absorbed' => $damageAbsorbed,
            'final'    => $finalDamage,
            'remainingHealth' => $this->health
        ];
    }

    // Getters para exibir no seu menu elegante
    public function getStatus(): string {
        return "{$this->className} | ❤️ HP: {$this->health} | 🛡️ DEF: {$this->defensePercentage}% | ✨ MP: {$this->magicalEnergy}";
    }

    public function isAlive(): bool {
        return $this->health > 0;
    }

    public function useMana(int $amount): bool {
        if ($this->magicalEnergy >= $amount) {
            $this->magicalEnergy -= $amount;
            return true;
        }
        return false;
    }
}