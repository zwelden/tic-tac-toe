<?php 

namespace App\State;

use App\Player;

final class GameState
{
    private GameBoardState $boardState;
    private bool $gameActive = true;
    private Player\GamePlayer|null $winner = null;
    private Player\GamePlayer $player1;
    private Player\GamePlayer $player2;
    private Player\GamePlayer|null $currentPlayer;
    private int $lastXPos;
    private int $lastYPos;

    public function __construct(GameBoardState $boardState)
    {
        $this->boardState = $boardState;

        $this->player1 = new Player\GamePlayer(1, 'Player 1', 'X');
        $this->player2 = new Player\GamePlayer(2, 'Player 2', 'O');

        $this->currentPlayer = null;
    }

    public function getCurrentPlayer(): Player\GamePlayer 
    {
        return $this->currentPlayer;
    }

    /**
     * Toggle player turn. If current player is null, then next player is Player 1
     */
    public function updatePlayerTurn(): void 
    {
        $this->currentPlayer = ($this->currentPlayer == $this->player1) 
                                ? $this->player2 
                                : $this->player1;
    }

    /**
     * Update the board state with the player's selection
     */
    public function placeTile(int $xPos, int $yPos): void 
    {
        $this->boardState->placeTile($xPos, $yPos, $this->currentPlayer);
    }

    /**
     * Check for win or tie condition
     * End game if all tiles are filled or winner has 3 in a row
     */
    public function evaluateEndConditions(): void 
    {
        if ($this->boardState->doesBoardHaveEmptyTiles() === false)
        {
            $this->gameActive = false;
        }

        $this->checkForWinner();
    }

    public function getGameActive(): bool 
    {
        return $this->gameActive;
    }

    public function getGameWinner(): Player\GamePlayer|null
    {
        return $this->winner;
    }

    public function getBoardState(): GameBoardState 
    {
        return $this->boardState;
    }

    private function checkForWinner()
    {
        $winner = $this->boardState->checkForWinner();

        if ($winner !== null)
        {
            $this->winner = $winner;
            $this->gameActive = false;
        }
    }
    
}

