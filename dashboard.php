```php
<?php

session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['userId'];
$backend = "https://radius-backend-0qv8.onrender.com";

$ch = curl_init("$backend/user/$userId/profile-html");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if ($httpCode !== 200) {
    echo "<h1 style='color:red'>Backend error: $httpCode</h1>";
    echo "<pre>" . htmlspecialchars($response ?? '') . "</pre>";
    exit;
}

$data = json_decode($response, true);

$currentHtml = $data["html"] ??
    "<html><body><h1>New Profile</h1></body></html>";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <link rel="icon" type="image/jpeg" href="logo.jpg">

    <title>Radius Profile Builder</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css"
    >

    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>

    <script
        src="https://js-cdn.music.apple.com/musickit/v3/musickit.js"
        async>
    </script>

    <style>

        #music-player {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 1000;
            background: #1a1a2e;
            border: 1px solid #2d2d4e;
            border-radius: 16px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
            min-width: 280px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        #music-player:hover {
            border-color: #4ade80;
            box-shadow: 0 8px 32px rgba(74,222,128,0.15);
        }

        #music-player .track-info {
            flex: 1;
            overflow: hidden;
        }

        #music-player .track-name {
            font-size: 13px;
            font-weight: 600;
            color: #e2e8f0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #music-player .track-counter {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        #music-player .progress-bar {
            width: 100%;
            height: 3px;
            background: #2d2d4e;
            border-radius: 2px;
            margin-top: 6px;
            cursor: pointer;
            overflow: hidden;
        }

        #music-player .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4ade80, #22d3ee);
            border-radius: 2px;
            width: 0%;
            transition: width 0.5s linear;
        }

        #music-player button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.2s;
            color: #94a3b8;
        }

        #music-player button:hover {
            background: rgba(74,222,128,0.15);
            color: #4ade80;
        }

        #music-player .play-btn {
            width: 36px;
            height: 36px;
            background: #4ade80 !important;
            color: #0f172a !important;
            border-radius: 50%;
        }

        #music-player .play-btn:hover {
            background: #22c55e !important;
            transform: scale(1.05);
        }

        #music-player .volume-wrapper {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        #music-player input[type="range"] {
            -webkit-appearance: none;
            width: 60px;
            height: 3px;
            background: #2d2d4e;
            border-radius: 2px;
            outline: none;
            cursor: pointer;
        }

        #music-player input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #4ade80;
            cursor: pointer;
        }

        #player-collapsed {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 1000;
            background: #1a1a2e;
            border: 1px solid #2d2d4e;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
            transition: all 0.2s ease;
        }

        #player-collapsed:hover {
            border-color: #4ade80;
            transform: scale(1.1);
        }

        .pulse-ring {
            position: absolute;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 2px solid #4ade80;
            animation: pulse 2s ease-out infinite;
            opacity: 0;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.6;
            }

            100% {
                transform: scale(1.6);
                opacity: 0;
            }
        }

    </style>

</head>

<body class="bg-gray-900 text-white">

<div class="flex flex-col h-screen">

    <div class="flex items-center justify-between px-6 py-4 bg-gray-800 shadow">

        <h1 class="text-xl font-bold text-green-400">
            Radius Profile Builder
        </h1>

        <div class="flex gap-3">

            <button
                id="templateMinimal"
                class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">
                Minimal
            </button>

            <button
                id="templateDark"
                class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">
                Dark
            </button>

            <button
                id="templateGamer"
                class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">
                Gamer
            </button>

            <a
                href="https://youtu.be/w6TYxcs5Qdo?si=rM8UvxIeybZT2U1F"
                target="_blank"
                class="px-3 py-1 bg-blue-600 rounded hover:bg-blue-500 text-sm font-semibold">
                Learn HTML
            </a>

            <button
                id="uploadImgBtn"
                class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">
                📷 Upload Image
            </button>

            <button
                id="musicBtn"
                class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">
                🎵 Add Recently Played
            </button>

            <button
                id="saveBtn"
                class="px-4 py-2 bg-green-500 rounded hover:bg-green-400 text-sm font-semibold">
                Save
            </button>

        </div>

    </div>

    <div class="flex flex-1 overflow-hidden">

        <div class="w-1/2 h-full border-r border-gray-700">

            <textarea id="editor"><?= htmlspecialchars($currentHtml) ?></textarea>

        </div>

        <div class="w-1/2 h-full bg-white">

            <iframe
                id="preview"
                class="w-full h-full border-0">
            </iframe>

        </div>

    </div>

</div>

<div
    id="uploadModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60">

    <div class="bg-gray-800 rounded-xl p-6 w-80 shadow-2xl space-y-3">

        <div class="flex justify-between items-center">

            <h2 class="text-sm font-semibold text-gray-200">
                Upload Image
            </h2>

            <button
                id="uploadModalClose"
                class="text-gray-500 hover:text-white text-xl leading-none">
                &times;
            </button>

        </div>

        <div
            id="uploadDrop"
            class="border-2 border-dashed border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:border-green-400 transition-colors">

            <p class="text-sm text-gray-400">

                <span class="text-green-400 font-medium">
                    Click to choose
                </span>

                or drag &amp; drop

            </p>

            <p class="text-xs text-gray-600 mt-1">
                JPG · PNG · WEBP · GIF · max 5 MB
            </p>

            <input
                id="uploadFileInput"
                type="file"
                accept="image/*"
                class="hidden">

        </div>

        <img
            id="uploadPreview"
            src=""
            alt=""
            class="hidden w-full max-h-36 object-contain rounded-lg bg-gray-700">

        <p
            id="uploadStatus"
            class="text-xs text-gray-400 hidden">
        </p>

        <button
            id="uploadSubmitBtn"
            class="w-full py-2 bg-green-500 hover:bg-green-400 text-sm font-semibold rounded-lg transition disabled:opacity-40 disabled:cursor-not-allowed"
            disabled>
            Upload
        </button>

        <div
            id="uploadResult"
            class="hidden space-y-2 pt-1 border-t border-gray-700">

            <p class="text-xs text-gray-400">
                Image URL:
            </p>

            <div class="flex gap-2">

                <input
                    id="uploadUrl"
                    type="text"
                    readonly
                    class="flex-1 bg-gray-900 text-green-400 text-xs px-3 py-2 rounded-lg border border-gray-700 outline-none">

                <button
                    id="uploadCopyBtn"
                    class="px-3 py-2 bg-gray-700 hover:bg-gray-600 text-xs rounded-lg transition">
                    Copy
                </button>

            </div>

            <button
                id="uploadInsertBtn"
                class="w-full py-2 bg-orange-500 hover:bg-orange-400 text-sm font-semibold rounded-lg transition">
                Insert &lt;img&gt; at cursor
            </button>

        </div>

    </div>

</div>

<div
    id="musicModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60">

    <div class="bg-gray-800 rounded-xl p-6 w-80 shadow-2xl space-y-3">

        <div class="flex justify-between items-center">

            <h2 class="text-sm font-semibold text-gray-200">
                Recently Played
            </h2>

            <button
                id="musicModalClose"
                class="text-gray-500 hover:text-white text-xl leading-none">
                &times;
            </button>

        </div>

        <div id="musicStep1">

            <p class="text-xs text-gray-400 mb-3">
                Connect Apple Music so your latest track shows up on your profile automatically.
            </p>

            <button
                id="musicConnectBtn"
                class="w-full py-2 bg-green-500 hover:bg-green-400 text-sm font-semibold rounded-lg transition disabled:opacity-40">
                Connect Apple Music
            </button>

        </div>

        <div
            id="musicStep2"
            class="hidden space-y-3">

            <p class="text-xs text-gray-400">
                Connected. Here's your current track:
            </p>

            <div class="flex items-center gap-3 bg-gray-900 rounded-lg p-3">

                <img
                    id="musicPreviewArt"
                    src=""
                    class="w-10 h-10 rounded
```
