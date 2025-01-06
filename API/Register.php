

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="../CSS/Registerstyle.css">
    <script>
    async function validateForm(event) {
        event.preventDefault(); // Prevent form submission

        const form = document.forms["imsregister"];
        const name = form["name"].value.trim();
        const mobile = form["mobile"].value.trim();
        const email = form["email"].value.trim();
        const password = form["password"].value;
        const cpassword = form["cpassword"].value;
        const gender = form["gender"].value;

        // Regular expressions
        const nameRegex = /^[A-Za-z\s]+$/;
        const phoneRegex = /^\d{10}$/;
        const emailRegex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/; // Ensures no spaces and no capital letters

        let isValid = true;

        // Clear any previous messages
        const emailMessage = document.getElementById('emailMessage');
        emailMessage.textContent = "";

        // Validation checks
        if (!name) {
            alert("Name is required.");
            isValid = false;
        } else if (!nameRegex.test(name)) {
            alert("Name must contain only alphabets.");
            isValid = false;
        }

        if (!mobile) {
            alert("Mobile number is required.");
            isValid = false;
        } else if (!phoneRegex.test(mobile)) {
            alert("Phone number must be 10 digits.");
            isValid = false;
        }

        if (!email) {
            alert("Email is required.");
            isValid = false;
        } else if (!emailRegex.test(email)) {
            alert("Please enter a valid email address (no spaces or capital letters).");
            isValid = false;
        }

        if (!password) {
            alert("Password is required.");
            isValid = false;
        } else if (password.length < 8) {
            alert("Password must be at least 8 characters long.");
            isValid = false;
        }

        if (!cpassword) {
            alert("Confirm Password is required.");
            isValid = false;
        } else if (password !== cpassword) {
            alert("Passwords do not match.");
            isValid = false;
        }

        if (!gender) {
            alert("Gender selection is required.");
            isValid = false;
        }

        // If any field is invalid, prevent further checks
        if (!isValid) {
            return false; // Stop further validation and allow correction
        }

        // Email availability check
        const emailAvailable = await checkEmailAvailability(email);

        if (!emailAvailable) {
            emailMessage.textContent = 'Email already exists!';
            emailMessage.style.color = 'red';
            return false; // Stop form submission
        } else {
            emailMessage.textContent = 'Email is available.';
            emailMessage.style.color = 'green';
        }

        // If everything is valid, submit the form
        form.submit();
    }

    // Asynchronous function to check email availability
    async function checkEmailAvailability(email) {
        try {
            const response = await fetch('check_email.php?email=' + encodeURIComponent(email));
            const data = await response.text();
            return data !== 'exists';
        } catch (error) {
            console.error('Error checking email availability:', error);
            return false;
        }
    }
</script>
</head>

<body>
    <button class="button" onclick="history.back()">Go Back</button>
    <h1>REGISTRATION</h1>
    <center>
        <div id="home">
            <form name="imsregister" method="post" action="../Connection/Connect.php">
                <h3>Personal Details</h3>

                <!-- Name Field -->
                <input type="text" name="name" placeholder="Name" 
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"> 
                <br>
                <small style="color:red;"><?= $errorMessages['name'] ?? '' ?></small>
                <br><br>

                <!-- Email Field -->
                <input type="text" name="email" placeholder="Email" 
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"> 
                <br>
                <small style="color:red;"><?= $errorMessages['email'] ?? '' ?></small>
                <br><br>

                <!-- Gender Field -->
                <p>Gender:</p>
                <input type="radio" name="gender" id="m" value="Male" 
                       <?= (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'checked' : '' ?>>
                <label for="m">Male</label>
                <input type="radio" name="gender" id="f" value="Female" 
                       <?= (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'checked' : '' ?>>
                <label for="f">Female</label>
                <br>
                <small style="color:red;"><?= $errorMessages['gender'] ?? '' ?></small>
                <br><br>

                <!-- Mobile Field -->
                <input type="text" name="mobile" placeholder="Mobile Number" 
                       value="<?= htmlspecialchars($_POST['mobile'] ?? '') ?>"> 
                <br>
                <small style="color:red;"><?= $errorMessages['mobile'] ?? '' ?></small>
                <br><br>

                <!-- Password Field -->
                <input type="password" name="password" placeholder="Password"> 
                <br><br>
                <input type="password" name="cpassword" placeholder="Confirm Password"> 
                <br>
                <small style="color:red;"><?= $errorMessages['password'] ?? '' ?></small>
                <br><br>

                <input type="submit" id="btn" name="submit" value="Submit">
                <br>
            </form>
            Already User? <a href="../User/Userlogin.php">Login Here</a>
        </div>
    </center>
</body>
</html>
