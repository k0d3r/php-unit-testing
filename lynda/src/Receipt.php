<?php
namespace TDD;

use \BadMethodCallException;

class Receipt
{
    public $tax;
    
    public function subTotal(array $items = [], $coupon)
    {
        if ($coupon > 1.00) {
            throw new BadMethodCallException('Coupon must be less than or equal to 1.00');
        }
        
        $sum = array_sum($items);
        
        if ( !is_null($coupon) ) {
            return $sum - ($sum * $coupon);
        }
        
        return $sum;
    }

    public function tax($amount)
    {
        return $amount * $this->tax;
    }

    public function postTaxTotal($items, $tax, $coupon)
    {
        $subTotal = $this->subTotal($items, $coupon);
        return $subTotal + $this->tax($subTotal, $tax);
    }

    public function add(array $numbers)
    {
        return array_sum($numbers);
    }

    // Round an int to 2 decimal places
    public function currencyAmount($input)
    {
        return round($input, 2);
    }
}
