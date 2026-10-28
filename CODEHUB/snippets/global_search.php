<?php
include("../db.php");

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    echo "<h2>Local Results</h2>";

    // Search inside your Code Hub snippets table
    $sql = "SELECT * FROM snippets WHERE title LIKE '%$query%' OR code LIKE '%$query%' OR language LIKE '%$query%'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='snippet-result'>";
            echo "<h3>" . htmlspecialchars($row['title']) . " (" . htmlspecialchars($row['language']) . ")</h3>";
            echo "<pre>" . htmlspecialchars(substr($row['code'], 0, 200)) . "...</pre>";
            echo "<a href='view_snippet.php?id=" . $row['id'] . "'>View Full</a>";
            echo "</div>";
        }
    } else {
        echo "<p>No local results found.</p>";
    }

    echo "<hr><h2>Global Results</h2>";

    // 🔹 Google Custom Search API (replace with your API key + CX ID)
    $apiKey = "YOUR_GOOGLE_API_KEY";
    $cx = "YOUR_CUSTOM_SEARCH_ENGINE_ID";
    $url = "https://www.googleapis.com/customsearch/v1?q=" . urlencode($query) . "&key=$apiKey&cx=$cx";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (!empty($data['items'])) {
        foreach ($data['items'] as $item) {
            echo "<div class='global-result'>";
            echo "<h3><a href='" . $item['link'] . "' target='_blank'>" . $item['title'] . "</a></h3>";
            echo "<p>" . $item['snippet'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No global results found.</p>";
    }
}
?>
