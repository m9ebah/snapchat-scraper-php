<?php

require 'vendor/autoload.php';

use SnapchatScraper\Clients\HttpClient;
use SnapchatScraper\StoryDownloader;
use SnapchatScraper\SnapchatScraper;

// Initialize the client
$httpClient = new HttpClient();

// Specify where to save the downloaded files
$downloadDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'downloads';

// Prepare the story download library
$storyDownloader = new StoryDownloader($downloadDirectory);

// Configure the main library
$scraper = new SnapchatScraper($httpClient, $storyDownloader);

// Specify the user name
$username = 'example_user';  // Example of a username

// Fetch Snapchat data
try {
    $snapchatData = $scraper->getSnapchatData($username);

    // Fetch stories
    $stories = $snapchatData['stories'];

// If there are stories, upload them.
    if (!empty($stories)) {
        echo "Loading stories...\n";
        $storyDownloader->download($stories);
        echo "Stories uploaded successfully.\n";
    } else {
        echo "There are no stories for this user.\n";
    }

} catch (\SnapchatScraper\Exceptions\ScraperException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

