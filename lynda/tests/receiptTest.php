<?php
namespace TDD\Test;

require_once dirname(__DIR__) . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use TDD\Receipt;

class ReceiptTest extends TestCase
{
    public function testTotal()
    {
        $receipt = new Receipt;

        $this->assertEquals(14, $receipt->total([0,2,5,8]), sprintf('Error from %s', __METHOD__));
    }
}
