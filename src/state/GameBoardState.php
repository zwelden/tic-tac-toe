<?php 

namespace App\State;

use App\Player;

final class GameBoardState
{
    private array $boardState;

    public function __construct()
    {
        $this->boardState = [
            [null, null, null],
            [null, null, null],
            [null, null, null]
        ];
    }

    public function getBoardStateArray(): array 
    {
        return $this->boardState;
    }

    /**
     * Update the board state with a player's tile selection
     */
    public function placeTile(int $xPos, int $yPos, Player\GamePlayer $player): void 
    {
        $this->validateXPosition($xPos);
        $this->validateYPosition($yPos);
        $this->validateTileFree($xPos, $yPos);
        
        $this->boardState[$yPos][$xPos] = $player;
    }

    /**
     * Check if there are any empty (null) tiles left on the board
     */
    public function doesBoardHaveEmptyTiles(): bool 
    {
        $hasEmptyTiles = false;

        foreach ($this->boardState as $row => $cols)
        {
            foreach ($cols as $tile)
            {
                if ($tile === null)
                {
                    $hasEmptyTiles = true;
                    break(2);
                }
            }
        }

        return $hasEmptyTiles;
    }

    /**
     * Does the board have a connecting 3 in a row. 
     * If so return the winning player
     * Else return null
     */
    public function checkForWinner(): null|Player\GamePlayer 
    {
        $winner = $this->evalBoardDiagonals();
        
        if ($winner === null) 
        {
            $winner = $this->evalBoardCols();
        }

        if ($winner === null) 
        {
            $winner = $this->evalBoardRows();
        }

        return $winner;
    }

    /**
     * Check all board columns for a winnner
     */
    private function evalBoardCols(): null|Player\GamePlayer
    {
        // example win condition
        // [ ][ ][X]
        // [ ][ ][X]
        // [ ][ ][X]

        foreach ($this->boardState[0] as $idx => $player)
        {
            if ($player !== null
                && $this->boardState[0][$idx] === $this->boardState[1][$idx]
                && $this->boardState[0][$idx] === $this->boardState[2][$idx]
            ) {
                return $player;
            }
        }

        return null;
    }

    /**
     * Check all board diagonals for a winner
     */
    private function evalBoardDiagonals(): null|Player\GamePlayer
    {
        // example win condition
        // [X][ ][ ]
        // [ ][X][ ]
        // [ ][ ][X]

        if ($this->boardState[0][0] !== null 
            && $this->boardState[0][0] === $this->boardState[1][1]
            && $this->boardState[0][0] === $this->boardState[2][2]
        ) {
            return $this->boardState[0][0];
        } 
        else if ($this->boardState[0][2] !== null 
            && $this->boardState[0][2] === $this->boardState[1][1]
            && $this->boardState[0][2] === $this->boardState[2][0]
        ) {
            return $this->boardState[0][2];
        }

        return null;
    }

    /**
     * Check all board rows for a winner
     */
    private function evalBoardRows(): null|Player\GamePlayer 
    {
        // example win condition
        // [ ][ ][ ]
        // [X][X][X]
        // [ ][ ][ ]

        foreach ($this->boardState as $row => $cols)
        {
            if ($cols[0] !== null
                && $cols[0] === $cols[1] 
                && $cols[1] === $cols[2]
            ) {
                return $cols[0];
            }
        }

        return null;
    }

    /**
     * Check if an xPos is in bounds
     */
    private function validateXPosition(int $xPos): void 
    {
        if ($xPos < 0 || $xPos > 2)
        {
            throw new \Exception("Invalid X position: {$x_pos}");
        }
    }

    /**
     * Check if a yPos is in bounds
     */
    private function validateYPosition(int $yPos): void 
    {
        if ($yPos < 0 || $yPos > 2)
        {
            throw new \Exception("Invalid Y position: {$yPos}");
        }
    }

    /**
     * Check if a tile is unoccupied (null)
     */
    private function validateTileFree(int $xPos, int $yPos): void 
    {
        $tile = $this->boardState[$yPos][$xPos];

        if ($tile !== null)
        {
            throw new \Exception("Tile already selected a X: {$xPos}, Y: {$yPos}, Symbol: {$tile->getPlayerSymbol()}");
        }
    }
}
