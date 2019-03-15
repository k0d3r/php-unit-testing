<?php
namespace TDD\Test;

require_once dirname(__DIR__) . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use TDD\Receipt;

class ReceiptTest extends TestCase
{
	private $receipt;

	public function setUp()
	{
		$this->receipt = new Receipt;
	}

	public function tearDown()
	{
		unset($this->receipt);
	}

	public function testTotal()
	{
		$input = [0,2,5,8];
		$expected = 15;
		$output = $this->receipt->total($input, null);
		$this->assertEquals($expected, $output, sprintf('Failure message from: %s', __METHOD__));
	}

	public function testTotalAndCoupon()
	{
		$input = [0,2,5,8];
		$coupon = 0.20;
		$expected = 12;
		$output = $this->receipt->total($input, $coupon);
		$this->assertEquals($expected, $output, sprintf('Failure message from: %s', __METHOD__));
	}

	public function testTax()
	{
		$input = 10.00;
		$tax = 0.10;
		$expected = 1.00;
		$output = $this->receipt->tax($input, $tax);
		$this->assertEquals($expected, $output, sprintf('Failure message from: %s', __METHOD__));
	}
}
