<?php

namespace SnapchatScraper;

use Exception;
use SnapchatScraper\Exceptions\ScraperException;
use SnapchatScraper\Clients\HttpClient;


class SnapchatScraper
{
    private HttpClient $httpClient;
    private StoryDownloader $storyDownloader;
    private object $pageProps;

    public function __construct(HttpClient $httpClient, StoryDownloader $storyDownloader)
    {
        $this->httpClient = $httpClient;
        $this->storyDownloader = $storyDownloader;
    }

    /**
     * Fetch Snapchat HTML data for a specific username.
     *
     * @param string $username
     * @return string
     * @throws ScraperException
     */
    private function fetchSnapchatHtmlData(string $username): string
    {
        $url = "https://www.snapchat.com/add/{$username}";
        try {
            return $this->httpClient->get($url);
        } catch (Exception $exception) {
            throw new ScraperException("Failed to fetch data for {$username}: " . $exception->getMessage());
        }
    }

    /**
     * Extract JSON data from the HTML content.
     *
     * @param string $html_content
     * @return object
     * @throws \Exception
     */
    public function extractJsonData(string $html_content): object
    {
        return Utils::extract_json($html_content);
    }

    /**
     * Retrieve Snapchat data for a given username.
     *
     * @param string $username
     * @return array
     * @throws ScraperException
     */
    public function getSnapchatData(string $username): array
    {
        $html_content = $this->fetchSnapchatHtmlData($username);

        $this->pageProps = $this->extractJsonData($html_content)->props->pageProps;

        $userData = $this->getUserData();
        $stories = $this->getStories();

        return [
            'user' => $userData,
            'stories' => $stories
        ];
    }


    /**
     * Get user data (username, display name, etc.).
     *
     * @return string
     */
    public function getUserData(): string
    {
        return Utils::get_username($this->pageProps);
    }

    /**
     * Get all the stories for the user.
     *
     * @return array|null
     */
    public function getStories(): ?array
    {
        return Utils::get_stories($this->pageProps);
    }


    /**
     * Get the media type of story (Image or Video).
     *
     * @param object $story
     * @return string
     */
    public function getMediaType(object $story): string
    {
        return Utils::get_media_type($story);
    }

    /**
     * Get the timestamp of a story.
     *
     * @param object $story
     * @return string
     */
    public function getTimestamp(object $story): string
    {
        return Utils::get_story_timestamp($story);
    }
}
