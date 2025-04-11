const jwt = require("jsonwebtoken");

// Middleware to verify JWT token
function verifyToken(req, res, next) {
    const token = req.header("Authorization")?.split(" ")[1]; // Bearer <token>
    
    console.log("Received token:", token);

    if (!token) {
        return res.status(401).json({ status: "error", message: "Token missing" });
    }

    jwt.verify(token, "your_secret_key", (err, decoded) => {
        if (err) {
            console.error("Token verification failed:", err.message);
            return res.status(401).json({ status: "error", message: "Invalid token" });
        }

        req.user = decoded;
        next();
    });
}

module.exports = verifyToken;