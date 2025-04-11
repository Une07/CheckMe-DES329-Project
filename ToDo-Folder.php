<?php
session_start();

// Get JWT token from session or localStorage
$token = $_SESSION['jwt_token'] ?? '';
if (!$token) {
    // Try to get it from JavaScript localStorage via cookie
    $token = $_COOKIE['jwt_token'] ?? '';
}

// Check for folder_id
if (!isset($_GET['folder_id']) || !is_numeric($_GET['folder_id'])) {
    echo "❌ Error: Invalid or missing folder ID.";
    exit;
}

$folder_id = intval($_GET['folder_id']);



// Fetch folder data
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:5001/api/folder/$folder_id");
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
    $folderName = $data['folder']['folder_name'];
    $todos = $data['todos'];
} else {
    $folderName = "Error loading folder";
    $todos = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($folderName) ?> - To-Do List</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
        }

        .add-button {
            width: 40px;
            height: 40px;
            background-color: #000;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }

        .todo-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border: 1px solid #eaeaea;
            padding: 15px;
            border-radius: 8px;
        }

        .checkbox-container {
            display: inline-block;
            height: 50px;
            width: 50px;
            position: relative;
        }
        .checkbox-container input {
            position: absolute;
            opacity: 0;
            height: 100%;
            width: 100%;
            cursor: pointer;
            z-index: 2;
            margin: 0;
        }

        .checkmark {
            position: relative;
            display: block;
            height: 50px;
            width: 50px;
            border: 2px solid #000;
            border-radius: 50%;
        }

        .checkbox-container input:checked ~ .checkmark:after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 45px;
            height: 45px;
            background-color: black;
            border-radius: 50%;
        }

        .project-details {
            flex: 1;
        }

        .project-title, .project-description {
            border: none;
            background: transparent;
            font-family: inherit;
            padding: 5px;
            border-radius: 3px;
            width: 100%;
        }

        .project-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-left: 30px;
        }

        .project-title:focus, .project-description:focus {
            outline: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .project-description {
            font-size: 1.25rem;
            color: #333;
            margin-left: 30px;
        }

        .due-date {
            font-size: 1.25rem;
            font-weight: normal;
            margin-left: 15px;
            white-space: nowrap;
        }

        .due-date input {
            border: none;
            background: transparent;
            font-family: inherit;
            font-size: inherit;
            width: 120px;
            text-align: right;
            padding: 5px;
            border-radius: 3px;
        }

        .due-date input:focus {
            outline: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        hr {
            border: none;
            border-top: 1px solid #eaeaea;
            margin: 0 0 30px 0;
        }

        .completed-projects {
            margin-top: 40px;
        }

        .completed-projects-header {
            font-size: 1.5rem;
            margin-bottom: 20px;
            font-weight: bold;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .close-modal {
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .button {
            background-color: black;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .delete-button {
            background-color: transparent;
            border: none;
            color: #ff3b30;
            cursor: pointer;
            font-size: 16px;
            padding: 5px;
            margin-left: 10px;
        }

        .go-back {
            position: fixed;
            bottom: 20px;
            left: 20px;
            font-size: 1rem;
            text-decoration: none;
            color: white;
            background-color: #1f1f1f;
            padding: 10px 20px;
            border-radius: 8px;
        }
        
        .folder-nav {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .folder-link {
            text-decoration: none;
            color: #333;
            padding: 8px 15px;
            border-radius: 20px;
            white-space: nowrap;
            background-color: #f0f0f0;
        }
        
        .folder-link.active {
            background-color: #000;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Folder navigation -->
    <div class="folder-nav" id="folder-navigation">
        <!-- Folders will be loaded here -->
    </div>
    
    <header>
        <h1><?= htmlspecialchars($folderName) ?></h1>
        <div class="add-button" onclick="openAddModal()">+</div>
    </header>

    <div id="pending-todos" class="project-list">
        <!-- Pending todos will be loaded here -->
    </div>

    <div class="completed-projects">
        <div class="completed-projects-header">Completed Projects</div>
        <div id="completed-projects" class="project-list">
            <!-- Completed todos will be loaded here -->
        </div>
    </div>

    <div id="add-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Add New To-Do</div>
                <span class="close-modal" onclick="closeAddModal()">&times;</span>
            </div>
            <form id="add-todo-form" onsubmit="addNewTodo(event)">
                <div class="form-group">
                    <label for="new-todo-title">To-Do Title</label>
                    <input type="text" id="new-todo-title" placeholder="Enter to-do title" required>
                </div>
                <div class="form-group">
                    <label for="new-todo-description">Description</label>
                    <input type="text" id="new-todo-description" placeholder="Enter description">
                </div>
                <div class="form-group">
                    <label for="new-todo-due-date">Due Date</label>
                    <input type="date" id="new-todo-due-date" placeholder="YYYY-MM-DD">
                </div>
                <button type="submit" class="button">Add To-Do</button>
            </form>
        </div>
    </div>
    
    <a href="ToDo.php?folder_id=<?= $folder_id ?>" class="go-back">Go Back</a>
    <script>
        // Get folder ID from URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const folderId = urlParams.get("folder_id");

        if (!folderId) {
            alert("Folder ID is missing!");
            window.location.href = "ToDo.php";
        }

        // First, save token to localStorage if it's not there yet
        if (!localStorage.getItem('token')) {
            // Try to get it from PHP $_SESSION via cookie
            document.cookie.split(';').forEach(cookie => {
                const [name, value] = cookie.trim().split('=');
                if (name === 'jwt_token') {
                    localStorage.setItem('token', value);
                }
            });
        }

        // Load folders for navigation
        async function loadFolders() {
            const token = localStorage.getItem('token');
            if (!token) {
                console.error('No token found in localStorage.');
                return;
            }
            
            try {
                const decodedToken = JSON.parse(atob(token.split('.')[1]));
                const username = decodedToken.username;
                
                const userResponse = await fetch(`http://localhost:5001/api/get-user-id?username=${username}`);
                const userData = await userResponse.json();
                
                if (!userData.user_id) {
                    console.error("Error: User ID not found in response.");
                    return;
                }
                
                const userId = userData.user_id;
                
                const foldersResponse = await fetch(`http://localhost:5001/api/todo-folders/${userId}`, {
                    headers: {
                        "Authorization": `Bearer ${token}`,
                    }
                });
                
                const foldersData = await foldersResponse.json();
                const folderNav = document.getElementById('folder-navigation');
                
                if (foldersData.status === "success") {
                    folderNav.innerHTML = "";
                    
                    foldersData.folders.forEach(folder => {
                        const folderLink = document.createElement('a');
                        folderLink.href = `ToDo-Folder.php?folder_id=${folder.folder_id}`;
                        folderLink.classList.add('folder-link');
                        if (folder.folder_id == folderId) {
                            folderLink.classList.add('active');
                        }
                        folderLink.textContent = folder.folder_name;
                        folderNav.appendChild(folderLink);
                    });
                }
            } catch (error) {
                console.error("Error loading folders:", error);
            }
        }

        async function fetchTodoItems() {
            try {
                const token = localStorage.getItem('token');
                if (!token) {
                    console.error('No token found in localStorage.');
                    return;
                }

                const response = await fetch(`http://localhost:5001/api/folder/${folderId}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Fetched data:', data);

                if (data.status === 'success') {
                    const pendingContainer = document.getElementById('pending-todos');
                    const completedContainer = document.getElementById('completed-projects');

                    pendingContainer.innerHTML = '';
                    completedContainer.innerHTML = '';

                    if (data.todos && data.todos.length > 0) {
                        data.todos.forEach(todo => {
                            const title = todo.title || '';
                            const description = todo.description || '';
                            const dueDate = todo.due_date ? new Date(todo.due_date).toISOString().split('T')[0] : '';

                            const todoElement = document.createElement('div');
                            todoElement.classList.add('todo-item');

                            todoElement.innerHTML = `
                                <div class="checkbox-container">
                                    <input type="checkbox" class="todo-checkbox" data-item-id="${todo.item_id}" ${todo.is_completed ? 'checked' : ''}>
                                    <span class="checkmark"></span>
                                </div>
                                <div class="project-details">
                                    <input type="text" class="project-title" value="${title}" readonly>
                                    <input type="text" class="project-description" value="${description}" readonly>
                                </div>
                                <div class="due-date">
                                    <input type="date" class="due-date-input" value="${dueDate}" readonly>
                                </div>
                                <button class="delete-button" onclick="deleteTodoItem(${todo.item_id})">Delete</button>
                            `;

                            if (todo.is_completed) {
                                completedContainer.appendChild(todoElement);
                            } else {
                                pendingContainer.appendChild(todoElement);
                            }
                        });

                        // Attach event listeners to checkboxes
                        document.querySelectorAll('.todo-checkbox').forEach(checkbox => {
                            checkbox.addEventListener('change', (event) => {
                                const itemId = event.target.dataset.itemId;
                                toggleCompletion(event, itemId);
                            });
                        });
                    } else {
                        pendingContainer.innerHTML = '<p>No to-do items available.</p>';
                    }
                } else {
                    console.error('Failed to fetch to-do items:', data.message);
                }
            } catch (error) {
                console.error('Error fetching to-do items:', error);
            }
        }

        async function toggleCompletion(event, itemId) {
            const isChecked = event.target.checked;

            try {
                const token = localStorage.getItem('token');
                const response = await fetch(`http://localhost:5001/api/todos/${itemId}/toggle`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                    body: JSON.stringify({ is_completed: isChecked }),
                });

                if (response.ok) {
                    console.log("To-do status updated successfully.");
                    fetchTodoItems();  // Reload the to-do items
                } else {
                    console.error('Failed to update to-do item:', response.statusText);
                }
            } catch (error) {
                console.error('Error updating to-do item:', error);
            }
        }

        // Delete a to-do item
        function deleteTodoItem(todoId) {
            const isConfirmed = window.confirm("Are you sure you want to delete this to-do item?");
            if (!isConfirmed) return;

            const token = localStorage.getItem("token");

            fetch(`http://localhost:5001/api/todos/${todoId}/delete`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log("Deleted:", data);
                fetchTodoItems();  // Reload the to-dos after deleting
            })
            .catch(error => console.error('Error:', error));
        }

        // Open the add modal
        function openAddModal() {
            document.getElementById('add-modal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('add-modal').style.display = 'none';
        }

        async function addNewTodo(event) {
            event.preventDefault();

            const title = document.getElementById('new-todo-title').value;
            const description = document.getElementById('new-todo-description').value;
            let dueDate = document.getElementById('new-todo-due-date').value;
            if (!dueDate) dueDate = null;

            const folderId = new URLSearchParams(window.location.search).get('folder_id');
            const newTodo = {
                title,
                description,
                due_date: dueDate,
                folder_id: folderId
            };

            const token = localStorage.getItem('token');
            try {
                const response = await fetch(`http://localhost:5001/api/addnewtodo`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(newTodo),
                });

                if (response.ok) {
                    document.getElementById('add-todo-form').reset();
                    closeAddModal();
                    fetchTodoItems(); // ⬅ reload items
                } else {
                    const data = await response.json();
                    alert(`Failed to add: ${data.message}`);
                }
            } catch (error) {
                console.error('Error adding to-do:', error);
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('add-modal');
            if (event.target === modal) {
                closeAddModal();
            }
        }

        // Handle JWT token from PHP to JavaScript
        function storeToken() {
            <?php if (!empty($token)): ?>
            if (!localStorage.getItem('token')) {
                localStorage.setItem('token', '<?= $token ?>');
            }
            <?php endif; ?>
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            storeToken();
            loadFolders();
            fetchTodoItems();
        });
    </script>
</body>
</html>
