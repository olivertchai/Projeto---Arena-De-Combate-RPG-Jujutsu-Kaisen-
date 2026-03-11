<?php 

use Player;

class playerRepository{
    private string $file = __DIR__ . "/../data/players.json";

    public function save(array $players):void {
        $dataToSave = [];

        foreach ($players as $index => $player){
            $dataToSave[]=[
                'id' => $index + 1, 
                'name' => $player->getName(),
                'rank' => $player->getRank(),
                'vocation_type' => $player->getVocation() ? $player->getVocation()->getClassName() : null
                ,'title' => $player->getRankTitle()
            ];
        }
        // json_encode converte dados, como arrays ou objetos, para o formato JSON 
        file_put_contents($this->file, json_encode($dataToSave, JSON_PRETTY_PRINT));
    }

    public function getAll():array {
        if(!file_exists($this->file)) return [];

        $content = file_get_contents($this->file);
        // json_decode converte uma string json em um objeto
        $jsonData = json_decode($content, true);

        // Se o JSON estiver vazio ou inválido, retorna array vazio
        if (!$jsonData) return [];  
        $playerObjects = [];
        foreach ($jsonData as $item) {
            // Importante: a ordem aqui deve ser a mesma do __construct acima
            $player = new Player($item['id'], $item['name'], $item['rank']);

            if ($item['vocation_type'] === 'Guerreiro') {
                $player->setVocation(new Warrior());
            } elseif ($item['vocation_type'] === 'Mago') {
                $player->setVocation(new Wizard());
            }

            $playerObjects[] = $player;
        }

        return $playerObjects;

    }
}