<?php
namespace TDD\Test;

require_once dirname(__DIR__) . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use TDD\Receipt;

/*
 * assertEquals(): == (Values match: 1 == 1.0) // true
 * assetSame(): === (Exact values and types match 1 === 1.0) // false
 */

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

    // Tests subTotal()
    public function testSubTotal()
    {
        $input = [0,2,5,8];
        $expected = 15;
        $output = $this->receipt->subTotal($input, null);
        $this->assertEquals($expected, $output, sprintf('Failure message from: %s', __METHOD__));
    }

    // Tests subTotal()
    public function testSubTotalAndCoupon()
    {
        $input = [0,2,5,8];
        $coupon = 0.20;
        $expected = 12;
        $output = $this->receipt->subTotal($input, $coupon);
        $this->assertEquals($expected, $output, sprintf('Failure message from: %s', __METHOD__));
    }

    // Tests subTotal()
    public function testSubTotalException()
    {
        $input = [0,2,5,8];
        $coupon = 1.20;
        $this->expectException('BadMethodCallException');
        $this->receipt->subTotal($input, $coupon);
    }

    public function testTax()
    {
        $input = 10.00;
        $this->receipt->tax = 0.10;
        $expected = 1.00;
        $output = $this->receipt->tax($input);
        $this->assertEquals($expected, $output, sprintf('Failure message from: %s', __METHOD__));
    }

    public function testPostTaxTotal()
    {
        $items = ['MOCKED_VALUES'];
        $tax = 0.20;
        $coupon = null;

        $receipt = $this->getMockBuilder('TDD\Receipt')
            ->setMethods(['subTotal', 'tax']) // Methods that the mock object will respond to
            ->getMock();

        $receipt
            ->expects($this->exactly(1)) // We only expect to call the subTotal() method this amount of times
            ->method('subTotal')
            ->with($items, $coupon) // with() turns the stub into a mock. Tesing with params is prob not necessary
            ->will($this->returnValue(10)); // The $subTotal var must be the same as tyhis if reused further in the code

        $receipt
            ->expects($this->once())
            ->method('tax')
            ->with(10, $tax) // The first param passed here must be equal to the return value of subTotal()
            ->will($this->returnValue(1.00));

        // Items, tax & coupon code arguments.
        // All these values do not matter because the return values from subTotal() and tax() are already set
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
        // The test function can take in any args, it's not related to the function to test method signature
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

    /** 
     * @dataProvider currencyAmountProvider 
     */
    public function testCurrencyAmount($input, $expected, $message)
    {
        // Check exact value and type
        $this->assertSame($expected, $this->receipt->currencyAmount($input), $message);
    }
    public function currencyAmountProvider()
    {
        return [
            [1, 1.00, '1 should be transformed into 1.00'],
            [1.1, 1.10, '1.1 should be transformed into 1.10'],
            [1.11, 1.11, '1.11 should stay as 1.11'],
            [1.111, 1.11, '1.111 should be transformed into 1.11']
        ];
    }
}
