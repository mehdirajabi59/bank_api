<?php

namespace App\Enums;

enum TransferConfig: int
{
    case Max = 50000000;
    case Min = 1000;
    case Fee = 500;

}
