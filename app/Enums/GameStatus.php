<?php

namespace App\Enums;

enum GameStatus: int
{
    case InInit = -2;
    case InProgress = -1;
    case Draw = 0;
    case Player1_Win = 1;
    case Player2_Win = 2;
}
