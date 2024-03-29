<?php

namespace Seatplus\TelegramChannel\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model implements TelegramNotifiable
{
    use HasFactory;

    public $guarded = [];
}
