<!--This is the registration page for every user.
The user needs to enter a username and choose or customise an avatar.
If an avatar is not chose, a default avatar will be used.
The username are validated to not allow any invalid characers. -->

<?php include 'base.php'; ?>
<?php 

    //Start sessions
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $isRegistered = isset($_SESSION['username']); // Check if the user is registered (via session variable)
    $avatar = isset($_SESSION['avatar']) ? $_SESSION['avatar'] : null;

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Store the username and avatar in session
        $username = $_POST['username'];
        $_SESSION['username'] = $username;

        // Check if a custom emoji was selected
        if (isset($_POST['avatar']) && $_POST['avatar'] === 'custom-emoji') {

            // Custom emoji avatar
            $skin = $_POST['skin'];
            $eyes = $_POST['eyes'];
            $mouth = $_POST['mouth'];

            // Combine them into one avatar (we'll store a string like "skin1_eyes2_mouth3")
            $selectedAvatar = $skin . "_" . $eyes . "_" . $mouth;

        } elseif (isset($_POST['avatar'])) {
            $_SESSION['image'] = $_POST['avatar']; // Use the selected avatar image
        } else {
            $selectedAvatar = 'images/default.jpg'; // Default to the default avatar
        }

        // Store in session
        $_SESSION['avatar'] = $selectedAvatar;

        header("Location: index.php"); // Redirects to the main page
        exit(); // Stop the script after the redirect
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>

        /*------------------------------------------------GENERAL STYLINGS----------------------------------------- */
       
        .hidden {
            display: none !important; /* Force the element to be hidden */
        }

        /*------------------------------------------------AVATAR------------------------------------------------ */

        /* Avatar Image Style */
        .avatar-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid transparent;
            cursor: pointer;
            transition: border-color 0.3s ease;  
        }

        /* Centering and layout of rows */
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; 
        }

        .col-3 {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px; 
        }

        #emojiContainer {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 20px;
        }

        .emoji-options {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 20px;
            gap: 20px;
        }

        /* Styling for each category (skin, eyes, mouth) */
        .option-category {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        /* Titles for each category (Skin, Eyes, Mouth) */
        .option-category h4 {
            margin-bottom: 10px;            
        }

        /* Styling for individual emoji options */
        .emoji-option {
            width: 50px;
            height: 50px;
            cursor: pointer;
            margin: 5px;
            transition: transform 0.2s ease;            
        }

        .emoji-option:hover {
            transform: scale(1.1);
        }

        /* Container for the emoji preview */
        .emoji-preview {
            position: relative;
            width: 100px; 
            height: 100px;
            margin: auto; 
        } 

        /* Styling for the images inside the preview */
        .emoji-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover; 
            position: absolute; 
        }

        /* Layering the images */
        .emoji-preview .skin {
            z-index: 1; 
        }
        .emoji-preview .eyes {
            z-index: 2; 
        }
        .emoji-preview .mouth {
            z-index: 3; 
        }

    </style>
</head>

<body style="font-family: Verdana, Arial; font-weight: Bold">

    <div id="main" class="container-fluid d-flex justify-content-center align-items-center">

        <div class="form-container p-4 mt-4 mb-4" style="background: rgba(255, 255, 255, 0.8); border-radius: 10px; width: 100%; max-width: 500px;">

            <h1 class="text-center" style="font-family: Verdana, Arial; font-weight: Bold">Register</h1>

            <form method="post" action="registration.php" onsubmit="return validateUsername()">

                <!------------------------------------Register Container-------------------------------------------->

                <div class="mb-3">

                    <!--HANDLING USERNAME-->
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" class="form-control" name="username" required>
                    <div id="usernameError" class="form-text text-danger" style="display:none; font-family: Verdana, Arial; font-weight: Bold">
                        Username contains invalid characters. 
                    </div>

                    <h6 class="mt-3" style="font-family: Verdana, Arial; font-weight: Bold">Choose Your Avatar: </h6>

                    <!-- Default and Predefined Avatars -->
                    <div class="container" >
                        <div class="row d-flex justify-content-center mt-4">  <!-- Bootstrap row with spacing -->

                            <div class="col-3 d-flex flex-column align-items-center">
                                <input type="radio" name="avatar" id="defaultAvatar" value="images/default.jpg" checked>
                                <label for="defaultAvatar">
                                    <img src="images/default.jpg" alt="Default Avatar" class="avatar-img">
                                </label>
                            </div>

                            <!-- Avatar Options - ANIMALS -->
                            <div class="col-3 text-center">
                                <label class="d-flex flex-column align-items-center">
                                    <input type="radio" name="avatar" value="images/bear.png">
                                    <img src="images/bear.png" alt="Bear" class="avatar-img">
                                </label>
                            </div>

                            <div class="col-3 text-center">
                                <label class="d-flex flex-column align-items-center">
                                    <input type="radio" name="avatar" value="images/cat.png">
                                    <img src="images/cat.png" alt="Cat" class="avatar-img">
                                </label>
                            </div>

                            <div class="col-3 text-center">
                                <label class="d-flex flex-column align-items-center">
                                    <input type="radio" name="avatar" value="images/chicken.png">
                                    <img src="images/chicken.png" alt="Chicken" class="avatar-img">
                                </label>
                            </div>

                            <div class="col-3 text-center">
                                <label class="d-flex flex-column align-items-center">
                                    <input type="radio" name="avatar" value="images/rabbit.png">
                                    <img src="images/rabbit.png" alt="Rabbit" class="avatar-img">
                                </label>
                            </div>

                            <div class="col-3 text-center">
                                <label class="d-flex flex-column align-items-center">
                                    <input type="radio" name="avatar" value="images/panda.png">
                                    <img src="images/panda.png" alt="Panda" class="avatar-img">
                                </label>
                            </div>

                            <!-- Avatar Options - BOY -->
                            <div class="col-3 text-center">
                                <label class="d-flex flex-column align-items-center">
                                    <input type="radio" name="avatar" value="images/boy1.png">
                                    <img src="images/boy1.png" alt="Boy 1" class="avatar-img">
                                </label>
                            </div>

                            <div class="col-3 text-center">
                                <label class="d-flex flex-column align-items-center">
                                    <input type="radio" name="avatar" value="images/boy2.png">
                                    <img src="images/boy2.png" alt="Boy 2" class="avatar-img">
                                </label>
                            </div>

                            <div class="col-3 text-center">
                                <label class="d-flex flex-column align-items-center">
                                    <input type="radio" name="avatar" value="images/boy3.png">
                                    <img src="images/boy3.png" alt="Boy 3" class="avatar-img">
                                </label>
                            </div>

                            <div class="col-3 text-center">
                                <label class="d-flex flex-column align-items-center">
                                    <input type="radio" name="avatar" value="images/boy4.png">
                                    <img src="images/boy4.png" alt="Boy 4" class="avatar-img">
                                </label>
                            </div>

                            <div class="col-3 text-center">
                                <label class="d-flex flex-column align-items-center">
                                    <input type="radio" name="avatar" value="images/boy5.png">
                                    <img src="images/boy5.png" alt="Boy 5" class="avatar-img">
                                </label>
                            </div>

                            <!-- Avatar Options - GIRL -->
                            <div class="col-3 text-center">
                                <label class="d-flex flex-column align-items-center">
                                    <input type="radio" name="avatar" value="images/girl1.png">
                                    <img src="images/girl1.png" alt="Girl 1" class="avatar-img">
                                </label>
                            </div>

                            <div class="col-3 text-center">
                                <label class="d-flex flex-column align-items-center">
                                    <input type="radio" name="avatar" value="images/girl2.png">
                                    <img src="images/girl2.png" alt="Girl 2" class="avatar-img">
                                </label>
                            </div>

                            <div class="col-3 text-center">
                                <label class="d-flex flex-column align-items-center">
                                    <input type="radio" name="avatar" value="images/girl3.png">
                                    <img src="images/girl3.png" alt="Girl 3" class="avatar-img">
                                </label>
                            </div>

                            <div class="col-3 text-center">
                                <label class="d-flex flex-column align-items-center">
                                    <input type="radio" name="avatar" value="images/girl4.png">
                                    <img src="images/girl4.png" alt="Girl 4" class="avatar-img">
                                </label>
                            </div>

                            <div class="col-3 text-center">
                                <label class="d-flex flex-column align-items-center">
                                    <input type="radio" name="avatar" value="images/girl5.png">
                                    <img src="images/girl5.png" alt="Girl 5" class="avatar-img">
                                </label>
                            </div>

                            <div class="col-3 text-center">
                                <input type="radio" name="avatar" id="customizeEmoji" value="custom-emoji" class="customize-emoji-radio">
                                <label for="customizeEmoji">Customize Your Own Emoji</label>
                            </div>

                            <!--Element used to access/modify the selected avatar-->
                            <input type="hidden" name="selected-avatar" id="selected-avatar" value="images/default.jpg"> 
                            
                            <!-- Emoji Customization Container (Hidden by default & opened only when customizeEmoji is clicked) -->

                            <div id="emojiContainer" class="hidden">

                                <!--SKIN OPTIONS-->
                                <div class="emoji-options">
                                    <div class="option-category">
                                        <h5 style="font-family: Verdana, Arial; font-weight: Bold">Skin</h5>
                                        <div class="options" id="skinOptions">
                                            <img src="emoji_assets/skin/green.png" class="emoji-option" data-type="skin">
                                            <img src="emoji_assets/skin/red.png" class="emoji-option" data-type="skin">
                                            <img src="emoji_assets/skin/yellow.png" class="emoji-option" data-type="skin">
                                        </div>
                                    </div>

                                    <!--EYE OPTIONS-->
                                    <div class="option-category">
                                        <h5 style="font-family: Verdana, Arial; font-weight: Bold">Eyes</h5>
                                        <div class="options" id="eyesOptions">
                                            <img src="emoji_assets/eyes/closed.png" class="emoji-option" data-type="eyes">
                                            <img src="emoji_assets/eyes/laughing.png" class="emoji-option" data-type="eyes">
                                            <img src="emoji_assets/eyes/long.png" class="emoji-option" data-type="eyes">
                                            <img src="emoji_assets/eyes/normal.png" class="emoji-option" data-type="eyes">
                                            <img src="emoji_assets/eyes/rolling.png" class="emoji-option" data-type="eyes">
                                            <img src="emoji_assets/eyes/winking.png" class="emoji-option" data-type="eyes">
                                        </div>
                                    </div>

                                    <!--MOUTH OPTIONS-->
                                    <div class="option-category">
                                        <h5 style="font-family: Verdana, Arial; font-weight: Bold">Mouth</h5>
                                        <div class="options" id="mouthOptions">
                                            <img src="emoji_assets/mouth/open.png" class="emoji-option" data-type="mouth">
                                            <img src="emoji_assets/mouth/sad.png" class="emoji-option" data-type="mouth">
                                            <img src="emoji_assets/mouth/smiling.png" class="emoji-option" data-type="mouth">
                                            <img src="emoji_assets/mouth/straight.png" class="emoji-option" data-type="mouth">
                                            <img src="emoji_assets/mouth/surprise.png" class="emoji-option" data-type="mouth">
                                            <img src="emoji_assets/mouth/teeth.png" class="emoji-option" data-type="mouth">
                                        </div>
                                    </div>
                                </div>

                                <!--WHEN CUSTOMISING AN EMOJI, THE USER CAN SEE THE PREVIEW OF THEIR AVATAR-->
                                <h5 style="font-family: Verdana, Arial; font-weight: Bold">Preview of your Emoji</h5>

                                <div class="emoji-preview">
                                    <img src="emoji_assets/skin/yellow.png" class="skin" alt="Skin" id="preview-skin">
                                    <img src="emoji_assets/eyes/normal.png" class="eyes" alt="Eyes" id="preview-eyes">
                                    <img src="emoji_assets/mouth/smiling.png" class="mouth" alt="Mouth" id="preview-mouth">
                                </div>    
                                
                                <!--Element used to access/modify the selected avatar-->
                                <form method="POST" action="index.php">
                                    <input type="hidden" name="skin" id="hidden-skin" value="emoji_assets/skin/yellow.png">
                                    <input type="hidden" name="eyes" id="hidden-eyes" value="emoji_assets/eyes/normal.png">
                                    <input type="hidden" name="mouth" id="hidden-mouth" value="emoji_assets/mouth/smiling.png"> 
                                </form>

                            </div>

                            <button type="submit" class="btn btn-primary w-100" style="font-family: Verdana, Arial; font-weight: Bold">Register</button>
                        </div>
                    </div>  
                </div>
            </form>
        </div>
    </div>

    <script>

        //FOR USERNAME VALIDATION (CHECKS FOR INVALID CHARACTERS)
        function validateUsername() {
            var username = document.getElementById("username").value;
            var usernameError = document.getElementById("usernameError");
            var invalidCharacters = /[!”@#%&ˆ*()+=\{\}\[\]—;:“’<>\?\/.\–^"']|--/;
            
            // Check if username contains invalid characters
            if (invalidCharacters.test(username)) {
                usernameError.style.display = "block";  // Show error message
                return false;  
            } else {
                usernameError.style.display = "none";  
                return true;  
            }
        }

        //FUNCTON TO ENSURE THAT WHEN customizeEmoji IS CLICKED, THE EMOJI CONTAINER IS DISPLAYED
        document.getElementById("customizeEmoji").addEventListener("change", function() {
            if (this.checked) {
                document.getElementById("emojiContainer").classList.remove("hidden");
            } else {
                document.getElementById("emojiContainer").classList.add("hidden");
            }
        });

        //HANDLING THE EMOJI SELECTION LOGIC TO ENSURE THE PREVIEW IS DISPLAYED CORRECTLY
        document.querySelectorAll(".emoji-option").forEach(function(option) {
            option.addEventListener("click", function() {
                const selectedType = this.dataset.type; // skin, eyes, or mouth
                const selectedSrc = this.getAttribute("src"); // Get the image src
                document.getElementById("preview-" + selectedType).src = selectedSrc; // Update the preview image
                // Update the hidden input field with the selected option
                document.getElementById("hidden-" + selectedType).value = selectedSrc;
            });
        });

        //HANDLING RADIO BUTTON CHANGES FOR AVATAR SELECTION
        document.querySelectorAll('input[name="avatar"]').forEach(function(input) {
            input.addEventListener('change', function() {
                const selectedAvatar = this.value;
                // If 'Customize Your Own Emoji' is selected, trigger the emoji customization
                if (selectedAvatar === 'custom-emoji') {
                    // Trigger your emoji customization function here
                    document.getElementById("emoji-customization").style.display = 'block'; 
                } else {
                    // Otherwise, just display the selected avatar
                    document.getElementById("preview-avatar").src = selectedAvatar;
                }
            });
        });

    </script>
</body>
</html>



