<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OOHSO Admin Login</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            padding: 40px 0;
        }

        .admin-container {
            background-color: rgba(25, 25, 25, 0.9);
            padding: 30px;
            border-radius: 5px;
            max-width: 400px;
            margin: 0 auto;
        }

        .admin-form {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .admin-form input {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background-color: rgba(0, 0, 0, 0.2);
            color: white;
        }

        .error-message {
            color: #fc2c1d;
            margin-bottom: 15px;
        }

        h2 {
            margin-bottom: 20px;
            font-weight: normal;
            font-family: 'Norman', 'Times New Roman', serif;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="admin-container">
            <h2>OOHSO Admin Access</h2>

            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <form class="admin-form" method="post" action="">
                <input type="hidden" name="action" value="login">
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">LOGIN</button>
            </form>
        </div>
    </div>
</body>

</html>