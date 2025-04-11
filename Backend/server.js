const express = require("express");
const bcrypt = require("bcryptjs");
const cors = require("cors");
const bodyParser = require("body-parser");
const jwt = require("jsonwebtoken");
const mysql = require("mysql2");
const path = require("path");
const exec = require("child_process").exec;

const app = express();
app.use(express.json());

// Create connection pool
const db = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: 'root',
    port: 8889,
    database: 'Checkme_Des329'
}).promise();


app.use((req, res, next) => {
    console.log("🔥 Incoming request:", req.method, req.url);
    next();
});
app.use((req, res, next) => {
    console.log(`${req.method} ${req.originalUrl}`);
    next();
});

// Middleware setup
app.use(cors({
    origin: "http://localhost:3000",
    allowedHeaders: ["Content-Type", "Authorization"],
    methods: ["GET", "POST", "PUT", "DELETE", "OPTIONS"], // Add OPTIONS here
    credentials: true
}));
app.use(bodyParser.json());

// Static file serving for PHP files (ensure PHP processing is set up)
app.use('/PHPCodesForEachPages', express.static(path.join(__dirname, '../PHPCodesForEachPages')));

// Redirect from ToDo-FolderPage.php to ToDo-Folder.php with query parameters intact
app.get('/PHPCodesForEachPages/ToDo-FolderPage.php', (req, res) => {
    const queryString = Object.keys(req.query).length ? '?' + new URLSearchParams(req.query).toString() : '';
    res.redirect(`/PHPCodesForEachPages/ToDo-Folder.php${queryString}`);
});

// Serve PHP file (You will need PHP-FPM or a similar setup for actual PHP execution)
app.get('/PHPCodesForEachPages/ToDo-Folder.php', (req, res) => {
    res.sendFile(path.join(__dirname, '../PHPCodesForEachPages/ToDo-Folder.php'));
});

// Middleware to verify JWT token
// In your server.js, update verifyToken middleware:

// Updated verifyToken middleware with better error handling
function verifyToken(req, res, next) {
    const authHeader = req.headers["authorization"];
    const token = authHeader && authHeader.split(" ")[1];

    if (!token) {
        console.log("⚠️ Token missing in request to1:", req.originalUrl);
        return res.status(401).json({ status: "error", message: "Token missing" });
    }

    console.log("🔑 Token received:", token);  // Log the token here

    jwt.verify(token, "your_secret_key", (err, decoded) => {
        if (err) {
            console.error("❌ Token verification failed:", err.message);
            return res.status(403).json({ status: "error", message: "Invalid or expired token" });
        }

        console.log("✅ Token verified. Decoded payload:", decoded);  // Log decoded token

        req.user = decoded;
        next();
    });
}
// Helper function to get user ID by username
async function getUserByUsername(username) {
    try {
        const [results] = await db.query("SELECT user_id FROM users WHERE username = ?", [username]);
        return results.length > 0 ? results[0] : null;
    } catch (err) {
        throw new Error(err.message);
    }
}

// API Endpoints

// Get user ID by username
app.get('/api/get-user-id', async (req, res) => {
    const { username } = req.query;
    if (!username) return res.status(400).json({ error: "Username is required" });

    try {
        const user = await getUserByUsername(username);
        if (!user) return res.status(404).json({ error: "User not found" });
        res.json({ user_id: user.user_id });
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

// User Signup
app.post("/signup", async (req, res) => {
    const { username, password, confirmpassword } = req.body;
    if (!username || !password || !confirmpassword) return res.status(400).json({ status: "error", message: "All fields are required" });
    if (password !== confirmpassword) return res.status(400).json({ status: "error", message: "Passwords do not match" });

    try {
        const hashedPassword = await bcrypt.hash(password, 10);
        const query = `INSERT INTO users (username, password, bio, follower_count, following_count, todo_post_count, is_private, language, notify_comment, notify_follow, notify_likes) VALUES (?, ?, '', 0, 0, 0, 0, 'en', 1, 1, 1)`;
        await db.query(query, [username, hashedPassword]);
        res.json({ status: "success", message: "User registered successfully" });
    } catch (err) {
        if (err.code === "ER_DUP_ENTRY") return res.status(409).json({ status: "error", message: "Username already exists" });
        res.status(500).json({ status: "error", message: "Server error" });
    }
});

// User Login

app.post("/login", async (req, res) => {
    const { username, password } = req.body;

    try {
        const [users] = await db.query("SELECT * FROM users WHERE username = ?", [username]);
        
        if (users.length === 0) {
            return res.status(401).json({ status: "error", message: "Invalid credentials" });
        }

        const user = users[0];
        const validPassword = await bcrypt.compare(password, user.password);
        
        if (!validPassword) {
            return res.status(401).json({ status: "error", message: "Invalid credentials" });
        }

        // Verify user exists in database before token generation
        const [userCheck] = await db.query("SELECT user_id FROM users WHERE user_id = ?", [user.user_id]);
        if (userCheck.length === 0) {
            return res.status(500).json({ status: "error", message: "User data inconsistency" });
        }

        const token = jwt.sign(
            { user_id: user.user_id, username: user.username },
            "your_secret_key",
            { expiresIn: "24h" }
        );

        res.json({ status: "success", token });
        
    } catch (err) {
        res.status(500).json({ status: "error", message: "Database error" });
    }
});
// app.post("/login", async (req, res) => {
//     const { username, password } = req.body;
//     if (!username || !password) return res.status(400).json({ status: "error", message: "Username and password are required" });

//     try {
//         const [results] = await db.query("SELECT * FROM users WHERE username = ?", [username]);
//         if (results.length === 0) return res.status(401).json({ status: "error", message: "Invalid username or password" });

//         const user = results[0];
//         const isMatch = await bcrypt.compare(password, user.password);
//         if (!isMatch) return res.status(401).json({ status: "error", message: "Invalid username or password" });

//         const token = jwt.sign({ user_id: user.user_id, username: user.username }, "your_secret_key", { expiresIn: "1h" });
//         res.json({ status: "success", message: "Login successful", token });
//     } catch (err) {
//         res.status(500).json({ status: "error", message: "Database error" });
//     }
// });

// Get Todo Folders
app.get("/api/todo-folders/:userId", verifyToken, async (req, res) => {
    const userId = req.params.userId;
    if (!userId || isNaN(userId)) return res.status(400).json({ status: "error", message: "Invalid user ID" });

    try {
        const query = `SELECT tf.folder_id, tf.folder_name, COUNT(ti.item_id) AS todo_count FROM todo_list_folders tf LEFT JOIN todo_items ti ON tf.folder_id = ti.folder_id WHERE tf.user_id = ? GROUP BY tf.folder_id, tf.folder_name`;
        const [results] = await db.query(query, [userId]);
        res.json({ status: "success", folders: results });
    } catch (err) {
        res.status(500).json({ status: "error", message: "Database query failed" });
    }
});

// Get Folder and Todos
app.get("/api/folder/:folderId", verifyToken, async (req, res) => {
    const folderId = req.params.folderId;
    if (!folderId || isNaN(folderId)) return res.status(400).json({ status: "error", message: "Invalid folder ID" });

    try {
        const [folderData] = await db.query("SELECT * FROM todo_list_folders WHERE folder_id = ?", [folderId]);
        if (folderData.length === 0) return res.status(404).json({ status: "error", message: "Folder not found" });

        const [todosData] = await db.query("SELECT * FROM todo_items WHERE folder_id = ?", [folderId]);
        res.json({ status: "success", folder: folderData[0], todos: todosData || [] });
    } catch (error) {
        res.status(500).json({ status: "error", message: "Internal Server Error", error: error.message });
    }
});

// Add New Folder
app.post("/api/todo-folders", verifyToken, async (req, res) => {
    const { folder_name, user_id } = req.body;
    if (!user_id || isNaN(user_id)) return res.status(400).json({ status: 'error', message: 'Invalid user ID' });
    if (!folder_name || typeof folder_name !== 'string' || folder_name.trim() === '') return res.status(400).json({ status: 'error', message: 'Folder name is required' });

    try {
        const [result] = await db.query('INSERT INTO todo_list_folders (folder_name, user_id) VALUES (?, ?)', [folder_name.trim(), user_id]);
        res.json({ status: 'success', folder_id: result.insertId });
    } catch (err) {
        res.status(500).json({ status: 'error', message: 'Database error' });
    }
});


app.put("/api/todos/:itemId/toggle", verifyToken, async (req, res) => {
    const { itemId } = req.params;
    const { is_completed } = req.body;

    if (!itemId || typeof is_completed !== 'boolean') {
        return res.status(400).json({ status: "error", message: "Invalid input" });
    }

    try {
        await db.query("UPDATE todo_items SET is_completed = ? WHERE item_id = ?", [is_completed, itemId]);
        res.json({ status: "success", message: "To-do status updated" });
    } catch (err) {
        res.status(500).json({ status: "error", message: "Failed to update to-do item" });
    }
});

app.delete("/api/todos/:id/delete", verifyToken, async (req, res) => {
    const todoId = req.params.id;
    if (!todoId || isNaN(todoId)) return res.status(400).json({ error: 'Invalid to-do item ID' });

    try {
        const [results] = await db.query('DELETE FROM todo_items WHERE item_id = ?', [todoId]);
        if (results.affectedRows === 0) return res.status(404).json({ error: 'To-do item not found' });

        res.json({ message: 'To-do item deleted successfully' });
    } catch (error) {
        res.status(500).json({ error: 'Failed to delete to-do item' });
    }
});

app.post("/api/addnewtodo", verifyToken, async (req, res) => {
    const { folder_id, title, description, due_date } = req.body;

    if (!folder_id || !title) {
        return res.status(400).json({ status: "error", message: "Folder ID and title are required" });
    }

    try {
        const [result] = await db.query(
            "INSERT INTO todo_items (folder_id, title, description, due_date, is_completed) VALUES (?, ?, ?, ?, false)",
            [folder_id, title, description || '', due_date]
        );
        res.json({ status: "success", item_id: result.insertId });
    } catch (err) {
        res.status(500).json({ status: "error", message: "Failed to create to-do item" });
    }
});


// In your server.js, update the user endpoint to include counts:
app.get('/api/user/:userId', verifyToken, async (req, res) => {
    const userId = req.params.userId;

    try {
        const [rows] = await db.query(`
            SELECT 
                u.username,
                u.bio,
                u.follower_count,
                u.following_count,
                u.todo_post_count,
                (SELECT COUNT(*) FROM todo_list_folders WHERE user_id = ?) AS todo_folder_count,
                (SELECT COUNT(*) FROM todo_items ti 
                 JOIN todo_list_folders tlf ON ti.folder_id = tlf.folder_id 
                 WHERE tlf.user_id = ?) AS total_todo_items
            FROM users u
            WHERE u.user_id = ?`, 
            [userId, userId, userId]
        );

        const user = rows[0];
        if (!user) {
            return res.status(404).json({ error: "User not found" });
        }




        res.json({
            username: user.username,
            bio: user.bio,
            follower_count: user.follower_count,
            following_count: user.following_count,
            todo_post_count: user.todo_post_count,
            is_private: user.is_private,
            language: user.language,
            notify_comment: user.notify_comment,
            notify_follow: user.notify_follow,
            notify_likes: user.notify_likes
        });
    } catch (error) {
        console.error("Error fetching user:", error);
        res.status(500).json({ error: "Database error" });
    }
});

app.get('/test', (req, res) => {
    res.json({ message: "Test route works!" });
});


//setting.php
async function getUserById(userId) {
    try {
        const [results] = await db.query("SELECT * FROM users WHERE user_id = ?", [userId]);
        return results.length > 0 ? results[0] : null;
    } catch (err) {
        throw new Error(err.message);
    }
}

// app.get("/api/user/settings", verifyToken, async (req, res) => {

    
//     console.log("👋 /api/user/settings route was hit");

//     const userId = req.user.user_id;
//     console.log("🧾 Extracted userId from token:", userId);

//     try {
//         // Check if user exists
//         const user = await getUserById(userId);
        
//         if (!user) {
//             console.error(`User ${userId} not found in database`);
            
//             // If user doesn't exist, create a default user with this ID (optional)
//             try {
//                 console.log(`Creating default user for ID ${userId}`);
//                 const [createResult] = await db.query(
//                     "INSERT INTO users (user_id, username, password, bio, follower_count, following_count, todo_post_count, is_private, language, notify_comment, notify_follow, notify_likes) VALUES (?, ?, ?, '', 0, 0, 0, 0, 'en', 1, 1, 1)",
//                     [userId, req.user.username || `user${userId}`, '$2a$10$defaulthashpassword']
//                 );
                
//                 // Return default settings for new user
//                 return res.json({
//                     status: "success",
//                     username: req.user.username || `user${userId}`,
//                     bio: '',
//                     follower_count: 0,
//                     following_count: 0,
//                     todo_post_count: 0,
//                     is_private: 0,
//                     language: 'en',
//                     notify_likes: 1,
//                     notify_comment: 1,
//                     notify_follow: 1,
//                     todo_count: 0,
//                     blocked_users: []
//                 });
//             } catch (createErr) {
//                 console.error("Error creating default user:", createErr);
//                 return res.status(404).json({ 
//                     status: "error",
//                     message: "User not found and could not create default user",
//                     solution: "Please register or contact support"
//                 });
//             }
//         }

//         // Get user settings
//         const [userData] = await db.query(`
//             SELECT 
//                 username, bio, follower_count, following_count,
//                 notify_likes, notify_comment, notify_follow,
//                 is_private, language, created_at
//             FROM users 
//             WHERE user_id = ?`,
//             [userId]
//         );

//         // Get blocked users
//         const [blockedUsers] = await db.query(`
//             SELECT u.user_id, u.username 
//             FROM blocked_users b
//             JOIN users u ON b.blocked_id = u.user_id
//             WHERE b.blocker_id = ?`,
//             [userId]
//         );

//         // Get todo count
//         const [todoCount] = await db.query(`
//             SELECT COUNT(*) as count 
//             FROM todo_items ti
//             JOIN todo_list_folders tlf ON ti.folder_id = tlf.folder_id
//             WHERE tlf.user_id = ?`,
//             [userId]
//         );

//         console.log(`Successfully fetched settings for user ${userId}`);
//         res.json({
//             status: "success",
//             ...userData[0],
//             todo_count: todoCount[0].count || 0,
//             blocked_users: blockedUsers || []
//         });

//     } catch (err) {
//         console.error("Database error in /api/user/settings:", err);
//         res.status(500).json({ 
//             status: "error",
//             message: "Database error",
//             error: err.message
//         });
//     }
// });


app.get("/api/user/settings", verifyToken, async (req, res) => {
    console.log("👋 /api/user/settings route was hit");

    const userId = req.user.user_id;  // Extract userId from token
    console.log("🧾 Extracted userId from token:", userId);

    try {
        // Log query to check the user from the database
        const [userCheck] = await db.query("SELECT username FROM users WHERE user_id = ?", [userId]);
        console.log("🧐 User check result:", userCheck);  // Log user check result

        if (userCheck.length === 0) {
            console.log("🚨 User not found in database.");
            return res.status(404).json({ error: "User not found" });
        }

        // Fetch user data from the database
        const [userData] = await db.query(`
            SELECT 
                username, bio, notify_likes, notify_comment, notify_follow, 
                is_private, language
            FROM users WHERE user_id = ?
        `, [userId]);
        console.log("🧑‍💻 User data:", userData);  // Log user data

        // Fetch blocked users
        const [blocked] = await db.query(`
            SELECT u.user_id, u.username FROM blocked_users b
            JOIN users u ON b.blocked_id = u.user_id
            WHERE b.blocker_id = ?
        `, [userId]);
        console.log("🛑 Blocked users:", blocked);  // Log blocked users

        // Send user settings as response
        res.json({ ...userData[0], blocked_users: blocked });

    } catch (err) {
        console.log("❌ Database error:", err.message);
        res.status(500).json({ status: "error", message: err.message });
    }
});



// Update profile (username, bio)
app.put("/api/user/profile", verifyToken, async (req, res) => {
    const { username, bio } = req.body;
    const userId = req.user.user_id;

    try {
        await db.query("UPDATE users SET username = ?, bio = ? WHERE user_id = ?", [username, bio, userId]);
        res.json({ status: "success", message: "Profile updated." });
    } catch (err) {
        res.status(500).json({ status: "error", message: "Database update failed." });
    }
});

// Update notification preferences
app.put("/api/user/notifications", verifyToken, async (req, res) => {
    const { notify_likes, notify_comment, notify_follow } = req.body;
    const userId = req.user.user_id;

    try {
        await db.query("UPDATE users SET notify_likes = ?, notify_comment = ?, notify_follow = ? WHERE user_id = ?", 
            [notify_likes, notify_comment, notify_follow, userId]);
        res.json({ status: "success" });
    } catch (err) {
        res.status(500).json({ status: "error", message: err.message });
    }
});

// Update privacy settings (public/private)
app.put("/api/user/privacy", verifyToken, async (req, res) => {
    const { is_private } = req.body;
    const userId = req.user.user_id;

    try {
        await db.query("UPDATE users SET is_private = ? WHERE user_id = ?", [is_private, userId]);
        res.json({ status: "success" });
    } catch (err) {
        res.status(500).json({ status: "error", message: err.message });
    }
});

// Update language preference
app.put("/api/user/language", verifyToken, async (req, res) => {
    const { language } = req.body;
    const userId = req.user.user_id;

    try {
        await db.query("UPDATE users SET language = ? WHERE user_id = ?", [language, userId]);
        res.json({ status: "success" });
    } catch (err) {
        res.status(500).json({ status: "error", message: err.message });
    }
});

// Block a user
app.post("/api/user/block", verifyToken, async (req, res) => {
    const blockerId = req.user.user_id;
    const { blocked_id } = req.body;

    try {
        await db.query("INSERT INTO blocked_users (blocker_id, blocked_id) VALUES (?, ?)", [blockerId, blocked_id]);
        res.json({ status: "success", message: "User blocked." });
    } catch (err) {
        res.status(500).json({ status: "error", message: err.message });
    }
});

// Unblock a user
app.delete("/api/user/block", verifyToken, async (req, res) => {
    const blockerId = req.user.user_id;
    const { blocked_id } = req.body;

    try {
        await db.query("DELETE FROM blocked_users WHERE blocker_id = ? AND blocked_id = ?", [blockerId, blocked_id]);
        res.json({ status: "success", message: "User unblocked." });
    } catch (err) {
        res.status(500).json({ status: "error", message: err.message });
    }
});

// Start server
app.listen(5001, () => {
    console.log("Server is running on http://localhost:5001");
});








