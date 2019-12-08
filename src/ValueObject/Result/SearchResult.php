<?php declare(strict_types=1);

namespace AsyncBot\Plugin\GoogleSearch\ValueObject\Result;

final class SearchResult
{
    private string $url;

    private string $title;

    private ?string $description;

    public function __construct(string $url, string $title, ?string $description = null)
    {
        $this->url         = $url;
        $this->title       = $title;
        $this->description = $description;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
