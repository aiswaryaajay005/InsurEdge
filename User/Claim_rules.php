<?php 
include 'User_sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Eligibility and Rules</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #333;
        }
        .container {
            max-width: 600px;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        h1 {
            font-size: 26px;
            color: #970747;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }
        ul {
            text-align: left;
            margin-bottom: 30px;
        }
        ul li {
            font-size: 16px;
            color: #333;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        ul li:last-child {
            border-bottom: none;
        }
        .note {
            font-size: 14px;
            color: #888;
            margin-bottom: 25px;
        }
        button {
            padding: 12px 20px;
            font-size: 16px;
            background-color: #970747;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Claim Eligibility and Rules</h1>
        <p>To be eligible for filing a claim, please follow these rules:</p>
        <ul>
            <li>Your policy must be active at the time of the incident.</li>
            <li>The incident must fall within your coverage duration.</li>
            <li>Documents required: Incident report, photos, receipts, and other relevant files.</li>
            <li>For ongoing policies, claim payouts will be based on the premiums paid to date.</li>
            <li>Full claim payment eligibility is assessed based on policy terms and duration.</li>
        </ul>
        <p class="note">*Note: Please ensure all documents are genuine to avoid delays or rejections in processing.</p>
        <button onclick="location.href='Policy_details.php'">Proceed to Claim</button>
    </div>
</body>
</html>
