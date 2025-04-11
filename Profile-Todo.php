<?php
session_start();

// Check if user is logged in by verifying the JWT token
if (!isset($_SESSION['jwt_token'])) {
    header("Location: Login.php");
    exit;
}

// Decode the JWT token to get user information
$token = $_SESSION['jwt_token'];
$tokenParts = explode(".", $token); 
$tokenPayload = base64_decode($tokenParts[1]);
$jwtPayload = json_decode($tokenPayload);

if (!$jwtPayload || !isset($jwtPayload->user_id)) {
    echo "❌ Error: Invalid token.";
    exit;
}

$user_id = $jwtPayload->user_id;

// Fetch user details from database API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:5001/api/user/$user_id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);

$userResponse = curl_exec($ch);
if ($userResponse === false) {
    die("❌ Error fetching user data: " . curl_error($ch));
}
curl_close($ch);

$userData = json_decode($userResponse, true);

// Fetch to-do folders from database API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:5001/api/todo-folders/$user_id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);

$folderResponse = curl_exec($ch);
if ($folderResponse === false) {
    die("❌ Error fetching folders: " . curl_error($ch));
}
curl_close($ch);

$folderData = json_decode($folderResponse, true);

// Extract data
$username = $userData['username'] ?? 'User';
$bio = $userData['bio'] ?? 'No bio yet';
$followerCount = $userData['follower_count'] ?? 0;
$followingCount = $userData['following_count'] ?? 0;
$folders = $folderData['folders'] ?? [];

$todoCount = $userData['todo_post_count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($username); ?>'s Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Your existing CSS styles */
        body {
            display: flex;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            overflow: hidden;
        }
        
        .sidebar {
            width: 250px;
            background-color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            font-size: 27px;
            font-weight: bold;
        }
        
        .sidebar a {
            text-decoration: none;
            color: black;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .sidebar a:nth-child(6) {
            margin-bottom: 200px;
        }
        
        .content {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .profile-section {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .profile-image {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 30px;
            background-color: #2a5c96;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .profile-info {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .profile-name {
            font-size: 30px;
            font-weight: bold;
        }
        
        .edit-button {
            background-color: black;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
        }
        
        .settings-icon {
            font-size: 24px;
            cursor: pointer;
        }
        
        .profile-stats {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .profile-stats span {
            font-weight: bold;
            margin-left: 20px;
        }
        
        .bio {
            width: 80%;
            margin: 20px 0;
            font-size: 20px;
            text-align: left;
        }
        
        .tab-container {
            width: 80%;
            margin: 20px 0;
        }
        
        .tab-line {
            width: 100%;
            height: 2px;
            background-color: black;
            position: relative;
            margin: 10px 0;
        }
        
        .tab-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        
        .tab-button {
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 30px;
            font-size: 18px;
            cursor: pointer;
            text-decoration: none;
        }
        
        .folder-links {
            width: 80%;
            margin-top: 20px;
        }
        
        .folder-link {
            padding: 12px 20px;
            margin: 10px 0;
            background-color: #f0f0f0;
            color: #333;
            border-radius: 25px;
            text-decoration: none;
            display: block;
            font-size: 18px;
            transition: background-color 0.2s;
        }
        
        .folder-link:hover {
            background-color: #ddd;
        }
        
        .no-folders {
            text-align: center;
            color: #666;
            font-style: italic;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="FeedPage-following.php"><img src="./image/logo.png" alt="CHECKME Logo" style="width: 200px; margin-bottom: -20px;"></a>
        <a href="FeedPage-foryou.php">🏠 Home</a>
        <a href="ToDo.php?folder_id=123">➕ TO-DO</a>
        <a href="Post.php">➕ Post</a>
        <a href="Profile-Todo.php" class="active">👤 Profile</a>
        <a href="Saved.php">🔖 Saved To-Do</a>
        <a href="Setting.php">⚙️ Setting</a>
        <a href="Login.php">🔄 Logout</a>
    </div>
    
    <div class="content">
        <div class="profile-section">
            <div class="profile-image">
                <img src="./image/profile.png" alt="Profile Picture">
            </div>
            
            <div class="profile-info">
                <div class="profile-header">
                    <div class="profile-name"><?php echo htmlspecialchars($username); ?></div>
                    <a href="Setting.php"><button class="edit-button">Edit Profile</button></a>
                    <a href="Setting.php"><div class="settings-icon">⚙️</div></a>
                </div>
                
                <div class="profile-stats">Follower: <span><?php echo $followerCount; ?></span></div>
                <div class="profile-stats">Following: <span><?php echo $followingCount; ?></span></div>
                <div class="profile-stats">To-Do: <span><?php echo $todoCount; ?></span></div>
            </div>
        </div>
        
        <div class="bio">
            <?php echo htmlspecialchars($bio); ?>
        </div>
        
        <div class="tab-container">
            <div class="tab-line"></div>
            <div class="tab-buttons">
                <a href="Profile-Post.php" class="tab-button">Post</a>
                <a href="Profile-Todo.php" class="tab-button active">To-Do</a>
            </div>
            <div class="tab-line"></div>
        </div>
        
        <div class="folder-links">
            <?php if (!empty($folders)): ?>
                <?php foreach ($folders as $folder): ?>
                    <a href="ToDo-Folder.php?folder_id=<?php echo $folder['folder_id']; ?>" class="folder-link">
                        <?php echo htmlspecialchars($folder['folder_name']); ?>
                        (<?php echo $folder['todo_count'] ?? 0; ?>)
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-folders">No folders yet. Create your first folder!</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Optional JavaScript for dynamic updates
        document.addEventListener('DOMContentLoaded', function() {
            // Highlight current page in sidebar
            const currentPage = window.location.pathname.split('/').pop();
            document.querySelectorAll('.sidebar a').forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>