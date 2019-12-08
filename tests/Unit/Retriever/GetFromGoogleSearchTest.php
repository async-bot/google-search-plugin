<?php declare(strict_types=1);

namespace AsyncBot\Plugin\GoogleSearchTest\Unit\Retriever;

use Amp\Http\Client\HttpClientBuilder;
use AsyncBot\Core\Http\Client;
use AsyncBot\Plugin\GoogleSearch\Collection\SearchResults;
use AsyncBot\Plugin\GoogleSearch\Retriever\GetFromGoogleSearch;
use AsyncBot\Plugin\GoogleSearchTest\Fakes\HttpClient\MockResponseInterceptor;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class GetFromGoogleSearchTest extends TestCase
{
    public function testGoogleSearchReturnsResults(): void
    {
        $httpClient = new Client(
            (new HttpClientBuilder())->intercept(
                new MockResponseInterceptor(file_get_contents(TEST_DATA_DIR . '/ResponseHtml/GoogleDotCom/valid.html')),
            )->build(),
        );

        $searchResult = wait((new GetFromGoogleSearch($httpClient))->retrieve('coca cola'));

        $this->assertInstanceOf(SearchResults::class, $searchResult);
    }
}
