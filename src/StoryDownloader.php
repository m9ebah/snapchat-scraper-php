<?php

namespace SnapchatScraper;

use SnapchatScraper\Exceptions\ScraperException;

class StoryDownloader
{
    private string $downloadDirectory;
    private object $pageProps;

    public function __construct(string $downloadDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'downloads')
    {

        if (!is_dir($downloadDirectory)) {
            mkdir($downloadDirectory, 0777, true);
        }

        $this->downloadDirectory = $downloadDirectory;


    }

    /**
     * Download stories from the snap list.
     *
     * @param array $stories
     * @throws ScraperException
     */
    public function download(array $stories): void
    {

        foreach ($stories as $story) {
            try {
                $this->downloadStory($story);
            } catch (\Exception $e) {
                error_log("[ERROR] Unable to download story: {$e->getMessage()}");
                throw new ScraperException("Failed to download story: {$e->getMessage()}", 0, $e);
            }
        }
    }

    /**
     * Download a single story.
     *
     * @param object $story
     * @throws ScraperException
     */
    private function downloadStory(object $story): void
    {
        if (!isset($story->snapUrls->mediaUrl)) {
            throw new ScraperException("No media URL found for this story.");
        }

        $mediaUrl = $story->snapUrls->mediaUrl;

        $extension = Utils::get_media_type($story);
        $timestamp = Utils::get_story_timestamp($story);
        $timestamp = preg_replace('/[\/:*?"<>|]/', '_', $timestamp);
        $fileName = "{$timestamp}.{$extension}";

        $filePath = $this->downloadDirectory . DIRECTORY_SEPARATOR . $fileName;

        if (filter_var($mediaUrl, FILTER_VALIDATE_URL) === false) {
            throw new ScraperException("Invalid media URL: {$mediaUrl}");
        }

        try {
            $fileContents = file_get_contents($mediaUrl);
            if ($fileContents === false) {
                throw new ScraperException("Failed to download content from {$mediaUrl}");
            }

            file_put_contents($filePath, $fileContents);
        } catch (\Exception $e) {
            throw new ScraperException("Error downloading story media: {$e->getMessage()}", 0, $e);
        }
    }
}
