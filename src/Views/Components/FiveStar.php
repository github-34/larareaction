<?php

namespace App\Express\Views\Components;

use Illuminate\View\Component;

class FiveStar extends Component
{
    /**
     * Object of Expression.
     *
     * @var string
     */
    public $object;
    public $starClass;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($object, $starClass)
    {
        $this->object = $object;
        $this->starClass = $starClass;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.express.five-star');
    }

    /**
     * Get the formatted expressions count.
     *
     * @return string
     */
    public function formatCount(int $expressionCount)
    {
        $countString = $expressionCount;

        if ($expressionCount > 999 && $expressionCount < 1000000) {
            $countString = floor($expressionCount / 1000).'k+';
        }

        if ($expressionCount > 1000000 && $expressionCount < 100000000) {
            $countString = floor($expressionCount / 1000000).'M+';
        }

        return (string) $countString;
    }

    public function formatAvg(float $avg)
    {
        return number_format($avg, 1);
    }
}
