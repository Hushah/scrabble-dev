<?php

use Src\Boot;
use Src\Engine\Dictionary\Dictionary;
use Src\Engine\Scrabble;

require_once 'Src/Boot.php';

// Initialise Scrabble Engine
$boot = new Boot();
$dictionary = new Dictionary($boot);
$scrabble = new Scrabble($dictionary);

// Handle AJAX request for the rack input
if (isset($_GET['rack'])) {
    // Remove non-alphabetical chars from rack
    $rack = $scrabble->removeNonAlphaCharacters($_GET['rack']);
    $results = [];

    // If the rack is empty
    if(!empty($rack)) {
        // Find words that can be made from the rack and assign to results
        $results = $scrabble->matchInDictionary($rack);
    }

    // Prepare the response
    $response = [
        // The first element of the response is the rack with non-alphabetical characters removed
        'cleanedRack' => htmlspecialchars($rack),
        // The second element is where we will store the results table HTML
        'resultsTable' => '',
    ];


    // If we have matched words in the dictionary, Output results as HTML
    if (!empty($results)) {
        // Start output buffering to capture table HTML
        ob_start();
        
        // Start the table
        echo "<table border='1' cellspacing='0' cellpadding='5'>";
        echo "<tr><th>Word</th><th>Score</th></tr>";
    
        // Loop through the results, create rows and insert the words and scores
        foreach ($results as $word => $score) {
            echo "<tr>";
            // Output word in uppercase for better readability
            echo "<td>" . htmlspecialchars(strtoupper($word)) . "</td>";
            echo "<td>" . htmlspecialchars($score) . "</td>";
            echo "</tr>";
        }
    
        // End the table
        echo "</table>";

        // Store the table HTML in the resultsTable response
        $response['resultsTable'] = ob_get_clean();

    } else { // If no words can be made from the rack
        // Store an empty string in the resultsTable response
        $response['resultsTable'] = "";
    }
    
    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Ensure the script doesn't continue after handling the AJAX request
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <script src="script.js"></script>
    <title>Scrabble Rack</title>
</head>
<body>
    <!-- All content is stored in this div -->
    <div class="content">
        <!-- Title of the page, placed at the top -->
        <div class="title">
            
            <h2 class="scrabbleTitle" id="idTitle">SCRABBLE </h2>
            <h2 class="rackTitle" id="idTitle">rack</h2>
            
        </div>
        
        <!-- Main content of the page -->
        <div class="mainContent">

            <!-- Left side of the page -->
            <div class="introInputAndRack">
                <div class="introAndInput">
                    <p id="instructions">
                        Hello and welcome to Scrabble RACK!<br>
                        Looking to brush up on your scrabble skills? Or just want to find some words to play with your friends?<br>
                        You've come to the right place! Enter your rack below to find words and their respective scrabble scores.<br>
                    </p>

                    <!-- Form to submit the rack input -->
                    <form id="rackForm" autocomplete="off">
                        <label for="rack">Enter your rack:</label>
                        <input type="text" name="rack" id="rackInput" oninput="updateResults()" required>
                    </form>
                </div>

                <!-- Display the rack beneath the intro and input -->
                <div class="rackDisplay" id="rackDisplayContainer"></div>
            </div>
            

            <!-- Right side of the page -->
            <!-- Div to display the results -->
            <div class="tableContainer" id="results"></div>
        </div>
    </div>
</body>
</html>
