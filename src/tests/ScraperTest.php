<?php

namespace SnapchatScraper\tests;

use Exception;
use PHPUnit\Framework\TestCase;
use SnapchatScraper\SnapchatScraper;
use SnapchatScraper\Clients\HttpClient;
use SnapchatScraper\StoryDownloader;
use SnapchatScraper\Exceptions\ScraperException;

class ScraperTest extends TestCase
{
    private $httpClient;
    private $storyDownloader;
    private $scraper;

    protected function setUp(): void
    {

        $this->httpClient = $this->createMock(HttpClient::class);

        $this->storyDownloader = $this->createMock(StoryDownloader::class);


        $this->scraper = new SnapchatScraper($this->httpClient, $this->storyDownloader);
    }

    public function testGetSnapchatDataSuccess()
    {

        $this->httpClient->method('get')
            ->willReturn('<html><script id="__NEXT_DATA__" type="application/json">{"props":{"pageProps":{"userProfile":{"publicProfileInfo":{"username":"test_user"}}}}}</script></html>');

        $result = $this->scraper->getSnapchatData('test_user');

        $this->assertArrayHasKey('user', $result);
        $this->assertEquals('test_user', $result['user']);
    }

    public function testGetSnapchatDataFailure()
    {

        $this->httpClient->method('get')
            ->willThrowException(new ScraperException("Request failed"));


        $this->expectException(ScraperException::class);


        $this->scraper->getSnapchatData('test_user');
    }

    /**
     * @throws \Exception
     */
    public function testExtractJsonData()
    {
        $htmlContent = '<script id="__NEXT_DATA__" type="application/json">{"props":{}}</script>';
        $json = $this->scraper->extractJsonData($htmlContent);

        $this->assertObjectHasProperty('props', $json);
    }

    /**
     * @throws \Exception
     */
    public function testExtractJsonDataNoScript()
    {
        $htmlContent = '<html><body>No script here</body></html>';

        $this->expectException(ScraperException::class);
        $this->scraper->extractJsonData($htmlContent);
    }


    /**
     * @throws \Exception
     */
    public function testExtractJsonDataInvalidJson()
    {
        $htmlContent = '<script id="__NEXT_DATA__" type="application/json">{invalid json}</script>';

        $this->expectException(ScraperException::class);
        $this->scraper->extractJsonData($htmlContent);
    }
}

