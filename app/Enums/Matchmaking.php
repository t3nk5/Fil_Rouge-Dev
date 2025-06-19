<?php

namespace App\Enums;

enum Matchmaking: int
{
    case Waiting = 0;
    case NotReady = -1;
    case Ready = 1;
    case InGame = 2;
}
