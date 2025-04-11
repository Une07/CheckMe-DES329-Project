

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
    <title>Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            overflow: hidden;
        }
        
        /* Sidebar styles */
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
        
        /* Main content area */
        .content {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        /* Profile section */
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
        
        /* Bio section */
        .bio {
            width: 80%;
            margin: 20px 0;
            font-size: 20px;
        }
        
        /* Tab navigation */
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
        }
        
        /* To-do items */
        .todo-item {
            width: 80%;
            background-color: black;
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            display: flex;
            align-items: center;
        }
        
        .user-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: white;
            margin-right: 20px;
        }
        
        .todo-content {
            flex-grow: 1;
        }
        
        .todo-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .todo-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .todo-list-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid white;
            background-color: transparent;
        }
        
        /* Share photo section */
        .share-photo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 80%;
            padding: 40px 0;
            text-align: center;
            background-color: white;
            color: black;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .share-photo-section span {
            font-size: 80px; /* Adjust size as needed */
            margin-bottom: 20px;
        }
        
        .share-photo-section h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .share-photo-section p {
            font-size: 16px;
            margin-bottom: 20px;
        }
        
        .share-photo-section a {
            background-color: black;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="FeedPage-following.php"><img src="./image/logo.png" alt="CHECKME Logo" style="width: 200px; margin-bottom: -20px;"></a>        <a href="FeedPage-foryou.php">🏠 Home</a>
        <a href="ToDo.php?folder_id=123">➕ TO-DO</a>
        <a href="Post.php">➕ Post</a>
        <a href="Profile-Post.php">👤 Profile</a>
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
                <a href="Profile-Post.php" class="tab-button" style="text-decoration: none;">Post</a>
                <a href="Profile-Todo.php" class="tab-button" style="text-decoration: none;">To-Do</a>
            </div>
            <div class="tab-line"></div>
        </div>
        
        <div class="share-photo-section">
            <span role="img" aria-label="camera">📸</span>
            <h2>Share photos</h2>
            <p>When you share photos, they will appear on your profile.</p>
            <a href="Post.php">Share your first photo</a>
        </div>
        
    </div>
</body>
</html>