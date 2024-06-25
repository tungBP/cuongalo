<?php
// The URL of the API endpoint
$api_url = 'http://localhost/auth/api.php'; // Replace 'your_project_folder' with the actual path

// Initialize a cURL session
$ch = curl_init($api_url);

// Set the cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the cURL session and store the response
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    // Output the raw response for debugging
    echo "Raw Response:\n";
    echo htmlspecialchars($response);
}

// Close the cURL session
curl_close($ch);
?>
