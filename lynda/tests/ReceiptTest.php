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

	public function testPostTaxTotal()
	{
		$receipt = $this->getMockBuilder('TDD\Receipt')
			->setMethods(['total', 'tax']) // Methods that the mock object will respond to
			->getMock();

		$receipt->method('total')
			->will($this->returnValue(10.00));

		$receipt->method('tax')
			->will($this->returnValue(1.00));

		// Items, tax & coupon code arguments.
		// All these values do not matter because the return values from total() and tax() are already set
		$result = $receipt->postTaxTotal([1,2,5,8,99], 0.20, null);
		$this->assertEquals(11, $result);
	}
}
