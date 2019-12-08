<?php declare(strict_types=1);

namespace AsyncBot\Plugin\GoogleSearchTest\Unit\ValueObject;

use AsyncBot\Plugin\GoogleSearch\ValueObject\Result\SearchResult;
use PHPUnit\Framework\TestCase;

class SearchResultTest extends TestCase
{
    private SearchResult $searchResult;

    protected function setUp(): void
    {
        $this->searchResult = new SearchResult('https://heap.space', 'HeapSpace', 'OpenGrok for Room-11');
    }

    public function testGetUrl(): void
    {
        $this->assertSame('https://heap.space', $this->searchResult->getUrl());
    }

    public function testGetTitle(): void
    {
        $this->assertSame('HeapSpace', $this->searchResult->getTitle());
    }

    public function testGetDescription(): void
    {
        $this->assertSame('OpenGrok for Room-11', $this->searchResult->getDescription());
    }

    public function testGetDescriptionWhenNull(): void
    {
    	$searchResult = new SearchResult('https://heap.space', 'HeapSpace');

        $this->assertNull($searchResult->getDescription());
    }
}
