<?php

namespace Tests\Unit;

use App\Models\Image;
use App\Models\User;

use App\Express\Xpress;
use App\Express\Models\Expression;
use App\Express\ExpressionService;
use App\Express\Facades\Express;

use PHPUnit\Framework\TestCase;

class ExpressionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_can_express_five_star_to_image()
    {
        //$this->seed();

        $user = User::find(1);
        $image = Image::all()->first();
        $re = Express::express($image, Xpress::FIVESTAR, Xpress::FIVESTARS);
        $re = Express::express($image, Xpress::MICHELINSTAR, Xpress::TWOMICHELINSTARS);
        $re = Express::express($image, Xpress::EMOTIVE, Xpress::HAPPY);
        $this->assertTrue(true);
    }
}
