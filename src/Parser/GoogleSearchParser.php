<?php declare(strict_types=1);

namespace AsyncBot\Plugin\GoogleSearch\Parser;

use AsyncBot\Plugin\GoogleSearch\Collection\SearchResults;
use AsyncBot\Plugin\GoogleSearch\Exception\UnexpectedHtmlFormat;
use AsyncBot\Plugin\GoogleSearch\ValueObject\Result\SearchResult;

final class GoogleSearchParser
{
    private const CONTAINER_ELEMENT_ID = 'main';

    public function parse(\DOMDocument $dom): SearchResults
    {
        $xpath = new \DOMXPath($dom);

        $mainContainer = $dom->getElementById(self::CONTAINER_ELEMENT_ID);

        if (!$mainContainer) {
            throw new UnexpectedHtmlFormat(self::CONTAINER_ELEMENT_ID);
        }

        return $this->buildResultSet($mainContainer, $xpath);
    }

    /**
     * @return SearchResults
     */
    private function buildResultSet(\DOMElement $mainContainer, \DOMXPath $xpath): SearchResults
    {
        $results = [];

        foreach ($xpath->evaluate('./div', $mainContainer) as $index => $resultNode) {
            if ($index < 3) {
                continue;
            }

            $resultContainer = $this->getResultNode($xpath, $resultNode);

            if ($resultContainer === null) {
                continue;
            }

            $results[] = new SearchResult(
                $this->getUrl($xpath, $resultContainer),
                $this->getTitle($xpath, $resultContainer),
                $this->getDescription($xpath, $resultContainer),
            );
        }

        return new SearchResults(...$results);
    }

    private function getResultNode(\DOMXPath $xpath, \DOMNode $node): ?\DOMNode
    {
        if ($xpath->evaluate('./div', $node)->length !== 1) {
            return null;
        }

        $searchResultContainer = $node->getElementsByTagName('div')->item(0);

        if ($xpath->evaluate('./div', $searchResultContainer)->length !== 3) {
            return null;
        }

        /** @var \DOMElement $titleAndUrlContainer */
        $titleAndUrlContainer = $xpath->evaluate('./div', $searchResultContainer)->item(0);

        if ($titleAndUrlContainer->getElementsByTagName('a')->length !== 1) {
            return null;
        }

        return $searchResultContainer;
    }

    private function getLinkNode(\DOMXPath $xpath, \DOMNode $resultContainer): \DOMNode
    {
        $linkNodes = $xpath->query('.//a', $resultContainer);

        return $linkNodes->item(0);
    }

    private function getUrl(\DOMXPath $xpath, \DOMNode $resultContainer): string
    {
        $linkNode = $this->getLinkNode($xpath, $resultContainer);

        return preg_replace('~^/url\?q=~', '', $linkNode->getAttribute('href'));
    }

    private function getTitle(\DOMXPath $xpath, \DOMNode $resultContainer): string
    {
        $linkNode = $this->getLinkNode($xpath, $resultContainer);

        return $linkNode->firstChild->textContent;
    }

    private function getDescription(\DOMXPath $xpath, \DOMNode $resultContainer): ?string
    {
		$descriptionText = trim($xpath->evaluate('./div', $resultContainer)->item(2)->textContent);

        if (!$descriptionText) {
            return null;
        }

        return $descriptionText;
    }
}
