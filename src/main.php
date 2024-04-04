<?php 

namespace App;

use App\State;
use App\IO;

define("ROOT_DIR", dirname(__FILE__));

require_once ROOT_DIR."/Game.php";
require_once ROOT_DIR."/io/GameIO.php";
require_once ROOT_DIR."/state/GameBoardState.php";
require_once ROOT_DIR."/state/GameState.php";
require_once ROOT_DIR."/player/GamePlayer.php";

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