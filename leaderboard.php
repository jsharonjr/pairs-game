
<!--This is for the leaderboard page, which which will have the usernames and scores of all the users. 
A player needs to play at least one level to be placed in the leaderboard. 
The base score 500 can't be used to get part in the leaderboard. 
This leaderboard will populate itself using leaderboard.json.-->

<?php include 'base.php'; ?>
<?php
// Check if the leaderboard file exists
if (file_exists('leaderboard.json')) {
    $leaderboardData = json_decode(file_get_contents('leaderboard.json'), true);
   
    // Check if the JSON data is valid
    if (is_array($leaderboardData)) {
        // Normalize data: Ensure all entries have 'finalScore' key
        foreach ($leaderboardData as &$entry) {
            // If 'score' exists but not 'finalScore', rename it
            if (isset($entry['score']) && !isset($entry['finalScore'])) {
                $entry['finalScore'] = $entry['score'];
                unset($entry['score']);  //  remove 'score' 
            }
        }

        // Sort leaderboard data by finalScore in descending order
        usort($leaderboardData, function($a, $b) {
            return $b['finalScore'] - $a['finalScore']; // Sort by finalScore, highest first
        });
        
    } else {
        echo "Error: Invalid leaderboard data.";
    }
} else {
    echo "Leaderboard file not found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <style>

        /* Container for the leaderboard */
        .leaderboard-container {
            background-color: grey; 
            box-shadow: 5px 5px 10px white; 
            border-radius: 10px; 
            padding: 20px; 
            width: 1000px;
            margin: 30px; 
            color: white; 
            font-weight: bold;
            font-weight: Verdana;
        }

        /* Table styles */
        .leaderboard-table {
            width: 100%;
            border-collapse: separate; 
            margin-top: 20px; 
            border-spacing: 2px;
        }

        .leaderboard-table th,
        .leaderboard-table td {
            padding: 10px;
            text-align: center; 
            border-bottom: 1px solid white; 
        }

        .leaderboard-table th {
            background-color: blue; 
            font-weight: bold;
        }

        .leaderboard-table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1); 
        }

        .leaderboard-table tr:hover {
            background-color: rgba(255, 255, 255, 0.2); 
        }
    </style>

</head>
<body>
    <div id="main">
        <div class="leaderboard-container">
            <h2 style="font-family: Verdana; font-weight: bold; color: white; text-align: center;">Leaderboard</h2>
            <table class="leaderboard-table" id="leaderboardTable">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Best Score</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through leaderboard data here -->
                    <?php
                        if (!empty($leaderboardData)) {
                            // Map the leaderboard data to HTML rows
                            $rows = array_map(function($entry) {
                                return '<tr><td>' . htmlspecialchars($entry['username']) . '</td><td>' . (isset($entry['finalScore']) ? htmlspecialchars($entry['finalScore']) : 'N/A') . '</td></tr>';
                            }, $leaderboardData);

                            // Output all rows
                            echo implode('', $rows);
                        } else {
                            echo '<tr><td colspan="2">No data available.</td></tr>';
                        }
                        ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
