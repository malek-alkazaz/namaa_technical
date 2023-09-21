<?php

namespace App\Enums;

use App\Traits\V1\Api\EnumOperation;


enum ArticleStatus: string
{
    use EnumOperation;

    case Pending = 'pending';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
}
