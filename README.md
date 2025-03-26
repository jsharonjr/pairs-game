This README.md will provide a concise description of the features of every .php file in the project

base.php:

- Has some common functionalities that all pages share
- Registered users:
  - Navbar has: Home on left, Play Pairs, Leaderboard and Avatar on right
  - All the items in navbar are linked to their respective pages
- Unregistered users:
  - Navbar has: Home on left, Play Pairs and Register on right

index.php:

- Registered users:
  - “Welcome to Pairs” message
  - Followed by a button “Click here to play” hyperlinked to the pairs.php page
- Unregistered users:
  - Message to register linked to registration.php
  - You can't play the game or have access to the leaderboard if you're not registered

pairs.php:

- Registered users:
  - Increasing number of cards at each level
  - Game design and logic explained in the report
  - Progressively, users match 2, 3 and 4 cards
  - Card images: random emojis created from emoji assets
  - Records total points as well as points per level
  - Every level has a timer
  - Background color changes to Gold when user exceeds previous best score for the current level.
  - On clicking Submit: current level points gets added to the overall points
  - On clicking Replay: Previous score is stored temporarily and proceeds to restart the game
  - Penalties: For every wrong matching of the card and crossing the time limit
  - Bonuses: For every right matching of the card
- Unregistered users:
  - Message to register with link to registration.php

registration.php:

- Username input field
- Username input validation
- Avatar field:
  - Choose an existing image as avatar
  - Customise your own avatar using the emoji assets
  - If the user chooses neither, a default avatar will be used

leaderboard.php:

- Will have usernames and total scores
- Enable multiple users
- Gets updated automatically as new users log in
