<?php declare(strict_types=1);

namespace AsyncBot\Plugin\GoogleSearch\Collection;

use AsyncBot\Plugin\GoogleSearch\ValueObject\Result\SearchResult;

final class SearchResults implements \Countable, \Iterator
{
    /** @var array<SearchResult> */
    private array $searchResults;

    public function __construct(SearchResult ...$results)
    {
        $this->searchResults = $results;
    }

    public function current(): SearchResult
    {
        return current($this->searchResults);
    }

    public function next(): void
    {
        next($this->searchResults);
    }

    public function key(): ?int
    {
        return key($this->searchResults);
    }

    public function valid(): bool
    {
        return $this->key() !== null;
    }

    public function rewind(): void
    {
        reset($this->searchResults);
    }

    public function count(): int
    {
        return count($this->searchResults);
    }
}
