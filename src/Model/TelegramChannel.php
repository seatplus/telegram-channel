<?php

namespace Seatplus\TelegramChannel\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramChannel extends Model implements TelegramNotifiable
{
    use HasFactory;

    public $guarded = [];
}
