<?php
function getAppDetails($appId) {
    $url = "https://play.google.com/store/apps/details?id=" . $appId;

    // Initialize cURL
    $ch = curl_init();

    // Set options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");

    // Execute the request
    $output = curl_exec($ch);

    // Close the cURL session
    curl_close($ch);

    // Check if the request was successful
    if ($output === false) {
        return null;
    }

    // Load the HTML into DOMDocument
    $dom = new DOMDocument();
    @$dom->loadHTML($output);

    // Create a new XPath object
    $xpath = new DOMXPath($dom);

    // Extract the app name
    $appNameNode = $xpath->query("//h1[@itemprop='name']");
    $appName = $appNameNode->length > 0 ? trim($appNameNode->item(0)->nodeValue) : null;

    // Extract the developer name
    $developerNode = $xpath->query("//a[contains(@href, '/store/apps/dev?id=')]//span");
    $developer = $developerNode->length > 0 ? trim($developerNode->item(0)->nodeValue) : null;

    // Extract the number of reviews
    $reviewsNode = $xpath->query("//span[@class='AYi5wd TBRnV']");
    $reviews = $reviewsNode->length > 0 ? trim($reviewsNode->item(0)->nodeValue) : null;

    // Extract the average rating
    $ratingNode = $xpath->query("//div[@itemprop='starRating']//div[@aria-label]");
    $rating = $ratingNode->length > 0 ? trim($ratingNode->item(0)->nodeValue) : null;

    // Extract the number of downloads
    $downloadsNode = $xpath->query("//div[contains(text(),'Downloads')]/preceding-sibling::div");
    $downloads = $downloadsNode->length > 0 ? trim($downloadsNode->item(0)->nodeValue) : null;

    // Extract the content rating
    $contentRatingNode = $xpath->query("//span[@itemprop='contentRating']//span");
    $contentRating = $contentRatingNode->length > 0 ? trim($contentRatingNode->item(0)->nodeValue) : null;

    // Extract the app icon image URL (large size)
    $iconImageNode = $xpath->query("//img[@itemprop='image' and contains(@class, 'T75of')]");
    $iconImage = $iconImageNode->length > 0 ? $iconImageNode->item(0)->getAttribute('srcset') : null;

    // Extract the app description
    $descriptionNode = $xpath->query("//div[@jsname='sngebd']//meta[@itemprop='description']");
    $description = $descriptionNode->length > 0 ? trim($descriptionNode->item(0)->getAttribute('content')) : null;

    // Return all extracted details in an associative array
    return [
        'name' => $appName,
        'developer' => $developer,
        'reviews' => $reviews,
        'rating' => $rating,
        'downloads' => $downloads,
        'contentRating' => $contentRating,
        'iconImage' => $iconImage,
        'description' => $description
    ];
}

// Example usage
$appId = "com.gameloft.android.ANMP.GloftA8HM"; // Replace with the actual app ID
$appDetails = getAppDetails($appId);

if ($appDetails) {
    echo "App Name: " . $appDetails['name'] . "<Br>";
    echo "Developer: " . $appDetails['developer'] . "<Br>";
   // echo "Reviews: " . $appDetails['reviews'] . "<Br>";
    echo "Rating: " . $appDetails['rating'] . "<Br>";
    echo "Downloads: " . $appDetails['downloads'] . "<Br>";
   // echo "Content Rating: " . $appDetails['contentRating'] . "<Br>";
  //  echo "Icon Image URL: " . $appDetails['iconImage'] . "<Br>";
  //  echo "Description: " . $appDetails['description'] . "<Br>";
} else {
    echo "Could not retrieve app details.";
}
?>
