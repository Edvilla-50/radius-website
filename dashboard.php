<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}
$userId = $_GET['id'] ?? $_SESSION['userId'];
$backend = "https://radius-backend-0qv8.onrender.com";
// Use cURL to fetch profile HTML
$ch = curl_init("$backend/user/$userId/profile-html");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if ($httpCode !== 200) {
    echo "<h1 style='color:red'>Backend error: $httpCode</h1>";
    echo "<pre>$response</pre>";
    exit;
}
$data = json_decode($response, true);
$currentHtml = $data["html"] ?? "<html><body><h1>New Profile</h1></body></html>";
?>
<!DOCTYPE html>
<link rel="icon" type="image/jpeg" href="/image/radius-image.jpg">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Radius Profile Builder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>
</head>
<body class="bg-gray-900 text-white">
<div class="flex flex-col h-screen">
    <div class="flex items-center justify-between px-6 py-4 bg-gray-800 shadow">
        <h1 class="text-xl font-bold text-green-400">Radius Profile Builder</h1>
        <div class="flex gap-3">
            <button id="templateMinimal" class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">Minimal</button>
            <button id="templateDark" class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">Dark</button>
            <button id="templateGamer" class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">Gamer</button>
            <a href="https://youtu.be/w6TYxcs5Qdo?si=rM8UvxIeybZT2U1F"
               target="_blank"
               class="px-3 py-1 bg-blue-600 rounded hover:bg-blue-500 text-sm font-semibold">
                Learn HTML
            </a>
            <button id="uploadImgBtn"
                    class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">
                📷 Upload Image
            </button>
            <button id="saveBtn" class="px-4 py-2 bg-green-500 rounded hover:bg-green-400 text-sm font-semibold">Save</button>
        </div>
    </div>
    <div class="flex flex-1 overflow-hidden">
        <div class="w-1/2 h-full border-r border-gray-700">
            <textarea id="editor"><?= htmlspecialchars($currentHtml) ?></textarea>
        </div>
        <div class="w-1/2 h-full bg-white">
            <iframe id="preview" class="w-full h-full border-0"></iframe>
        </div>
    </div>
</div>

<!-- Image Upload Modal -->
<div id="uploadModal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60">
    <div class="bg-gray-800 rounded-xl p-6 w-80 shadow-2xl space-y-3">

        <div class="flex justify-between items-center">
            <h2 class="text-sm font-semibold text-gray-200">Upload Image</h2>
            <button id="uploadModalClose"
                    class="text-gray-500 hover:text-white text-xl leading-none">&times;</button>
        </div>

        <!-- drop zone -->
        <div id="uploadDrop"
             class="border-2 border-dashed border-gray-600 rounded-lg p-6
                    text-center cursor-pointer hover:border-green-400 transition-colors">
            <p class="text-sm text-gray-400">
                <span class="text-green-400 font-medium">Click to choose</span>
                or drag &amp; drop
            </p>
            <p class="text-xs text-gray-600 mt-1">JPG · PNG · WEBP · GIF · max 5 MB</p>
            <input id="uploadFileInput" type="file" accept="image/*" class="hidden">
        </div>

        <!-- preview -->
        <img id="uploadPreview" src="" alt=""
             class="hidden w-full max-h-36 object-contain rounded-lg bg-gray-700">

        <!-- status line -->
        <p id="uploadStatus" class="text-xs text-gray-400 hidden"></p>

        <button id="uploadSubmitBtn"
                class="w-full py-2 bg-green-500 hover:bg-green-400 text-sm font-semibold
                       rounded-lg transition disabled:opacity-40 disabled:cursor-not-allowed"
                disabled>
            Upload
        </button>

        <!-- result (shown after upload succeeds) -->
        <div id="uploadResult" class="hidden space-y-2 pt-1 border-t border-gray-700">
            <p class="text-xs text-gray-400">Image URL:</p>
            <div class="flex gap-2">
                <input id="uploadUrl" type="text" readonly
                       class="flex-1 bg-gray-900 text-green-400 text-xs px-3 py-2
                              rounded-lg border border-gray-700 outline-none">
                <button id="uploadCopyBtn"
                        class="px-3 py-2 bg-gray-700 hover:bg-gray-600 text-xs rounded-lg transition">
                    Copy
                </button>
            </div>
            <button id="uploadInsertBtn"
                    class="w-full py-2 bg-orange-500 hover:bg-orange-400 text-sm font-semibold
                           rounded-lg transition">
                Insert &lt;img&gt; at cursor
            </button>
        </div>

    </div>
</div>

<script>
const USER_ID = <?= (int)$userId ?>;
</script>
<script src="js/editor.js"></script>
</body>
</html>