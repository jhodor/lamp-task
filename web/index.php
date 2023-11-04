<!DOCTYPE html>
<html>
<head>
    <title>File Upload</title>
    <link rel="stylesheet" href="/css/index.css" />
    <script src="/js/upload.js"></script>
</head>
<body>
<div class="center-container">
    <h1>Upload a File</h1>
    <form action="/api/upload.php" method="post" enctype="multipart/form-data" name="uploadForm">
        <label for="fileToUpload" class="file-label" onclick="updateLabel()">Choose a file</label>
        <input type="file" id="fileToUpload" name="fileToUpload" onchange="updateLabel()" style="display: none;">
    </form>
    <p class="error-message"><? echo $_GET['message'] ?? ''; ?></p>
</div>
</body>
</html>