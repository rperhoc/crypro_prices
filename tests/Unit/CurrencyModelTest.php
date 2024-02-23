<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Currency;

class CurrencyModelTest extends TestCase
{
    use DatabaseTransactions;

    public function test_is_favourited_by(): void
    {
        $user = User::factory()->create();
        $currency = Currency::factory()->create();

        $currency->favouritedByUsers()->attach($user);

        $isFavourited = $currency->isFavouritedBy($user);

        $this->assertTrue($isFavourited);

        $currency->favouritedByUsers()->detach($user);

        $isFavourited = $currency->isFavouritedBy($user);

        $this->assertFalse($isFavourited);
    }
}
