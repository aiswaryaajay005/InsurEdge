<!DOCTYPE html>
<html lang="en">
    <!-- userlogin.php -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f5;
        }

        h1 {
            color: #970747;
            text-align: center;
            margin: 20px 0;
            font-size: 2.5em;
            
        }

      

        #log {
            background-color: #fff;
            border: 2px solid #ccc;
            border-radius: 8px;
            padding: 40px;
            width: 400px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        form {
            text-align: center;
        }

        form input[type="text"],
        form input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        form a {
            color: #0066cc;
            text-decoration: none;
        }

        form a:hover {
            text-decoration: underline;
        }

        .btn {
            background-color: #970747;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .btn:hover {
            background-color: #970742;
        }

        .btnn a {
            text-decoration: none;
            color: white;
        }

        .btnn {
            background-color: #970747;
            color: white;
            padding: 10px 20px;
            margin: 20px;
            border-radius: 5px;
            display: inline-block;
        }

        .btnn:hover {
            background-color: #004b99;
        }

        center {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <button class="btnn"><a href="../API/Home.html">Home</a></button>
    <h1>INSUREDGE</h1>
    
    <center>
        <div id="log">
            <form action="Loginset.php" method="post">
                <h2><u>Login to your account</u></h2><br>
                <input type="text" name="emailid" placeholder="Email-ID"><br><br>
                <input type="password" name="password" placeholder="Password"><br><br>
            
                <button class="btn">Login</button><br><br>
                New user? <a href="../API/Register.php">Register here</a>
            </form>
        </div>
    </center>
</body>
</html>
