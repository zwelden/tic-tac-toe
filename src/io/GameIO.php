<?php 

namespace App\IO;

use App\Player;
use App\State;

final class GameIO
{
    const COLOR_NC           = "\e[0m"; # No Color
    const COLOR_BLACK        = "\e[0;30m";
    const COLOR_GRAY         = "\e[1;30m";
    const COLOR_RED          = "\e[0;31m";
    const COLOR_LIGHT_RED    = "\e[1;31m";
    const COLOR_GREEN        = "\e[0;32m";
    const COLOR_LIGHT_GREEN  = "\e[1;32m";
    const COLOR_BROWN        = "\e[0;33m";
    const COLOR_YELLOW       = "\e[1;33m";
    const COLOR_BLUE         = "\e[0;34m";
    const COLOR_LIGHT_BLUE   = "\e[1;34m";
    const COLOR_PURPLE       = "\e[0;35m";
    const COLOR_LIGHT_PURPLE = "\e[1;35m";
    const COLOR_CYAN         = "\e[0;36m";
    const COLOR_LIGHT_CYAN   = "\e[1;36m";
    const COLOR_LIGHT_GRAY   = "\e[0;37m";
    const COLOR_WHITE        = "\e[1;37m";

    private array $tileMap = [
        'display' => '',
        'empty_tiles' => []
    ];

    private array $playerColors = [
        1 => self::COLOR_RED,
        2 => self::COLOR_CYAN
    ];

    public function __construct()
    {
        // init
    }

    /**
     * Build and render the tic tac toe board for display
     */
    public function renderGameState(State\GameState $gameState, bool $gameOver=false): void
    {
        $this->clearScreen();

        $boardState = $gameState->getBoardState();
        $boardArray = $boardState->getBoardStateArray();

        $this->resetTileMap();
        $this->buildTileMap($boardArray, $gameOver);
        $this->renderTicTacToeBoard();
    }

    /**
     * Request user select a tile to claim
     * Function calls itself recursively if invalid input submitted 
     * If recursive stack exceeds 6, exit 
     */
    public function requestPlayerSelectTile(Player\GamePlayer $player, int $depth=0): array
    {
        if ($depth > 5)
        {
            throw new \Exception("Failure limit exceeded");
        }

        $displayStr = "{$player->getPlayerName()}, please select tile: ";

        $tileSelection = readline($displayStr);

        if ($tileSelection === false) // ctrl-d will make readline return false
        {
            // end game
            exit();
        }

        $tileSelection = intval(trim($tileSelection));

        if ($this->isValidTileSelection($tileSelection)) 
        {
            return $this->tileMap['empty_tiles'][$tileSelection];
        }

        return $this->requestPlayerSelectTile($player, ++$depth);
    }

    /**
     * Render the Game Over message
     */
    public function renderEndState(State\GameState $gameState): void
    {
        $this->renderGameState($gameState, true);
        $winner = $gameState->getGameWinner();

        echo "Game Over!\n";

        if ($winner === null)
        {
            $startColor = self::COLOR_YELLOW;
            $endColor   = self::COLOR_NC;

            echo "{$startColor}Tie{$endColor}\n\n";
            return;
        }
        
        $playerColor = $this->playerColors[$winner->getPlayerNumber()];
        $endColor    = self::COLOR_NC;
        echo "{$playerColor}{$winner->getPlayerName()}{$endColor} Wins!\n\n";
    }

    private function clearScreen(): void 
    {
        if (str_starts_with(strtoupper(PHP_OS), 'WIN')) 
        {
            // Windows
            system('cls');
        }
        else 
        {
            // *nix / OSX
            system('clear');
        }
        
    }

    private function resetTileMap(): void 
    {
        $this->tileMap = [
            'display' => '',
            'empty_tiles' => []
        ];
    }

    /**
     * Build a hash map that contains the board display string and a reference 
     * of empty tile ids to x,y coordinates
     */
    private function buildTileMap($boardArray, bool $gameOver=false): void 
    {
        $emptyTileId = 1;

        foreach ($boardArray as $yPos => $cols)
        {
            $colString = "";

            foreach ($cols as $xPos => $player)
            {
                $tileDisplay = '';

                if ($player === null)
                {
                    if ($gameOver === false)
                    {
                        $this->tileMap['empty_tiles'][$emptyTileId] = [
                            'xPos' => $xPos,
                            'yPos' => $yPos
                        ];
    
                        $tileDisplay = "[{$emptyTileId}]";
                        $colString   .= $tileDisplay;
    
                        $emptyTileId++;
                    }
                    else 
                    {
                        $tileDisplay = "[ ]";
                        $colString   .= $tileDisplay;
                    }
                    
                    continue;
                }

                $playerColor = $this->playerColors[$player->getPlayerNumber()];
                $endColor    = self::COLOR_NC;
                $tileDisplay = "[{$playerColor}{$player->getPlayerSymbol()}{$endColor}]";
                $colString   .= $tileDisplay;
            }

            $this->tileMap['display'] .= $colString . "\n";
        }
    }

    private function renderTicTacToeBoard(): void 
    {
        echo "Tic Tac Toe\n\n";
        echo $this->tileMap['display'] . "\n\n";
    }

    /**
     * is tile selection a valid empty tile on the screen
     */
    private function isValidTileSelection($tileSelection): bool 
    {
       return (array_key_exists($tileSelection, $this->tileMap['empty_tiles']));
    }
}
