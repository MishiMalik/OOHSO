<?php
// Ensure this file is included through index.php
if (!defined('INCLUDED_FROM_INDEX')) {
    define('INCLUDED_FROM_INDEX', true);
}

// Get subscriber count and list
try {
    $conn = getDbConnection();
    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    // Get total subscribers
    $stmt = $conn->query("SELECT COUNT(*) FROM subscribers");
    $totalSubscribers = $stmt->fetchColumn();

    // Get subscribers
    $stmt = $conn->query("SELECT * FROM subscribers ORDER BY created_at DESC");
    $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Error loading subscriber data. " . $e->getMessage();
}

// Get admin user info
$adminName = $_SESSION['admin_name'] ?? 'Admin User';
$adminEmail = $_SESSION['admin_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OOHSO Admin Dashboard</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Montserrat', sans-serif;
        }

        .admin-header {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            box-sizing: border-box;
        }

        .admin-logo {
            font-size: 1.2rem;
            font-weight: bold;
            font-family: 'Norman', 'Times New Roman', serif;
        }

        .admin-nav {
            display: flex;
            align-items: center;
        }

        .admin-nav-item {
            margin-left: 20px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.8rem;
            letter-spacing: 1px;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }

        .admin-container {
            padding: 30px;
            width: 100%;
            max-width: 1200px;
            margin: 80px auto 30px auto;
            /* Added top margin to account for fixed header */
            box-sizing: border-box;
        }

        .admin-container h2 {
            margin-bottom: 20px;
            font-weight: normal;
            font-family: 'Norman', 'Times New Roman', serif;
        }

        .admin-panel {
            background-color: rgba(25, 25, 25, 0.9);
            border-radius: 5px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .stats {
            margin-bottom: 20px;
            font-family: 'Montserrat', sans-serif;
        }

        .admin-buttons {
            margin: 20px 0;
        }

        .admin-buttons button,
        .admin-buttons a {
            background: transparent;
            color: white;
            border: 1px solid white;
            padding: 8px 15px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.75rem;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 400;
            margin-right: 10px;
            text-decoration: none;
            display: inline-block;
        }

        .admin-buttons button:hover,
        .admin-buttons a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background-color: rgba(0, 0, 0, 0.3);
            font-weight: 500;
            letter-spacing: 1px;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .user-profile {
            display: flex;
            align-items: center;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #fc2c1d;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 500;
        }

        .user-email {
            font-size: 0.75rem;
            opacity: 0.7;
        }
    </style>
</head>

<body>
    <div class="admin-header">
        <div class="admin-logo">OOHSO ADMIN</div>
        <div class="admin-nav">
            <div class="user-profile">
                <div class="user-avatar"><?php echo strtoupper(substr($adminName, 0, 1)); ?></div>
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($adminName); ?></div>
                    <div class="user-email"><?php echo htmlspecialchars($adminEmail); ?></div>
                </div>
            </div>
            <a href="?logout=1" class="admin-nav-item">LOGOUT</a>
        </div>
    </div>

    <div class="admin-container">
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <div class="admin-panel">
            <h2>Email Subscribers</h2>
            <div class="stats">
                Total Subscribers: <?php echo $totalSubscribers; ?> </div>
            <div class="admin-buttons">
                <a href="export_subscribers.php" target="_blank">EXPORT CSV</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Signup Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($subscribers) && count($subscribers) > 0): ?>
                        <?php foreach ($subscribers as $index => $subscriber): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($subscriber['email']); ?></td>
                                <td><?php echo htmlspecialchars($subscriber['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">No subscribers yet</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // No tab functionality needed anymore
    </script>
</body>

</html>