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
                    class="w-10 h-10 rounded object-cover bg-gray-700"
                    alt="">

                <div class="overflow-hidden">

                    <div
                        id="musicPreviewTrack"
                        class="text-sm font-medium text-gray-200 truncate">
                    </div>

                    <div
                        id="musicPreviewArtist"
                        class="text-xs text-gray-500 truncate">
                    </div>

                </div>

            </div>

            <button
                id="musicInsertBtn"
                class="w-full py-2 bg-orange-500 hover:bg-orange-400 text-sm font-semibold rounded-lg transition">
                Insert Recently Played widget at cursor
            </button>

        </div>

        <p
            id="musicStatus"
            class="text-xs text-gray-400 hidden">
        </p>

    </div>

</div>

<div id="music-player">

    <button
        id="collapseBtn"
        title="Minimize"
        style="position:absolute;top:8px;right:8px;width:20px;height:20px;font-size:11px;color:#475569;">

        <svg
            viewBox="0 0 24 24"
            width="14"
            height="14"
            fill="none"
            stroke="currentColor"
            stroke-width="2.5">

            <path d="M19 15l-7 7-7-7"/>

        </svg>

    </button>

    <button id="prevBtn" title="Previous">

        <svg
            viewBox="0 0 24 24"
            width="18"
            height="18"
            fill="currentColor">

            <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/>

        </svg>

    </button>

    <button
        id="playPauseBtn"
        class="play-btn"
        title="Play / Pause">

        <svg
            id="playIcon"
            viewBox="0 0 24 24"
            width="18"
            height="18"
            fill="currentColor">

            <path d="M8 5v14l11-7z"/>

        </svg>

        <svg
            id="pauseIcon"
            viewBox="0 0 24 24"
            width="18"
            height="18"
            fill="currentColor"
            style="display:none">

            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>

        </svg>

    </button>

    <button id="nextBtn" title="Next">

        <svg
            viewBox="0 0 24 24"
            width="18"
            height="18"
            fill="currentColor">

            <path d="M6 18l8.5-6L6 6v12zm2.5-6l5.5 4V8l-5.5 4zM16 6h2v12h-2z"/>

        </svg>

    </button>

    <div class="track-info">

        <div
            class="track-name"
            id="trackName">
            Track 1
        </div>

        <div
            class="track-counter"
            id="trackCounter">
            1 / 4 · looping
        </div>

        <div
            class="progress-bar"
            id="progressBar">

            <div
                class="progress-fill"
                id="progressFill">
            </div>

        </div>

    </div>

    <div class="volume-wrapper">

        <svg
            viewBox="0 0 24 24"
            width="16"
            height="16"
            fill="currentColor"
            style="color:#64748b;flex-shrink:0">

            <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25-2.5-4.02z"/>

        </svg>

        <input
            type="range"
            id="volumeSlider"
            min="0"
            max="1"
            step="0.05"
            value="0.7">

    </div>

</div>

<div
    id="player-collapsed"
    title="Open player">

    <div
        class="pulse-ring"
        id="pulseRing"
        style="display:none">
    </div>

    <svg
        viewBox="0 0 24 24"
        width="22"
        height="22"
        fill="#4ade80">

        <path d="M12 3v10.55A4 4 0 1014 17V7h4V3h-6z"/>

    </svg>

</div>

<audio id="audioPlayer"></audio>

<script>

const USER_ID = <?= (int)$userId ?>;

const tracks = [
    {
        name: "Are You Happy Now?",
        src: "are-you-happy-now.mp3"
    },
    {
        name: "Girlfriend",
        src: "girlfriend.mp3"
    },
    {
        name: "Parrot Prince",
        src: "parrot-prince.mp3"
    },
    {
        name: "Poolside",
        src: "poolside.mp3"
    }
];

let currentIndex = 0;
let isPlaying = false;

const audio = document.getElementById("audioPlayer");
const playPauseBtn = document.getElementById("playPauseBtn");
const playIcon = document.getElementById("playIcon");
const pauseIcon = document.getElementById("pauseIcon");
const trackName = document.getElementById("trackName");
const trackCounter = document.getElementById("trackCounter");
const progressFill = document.getElementById("progressFill");
const progressBar = document.getElementById("progressBar");
const volumeSlider = document.getElementById("volumeSlider");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");
const collapseBtn = document.getElementById("collapseBtn");
const playerFull = document.getElementById("music-player");
const playerMini = document.getElementById("player-collapsed");
const pulseRing = document.getElementById("pulseRing");

audio.volume = parseFloat(volumeSlider.value);

function loadTrack(index) {

    const track = tracks[index];

    audio.src = track.src;

    trackName.textContent = track.name;

    trackCounter.textContent =
        `${index + 1} / ${tracks.length} · looping`;

    progressFill.style.width = "0%";
}

function playTrack() {

    audio.play().catch(() => {});

    isPlaying = true;

    playIcon.style.display = "none";
    pauseIcon.style.display = "block";
    pulseRing.style.display = "block";
}

function pauseTrack() {

    audio.pause();

    isPlaying = false;

    playIcon.style.display = "block";
    pauseIcon.style.display = "none";
    pulseRing.style.display = "none";
}

function goNext() {

    currentIndex =
        (currentIndex + 1) % tracks.length;

    loadTrack(currentIndex);

    if (isPlaying) {
        playTrack();
    }
}

function goPrev() {

    if (audio.currentTime > 3) {

        audio.currentTime = 0;

    } else {

        currentIndex =
            (currentIndex - 1 + tracks.length) %
            tracks.length;

        loadTrack(currentIndex);

        if (isPlaying) {
            playTrack();
        }
    }
}

audio.addEventListener("ended", function () {

    currentIndex =
        (currentIndex + 1) % tracks.length;

    loadTrack(currentIndex);

    if (isPlaying) {
        playTrack();
    }
});

audio.addEventListener("timeupdate", function () {

    if (audio.duration) {

        progressFill.style.width =
            (audio.currentTime / audio.duration * 100) + "%";
    }
});

progressBar.addEventListener("click", function (e) {

    if (!audio.duration) {
        return;
    }

    const rect =
        progressBar.getBoundingClientRect();

    audio.currentTime =
        ((e.clientX - rect.left) / rect.width) *
        audio.duration;
});

volumeSlider.addEventListener("input", function () {

    audio.volume =
        parseFloat(volumeSlider.value);
});

playPauseBtn.addEventListener("click", function () {

    if (isPlaying) {
        pauseTrack();
    } else {
        playTrack();
    }
});

prevBtn.addEventListener("click", goPrev);

nextBtn.addEventListener("click", function () {

    goNext();

    if (isPlaying) {
        playTrack();
    }
});

collapseBtn.addEventListener("click", function () {

    playerFull.style.display = "none";
    playerMini.style.display = "flex";
});

playerMini.addEventListener("click", function () {

    playerMini.style.display = "none";
    playerFull.style.display = "flex";
});

loadTrack(currentIndex);

document.addEventListener(
    "click",
    function startOnInteraction() {

        playTrack();

        document.removeEventListener(
            "click",
            startOnInteraction
        );

    },
    { once: true }
);

</script>

<script>

(function () {

    const BACKEND =
        "https://radius-backend-0qv8.onrender.com";

    const musicBtn =
        document.getElementById("musicBtn");

    const musicModal =
        document.getElementById("musicModal");

    const musicModalClose =
        document.getElementById("musicModalClose");

    const musicStep1 =
        document.getElementById("musicStep1");

    const musicStep2 =
        document.getElementById("musicStep2");

    const musicConnectBtn =
        document.getElementById("musicConnectBtn");

    const musicInsertBtn =
        document.getElementById("musicInsertBtn");

    const musicStatus =
        document.getElementById("musicStatus");

    let musicKitInstance = null;

    let currentSnippet = null;

    let musicKitReady =
        typeof MusicKit !== "undefined";

    document.addEventListener(
        "musickitloaded",
        function () {

            console.log("MusicKit loaded.");

            musicKitReady = true;
        }
    );

    function waitForMusicKit() {

        if (typeof MusicKit !== "undefined") {

            musicKitReady = true;

            return Promise.resolve();
        }

        return new Promise(function (resolve, reject) {

            const timeout =
                setTimeout(function () {

                    reject(
                        new Error(
                            "MusicKit failed to load."
                        )
                    );

                }, 15000);

            document.addEventListener(
                "musickitloaded",
                function () {

                    clearTimeout(timeout);

                    musicKitReady = true;

                    resolve();

                },
                { once: true }
            );
        });
    }

    musicBtn.addEventListener(
        "click",
        function () {

            musicModal.classList.remove("hidden");

            checkExistingConnection();
        }
    );

    musicModalClose.addEventListener(
        "click",
        function () {

            musicModal.classList.add("hidden");
        }
    );

    function setStatus(
        message,
        isError = false
    ) {

        musicStatus.textContent = message;

        musicStatus.classList.remove("hidden");

        musicStatus.classList.remove(
            "text-gray-400",
            "text-red-400"
        );

        if (isError) {

            musicStatus.classList.add(
                "text-red-400"
            );

        } else {

            musicStatus.classList.add(
                "text-gray-400"
            );
        }
    }

    async function checkExistingConnection() {

        try {

            const res = await fetch(
                `${BACKEND}/api/music/${USER_ID}`,
                {
                    method: "GET",
                    cache: "no-store"
                }
            );

            if (res.ok) {

                const body =
                    await res.text();

                if (
                    body &&
                    body !== "null"
                ) {

                    const snippet =
                        JSON.parse(body);

                    if (snippet) {

                        showPreview(snippet);

                        return;
                    }
                }
            }

        } catch (error) {

            console.log(
                "No existing Apple Music connection:",
                error
            );
        }

        musicStep1.classList.remove("hidden");
        musicStep2.classList.add("hidden");
    }

    async function initMusicKit() {

        if (musicKitInstance) {
            return musicKitInstance;
        }

        await waitForMusicKit();

        if (typeof MusicKit === "undefined") {

            throw new Error(
                "MusicKit is not available."
            );
        }

        console.log(
            "Requesting developer token..."
        );

        const tokenRes = await fetch(
            `${BACKEND}/api/music/dev-token`,
            {
                method: "GET",
                cache: "no-store"
            }
        );

        if (!tokenRes.ok) {

            throw new Error(
                `Developer token request failed: HTTP ${tokenRes.status}`
            );
        }

        const tokenData =
            await tokenRes.json();

        console.log(
            "Developer token received:",
            !!tokenData.token
        );

        if (!tokenData.token) {

            throw new Error(
                "Backend returned no developer token."
            );
        }

        console.log(
            "Configuring MusicKit..."
        );

        await MusicKit.configure({

            developerToken:
                tokenData.token,

            app: {
                name: "Radius",
                build: "1.0.0"
            }
        });

        console.log(
            "MusicKit configured."
        );

        musicKitInstance =
            MusicKit.getInstance();

        if (!musicKitInstance) {

            throw new Error(
                "MusicKit.getInstance() returned nothing."
            );
        }

        console.log(
            "MusicKit instance created."
        );

        return musicKitInstance;
    }

    musicConnectBtn.addEventListener(
        "click",
        async function () {

            musicConnectBtn.disabled = true;

            setStatus("Connecting...");

            try {

                console.log(
                    "Starting Apple Music authorization..."
                );

                const music =
                    await initMusicKit();

                console.log(
                    "MusicKit authorization status:",
                    music.authorizationStatus
                );

                console.log(
                    "Calling music.authorize()..."
                );

                const userToken =
                    await music.authorize();

                console.log(
                    "Apple Music authorization succeeded."
                );

                if (!userToken) {

                    throw new Error(
                        "Apple Music returned an empty user token."
                    );
                }

                console.log(
                    "Apple Music user token received."
                );

                const res = await fetch(
                    `${BACKEND}/api/music/connect/${USER_ID}`,
                    {
                        method: "POST",
                        headers: {
                            "Content-Type":
                                "application/json"
                        },
                        body: JSON.stringify({
                            appleMusicUserToken:
                                userToken
                        })
                    }
                );

                if (!res.ok) {

                    const errorText =
                        await res.text();

                    throw new Error(
                        `Backend connect failed: HTTP ${res.status} ${errorText}`
                    );
                }

                const snippet =
                    await res.json();

                console.log(
                    "Apple Music connection saved."
                );

                showPreview(snippet);

                setStatus("");

            } catch (error) {

                console.error(
                    "================================"
                );

                console.error(
                    "APPLE MUSIC AUTH ERROR"
                );

                console.error(
                    "================================"
                );

                console.error(
                    "Error:",
                    error
                );

                console.error(
                    "Name:",
                    error?.name
                );

                console.error(
                    "Message:",
                    error?.message
                );

                console.error(
                    "Code:",
                    error?.code
                );

                console.error(
                    "Stack:",
                    error?.stack
                );

                try {

                    console.error(
                        "Full error:",
                        JSON.stringify(
                            error,
                            null,
                            2
                        )
                    );

                } catch (jsonError) {

                    console.error(
                        "Could not stringify error:",
                        jsonError
                    );
                }

                setStatus(
                    `Auth failed: ${error?.name || "unknown"} — ${error?.message || "unknown error"}`,
                    true
                );

            } finally {

                musicConnectBtn.disabled = false;
            }
        }
    );

    function showPreview(snippet) {

        currentSnippet = snippet;

        musicStep1.classList.add("hidden");

        musicStep2.classList.remove("hidden");

        document.getElementById(
            "musicPreviewArt"
        ).src =
            snippet.albumArtUrl || "";

        document.getElementById(
            "musicPreviewTrack"
        ).textContent =
            snippet.trackName ||
            "No recent tracks yet";

        document.getElementById(
            "musicPreviewArtist"
        ).textContent =
            snippet.artistName || "";
    }

    musicInsertBtn.addEventListener(
        "click",
        function () {

            const uid = USER_ID;

            const widgetHtml = `

<!-- Radius: Recently Played (auto-updates) -->

<div
    id="radius-recently-played-${uid}"
    data-user-id="${uid}"
    style="display:flex;align-items:center;gap:10px;background:#1a1a2e;border:1px solid #2d2d4e;border-radius:12px;padding:10px 14px;max-width:320px;font-family:sans-serif;">

    <img
        id="rp-art-${uid}"
        src=""
        alt=""
        style="width:44px;height:44px;border-radius:6px;object-fit:cover;background:#2d2d4e;">

    <div style="overflow:hidden;flex:1;">

        <div
            id="rp-track-${uid}"
            style="color:#e2e8f0;font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            Loading...
        </div>

        <div
            id="rp-artist-${uid}"
            style="color:#94a3b8;font-size:12px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
        </div>

    </div>

    <button
        id="rp-play-${uid}"
        style="background:#4ade80;border:none;border-radius:50%;width:32px;height:32px;cursor:pointer;display:none;">
        ▶
    </button>

</div>

<audio id="rp-audio-${uid}"></audio>

<script>

(function () {

    var audio =
        document.getElementById(
            "rp-audio-${uid}"
        );

    var button =
        document.getElementById(
            "rp-play-${uid}"
        );

    var playing = false;

    var currentPreviewUrl = "";

    function loadRecentlyPlayed() {

        fetch(
            "${BACKEND}/api/music/${uid}",
            {
                method: "GET",
                cache: "no-store"
            }
        )
            .then(function (response) {

                if (!response.ok) {

                    throw new Error(
                        "HTTP " + response.status
                    );
                }

                return response.json();
            })
            .then(function (data) {

                if (!data || !data.trackName) {
                    return;
                }

                var art =
                    document.getElementById(
                        "rp-art-${uid}"
                    );

                var track =
                    document.getElementById(
                        "rp-track-${uid}"
                    );

                var artist =
                    document.getElementById(
                        "rp-artist-${uid}"
                    );

                if (art) {

                    art.src =
                        data.albumArtUrl || "";
                }

                if (track) {

                    track.textContent =
                        data.trackName;
                }

                if (artist) {

                    artist.textContent =
                        data.artistName || "";
                }

                if (
                    button &&
                    audio &&
                    data.previewUrl
                ) {

                    button.style.display =
                        "block";

                    if (
                        currentPreviewUrl !==
                        data.previewUrl
                    ) {

                        currentPreviewUrl =
                            data.previewUrl;

                        if (playing) {

                            audio.pause();

                            audio.currentTime = 0;

                            playing = false;

                            button.textContent =
                                "▶";
                        }
                    }

                    button.onclick =
                        function () {

                            if (playing) {

                                audio.pause();

                                button.textContent =
                                    "▶";

                                playing = false;

                            } else {

                                audio.src =
                                    currentPreviewUrl;

                                audio.play()
                                    .then(function () {

                                        button.textContent =
                                            "❚❚";

                                        playing = true;

                                    })
                                    .catch(function (error) {

                                        console.error(
                                            "Preview playback failed:",
                                            error
                                        );

                                    });
                            }
                        };

                    audio.onended =
                        function () {

                            button.textContent =
                                "▶";

                            playing = false;
                        };
                }
            })
            .catch(function (error) {

                console.error(
                    "Recently played widget error:",
                    error
                );
            });
    }

    loadRecentlyPlayed();

    setInterval(
        loadRecentlyPlayed,
        30000
    );

})();

<\/script>

`.trim();

            const cm =
                document.querySelector(".CodeMirror") &&
                document.querySelector(".CodeMirror").CodeMirror;

            if (cm) {

                cm.replaceSelection(
                    widgetHtml
                );

            } else {

                const editor =
                    document.getElementById("editor");

                const pos =
                    editor.selectionStart;

                editor.value =
                    editor.value.slice(
                        0,
                        pos
                    ) +
                    widgetHtml +
                    editor.value.slice(
                        pos
                    );
            }

            musicModal.classList.add(
                "hidden"
            );
        }
    );

})();

</script>

<script src="js/editor.js"></script>

</body>

</html>
