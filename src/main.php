<?php 

namespace App;

use App\State;
use App\IO;

require_once "./Game.php";
require_once "./io/GameIO.php";
require_once "./state/GameBoardState.php";
require_once "./state/GameState.php";
require_once "./player/GamePlayer.php";

$boardState = new State\GameBoardState();
$gameState  = new State\GameState($boardState);
$gameIO     = new IO\GameIO();
$game       = new Game($gameState, $gameIO);

try 
{
    $game->startGame();
} 
catch (\Throwable $th) 
{
    echo "\n\n";
    echo $th->getMessage();
    echo "\n\n";
}