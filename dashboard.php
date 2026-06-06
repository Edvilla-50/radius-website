<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}
$userId = $_GET['id'] ?? $_SESSION['userId'];
$backend = "https://radius-backend-0qv8.onrender.com";
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

    <style>
        /* ── Music Player ── */
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
        #music-player .track-info { flex: 1; overflow: hidden; }
        #music-player .track-name {
            font-size: 13px; font-weight: 600; color: #e2e8f0;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        #music-player .track-counter { font-size: 11px; color: #64748b; margin-top: 2px; }
        #music-player .progress-bar {
            width: 100%; height: 3px; background: #2d2d4e;
            border-radius: 2px; margin-top: 6px; cursor: pointer; overflow: hidden;
        }
        #music-player .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4ade80, #22d3ee);
            border-radius: 2px; width: 0%; transition: width 0.5s linear;
        }
        #music-player button {
            background: none; border: none; cursor: pointer; padding: 4px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; transition: background 0.2s; color: #94a3b8;
        }
        #music-player button:hover { background: rgba(74,222,128,0.15); color: #4ade80; }
        #music-player .play-btn {
            width: 36px; height: 36px;
            background: #4ade80 !important; color: #0f172a !important; border-radius: 50%;
        }
        #music-player .play-btn:hover { background: #22c55e !important; transform: scale(1.05); }
        #music-player .volume-wrapper { display: flex; align-items: center; gap: 6px; }
        #music-player input[type="range"] {
            -webkit-appearance: none; width: 60px; height: 3px;
            background: #2d2d4e; border-radius: 2px; outline: none; cursor: pointer;
        }
        #music-player input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none; width: 10px; height: 10px;
            border-radius: 50%; background: #4ade80; cursor: pointer;
        }
        #player-collapsed {
            position: fixed; bottom: 24px; right: 24px; z-index: 1000;
            background: #1a1a2e; border: 1px solid #2d2d4e; border-radius: 50%;
            width: 48px; height: 48px; display: none; align-items: center;
            justify-content: center; cursor: pointer;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4); transition: all 0.2s ease;
        }
        #player-collapsed:hover { border-color: #4ade80; transform: scale(1.1); }
        .pulse-ring {
            position: absolute; width: 48px; height: 48px; border-radius: 50%;
            border: 2px solid #4ade80; animation: pulse 2s ease-out infinite; opacity: 0;
        }
        @keyframes pulse {
            0%   { transform: scale(1);   opacity: 0.6; }
            100% { transform: scale(1.6); opacity: 0;   }
        }
    </style>
</head>
<body class="bg-gray-900 text-white">
<div class="flex flex-col h-screen">

    <!-- Top bar -->
    <div class="flex items-center justify-between px-6 py-4 bg-gray-800 shadow">
        <h1 class="text-xl font-bold text-green-400">Radius Profile Builder</h1>
        <div class="flex gap-3">
            <button id="templateMinimal" class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">Minimal</button>
            <button id="templateDark"    class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">Dark</button>
            <button id="templateGamer"   class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">Gamer</button>
            <a href="https://youtu.be/w6TYxcs5Qdo?si=rM8UvxIeybZT2U1F"
               target="_blank"
               class="px-3 py-1 bg-blue-600 rounded hover:bg-blue-500 text-sm font-semibold">
                Learn HTML
            </a>
            <button id="uploadImgBtn" class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">
                📷 Upload Image
            </button>
            <button id="saveBtn" class="px-4 py-2 bg-green-500 rounded hover:bg-green-400 text-sm font-semibold">Save</button>
        </div>
    </div>

    <!-- Main content -->
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
<div id="uploadModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60">
    <div class="bg-gray-800 rounded-xl p-6 w-80 shadow-2xl space-y-3">
        <div class="flex justify-between items-center">
            <h2 class="text-sm font-semibold text-gray-200">Upload Image</h2>
            <button id="uploadModalClose" class="text-gray-500 hover:text-white text-xl leading-none">&times;</button>
        </div>
        <div id="uploadDrop"
             class="border-2 border-dashed border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:border-green-400 transition-colors">
            <p class="text-sm text-gray-400">
                <span class="text-green-400 font-medium">Click to choose</span> or drag &amp; drop
            </p>
            <p class="text-xs text-gray-600 mt-1">JPG · PNG · WEBP · GIF · max 5 MB</p>
            <input id="uploadFileInput" type="file" accept="image/*" class="hidden">
        </div>
        <img id="uploadPreview" src="" alt="" class="hidden w-full max-h-36 object-contain rounded-lg bg-gray-700">
        <p id="uploadStatus" class="text-xs text-gray-400 hidden"></p>
        <button id="uploadSubmitBtn"
                class="w-full py-2 bg-green-500 hover:bg-green-400 text-sm font-semibold rounded-lg transition disabled:opacity-40 disabled:cursor-not-allowed"
                disabled>
            Upload
        </button>
        <div id="uploadResult" class="hidden space-y-2 pt-1 border-t border-gray-700">
            <p class="text-xs text-gray-400">Image URL:</p>
            <div class="flex gap-2">
                <input id="uploadUrl" type="text" readonly
                       class="flex-1 bg-gray-900 text-green-400 text-xs px-3 py-2 rounded-lg border border-gray-700 outline-none">
                <button id="uploadCopyBtn" class="px-3 py-2 bg-gray-700 hover:bg-gray-600 text-xs rounded-lg transition">Copy</button>
            </div>
            <button id="uploadInsertBtn"
                    class="w-full py-2 bg-orange-500 hover:bg-orange-400 text-sm font-semibold rounded-lg transition">
                Insert &lt;img&gt; at cursor
            </button>
        </div>
    </div>
</div>


<div id="music-player">
    <button id="collapseBtn" title="Minimize"
            style="position:absolute;top:8px;right:8px;width:20px;height:20px;font-size:11px;color:#475569;">
        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M19 15l-7 7-7-7"/>
        </svg>
    </button>

    <button id="prevBtn" title="Previous">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/>
        </svg>
    </button>

    <button id="playPauseBtn" class="play-btn" title="Play / Pause">
        <svg id="playIcon" viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M8 5v14l11-7z"/>
        </svg>
        <svg id="pauseIcon" viewBox="0 0 24 24" width="18" height="18" fill="currentColor" style="display:none">
            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
        </svg>
    </button>

    <!-- Next -->
    <button id="nextBtn" title="Next">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M6 18l8.5-6L6 6v12zm2.5-6l5.5 4V8l-5.5 4zM16 6h2v12h-2z"/>
        </svg>
    </button>

    <!-- Track info + progress -->
    <div class="track-info">
        <div class="track-name" id="trackName">Track 1</div>
        <div class="track-counter" id="trackCounter">1 / 4 · looping</div>
        <div class="progress-bar" id="progressBar">
            <div class="progress-fill" id="progressFill"></div>
        </div>
    </div>

    <!-- Volume -->
    <div class="volume-wrapper">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" style="color:#64748b;flex-shrink:0">
            <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/>
        </svg>
        <input type="range" id="volumeSlider" min="0" max="1" step="0.05" value="0.7">
    </div>
</div>

<!-- ── Music Player (collapsed) ── -->
<div id="player-collapsed" title="Open player">
    <div class="pulse-ring" id="pulseRing" style="display:none"></div>
    <svg viewBox="0 0 24 24" width="22" height="22" fill="#4ade80">
        <path d="M12 3v10.55A4 4 0 1014 17V7h4V3h-6z"/>
    </svg>
</div>

<audio id="audioPlayer"></audio>

<script>
    const USER_ID = <?= (int)$userId ?>;

    // ── MUSIC PLAYER ─────────────────────────────────────────────
    // 🎵 Replace src values with your actual audio file paths
    const tracks = [
    { name: "Are You Happy Now?",  src: "are-you-happy-now.mp3" },
    { name: "Girlfriend",          src: "girlfriend.mp3" },
    { name: "Parrot Prince",       src: "parrot-prince.mp3" },
    { name: "Poolside",            src: "poolside.mp3" },
    ];

    let currentIndex = 0;
    let isPlaying    = false;

    const audio        = document.getElementById("audioPlayer");
    const playPauseBtn = document.getElementById("playPauseBtn");
    const playIcon     = document.getElementById("playIcon");
    const pauseIcon    = document.getElementById("pauseIcon");
    const trackName    = document.getElementById("trackName");
    const trackCounter = document.getElementById("trackCounter");
    const progressFill = document.getElementById("progressFill");
    const progressBar  = document.getElementById("progressBar");
    const volumeSlider = document.getElementById("volumeSlider");
    const prevBtn      = document.getElementById("prevBtn");
    const nextBtn      = document.getElementById("nextBtn");
    const collapseBtn  = document.getElementById("collapseBtn");
    const playerFull   = document.getElementById("music-player");
    const playerMini   = document.getElementById("player-collapsed");
    const pulseRing    = document.getElementById("pulseRing");

    audio.volume = parseFloat(volumeSlider.value);

    function loadTrack(index) {
        const track = tracks[index];
        audio.src = track.src;
        trackName.textContent    = track.name;
        trackCounter.textContent = `${index + 1} / ${tracks.length} · looping`;
        progressFill.style.width = "0%";
    }

    function playTrack() {
        audio.play().catch(() => {});
        isPlaying = true;
        playIcon.style.display  = "none";
        pauseIcon.style.display = "block";
        pulseRing.style.display = "block";
    }

    function pauseTrack() {
        audio.pause();
        isPlaying = false;
        playIcon.style.display  = "block";
        pauseIcon.style.display = "none";
        pulseRing.style.display = "none";
    }

    function goNext() {
        currentIndex = (currentIndex + 1) % tracks.length; // wraps 4 → 0
        loadTrack(currentIndex);
        if (isPlaying) playTrack();
    }

    function goPrev() {
        if (audio.currentTime > 3) {
            audio.currentTime = 0; // restart current track if past 3 s
        } else {
            currentIndex = (currentIndex - 1 + tracks.length) % tracks.length;
            loadTrack(currentIndex);
            if (isPlaying) playTrack();
        }
    }

    // Auto-advance and loop when a track ends
    audio.addEventListener("ended", () => { goNext(); playTrack(); });

    // Progress bar
    audio.addEventListener("timeupdate", () => {
        if (audio.duration)
            progressFill.style.width = (audio.currentTime / audio.duration * 100) + "%";
    });

    // Seek on click
    progressBar.addEventListener("click", (e) => {
        if (!audio.duration) return;
        const rect = progressBar.getBoundingClientRect();
        audio.currentTime = ((e.clientX - rect.left) / rect.width) * audio.duration;
    });

    volumeSlider.addEventListener("input", () => { audio.volume = parseFloat(volumeSlider.value); });
    playPauseBtn.addEventListener("click", () => { isPlaying ? pauseTrack() : playTrack(); });
    prevBtn.addEventListener("click", goPrev);
    nextBtn.addEventListener("click", () => { goNext(); if (isPlaying) playTrack(); });

    // Collapse / expand
    collapseBtn.addEventListener("click", () => {
        playerFull.style.display = "none";
        playerMini.style.display = "flex";
    });
    playerMini.addEventListener("click", () => {
        playerMini.style.display = "none";
        playerFull.style.display = "flex";
    });

    // Load first track on startup (no autoplay until user clicks)
    loadTrack(currentIndex);
    playTrack(); // Start playing immediately
</script>

<script src="js/editor.js"></script>
</body>
</html>