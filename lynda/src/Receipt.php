<?php
namespace TDD;

class Receipt
{
	public function total(array $items = [], $coupon)
	{
		$sum = array_sum($items);
		
		if ( !is_null($coupon) ) {
			return $sum - ($sum * $coupon);
		}
		
		return $sum;
	}

	public function tax($amount, $tax)
	{
		return $amount * $tax;
	}

	 public function postTaxTotal($items, $tax, $coupon)
	 {
		$subTotal = $this->total($items, $coupon);
		return $subTotal + $this->tax($subTotal, $tax);
	 }

	 public function add(array $numbers)
	 {
		return array_sum($numbers);
	 }
}
