<?php 
include "init.php";
include "header.php";
?>

<div id="forms">
    <form id="loginForm" method="post" action="http://www.randyconnolly.com/tests/process.php">
        <fieldset>
            <legend id="loginTitle">Login</legend>
            <div id="loginFormInputElements">
                <div class="loginFormElements">
                    <label id="loginEmailLabel" class="loginLabel">Email: </label>
                    <input type="email" name="username" id="loginEmailInput" placeholder="your@email.com" required>
                </div>

                <div class="loginFormElement">
                    <label id="loginPasswordLabel" class="loginLabel">Password: </label>
                    <input type="password" name="loginpassword" id="loginPasswordInput" placeholder="********" required>
                </div>
            </div>
            <div class="loginFormElement">
                <button type="submit" id="loginSubmitButton">Login</button>
            </div>

            <div class="loginFormElement">
                <a href="#">Forgot Password?</a>
            </div>
        </fieldset>
    </form>

    <form id="signUpForm" method="post" action="http://www.randyconnolly.com/tests/process.php">
        <fieldset>
            <legend id="signUpTitle">Sign-Up</legend>
            <div id="signUpInputElements">
                <div class="SUformElement">
                    <label id="SUfnameLabel" class="signUpLabel" for="fnameInput">First name: </label>
                    <input type="text" name="fname" id="fnameInput" required>
                </div>

                <div class="SUformElement">
                    <label id="SUlnameLabel" class="signUpLabel" for="lnameInput">Last name: </label>
                    <input type="text" name="lname" id="lnameInput" required>
                </div>

                <div class="SUformElement">
                    <label id="SUemailLabel" class="signUpLabel" for="signUpEmailInput">Email: </label>
                    <input type="email" name="email" id="signUpEmailInput" required>
                </div>

                <div class="SUformElement">
                    <label id="SUpasswordLabel" class="signUpLabel" for="signUpPasswordInput">Password: </label>
                    <input type="password" name="signuppassword" id="signUpPasswordInput" required>
                </div>

                <div class="SUformElement">
                    <label id="SUconfirmPasswordLabel" class="signUpLabel" for="signUpPasswordConfirmationInput">Confirm Password: </label>
                    <input type="password" name="confirmpassword" id="signUpPasswordConfirmationInput" required>
                </div>
            </div>

            <div class="SUformElement">
                <button type="submit">Sign-up</button>
            </div>
        </fieldset>
    </form>
</div>

<?php 
include "footer.php";
?>