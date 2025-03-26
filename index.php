
<!--This is the index file, which serves as the landing or home page.
If registered, it'll suggest the user to interact with the game.
Else, the user will be asked to register.-->

<?php include 'base.php'; ?>
<?php

    //Start session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();    
    }

    // Check if the user is registered (via session variable)
    $isRegistered = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    
    <style>

    /*--------------------------------------GENERAL STYLINGS------------------------------------------------------*/
   
    #main h1, p {
        font-weight: bold;
        color: black;
        background-color: rgba(255, 255, 255, 0.7);
        padding: 20px;
        margin-top: 20px;
        text-align: center;
        border-radius: 5px;
    }

    /* Styles for the 'if' part (when the user is registered) */
    #main .registered-message {
        margin-top: 20px;
    }

    #main .button-container {
        margin-top: 20px;
        text-align: center;
    }

    /* Styles for the 'else' part (when the user is not registered) */
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

    </style>
</head>

<body>       
    <div id="main" >

        <!--FOR REGISTERED USERS-->
        <?php if ($isRegistered): ?>
            <h1 class="registered-message">Welcome to Pairs!</h1>
            <div class="button-container">
                <a href="pairs.php">
                    <button class="btn btn-info" type="button">Click here to play!</button>
                </a>
            </div>
        <?php endif; ?>

        <!--FOR UNREGISTERED USERS-->
        <?php if (!($isRegistered)): ?>
            <div class="not-registered-message">
                <p style="font-size:40px">
                    You're not using a registered session? 
                    <a href="registration.php">Register now!</a>
                </p>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>