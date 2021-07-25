<?php

namespace Seatplus\TelegramChannel\Database\Factories;



use Illuminate\Database\Eloquent\Factories\Factory;
use Seatplus\TelegramChannel\Model\TelegramUser;

class TelegramUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TelegramUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }
}
