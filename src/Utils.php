<?php

namespace SnapchatScraper;

use Exception;
use SnapchatScraper\Exceptions\ScraperException;

class Utils
{
    /**
     * Extract JSON data from HTML content.
     *
     * @param string $html_content
     * @return object
     * @throws Exception
     */
    public static function extract_json(string $html_content): object
    {
        $regexp = '#<script\s*id="__NEXT_DATA__"\s*type="application/json">([^<]+)</script>#';
        preg_match_all($regexp, $html_content, $matches);

        if (empty($matches[1][0])) {
            throw new ScraperException("No JSON data found in the HTML content.");
        }

        $json_data = json_decode($matches[1][0]);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ScraperException("Invalid JSON data found in the HTML content.");
        }

        return $json_data;
    }

    /**
     * Get the list of stories from page properties.
     *
     * @param object $page_props
     * @return array|null
     */
    public static function get_stories(object $page_props): ?array
    {
        return $page_props->story->snapList ?? [];
    }

    /**
     * Get the username from pageProps.
     *
     * @param object $page_props
     * @return string
     */
    public static function get_username(object $page_props): string
    {
        return $page_props->userProfile->publicProfileInfo->username ?? 'Unknown';
    }

    /**
     * Get the timestamp for a given story.
     *
     * @param object $story
     * @return string
     */
    public static function get_story_timestamp(object $story): string
    {
        if (isset($story->timestampInSec->value) && is_numeric($story->timestampInSec->value)) {
            return date('Y-m-d H:i:s', (int) $story->timestampInSec->value);
        }
        return 'Unknown';
    }

    /**
     * Get the media type (Image or Video) for a given story.
     *
     * @param object $story
     * @return string
     */
    public static function get_media_type(object $story): string
    {
        return  $story->snapMediaType ? 'mp4' : 'jpg';
    }
}
