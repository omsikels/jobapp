<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply Now - Jobyment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/styles.css"> 
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            text-align: center;
            padding-top: 80px; /* Prevents navbar overlap */
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
        }
        .step-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        .step img {
            width: 100%; 
            max-width: 15500px;
            height: auto; 
            margin-bottom: 10px;
        }
        .step h3 {
            font-size: 1.8em;
            font-weight: bold;
            color: #007bff;
        }
        .step p {
            font-size: 1.2em;
            color: #555;
            margin-bottom: 30px;
        }
        .apply-button {
            margin-top: 20px;
            padding: 15px 30px;
            background-color: #007bff;
            color: white;
            font-size: 1.5em;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .apply-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="step-container">
            <div class="step">
                <img src="steps.png" alt="apply">
            </div>
        </div>

        <button onclick="window.location.href='forms.php'" class="btn btn-primary">Apply Now</button>
        <h2>Ready to Apply?</h2>
        <p>Start your application process now. Make sure you have all required documents ready before beginning.</p>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
