

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
            align-items: flex-start;
            gap: 15px;
            justify-content: flex-start;
            font-size: 25px;
            transition: transform 0.2s ease-in-out;
        }
        .feed-item:hover {
            transform: translateY(-5px);
        }
        .user-info {
            flex-grow: 0;
            width: 150px;
            border-right: 5px solid white;
            padding-right: 20px;
        }
        .to-do-list-container {
            display: flex;
            flex-direction: row;
            align-items: center;
            flex-grow: 1;
        }
        .to-do-title {
            border-right: 5px solid white;
            padding-right: 20px;
            margin-right: 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
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
        .feed-item a {
            color: white;
            text-decoration: none !important;
            display: block;
        }

        .to-do-list a {
            color: white;
            text-decoration: none !important;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="FeedPage-foryou.php"><img src="./image/logo.png" alt="CHECKME Logo" style="width: 200px; margin-bottom: -20px;"></a> 
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
        
        
        <a href="Profile-B-Post.php" class="feed-item" style="text-decoration: none;">
            <div class="user-info">
                👤 <strong>User B</strong><br>
                Follower: 600  Following: 650
            </div>
            <div class="to-do-list-container" style="display: flex; flex-direction: row; align-items: center;">
                <div class="to-do-title" style="margin-top: 40px;">Title of To-Do list</div>
                <div class="to-do-list" style="margin-top: 50px;">
                    <label>
                        <input type="checkbox">
                        <span>To-Do list 1</span>
                    </label>
                    <label style="display: block; margin-top: 5px;">
                        <input type="checkbox">
                        <span>To-Do list 2</span>
                    </label>
                </div>
            </div>
        </a>


        <div class="feed-item">
            <div class="user-info">
                👤 <strong>User D</strong><br>
                Follower: 600  Following: 650
            </div>
            <div class="to-do-list-container">
                <div class="to-do-title" style="margin-top: 40px;">Title of To-Do list</div>
                <div class="to-do-list" style="margin-top: 50px;">
                    <label><input type="checkbox"> <a href="#">To-Do list 1</a></label>
                    <label><input type="checkbox"> <a href="#">To-Do list 2</a></label>
                </div>
            </div>
        </div>
        
        <div class="feed-item">
            <div class="user-info">
                👤 <strong>User E</strong><br>
                Follower: 600  Following: 650
            </div>
            <div class="to-do-list-container">
                <div class="to-do-title" style="margin-top: 40px;">Title of To-Do list</div>
                <div class="to-do-list" style="margin-top: 50px;">
                    <label><input type="checkbox"> <a href="#">To-Do list 1</a></label>
                    <label><input type="checkbox"> <a href="#">To-Do list 2</a></label>
                </div>
            </div>
        </div>
        
        <div class="feed-item">
            <div class="user-info">
                👤 <strong>User F</strong><br>
                Follower: 600  Following: 650
            </div>
            <div class="to-do-list-container">
                <div class="to-do-title" style="margin-top: 40px;">Title of To-Do list</div>
                <div class="to-do-list" style="margin-top: 50px;">
                    <label><input type="checkbox"> <a href="#">To-Do list 1</a></label>
                    <label><input type="checkbox"> <a href="#">To-Do list 2</a></label>
                </div>
            </div>
        </div>
        
        <div class="feed-item">
            <div class="user-info">
                👤 <strong>User G</strong><br>
                Follower: 600  Following: 650
            </div>
            <div class="to-do-list-container">
                <div class="to-do-title" style="margin-top: 40px;">Title of To-Do list</div>
                <div class="to-do-list" style="margin-top: 50px;">
                    <label><input type="checkbox"> <a href="#">To-Do list 1</a></label>
                    <label><input type="checkbox"> <a href="#">To-Do list 2</a></label>
                </div>
            </div>
        </div>
        
        <div class="feed-item">
            <div class="user-info">
                👤 <strong>User H</strong><br>
                Follower: 600  Following: 650
            </div>
            <div class="to-do-list-container">
                <div class="to-do-title" style="margin-top: 40px;">Title of To-Do list</div>
                 <div class="to-do-list" style="margin-top: 50px;">
                    <label><input type="checkbox"> <a href="#">To-Do list 1</a></label>
                    <label><input type="checkbox"> <a href="#">To-Do list 2</a></label>
                </div>
            </div>
        </div>
        
    </div>
</body>
</html>