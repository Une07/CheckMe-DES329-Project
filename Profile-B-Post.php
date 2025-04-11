<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile-B</title>
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
        
        /* Profile container */
        .profile-container {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #f8f8f8;
            position: relative; /* For popup positioning */
        }
        
        /* Profile header */
        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        /* Profile image */
        .profile-image {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background-color: #2b5797;
            overflow: hidden;
            margin-right: 20px;
        }
        
        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Profile info */
        .profile-info {
            flex-grow: 1;
        }
        
        .profile-name {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .profile-stats {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        /* Follow button */
        .follow-button {
            background-color: black;
            color: white;
            border: none;
            padding: 12px 40px;
            font-size: 20px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: bold;
        }
        
        /* More options button */
        .more-options {
            font-size: 40px;
            margin-left: 15px;
            font-weight: bold;
            cursor: pointer;
        }
        
        /* Profile title */
        .profile-title {
            font-size: 28px;
            font-weight: bold;
            margin: 30px 0;
        }
        
        /* Tabs */
        .tab-container {
            position: relative;
            margin-top: 60px;
        }
        
        .horizontal-line {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background-color: black;
        }
        
        .tab-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
        }
        
        .tab-button {
            background-color: black;
            color: white;
            border: none;
            padding: 12px 60px;
            font-size: 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
        }
        
        /* Private account message */
        .private-account {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 60px;
            text-align: center;
        }
        
        .lock-icon {
            font-size: 100px;
            margin-bottom: 20px;
        }
        
        .private-text {
            font-size: 24px;
            font-weight: bold;
        }
        
        /* Popup menu */
        .popup-menu {
            display: none;
            position: absolute;
            top: 100px;
            right: 50px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 250px;
            z-index: 1000;
        }
        
        .popup-menu-item {
            padding: 15px 20px;
            font-size: 18px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }
        
        .popup-menu-item:last-child {
            border-bottom: none;
        }
        
        .popup-menu-item:hover {
            background-color: #f5f5f5;
        }
        
        /* Overlay for popup */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 900;

        }

        .following {
            background-color: white !important;
            color: black !important;
            border: 1px solid black;
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
    
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-image">
                <img src="./image/profile.png" alt="Profile Picture">
            </div>
            
            <div class="profile-info">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div class="profile-name">User B</div>
                    <div style="display: flex; align-items: center;">
                        <button class="follow-button">Follow</button>
                        <div class="more-options" id="menu-button">⋮</div>
                    </div>
                </div>
                
                <div class="profile-stats">Follower: 600</div>
                <div class="profile-stats">Following: 650</div>
                <div class="profile-stats">Post: 0</div>
                <div class="profile-stats">To-Do: 2</div>
            </div>
        </div>
        
        <div class="profile-title">Project yang yer ah kub</div>
        
        <div class="tab-container">
            <div class="horizontal-line"></div>
            <div class="horizontal-line" style="bottom: 0; top: auto;"></div>
            
            <div class="tab-buttons">
                <button class="tab-button">Post</button>
                <button class="tab-button">To-Do</button>
            </div>
        </div>
        
        <div class="private-account">
            <div class="lock-icon">🔒</div>
            <div class="private-text">This account is private</div>
            <div class="private-text">Follow to see their photos and videos</div>
        </div>
        
        <!-- Popup menu -->
        <div class="overlay" id="overlay"></div>
        <div class="popup-menu" id="popup-menu">
            <div class="popup-menu-item">Block</div>
            <div class="popup-menu-item">Restrict</div>
            <div class="popup-menu-item">Report</div>
            <div class="popup-menu-item">Share</div>
            <div class="popup-menu-item">About</div>
            <div class="popup-menu-item" id="cancel-button">Cancel</div>
        </div>
    </div>
    
    <script>
        //following logic
        const followButton = document.querySelector('.follow-button');

        followButton.addEventListener('click', () => {
            const isFollowing = followButton.classList.contains('following');

            if (isFollowing) {
                followButton.textContent = 'Follow';
                followButton.classList.remove('following');
            } else {
                followButton.textContent = 'Following';
                followButton.classList.add('following');
            }
        });

        // Get elements
        const menuButton = document.getElementById('menu-button');
        const popupMenu = document.getElementById('popup-menu');
        const overlay = document.getElementById('overlay');
        const cancelButton = document.getElementById('cancel-button');
        
        // Show popup menu when clicking the three dots
        menuButton.addEventListener('click', () => {
            popupMenu.style.display = 'block';
            overlay.style.display = 'block';
        });
        
        // Hide popup menu when clicking cancel
        cancelButton.addEventListener('click', () => {
            popupMenu.style.display = 'none';
            overlay.style.display = 'none';
        });
        
        // Hide popup menu when clicking outside
        overlay.addEventListener('click', () => {
            popupMenu.style.display = 'none';
            overlay.style.display = 'none';
        });
        
        // Hide popup menu when clicking on any menu item
        const menuItems = document.querySelectorAll('.popup-menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                if (item.id !== 'cancel-button') {
                    alert('You selected: ' + item.textContent);
                }
                popupMenu.style.display = 'none';
                overlay.style.display = 'none';
            });
        });
    </script>
</body>
</html>