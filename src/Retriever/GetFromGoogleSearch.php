<?php declare(strict_types=1);

namespace AsyncBot\Plugin\GoogleSearch\Retriever;

use Amp\Promise;
use AsyncBot\Core\Http\Client;
use AsyncBot\Plugin\GoogleSearch\Collection\SearchResults;
use AsyncBot\Plugin\GoogleSearch\Parser\GoogleSearchParser;
use function Amp\call;

final class GetFromGoogleSearch
{
    private Client $httpClient;

    private const BASE_URL = 'https://www.google.com/search';

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return Promise<SearchResults>
     */
    public function retrieve(string $term): Promise
    {
        return call(function () use ($term) {
            /** @var \DOMDocument $dom */
            $dom = yield $this->httpClient->requestHtml($this->buildSearchUrl($term));

            return (new GoogleSearchParser())->parse($dom);
        });
    }

    private function buildSearchUrl(string $term): string
    {
        return self::BASE_URL . '?' . http_build_query(
            [
                'q' => $term,
                'lr' => 'lang_en',
            ],
        );
    }
}
