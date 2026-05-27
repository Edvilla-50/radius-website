<link rel="icon" type="image/jpeg" href="/image/radius-image.jpg">
<?php
$userId = $_GET['id'] ?? null;
if (!$userId) {
    http_response_code(400);
    echo "Missing user id";
    exit;
}

// call backend to get current HTML
$backend = "https://radius-backend-0qv8.onrender.com"; // change when hosted
$response = file_get_contents("$backend/user/$userId/profile-html");
$data = json_decode($response, true);
$currentHtml = $data["html"] ?? "<html><body><h1>New Profile</h1></body></html>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Radius Profile Builder</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CodeMirror -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>

    <style>
        /* Music Player Styles */
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
            transition: all 0.3s ease;
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
            position: relative;
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
            0%   { transform: scale(1); opacity: 0.6; }
            100% { transform: scale(1.6); opacity: 0; }
        }
    </style>
</head>
<body class="bg-gray-900 text-white">
<div class="flex flex-col h-screen">

    <!-- Top bar -->
    <div class="flex items-center justify-between px-6 py-4 bg-gray-800 shadow">
        <h1 class="text-xl font-bold text-green-400">Radius Profile Builder</h1>
        <div class="flex gap-3">
            <button id="templateMinimal" class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">
                Minimal
            </button>
            <button id="templateDark" class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">
                Dark
            </button>
            <button id="templateGamer" class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">
                Gamer
            </button>
            <button id="saveBtn" class="px-4 py-2 bg-green-500 rounded hover:bg-green-400 text-sm font-semibold">
                Save
            </button>
        </div>
    </div>

    <!-- Main content -->
    <div class="flex flex-1 overflow-hidden">

        <!-- Editor -->
        <div class="w-1/2 h-full border-r border-gray-700">
            <textarea id="editor"><?= htmlspecialchars($currentHtml) ?></textarea>
        </div>

        <!-- Preview -->
        <div class="w-1/2 h-full bg-white">
            <iframe id="preview" class="w-full h-full border-0"></iframe>
        </div>

    </div>
</div>

<!-- ─── Music Player (expanded) ─── -->
<div id="music-player">
    <!-- Collapse button -->
    <button id="collapseBtn" title="Minimize" style="position:absolute;top:8px;right:8px;width:20px;height:20px;font-size:11px;color:#475569;">
        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M19 15l-7 7-7-7"/>
        </svg>
    </button>

    <!-- Prev -->
    <button id="prevBtn" title="Previous">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/>
        </svg>
    </button>

    <!-- Play/Pause -->
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

<!-- ─── Music Player (collapsed) ─── -->
<div id="player-collapsed" title="Open player">
    <div class="pulse-ring" id="pulseRing" style="display:none"></div>
    <svg viewBox="0 0 24 24" width="22" height="22" fill="#4ade80">
        <path d="M12 3v10.55A4 4 0 1014 17V7h4V3h-6z"/>
    </svg>
</div>

<audio id="audioPlayer"></audio>

<script>
    const USER_ID = <?= (int)$userId ?>;

    // ─── MUSIC PLAYER ───────────────────────────────────────────────
    // 🎵 Replace these paths with your actual audio files
    const tracks = [
        { name: "Track 1", src: "ES_Are You Happy Now? - The Big Let Down.mp3" },
        { name: "Track 2", src: "ES_Girlfriend (Instrumental Version) - Martin Landh.mp3" },
        { name: "Track 3", src: "ES_Parrot Prince - Ryan James Carr.mp3" },
        { name: "Track 4", src: "ES_Poolside (Instrumental Version) - Maybe.mp3"},
    ];

    let currentIndex = 0;
    let isPlaying = false;

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
        trackName.textContent = track.name;
        trackCounter.textContent = `${index + 1} / ${tracks.length} · looping`;
        progressFill.style.width = "0%";
    }

    function playTrack() {
        audio.play().catch(() => {}); // catch autoplay block gracefully
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

    function nextTrack() {
        currentIndex = (currentIndex + 1) % tracks.length;
        loadTrack(currentIndex);
        if (isPlaying) playTrack();
    }

    function prevTrack() {
        // restart current track if past 3s, else go to previous
        if (audio.currentTime > 3) {
            audio.currentTime = 0;
        } else {
            currentIndex = (currentIndex - 1 + tracks.length) % tracks.length;
            loadTrack(currentIndex);
            if (isPlaying) playTrack();
        }
    }

    // Auto-advance to next track when one ends; loops back after track 4
    audio.addEventListener("ended", () => {
        nextTrack();
        playTrack();
    });

    // Update progress bar
    audio.addEventListener("timeupdate", () => {
        if (audio.duration) {
            progressFill.style.width = (audio.currentTime / audio.duration * 100) + "%";
        }
    });

    // Seek on progress bar click
    progressBar.addEventListener("click", (e) => {
        if (!audio.duration) return;
        const rect = progressBar.getBoundingClientRect();
        const ratio = (e.clientX - rect.left) / rect.width;
        audio.currentTime = ratio * audio.duration;
    });

    // Volume
    volumeSlider.addEventListener("input", () => {
        audio.volume = parseFloat(volumeSlider.value);
    });

    // Play/Pause button
    playPauseBtn.addEventListener("click", () => {
        if (isPlaying) { pauseTrack(); } else { playTrack(); }
    });

    prevBtn.addEventListener("click", prevTrack);
    nextBtn.addEventListener("click", () => { nextTrack(); if (isPlaying) playTrack(); });

    // Collapse / expand
    collapseBtn.addEventListener("click", () => {
        playerFull.style.display = "none";
        playerMini.style.display = "flex";
    });
    playerMini.addEventListener("click", () => {
        playerMini.style.display = "none";
        playerFull.style.display = "flex";
    });

    // Load first track on startup (don't autoplay until user clicks)
    loadTrack(currentIndex);
</script>

<script src="js/editor.js"></script>
</body>
</html>