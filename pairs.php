
<!--This is the game page, where the user can if registered, can start the pairs game. 
If not registered, they will be asked to register. -->

<?php include 'base.php'; ?>
<?php

    //Start session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if the user is registered (via session variable)
    $isRegistered = isset($_SESSION['username']);

    //INITIALIZE LEVEL IF NOT ALREADY SET
    if (!isset($_SESSION['level'])) {
        $_SESSION['level'] = 1; // Set initial level to 1
    }

    //UPDATE THE LEVEL IF NECESSARY
    if (isset($_POST['update_level']) && $_POST['update_level'] == 'true') {
        $_SESSION['level'] += 1;
    }

    //INITIALIZE FINAL SCORE IF IT'S NOT SET YET
    if (!isset($_SESSION['finalScore'])) {
        $_SESSION['finalScore'] = 500; // Set the base score to 500 (The base score and final score refer to the same variable)
    }

    //ADD THE POSTED SCORE TO THE FINAL SCORE AND UPDATE THE LEADERBOARD
    if (isset($_POST['finalScore'])) {
        $_SESSION['finalScore'] += $_POST['finalScore'];

        // Store username and score
        $username = $_SESSION['username'];  
        $finalScore = $_SESSION['finalScore'];

        // Read the existing leaderboard data from the JSON file
        $leaderboardData = json_decode(file_get_contents('leaderboard.json'), true);

        // Add the new user data
        $leaderboardData[] = [
            'username' => $username,
            'finalScore' => $finalScore,
        ];

        // Save updated data back to the JSON file
        file_put_contents('leaderboard.json', json_encode($leaderboardData, JSON_PRETTY_PRINT));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pairs</title>   
    <style>

        /* FOR GAME CONTAINER, which includes: 
            the current level, 
            final(or base) score of the user, 
            the temporary score, 
            timer, 
            the start button (to start the game) */

        .hiddenGame {
            display: none;
        }

        #games {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 1200px; 
            margin: 20px auto; 
            padding: 20px;
            box-sizing: border-box;
            background: grey;
            box-shadow: 5px 5px 10px 0px white;
            overflow: hidden;
            border-radius: 10px;
        }

        #game-board {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .top-row {
            font-family: Verdana;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            color: white;
            width: 100%;
            justify-content: space-between;
            margin: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
        }

        #final-score {
            font-weight: bold;
            font-family: Verdana;
            padding: 5px 10px;
            border-radius: 8px; 
        } 

        #level-base-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start; 
            margin-bottom: 10px;
        }

        #level-display {
            border-radius: 10px;
            padding: 5px 10px;
        }

        #base-score-display {
            font-family: Verdana;
            font-weight: bold;
            color: white;
            margin-top: 5px; 
        }

        #score-time {
            display: none; /* Hidden initially, will show after game starts */
            font-family: Verdana;
            font-weight: bold;
            color: white;
            border-radius: 10px;
            padding: 5px 10px;
            margin-top: 10px;
        }

        #score-time span {
            display: inline-block;
            margin-right: 10px;
        }

        .heading {
            text-align: center;
            margin: 20px;
        }

        .start-button-container {
            margin-top: 20px;
        }

        .start-button-container button {
            font-family: Verdana;
            font-weight: bold;
            color: white;
        }

        .d-flex {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .d-flex button {
            margin: 10px;
        }

        .hiddenButton {
            display: none;
        }

        /*------------------Styles for the 'else' part (when the user is not registered)-----------------*/

        #main .not-registered-message {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Link styling */
        #main a {
            text-decoration: underline;
        }

    /*---------------------------------------------FOR CARDS------------------------------------------*/

        .card {
            width: 120px;
            height: 120px;
            background-color: gray;
            display: inline-block;
            margin: 10px;
            border-radius: 15px;
            text-align: center;
            cursor: pointer;
            position: relative;
        }

        .hidden {
            background-color: gray;
        }

    /*-----------------------------------------FOR EMOJIS IN CARDS----------------------------------------*/
        .emoji {
            width: 100%;
            height: 100%;
            position: relative;
            display: none;
        }

        .emoji img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .flipped .emoji {
            display: block;
        }

    </style>
</head>
<body>
    <div id="main">

        <!--IF THE USER IS REGISTERED-->
        <?php if ($isRegistered): ?>   
            <div id="games">
                <div class="top-row">
                    <!-- Level and Base Score displayed together -->
                    <div id="level-base-container">
                        <div id="level-display">Level: <?php echo isset($_SESSION['level']) ? $_SESSION['level'] : '1'; ?></div>
                        <div id="base-score-display">
                            <span id="final-score">Overall Score: <?php echo isset($_SESSION['finalScore']) ? $_SESSION['finalScore'] : 'Not Set'; ?></span>
                        </div>
                    </div>
                    <div class="heading">
                        <h1 style="font-family: Verdana; font-weight: bold; color: white;">Pairs Game</h1>
                    </div>
                    <!-- Score and Timer are hidden initially -->
                    <div id="score-time" style="display: none;">
                        <span id="current-score">Level Score: 0</span>
                        <br>
                        <span id="timer">Time: 00:00:00</span>
                    </div>
                </div>

                <div class="start-button-container">
                    <button id="start-button" type="button" class="btn btn-primary" style="font-family: Verdana; font-weight: bold; color: white;" onclick="startGame()">Start the game</button>
                </div>
                <div id="game-board" class="hiddenGame" style="font-family: Verdana;"></div>
                <div id="previous-score-container" style="display: none;">
                    <span id="previous-score" style="font-weight: bold; color: white;">Previous Score: 0</span>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-success hiddenButton" id="submit-btn" onclick="updateLevel()" style="font-family: Verdana; font-weight: bold; color: white; margin: 10px;">Submit</button>
                    <button type="button" class="btn btn-danger hiddenButton" id="replay-btn" onclick="onReplayClick()" style="font-family: Verdana; font-weight: bold; color: white; margin: 10px;">Play Again</button>
                </div>

                <div class="penalty-message" style="font-family: Verdana; color: black; font-weight: bold; margin-top: 20px; text-align: center;">
                    A penalty of -10 is applied for every 10 seconds after the allotted time.
                </div>

            </div>
         
        <!--IF THE USER IS NOT REGISTERED-->
        <?php else: ?>
            <div class="not-registered-message">
                <h1 style="font-weight: bold; color: black; background-color: rgba(255, 255, 255, 0.7); padding: 20px;
                            margin-top: 20px; text-align: center; border-radius: 5px;">
                    You're not using a registered session? 
                    <a href="registration.php">Register now!</a>
                </h1>
            </div>
        <?php endif; ?>

    </div>

    <script>
    
        let finalScore; //The overall score for the user in game
        let currentScore; //The temporary score for a level that can either be saved or neglected to replay th level
        let maxFlips; //The maximum number of flips for every level at a time (2, 3 or 4)
        let previousScore = null; // To store previous score
        let currentLevel = <?php echo $_SESSION['level']; ?>; //Current level of the game
        let totalCards; //Total number of cards in the current level
        let cardsPerMatch; //Same as maxFlips. 
        let elapsedTime = 0; // To track elapsed time after the initial time limit
        let timePenaltyInterval = 10;  // Every 10 seconds after time runs out, -10 points
        let remainingTime; 
        let timerInterval;
        const levelSets = [3, 5, 7, 9, 11, 8, 9, 10, 11, 12, 9, 10, 11, 12, 13];// Number of sets of cards for each level 
        let flippedCards = []; //Helper variable that will be resetted everytime the game starts
        let matchedPairs = 0;//Helper variable that will be resetted everytime the game starts

        // To calculate the time limit for the current level 
        function calculateBaseTime(level, totalCards) {
            let baseTime = 0;
            if (currentLevel === 1) baseTime = 20;
            else if (currentLevel === 2) baseTime = 30;
            else if (currentLevel === 3) baseTime = 40;
            else if (currentLevel === 4) baseTime = 50;
            else if (currentLevel === 5) baseTime = 60;
            else if (currentLevel === 6) baseTime = 120;
            else if (currentLevel === 7) baseTime = 135;
            else if (currentLevel === 8) baseTime = 150;
            else if (currentLevel === 9) baseTime = 165;
            else if (currentLevel === 10) baseTime = 180;
            else if (currentLevel === 11) baseTime = 190;
            else if (currentLevel === 12) baseTime = 210;
            else if (currentLevel === 13) baseTime = 230;
            else if (currentLevel === 14) baseTime = 250;
            else if (currentLevel === 15) baseTime = 270;
            else {
                if (maxFlips === 3) {
                    baseTime = totalCards * 6; 
                } else {
                    baseTime = totalCards * 7; 
                }
            }
            return baseTime;
        }

        // To calculate time per card
        function getTimePerCard(maxFlips) {
            if (maxFlips === 2) return 3;
            if (maxFlips === 3) return 5;
            if (maxFlips === 4) return 7;
        }

        // To return difficulty factor based on match type
        function getDifficultyFactor(maxFlips) {
            if (maxFlips === 2) return 0.5;
            if (maxFlips === 3) return 1;
            if (maxFlips === 4) return 1.5;
        }

        // To calculate the total time limit for a level
        function calculateTimeLimit(currentLevel, totalCards, maxFlips) {
            let timeLimit;

            //For levels 1-15, timeLimit is hard-coded
            if (currentLevel === 1) {
                timeLimit = 20;  
            } else if(currentLevel === 2) {
                timeLimit = 30;  
            } else if(currentLevel === 3) {
                timeLimit = 40;  
            } else if(currentLevel === 4) {
                timeLimit = 50;  
            } else if(currentLevel === 5) {
                timeLimit = 60;  
            } else if(currentLevel === 6) {
                timeLimit = 120; 
            } else if(currentLevel === 7) {
                timeLimit = 135;  
            } else if(currentLevel === 8) {
                timeLimit = 150;  
            } else if(currentLevel === 9) {
                timeLimit = 165;  
            } else if(currentLevel === 10) {
                timeLimit = 180;  
            } else if(currentLevel === 11) {
                timeLimit = 190;  
            } else if(currentLevel === 12) {
                timeLimit = 210;  
            } else if(currentLevel === 13) {
                timeLimit = 230;  
            } else if(currentLevel === 14) {
                timeLimit = 250;  
            } else if(currentLevel === 15) {
                timeLimit = 270;  
            } else {
                // For levels after 15, use the existing formula
                const baseTime = calculateBaseTime(currentLevel, totalCards);
                const timePerCard = getTimePerCard(maxFlips);
                const difficultyFactor = getDifficultyFactor(maxFlips);

                // Total time limit calculation for levels above 15
                timeLimit = (baseTime + (totalCards * timePerCard)) * difficultyFactor;
            }
            return timeLimit;
        } 

        /* To update the score based on the player's action,
        this function includes the penalties given for a wrong match and bonuses given for a right one */
        function updateScore(isCorrect) {
            let wrongPenalty, rightBonus;

            if (currentLevel >= 1 && currentLevel <= 5) {
                wrongPenalty = -10;
                rightBonus = 10;
            } else if (currentLevel >= 6 && currentLevel <= 10) {
                wrongPenalty = -10;
                rightBonus = 15;
            } else if (currentLevel >= 11 && currentLevel <= 15) {
                wrongPenalty = -15;
                rightBonus = 25;
            } else {
                if (maxFlips === 3) {
                    wrongPenalty = -10;
                    rightBonus = 15;
                } else {
                    wrongPenalty = -15;
                    rightBonus = 25;
                }
            }

            if (isCorrect) {
                currentScore += rightBonus; 
            } else {
                currentScore += wrongPenalty; 
            }

            document.getElementById('current-score').innerText = `Score: ${currentScore}`; 

            // Call updateBG to check if the player replayed the game level
            updateBG();
        }

        // Handle the Submit button
        document.getElementById('submit').addEventListener('click', () => {
            // Send the currentScore to PHP using fetch
            fetch('pairs.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `finalScore=${currentScore}`
            }).then(response => {
                location.reload(); // Reload to update the base score
            });
        });

        // Format the time in HH:MM:SS format
        function formatTime(seconds) {
            let hours = Math.floor(Math.abs(seconds) / 3600);
            let minutes = Math.floor((Math.abs(seconds) % 3600) / 60);
            let sec = Math.abs(seconds) % 60;

            // Add negative sign if the player ran out of the allotted time limit 
            let timePrefix = seconds < 0 ? '-' : '';

            return `${timePrefix}${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${sec.toString().padStart(2, '0')}`;
        }

        // To start the timer
        function startTimer() {

            remainingTime = calculateTimeLimit(currentLevel, totalCards, maxFlips);  // Initial time limit calculation
            elapsedTime = 0; // Reset elapsed time each time the timer starts

            // Display initial time when the timer starts
            document.getElementById('timer').innerText = `Time Left: ${formatTime(remainingTime)}`;

            timerInterval = setInterval(function() {
                // Keep track of time past the limit for penalty purposes
                elapsedTime++;

                // Apply penalty every 10 seconds after the allotted time has passed
                if (elapsedTime % timePenaltyInterval === 0 && remainingTime <= 0) {
                    currentScore -= 10;  
                    document.getElementById('current-score').innerText = `Score: ${currentScore}`;
                }

                // Display the time left
                if (remainingTime > 0) {
                    remainingTime--;
                } else {
                    remainingTime--;  // Continue subtracting when it's negative (time passed the limit)
                }

                document.getElementById('timer').innerText = `Time Left: ${formatTime(remainingTime)}`;
            }, 1000);  // Update every second
        }

        // When the game is finished, stop the timer and keep the score updated
        function endGame() {
            clearInterval(timerInterval);  // Stop the timer when the game ends
            document.getElementById('current-score').innerText = `Score: ${currentScore}`;
        }

        // Function to reset game state after a level replay
        function resetGameState() {
            // Ensure buttons are visible again after replay
            document.querySelectorAll('.level-button').forEach(button => {
                button.style.display = 'block';  // Ensure buttons are visible
            });

            // Reset flipped cards and matched sets count
            flippedCards = [];
            matchedPairs = 0;

            // Store the previous score ONLY if replaying
            if (currentScore !== null && currentScore !== undefined) {
                previousScore = currentScore;
                document.getElementById('previous-score').innerText = `Previous Score: ${previousScore}`;
                document.getElementById('previous-score-container').style.display = 'block'; // Show previous score
            }

            currentScore = 0;
            document.getElementById('current-score').innerText = `Score: ${currentScore}`;

            const gameBoard = document.getElementById('game-board');
            gameBoard.innerHTML = '';  

            // Reset background color
            document.getElementById('games').style.background = 'grey';
        }

        // Function to update score and check for gold background
        function updateBG() {
            let gameContainer = document.getElementById('games');

            // Only turn gold if previousScore is set and the new score is higher
            if (previousScore !== null && previousScore !== undefined && currentScore > previousScore) {
                gameContainer.style.background = '#FFD700';
            } else {
                gameContainer.style.background = 'grey'; //console
            }
        }

        // Function to handle replay button click
        function onReplayClick() {
            resetGameState(); // Reset the game state
            startGame(); // Start the game again
        }

        window.onload = function() {
            // Set the current level display on page load
            document.getElementById('level-display').textContent = `Level: ${currentLevel}`;
        };

        //Start the game, by generating the cards (based on level), hiding the start button
        function startGame() {
            currentScore = 0; // Reset the score to 0 at the start of the game
            document.getElementById('current-score').innerText = `Score: ${currentScore}`;
            // Show the score and timer once the game starts
            document.getElementById('score-time').style.display = 'block';

            // Calculate totalCards and cardsPerMatch based on current level
            if (currentLevel >= 16) {
                totalCards = 52 + (currentLevel - 16) * 2;  // Start with 52 cards for level 16, increment by 2 for each level

                // Ensure totalCards is a multiple of 3 or 4
                if (totalCards % 3 !== 0 && totalCards % 4 !== 0) {
                    if ((totalCards + 1) % 3 === 0 || (totalCards + 1) % 4 === 0) {
                        totalCards += 1; // Add 1 to make it a multiple of 3 or 4
                    } else if ((totalCards - 1) % 3 === 0 || (totalCards - 1) % 4 === 0) {
                        totalCards -= 1; // Subtract 1 to make it a multiple of 3 or 4
                    }
                }

                // Set cardsPerMatch based on divisibility of totalCards
                if (totalCards % 3 === 0) {
                    cardsPerMatch = 3;
                    maxFlips = 3;
                } else if (totalCards % 4 === 0) {
                    cardsPerMatch = 4;
                    maxFlips = 4;
                }
            } else {
                if (currentLevel >= 1 && currentLevel <= 15) {
                    let multiplier = currentLevel <= 5 ? 2 : currentLevel <= 10 ? 3 : 4;
                    totalCards = levelSets[currentLevel - 1] * multiplier;
                    cardsPerMatch = multiplier;
                    maxFlips = multiplier;
                }
            }

            let sets;

            // For levels 1 to 15, get sets from the levelSets list
            if (currentLevel <= 15) {
                sets = levelSets[currentLevel - 1];
            } 
            // For levels after 15, start with 52 and increment by 2 for each level
            else {
                sets = Math.floor(totalCards / cardsPerMatch);
            }

            // Generate the cards based on the calculated sets
            generateCards(sets);

            // Start the timer
            startTimer();

            // Reveal the game board and hide the start button
            document.getElementById('game-board').classList.remove('hiddenGame');
            document.getElementById('start-button').classList.add('hiddenButton');
            document.getElementById('level-display').textContent = `Level: ${currentLevel}`;

            // Initially hide the buttons
            document.getElementById('submit-btn').classList.add('hiddenButton');
            document.getElementById('replay-btn').classList.add('hiddenButton');
        }

        //Generate the cards - an helper function to startGame()
        function generateCards(sets) {
            const gameBoard = document.getElementById('game-board');
            gameBoard.innerHTML = ''; // Clear previous cards 

            const allEmojis = generateRandomEmojis(sets);

            // Duplicate the emojis based on cards per match
            const cards = [];
            for (let i = 0; i < allEmojis.length; i++) {
                for (let j = 0; j < cardsPerMatch; j++) {
                    cards.push(allEmojis[i]);
                }
            }

            // Shuffle the cards
            const shuffledCards = cards.sort(() => Math.random() - 0.5);

            // Render the shuffled cards
            shuffledCards.forEach((emoji, index) => {
                gameBoard.innerHTML += `
                    <div class="card hidden" data-index="${index}" onclick="flipCard(this)">
                        <div class="emoji">
                            <img src="emoji_assets/skin/${emoji.skin}" width="120">
                            <img src="emoji_assets/eyes/${emoji.eyes}" width="50">
                            <img src="emoji_assets/mouth/${emoji.mouth}" width="50">
                        </div>
                    </div>
                `;
            });
        }

        //Generate random unique emojis from the emoji assets
        function generateRandomEmojis(count) {
            const skins = ['green.png', 'red.png', 'yellow.png'];
            const eyes = ['closed.png', 'laughing.png', 'long.png', 'normal.png', 'rolling.png', 'winking.png'];
            const mouths = ['open.png', 'sad.png', 'smiling.png', 'straight.png', 'surprise.png', 'teeth.png'];

            const uniqueEmojis = new Set();

            while (uniqueEmojis.size < count) {
                const skin = skins[Math.floor(Math.random() * skins.length)];
                const eye = eyes[Math.floor(Math.random() * eyes.length)];
                const mouth = mouths[Math.floor(Math.random() * mouths.length)];

                const emojiKey = `${skin}-${eye}-${mouth}`; // Create a unique identifier for each emoji
                
                if (!uniqueEmojis.has(emojiKey)) {
                    uniqueEmojis.add(emojiKey);
                }
            }

            // Convert back to an array of emoji objects
            return Array.from(uniqueEmojis).map(key => {
                const [skin, eyes, mouth] = key.split('-');
                return { skin, eyes, mouth };
            });
        }

        //Determine and control the logic for flipping up and down the cards
        function flipCard(card) {

            // Ensure that no more than the allowed number of flips happen
            if (flippedCards.length < maxFlips && !card.classList.contains('flipped')) {
                card.classList.add('flipped');
                flippedCards.push(card);

                // Check for a match when max flips have been reached
                if (flippedCards.length === maxFlips) {
                    checkMatch();
                }
            }
        }

        /*Function to check whether the flipped cards match or not. 
        The function also updates scores accordingly, and ends the game when all the cards are flipped*/
        function checkMatch() {
            const [card1, card2, card3, card4] = flippedCards;
            const emoji1 = card1.querySelector('.emoji').innerHTML;
            const emoji2 = card2.querySelector('.emoji').innerHTML;
            const emoji3 = card3 ? card3.querySelector('.emoji').innerHTML : null;
            const emoji4 = card4 ? card4.querySelector('.emoji').innerHTML : null;

            let isMatch = false;

            if (currentLevel >= 16) {

                isMatch = (cardsPerMatch === 3)
                    ? (emoji1 === emoji2 && emoji1 === emoji3)
                    : (emoji1 === emoji2 && emoji1 === emoji3 && emoji1 === emoji4);
            } else if (currentLevel >= 11 && currentLevel <= 15) {
                isMatch = (emoji1 === emoji2 && emoji1 === emoji3 && emoji1 === emoji4);
            } else if (currentLevel >= 6 && currentLevel <= 10) {
                isMatch = (emoji1 === emoji2 && emoji1 === emoji3);
            } else {
                isMatch = (emoji1 === emoji2);
            }

            if (isMatch) {
                matchedPairs++;
                updateScore(true);
                const totalPairs = currentLevel <= 15
                    ? levelSets[currentLevel - 1] 
                    : Math.floor((52 + (currentLevel - 16) * 2) / (currentLevel >= 16 && (52 + (currentLevel - 16) * 2) % 3 === 0 ? 3 : 4));

                if (matchedPairs === totalPairs) {
                    endGame();
                    document.getElementById('submit-btn').classList.remove('hiddenButton');
                    document.getElementById('replay-btn').classList.remove('hiddenButton');
                }

                flippedCards = [];
            } else {
                updateScore(false);
                setTimeout(() => {
                    card1.classList.remove('flipped');
                    card2.classList.remove('flipped');
                    if (card3) card3.classList.remove('flipped');
                    if (card4) card4.classList.remove('flipped');
                    flippedCards = [];
                }, 800);
            }
        }

        //Send the request to PHP side to update and store the level variable in session
        function updateLevel() {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'pairs.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            const data = `update_level=true&finalScore=${currentScore}`;
            xhr.onload = function () {
                if (xhr.status === 200) {
                    location.reload();                
                }
            };
            // Send the current level to PHP for updating
            xhr.send(data);
        }

    </script>
  
</body>
</html>
