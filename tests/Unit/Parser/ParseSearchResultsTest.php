<?php declare(strict_types=1);

namespace AsyncBot\Plugin\GoogleSearchTest\Unit\Parser;

use AsyncBot\Plugin\GoogleSearch\Collection\SearchResults;
use AsyncBot\Plugin\GoogleSearch\Exception\UnexpectedHtmlFormat;
use AsyncBot\Plugin\GoogleSearch\Parser\GoogleSearchParser;
use PHPUnit\Framework\TestCase;
use function Room11\DOMUtils\domdocument_load_html;

class ParseSearchResultsTest extends TestCase
{
    private function getDomFromFakeResponse(string $filename): \DOMDocument
    {
        return domdocument_load_html(
            file_get_contents(TEST_DATA_DIR . '/ResponseHtml/GoogleDotCom/' . $filename),
        );
    }

    public function testParserReturnsSearchResultsWhenValid(): void
    {
        $parsedResult = (new GoogleSearchParser())->parse(
            $this->getDomFromFakeResponse('valid.html'),
        );

        $this->assertInstanceOf(SearchResults::class, $parsedResult);
    }

    public function testParserThrowsWhenMissingMainContainer(): void
    {
        $this->expectException(UnexpectedHtmlFormat::class);
        $this->expectExceptionMessage('Could not find the "main" element in the document.');

        (new GoogleSearchParser())->parse(
            $this->getDomFromFakeResponse('missing-main-container.html'),
        );
    }

    public function testParserSetsDescriptionToNullWhenEmpty(): void
    {
        $searchResults = (new GoogleSearchParser())->parse(
            $this->getDomFromFakeResponse('empty-description-element.html'),
        );

        $searchResults = iterator_to_array($searchResults);

        $this->assertNull($searchResults[0]->getDescription());
    }
}
