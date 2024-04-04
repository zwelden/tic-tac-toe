<?php 

namespace App;

use App\IO;
use App\State;

final class Game
{
    public function __construct(
        private State\GameState $gameState, 
        private IO\GameIO $gameIO
    ) {
        // INIT
    }

    /**
     * Main game loop
     */
    public function startGame(): void 
    {
        while($this->isGameActive())
        {
            $this->nextPlayer();
            $this->renderGameState();
            $this->updateGameState();
            $this->checkForGameOver();
        }

        $this->handleGameEnd();
    }

    /**
     * If game active = false, game is over
     */
    private function isGameActive(): bool 
    {
        return $this->gameState->getGameActive();
    }

    /**
     * Toggle player turn
     */
    private function nextPlayer(): void 
    {
        $this->gameState->updatePlayerTurn();
    }

    /**
     * Get player tile selection choice
     */
    private function updateGameState(): void 
    {
        $currentPlayer  = $this->gameState->getCurrentPlayer();
        $tile_selection = $this->gameIO->requestPlayerSelectTile($currentPlayer);

        $xPos = $tile_selection['xPos'];
        $yPos = $tile_selection['yPos'];

        $this->gameState->placeTile($xPos, $yPos);
    }

    private function checkForGameOver(): void 
    {
        $this->gameState->evaluateEndConditions();
    }

    private function renderGameState(): void
    {
        $this->gameIO->renderGameState($this->gameState);
    }

    private function handleGameEnd(): void 
    {
        $this->gameIO->renderEndState($this->gameState);
    }
}
