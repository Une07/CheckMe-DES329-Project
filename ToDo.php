<?php
session_start();

$token = $_SESSION['jwt_token'] ?? '';  // Retrieve JWT token from session

if (!isset($_GET['folder_id']) || !is_numeric($_GET['folder_id'])) {
    echo "❌ Error: Invalid or missing folder ID.";
    exit;
}

$folder_id = intval($_GET['folder_id']);
$folderId = $folder_id;

if (!$token) {
    die("❌ Error: Token is missing. Please log in.");
}

// Set up cURL to fetch folder data
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:5001/api/folder/$folderId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);

if ($response === false) {
    die("❌ cURL error: " . curl_error($ch));
}
curl_close($ch);

$data = json_decode($response, true);

if (isset($data['status']) && $data['status'] === 'success') {
    $folderName = $data['folder']['folder_name'];  // Get folder name
    $todos = $data['todos'];  // Get to-dos
} elseif (isset($data['status']) && $data['status'] === 'error') {
    $folderName = "Folder not found";  // Display message when folder is not found
    $todos = [];
} else {
    $folderName = "Unknown error loading folder";
    $todos = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.min.js"></script>
    <title>ToDo list</title>
    <style>
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
            flex-shrink: 0;
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
            padding: 20px;
            background-color: white;
            overflow-y: auto;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .search-bar {
            width: 93%;
            padding: 15px 20px 15px 60px;
            border-radius: 50px;
            border: none;
            background-color: #333;
            color: white;
            font-size: 18px;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>');
            background-repeat: no-repeat;
            background-position: 20px center;
        }

        .add-button {
            padding: 12px 20px;
            font-size: 20px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
        }

        .card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .card {
            background-color: #333;
            color: white;
            border-radius: 15px;
            padding: 20px;
            position: relative;
            min-height: 120px;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s, opacity 0.2s;

        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .card-icon {
            position: absolute;
            left: 20px;
            top: 20px;
            font-size: 38px;
        }

        .card-count {
            position: absolute;
            right: 25px;
            top: 20px;
            font-size: 40px;
            font-weight: bold;
        }

        .card-title {
            position: absolute;
            left: 20px;
            bottom: 20px;
            font-size: 28px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
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

    <!-- Content -->
    <div class="content">
        <!-- Top bar with search and plus button -->
        <div class="top-bar">
        <input type="text" class="search-bar" id="searchInput" placeholder="Search">
            <button class="add-button" onclick="createNewFolder()">+</button>
        </div>



        <!-- Container for the to-do cards -->
        <div id="todo-folder-grid" class="card-grid">
            <!-- Dynamically loaded to-do folders will appear here -->
        </div>
    </div>

    <!-- Include the JWT Decode library -->
<script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.min.js"></script>

    <script>
        // Function to load folders and to-do tasks
    function loadFolders() {
        const token = localStorage.getItem('token'); // Fetch token from localStorage

        if (token) {
            try {
                const decodedToken = jwt_decode(token); // Decode JWT to get user info
                console.log("Decoded token:", decodedToken); // Log decoded token for debugging

                const username = decodedToken.username;  // Extract the username from the decoded token
                console.log("Username extracted from token:", username);  // Log the username to verify

                // Fetch user ID based on the username
                console.log("Fetching user ID for username:", username);
                fetch(`http://localhost:5001/api/get-user-id?username=${username}`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.user_id) {
                            console.error("Error: User ID not found in response.");
                            alert("Error: Could not fetch user ID.");
                            return;
                        }

                        const userId = data.user_id;  // Get user_id from the server
                        console.log("User ID retrieved:", userId);  // Log the userId to verify

                        // Now fetch folders using userId
                        fetch(`http://localhost:5001/api/todo-folders/${userId}`, {
                            method: "GET",
                            headers: {
                                "Authorization": `Bearer ${token}`, // Send the token in the Authorization header
                                "Content-Type": "application/json"
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            const grid = document.getElementById('todo-folder-grid');
                            if (grid) {
                                grid.innerHTML = ""; // Clear existing content

                                if (data.status === "success") {
                                    data.folders.forEach(folder => {
                                        const card = document.createElement("a");
                                        card.href = `ToDo-Folder.php?folder_id=${folder.folder_id}`; // Link to the folder page
                                        card.classList.add("card");

                                        const cardIcon = document.createElement("div");
                                        cardIcon.classList.add("card-icon");
                                        cardIcon.textContent = "📋";

                                        const cardTitle = document.createElement("div");
                                        cardTitle.classList.add("card-title");
                                        cardTitle.textContent = folder.folder_name;

                                        const cardCount = document.createElement("div");
                                        cardCount.classList.add("card-count");
                                        cardCount.textContent = folder.todo_count;

                                        card.appendChild(cardIcon);
                                        card.appendChild(cardTitle);
                                        card.appendChild(cardCount);
                                        grid.appendChild(card);
                                    });
                                }
                            } else {
                                console.error("todo-folder-grid element not found in DOM.");
                            }
                        })
                        .catch(err => {
                            console.error("Error loading folders: ", err);  // Log any error
                        });
                    })
                    .catch(error => {
                        console.error("Error fetching user ID:", error);
                        alert("Error: Could not fetch user ID.");
                    });

            } catch (error) {
                console.error("Error decoding token: ", error);  // Log the error if token decoding fails
            }
        } else {
            console.log("No token found in localStorage.");
        }
    }
    

        // Function to create a new folder
        function createNewFolder() {
            console.log("createNewFolder function called");

            const token = localStorage.getItem('token');  // Get token from localStorage
            console.log("Token retrieved:", token);

            if (!token) {
                console.log("No token found in localStorage.");
                alert("You must be logged in to create a folder.");
                return;
            }

            const folderName = prompt("Enter the folder name:");  // Prompt for folder name
            console.log("Folder name entered:", folderName);

            if (!folderName) {
                console.log("Folder name is required.");
                alert("Folder name is required.");
                return;
            }

            try {
                const decodedToken = jwt_decode(token);  // Decode JWT to get user info
                console.log("Decoded token:", decodedToken);

                const username = decodedToken.username;  // Extract the username from the decoded token
                console.log("Username extracted from token:", username);

                // Fetch user ID based on the username
                console.log("Fetching user ID for username:", username);

                fetch(`http://localhost:5001/api/get-user-id?username=${username}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log("User ID fetched:", data);
                        if (!data.user_id) {
                            console.error("Error: User ID not found in response.");
                            alert("Error: Could not fetch user ID.");
                            return;
                        }

                        const userId = data.user_id;  // Get user_id from the server
                        console.log("User ID retrieved:", userId);

                        // Sending the API request to create a new folder
                        console.log("Sending request to create folder with name:", folderName, "and user_id:", userId);
                        fetch('http://localhost:5001/api/todo-folders', {
                            method: 'POST',
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ folder_name: folderName, user_id: userId }),  // Include user_id in the request
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log("Folder creation response:", data);
                            if (data.status === 'success') {
                                console.log('Folder created successfully');
                                loadFolders();  // Reload folders after creating a new one
                            } else {
                                console.error('Error creating folder:', data.message);
                                alert(`Error: ${data.message}`);
                            }
                        })
                        .catch(error => {
                            console.error('Error during request:', error);
                            alert("An error occurred while creating the folder.");
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching user ID:', error);
                        alert("Error: Could not fetch user ID.");
                    });
            } catch (error) {
                console.error("Error decoding token: ", error);
                alert("Error: Could not decode token. Please try logging in again.");
            }
        }

        loadFolders();  // Call loadFolders on page load to display the folders
        function handleSearch() {
            const searchValue = document.getElementById("searchInput").value.toLowerCase();
            const cards = document.querySelectorAll("#todo-folder-grid .card");

            cards.forEach(card => {
                const title = card.querySelector(".card-title").textContent.toLowerCase();
                card.style.display = title.includes(searchValue) ? "block" : "none";
            });
        }
        document.getElementById("searchInput").addEventListener("input", handleSearch);
    </script>
</body>
</html>