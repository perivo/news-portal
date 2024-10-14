<?php
// newsapi_fetch.php

function fetchNewsArticles($category = 'technology') {
    $apiKey = NEWS_API_KEY; // Get the API key from the config
    $url = "https://newsapi.org/v2/top-headlines?category=" . urlencode($category) . "&apiKey=" . $apiKey;

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: YourAppName']); // Set User-Agent header
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response
    $data = json_decode($response, true);

    // Check for errors in the response
    if ($data['status'] !== 'ok') {
        echo "Error fetching news: " . $data['message'];
        return [];
    }

    // Process articles
    $articles = [];
    foreach ($data['articles'] as $article) {
        $articles[] = [
            'id' => uniqid(), // Unique ID for article (or use database ID if saving)
            'title' => $article['title'],
            'description' => $article['description'],
            'url' => $article['url'],
            'imageUrl' => $article['urlToImage'],
        ];
    }

    return $articles;
}
?>
