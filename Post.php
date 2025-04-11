<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create A Post</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .post-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            display: flex;
            flex-wrap: wrap;
        }

        .post-container h1 {
            width: 100%;
            margin-bottom: 20px;
        }

        .image-upload {
            border: 2px dashed #ccc;
            width: 300px;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            margin-right: 30px; /* Added space here */
        }

        .image-upload img {
            max-width: 100%;
            max-height: 100%;
        }

        .caption-section {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            align-items: flex-end;
            margin-left: 20px; /* Added space here */
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px; /* Added space here */
            align-self: flex-start;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .caption-input {
            border: 1px solid #ccc;
            padding: 10px;
            height: 150px;
            resize: vertical;
            margin-bottom: 20px; /* Added space here */
            width: 100%;
        }

        .post-button {
            background-color: black;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }


        .go-back {
            position: absolute;
            bottom: 20px;
            left: 20px;
            font-size: 1.8vw;
            text-decoration: none;
            color: white;
            background-color: #1f1f1f;
            padding: 10px 20px;
            border-radius: 8px;
        }

        
    </style>
</head>
<body>
    <div class="post-container">
        <h1>Create A Post</h1>

        <label for="imageUpload" class="image-upload">
            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='40' fill='none' stroke='black' stroke-width='4'/%3E%3Cline x1='50' y1='30' x2='50' y2='70' stroke='black' stroke-width='4'/%3E%3Cline x1='30' y1='50' x2='70' y2='50' stroke='black' stroke-width='4'/%3E%3C/svg%3E" alt="Upload Image">
            <input type="file" id="imageUpload" style="display: none;">
        </label>

        <div class="caption-section">
            <div class="user-info">
                <img src="./image/profile.png" alt="User A">
                <span>User A</span>
            </div>
            <textarea class="caption-input" placeholder="Add caption here"></textarea>
            <button class="post-button">Post</button>
        </div>
    </div>
    <a href="javascript:history.back()" class="go-back">Go Back</a>


    <script>
        document.getElementById('imageUpload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.image-upload img').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
    
</body>
</html>