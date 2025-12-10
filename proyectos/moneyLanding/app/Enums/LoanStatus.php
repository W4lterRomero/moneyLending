<?php

namespace App\Enums;

enum LoanStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Completed = 'completed';
    case Delinquent = 'delinquent';
    case Cancelled = 'cancelled';
}
