<?php
// rss_fetch.php

function fetchRssFeeds() {
    $rssFeeds = [
        "https://example.com/rss", // Replace with your desired RSS feed URLs
    ];

    $articles = [];

    foreach ($rssFeeds as $feed) {
        $rss = simplexml_load_file($feed);
        foreach ($rss->channel->item as $item) {
            $articles[] = [
                'title' => (string)$item->title,
                'description' => (string)$item->description,
                'url' => (string)$item->link,
                'imageUrl' => (string)$item->image, // Modify as per your RSS structure
            ];
        }
    }

    return $articles;
}
?>
