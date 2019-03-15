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
		$items = ['MOCKED_VALUES'];
		$tax = 0.20;
		$coupon = null;

		$receipt = $this->getMockBuilder('TDD\Receipt')
			->setMethods(['total', 'tax']) // Methods that the mock object will respond to
			->getMock();

		$receipt
			->expects($this->exactly(1)) // We only expect to call the total() method this amount of times
			->method('total')
			->with($items, $coupon) // with() turns the stub into a mock. Tesing with params is prob not necessary
			->will($this->returnValue(10)); // The $subTotal var must be the same as tyhis if reused further in the code

		$receipt
			->expects($this->once())
			->method('tax')
			->with(10, $tax) // The first param passed here must be equal to the return value of total()
			->will($this->returnValue(1.00));

		// Items, tax & coupon code arguments.
		// All these values do not matter because the return values from total() and tax() are already set
		$result = $receipt->postTaxTotal($items, $tax, $coupon);
		$expectedResult = 11;
		$this->assertEquals($expectedResult, $result);
	}

	/**
	 * Data Providers enable testing of multiple input variables for a method
	 * @dataProvider addProvider
	 */
	public function testAdd($input, $expected)
	{
		$expectedResult = $expected;
		$result = $this->receipt->add($input);
		
		$this->assertEquals($expectedResult, $result, "Sum should equal $expected");
	}
	public function addProvider()
	{
		// Array elements match up to function params
		return [
			[
				[1,2,3],
				6
			],
			[
				[-1,2,3],
				4
			]
		];
	}
}
