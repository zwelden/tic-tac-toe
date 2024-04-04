<?php 

namespace App\Player;

final class GamePlayer
{
    public function __construct(
        private int $playerNumber, 
        private string $playerName, 
        private string $playerSymbol
    ) {
      // INIT 
    }

    public function getPlayerNumber(): int 
    {
        return $this->playerNumber;
    }

    public function getPlayerName(): string 
    {
        return $this->playerName;
    }

    public function getPlayerSymbol(): string 
    {
        return $this->playerSymbol;
    }
}
