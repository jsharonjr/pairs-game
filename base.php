
<!--This is the base file containing common components, 
such as functionalities, styles, elements and Bootstrap links which are included in other files. -->

<?php
    //Start session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $isRegistered = isset($_SESSION['username']); // Check if the user is registered (via session variable)
    $avatar = isset($_SESSION['avatar']) ? $_SESSION['avatar'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

    <style>

        /*-------------------------------------------THE MAIN DIV---------------------------------------------------- */
        #main {
        background-image: url('images/arcade-unsplash.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: absolute; 
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        min-height: 100vh; 
        height: auto; 
        overflow: auto;
        }

        #main body {
            font-family: Verdana;
        }

        /*-------------------------------------------NAVBAR----------------------------------------------------------- */

        .navbar {
            background-color: blue; 
            font-family: Verdana; 
            font-weight: bold; 
            font-size: 12px; 
            padding: 10px 15px; 
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 50px; 
            position: relative; 
            z-index: 10; 
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 15px;
            display: block;
            text-align: center;
        }

        .navbar .left { 
            margin-right: auto; /* Pushes "Home" to the left */
        }

        .navbar .right { 
            display: flex;
            gap: 10px; /* Adds spacing between the right-side items */
        }

        /*----------------------------------------------AVATARS----------------------------------------------*/

        .avatar-preview {
            display: flex;
            position: relative;
            width: 40px;
            height: 40px;
           
        }

        .avatar-preview img {
            position: absolute;
            width: 40px;
            height: 40px;
            
        }

        .image-preview {
            display: flex;
            position: relative;
            width: 40px;
            
            height: 40px;     
        }

        .image-preview img {
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50%;   
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>
    <nav class="navbar">
        <div class="left" name="home">
            <a href="index.php">Home</a> <!--THE HOME PAGE-->
        </div>
        <div class="right">
            <div name="memory"><a href="pairs.php">Play Pairs</a></div> <!--THE PAIRS GAME PAGE-->

            <?php if ($isRegistered): ?>
                <div name="leaderboard"><a href="leaderboard.php">Leaderboard</a></div> <!--THE LEADERBOARD PAGE-->
                <?php
                    /* The first if condition checks if the user selected a custom emoji, 
                    the second else-if checks if the user chose an image, 
                    and the final else assumes the user selected the default option. */
                    if (isset($_SESSION['avatar']) && strpos($_SESSION['avatar'], 'emoji_assets/') !== false):
                        $emojiParts = preg_split('/_(?=emoji_assets\/)/', $_SESSION['avatar']); ?>
                    <div class="avatar-preview">
                        <img src="<?php echo htmlspecialchars($emojiParts[0]); ?>" class="avatar">
                        <img src="<?php echo htmlspecialchars($emojiParts[1]); ?>" class="avatar">                          
                        <img src="<?php echo htmlspecialchars($emojiParts[2]); ?>" class="avatar"> 
                    </div>

                <?php elseif (isset($_SESSION['image'])) : ?>   
                    <div class="image-preview">
                        <img src="<?php echo htmlspecialchars($_SESSION['image']); ?>" class="avatar">
                    </div>

                <?php else: ?>
                    <div class="image-preview">
                        <img src="images/default.jpg" class="avatar">
                    </div>

                <?php endif; ?>            
                <?php else: ?>

                <div name="register"><a href="registration.php">Register</a></div> <!--THE REGISTRATION PAGE-->

            <?php endif; ?>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>