<?php declare(strict_types=1);

namespace AsyncBot\Plugin\GoogleSearch;

use Amp\Promise;
use AsyncBot\Core\Http\Client;
use AsyncBot\Plugin\GoogleSearch\Collection\SearchResults;
use AsyncBot\Plugin\GoogleSearch\Retriever\GetFromGoogleSearch;

final class Plugin
{
    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return Promise<SearchResults>
     */
    public function search(string $term): Promise
    {
        return (new GetFromGoogleSearch($this->httpClient))->retrieve($term);
    }
}
