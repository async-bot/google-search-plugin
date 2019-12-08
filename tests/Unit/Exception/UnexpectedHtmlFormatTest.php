<?php declare(strict_types=1);

namespace AsyncBot\Plugin\GoogleSearchTest\Unit\Exception;

use AsyncBot\Plugin\GoogleSearch\Exception\UnexpectedHtmlFormat;
use PHPUnit\Framework\TestCase;

class UnexpectedHtmlFormatTest extends TestCase
{
    public function testCorrectMessageFormat(): void
    {
        $this->expectException(UnexpectedHtmlFormat::class);
        $this->expectExceptionMessage('Could not find the `main` element in the document.');

        throw new UnexpectedHtmlFormat('main');
    }
}
