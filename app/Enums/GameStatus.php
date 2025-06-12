<?php
namespace App\Enums;
enum GameStatus: int
{
    case Draw = -1;
    case InProgress = 0;
    case Player1_Win = 1;
    case Player2_Win = 2;
}
