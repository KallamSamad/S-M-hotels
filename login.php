<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S&M Login Page</title>
</head>
<body>
    <div class="loginpage">
    <div class="standingphoto">
        <img src="images/standinghotel.webp">
    </div>
    <div class="headerlogin"> 
    <div class="img">
          <h1 class="loginhdr">Welcome <br>To</h1>
         <img src="images/logo.webp" >
         <p class="loginmsg" >Enter your username and password to see this page</p>
    </div>

   
    <div class="formlogin">
        <form method="POST" action="index.php" class="formspace">
    
            <label for="username" style="color: #ebddd7;">Username</label>
            <input type="text" id="username" name="username" placeholder="eg. SMuser25" required autocomplete="off">
            <label for="password" style="color: #ebddd7;">Password</label>
            <input type="password" id="password" name="password" minlength="8"  title="Must contain 8+ characters" required>
            <input type="submit" name="submit" value="submit">
            </form>
            <p style="color: #ebddd7;">Not got an Account?<a class="signup" href="addUser.php"> Sign up</a></p>
        </div>
    </div>
</div>

    <div class="footer">
    © 2025 S&M Hotels. All rights reserved.
    </div>
</body>
</body>
</html>