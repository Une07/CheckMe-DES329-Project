<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
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
        .feed-container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            overflow-y: auto;
            height: 100vh;
            background-color: #f8f8f8;
        }
        .feed-header {
            display: flex;
            gap: 20px;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .feed-header a {
            background: black;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .feed-item {
            background: black;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            justify-content: space-between;
            font-size: 25px;
        }
        .user-info {
            flex-grow: 0;
            width: 150px;
            border-right: 5px solid white; /* Added white line */
            padding-right: 20px; /* Added padding to separate content from line */
        }
        .to-do-list-container {
            display: flex;
            align-items: center;
            flex-grow: 1;

        }
        .to-do-title {
            font-weight: bold;
            margin-right: 20px;
            border-right: 5px solid white; /* Added white line */
            padding-right: 20px; /* Added padding to separate content from line */
            align-self: stretch; /* Stretch to parent height */
        }
        .to-do-list {
            display: flex;
            flex-direction: column;
            gap: 5px;
            text-align: left;
        }
        .to-do-list label {
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="FeedPage-following.php"><img src="./image/logo.png" alt="CHECKME Logo" style="width: 200px; margin-bottom: -20px;"></a> 
        <a href="FeedPage-foryou.php">🏠 Home</a>
        <a href="ToDo.php?folder_id=123">➕ TO-DO</a>
        <a href="Post.php">➕ Post</a>
        <a href="Profile-Post.php">👤 Profile</a>
        <a href="Saved.php">🔖 Saved To-Do</a>
        <a href="Setting.php">⚙️ Setting</a>
        <a href="Login.php">🔄 Logout</a>
    </div>
    <div class="feed-container">
        <div class="feed-header">
            <a href="FeedPage-foryou.php">For you</a>
            <a href="FeedPage-following.php">Following</a>
        </div>
        
        
        <div class="feed-item">
            <div class="user-info">
                👤 <strong>User F</strong><br>
                Follower: 600  Following: 650
            </div>
            <div class="to-do-list-container">
                <div class="to-do-title">Title of To-Do list</div>
                <div class="to-do-list">
                    <label><input type="checkbox"> To-Do list 1</label>
                    <label><input type="checkbox"> To-Do list 2</label>
                </div>
            </div>
        </div>
        
        <div class="feed-item">
            <div class="user-info">
                👤 <strong>User G</strong><br>
                Follower: 600  Following: 650
            </div>
            <div class="to-do-list-container">
                <div class="to-do-title">Title of To-Do list</div>
                <div class="to-do-list">
                    <label><input type="checkbox"> To-Do list 1</label>
                    <label><input type="checkbox"> To-Do list 2</label>
                </div>
            </div>
        </div>
        
        <div class="feed-item">
            <div class="user-info">
                👤 <strong>User H</strong><br>
                Follower: 600  Following: 650
            </div>
            <div class="to-do-list-container">
                <div class="to-do-title">Title of To-Do list</div>
                <div class="to-do-list">
                    <label><input type="checkbox"> To-Do list 1</label>
                    <label><input type="checkbox"> To-Do list 2</label>
                </div>
            </div>
        </div>
        
    </div>
</body>
</html>