<?php
session_start();
$token = $_SESSION['jwt_token'] ?? '';

if (!$token) {
    header("Location: Login.php");
    exit;
}

// Decode JWT
$tokenParts = explode('.', $token);
$payload = json_decode(base64_decode($tokenParts[1]));
$user_id = $payload->user_id ?? null;

if (!$user_id) {
    echo "❌ Invalid token.";
    exit;
}

// Fetch user settings from API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:5001/api/user/settings");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// Default values if the data is missing
$username = isset($data['username']) ? htmlspecialchars($data['username']) : '';
$bio = isset($data['bio']) ? htmlspecialchars($data['bio']) : '';
$notify_likes = isset($data['notify_likes']) ? $data['notify_likes'] : 0;
$notify_comment = isset($data['notify_comment']) ? $data['notify_comment'] : 0;
$notify_follow = isset($data['notify_follow']) ? $data['notify_follow'] : 0;
$is_private = isset($data['is_private']) ? $data['is_private'] : 0;
$language = isset($data['language']) ? $data['language'] : 'en';
$blocked_users = isset($data['blocked_users']) && is_array($data['blocked_users']) ? $data['blocked_users'] : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
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
        }

        .setting-section {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
        }

        .setting-section h2 {
            margin-top: 0;
        }

        .setting-option {
            margin-bottom: 10px;
        }

        .setting-option label {
            display: block;
            margin-bottom: 5px;
        }

        .setting-option input[type="text"],
        .setting-option select,
        .setting-option textarea,
        .setting-option input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .setting-option input[type="checkbox"],
        .setting-option input[type="radio"] {
            margin-right: 5px;
        }

        .setting-option button {
            background-color: black;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .setting-option button:hover {
            background-color: #333;
        }

        .blocked-account-list {
            list-style-type: none;
            padding: 0;
        }

        .blocked-account-list li {
            display: flex;
            justify-content: space-between;
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .blocked-account-list li button {
            background-color: white;
            color: black;
            border: 1px solid black;
        }

        .blocked-account-list li button:hover {
            background-color: #eee;
        }
        </style>
</head>
<body>
<div class="sidebar">
    <a href="FeedPage-following.php"><img src="./image/logo.png" alt="CHECKME Logo" style="width: 200px; margin-bottom: -20px;"></a>
    <a href="FeedPage-foryou.php">🏠 Home</a>
    <a href="ToDo.php">➕ TO-DO</a>
    <a href="Post.php">➕ Post</a>
    <a href="Profile-Post.php">👤 Profile</a>
    <a href="Saved.php">🔖 Saved To-Do</a>
    <a href="Setting.php">⚙️ Setting</a>
    <a href="Login.php">🔄 Logout</a>
</div>
<div class="content">
    <h1>Settings</h1>
    <div class="setting-section">
        <h2>Edit Profile</h2>
        <div class="setting-option">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= $username ?>">
        </div>
        <div class="setting-option">
            <label for="bio">Bio:</label>
            <textarea id="bio" name="bio"><?= $bio ?></textarea>
        </div>
        <div class="setting-option">
            <button onclick="saveProfile()">Save Changes</button>
        </div>
    </div>

    <div class="setting-section">
        <h2>Notification Settings</h2>
        <div class="setting-option">
            <label><input type="checkbox" id="likes" <?= $notify_likes ? 'checked' : '' ?>> Likes</label>
            <label><input type="checkbox" id="comments" <?= $notify_comment ? 'checked' : '' ?>> Comments</label>
            <label><input type="checkbox" id="follows" <?= $notify_follow ? 'checked' : '' ?>> Follows</label>
        </div>
        <div class="setting-option">
            <button onclick="updateNotifications()">Update Notifications</button>
        </div>
    </div>

    <div class="setting-section">
        <h2>Account Privacy</h2>
        <div class="setting-option">
            <label><input type="radio" name="privacy" value="0" <?= $is_private == 0 ? 'checked' : '' ?>> Public</label>
            <label><input type="radio" name="privacy" value="1" <?= $is_private == 1 ? 'checked' : '' ?>> Private</label>
        </div>
        <div class="setting-option">
            <button onclick="updatePrivacy()">Update Privacy</button>
        </div>
    </div>

    <div class="setting-section">
        <h2>Language</h2>
        <div class="setting-option">
            <select id="language">
                <option value="en" <?= $language === 'en' ? 'selected' : '' ?>>English</option>
                <option value="es" <?= $language === 'es' ? 'selected' : '' ?>>Spanish</option>
                <option value="fr" <?= $language === 'fr' ? 'selected' : '' ?>>French</option>
                <option value="zh" <?= $language === 'zh' ? 'selected' : '' ?>>Chinese</option>
            </select>
        </div>
        <div class="setting-option">
            <button onclick="updateLanguage()">Save Language</button>
        </div>
    </div>

    <div class="setting-section">
        <h2>Blocked Accounts</h2>
        <ul class="blocked-account-list">
            <?php foreach ($blocked_users as $blocked_user): ?>
                <li>
                    <?= $blocked_user['username'] ?>
                    <button onclick="unblockUser(<?= $blocked_user['user_id'] ?>)">Unblock</button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<script>
    // Get token from PHP session or localStorage
    const token = localStorage.getItem("token") || "<?php echo $token; ?>";

    // Add token validation
    function isTokenValid(token) {
        try {
            const payload = JSON.parse(atob(token.split('.')[1]));
            const now = Math.floor(Date.now() / 1000);
            return payload.exp > now;
        } catch (e) {
            return false;
        }
    }

    if (!token || !isTokenValid(token)) {
        console.log("Token invalid or expired, redirecting to login");
        window.location.href = "Login.php";
        throw new Error("Invalid token");
    }
    
    // Profile update function
    async function saveProfile() {
        const username = document.getElementById("username").value;
        const bio = document.getElementById("bio").value;

        try {
            const res = await fetch("http://localhost:5001/api/user/profile", {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`
                },
                body: JSON.stringify({ username, bio })
            });

            if (!res.ok) {
                const errorText = await res.text();
                throw new Error(errorText);
            }

            const data = await res.json();
            console.log("Profile update response:", data);
            alert(data.message || "Profile updated successfully");
        } catch (error) {
            console.error("Error updating profile:", error);
            alert("Failed to update profile: " + error.message);
        }
    }

    // Update notifications
    async function updateNotifications() {
        const notify_likes = document.getElementById("likes").checked;
        const notify_comment = document.getElementById("comments").checked;
        const notify_follow = document.getElementById("follows").checked;

        try {
            const res = await fetch("http://localhost:5001/api/user/notifications", {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`
                },
                body: JSON.stringify({ notify_likes, notify_comment, notify_follow })
            });

            if (!res.ok) {
                const errorText = await res.text();
                throw new Error(errorText);
            }

            const data = await res.json();
            alert("Notification settings updated successfully");
        } catch (error) {
            console.error("Error updating notifications:", error);
            alert("Failed to update notifications: " + error.message);
        }
    }

    // Update privacy settings
    async function updatePrivacy() {
        const privacyRadio = document.querySelector("input[name='privacy']:checked");
        if (!privacyRadio) {
            alert("Please select a privacy option");
            return;
        }
        
        const value = privacyRadio.value;

        try {
            const res = await fetch("http://localhost:5001/api/user/privacy", {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`
                },
                body: JSON.stringify({ is_private: parseInt(value) })
            });

            if (!res.ok) {
                const errorText = await res.text();
                throw new Error(errorText);
            }

            const data = await res.json();
            alert("Privacy settings updated successfully");
        } catch (error) {
            console.error("Error updating privacy:", error);
            alert("Failed to update privacy: " + error.message);
        }
    }

    // Update language preference
    async function updateLanguage() {
        const language = document.getElementById("language").value;

        try {
            const res = await fetch("http://localhost:5001/api/user/language", {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`
                },
                body: JSON.stringify({ language })
            });

            if (!res.ok) {
                const errorText = await res.text();
                throw new Error(errorText);
            }

            const data = await res.json();
            alert("Language preference updated successfully");
        } catch (error) {
            console.error("Error updating language:", error);
            alert("Failed to update language: " + error.message);
        }
    }

    // Unblock user
    async function unblockUser(userId) {
        if (!confirm("Are you sure you want to unblock this user?")) {
            return;
        }

        try {
            const res = await fetch("http://localhost:5001/api/user/block", {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`
                },
                body: JSON.stringify({ blocked_id: userId })
            });

            if (!res.ok) {
                const errorText = await res.text();
                throw new Error(errorText);
            }

            const data = await res.json();
            alert("User unblocked successfully");
            
            // Reload settings to update the blocked users list
            loadSettings();
        } catch (error) {
            console.error("Error unblocking user:", error);
            alert("Failed to unblock user: " + error.message);
        }
    }

    // Load settings function
    async function loadSettings() {
        console.log("🔁 loadSettings function called");
        try {
            // Get token from localStorage or PHP session
            const token = localStorage.getItem('token') || '<?php echo $token; ?>';
            
            if (!token) {
                window.location.href = "Login.php";
                return;
            }

            // Fetch user settings
            const response = await fetch("http://localhost:5001/api/user/settings", {
                method: "GET",
                headers: {
                    "Authorization": `Bearer ${token}`,
                    // "Content-Type": "application/json"
                }
            });
            console.log("Making fetch call to:", "http://localhost:5001/api/user/settings");
            // Handle response
            if (!response.ok) {
                const errorData = await response.json();
                
                // Special handling for missing user
                if (response.status === 404 && errorData.message === "User not found") {
                    localStorage.removeItem("token");
                    alert("Your account appears to be missing. Please contact support.");
                    window.location.href = "Register.php";
                    return;
                }
                
                throw new Error(errorData.message || "Failed to load settings");
            }

            const data = await response.json();
            console.log("Settings data:", data);

            // Update form fields
            document.getElementById("username").value = data.username || "";
            document.getElementById("bio").value = data.bio || "";
            
            // Update notification toggles
            document.getElementById("likes").checked = Boolean(data.notify_likes);
            document.getElementById("comments").checked = Boolean(data.notify_comment);
            document.getElementById("follows").checked = Boolean(data.notify_follow);
            
            // Update privacy setting
            const privacyValue = data.is_private ? "1" : "0";
            document.querySelector(`input[name="privacy"][value="${privacyValue}"]`).checked = true;
            
            // Update language
            document.getElementById("language").value = data.language || "en";
            
            // Update blocked users list
            const blockedList = document.querySelector('.blocked-account-list');
            blockedList.innerHTML = (data.blocked_users || []).map(user => `
                <li>
                    ${user.username}
                    <button onclick="unblockUser(${user.user_id})">Unblock</button>
                </li>
            `).join('');


            } catch (error) {
            // console.error("❌ Error loading settings:", error);
            // alert("Error loading settings: 1" + error.message);

            // 🔐 Clear token if it's invalid or expired
            if (
                error.message.toLowerCase().includes("token") ||
                error.message.toLowerCase().includes("unauthorized") ||
                error.message.toLowerCase().includes("jwt expired")
            ) {
                localStorage.removeItem("token");
                window.location.href = "Login.php";
            }
        }
    }

    // Load settings when the page loads
    window.addEventListener("load", loadSettings);
</script>
</body>
</html>