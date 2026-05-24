// init CodeMirror
const editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
    mode: "xml",
    theme: "default",
    lineNumbers: true,
    tabSize: 2,
    lineWrapping: true
});
const preview = document.getElementById("preview");
const saveBtn = document.getElementById("saveBtn");

// live preview
function updatePreview() {
    preview.srcdoc = editor.getValue();
}
editor.on("change", updatePreview);
updatePreview();

// templates
const templates = {
    minimal: `
<html>
  <body style="font-family: Arial; background: white; padding: 20px;">
    <h1 style="color: #333;">My Profile</h1>
    <p style="color: #666;">This is my bio.</p>
    <blockquote style="color: #999; font-style: italic;">"Favorite quote here."</blockquote>
  </body>
</html>`,
    dark: `
<html>
  <body style="font-family: Arial; background: #1a1a2e; padding: 20px; color: white;">
    <h1 style="color: #e94560;">My Profile</h1>
    <p style="color: #ccc;">This is my bio.</p>
    <blockquote style="color: #e94560; font-style: italic;">"Favorite quote here."</blockquote>
  </body>
</html>`,
    gamer: `
<html>
  <body style="font-family: 'Courier New'; background: #0d0d0d; padding: 20px; color: #00ff00;">
    <h1 style="color: #00ff00; text-shadow: 0 0 10px #00ff00;">My Profile</h1>
    <p style="color: #00cc00;">This is my bio.</p>
    <blockquote style="color: #ff00ff; font-style: italic;">"Favorite quote here."</blockquote>
  </body>
</html>`
};

document.getElementById("templateMinimal").onclick = () => {
    editor.setValue(templates.minimal.trim());
    updatePreview();
};
document.getElementById("templateDark").onclick = () => {
    editor.setValue(templates.dark.trim());
    updatePreview();
};
document.getElementById("templateGamer").onclick = () => {
    editor.setValue(templates.gamer.trim());
    updatePreview();
};

// save
saveBtn.onclick = async () => {
    const html = editor.getValue();
    const res = await fetch("api/updateProfile.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ userId: USER_ID, html })
    });
    if (res.ok) {
        alert("Profile saved!");
        await reloadFromBackend();
    } else {
        alert("Failed to save profile");
    }
};

async function reloadFromBackend() {
    const res = await fetch(`api/getProfile.php?id=${USER_ID}`);
    const data = await res.json();
    editor.setValue(data.html);
    updatePreview();
}

// ── Image Upload ────────────────────────────────────────────────

const uploadBtn   = document.getElementById("uploadImgBtn");
const modal       = document.getElementById("uploadModal");
const modalClose  = document.getElementById("uploadModalClose");
const dropZone    = document.getElementById("uploadDrop");
const fileInput   = document.getElementById("uploadFileInput");
const imgPreview  = document.getElementById("uploadPreview");
const submitBtn   = document.getElementById("uploadSubmitBtn");
const statusMsg   = document.getElementById("uploadStatus");
const resultBox   = document.getElementById("uploadResult");
const urlInput    = document.getElementById("uploadUrl");
const copyBtn     = document.getElementById("uploadCopyBtn");
const insertBtn   = document.getElementById("uploadInsertBtn");

let chosenFile = null;

function openModal() {
    modal.classList.remove("hidden");
    resetModal();
}
function closeModal() {
    modal.classList.add("hidden");
}
function resetModal() {
    chosenFile = null;
    fileInput.value = "";
    imgPreview.classList.add("hidden");
    imgPreview.src = "";
    resultBox.classList.add("hidden");
    submitBtn.disabled = true;
    submitBtn.textContent = "Upload";
    setStatus("");
}

function setStatus(msg, isError = false) {
    if (!msg) { statusMsg.classList.add("hidden"); return; }
    statusMsg.textContent = msg;
    statusMsg.className = "text-xs mt-1 " + (isError ? "text-red-400" : "text-gray-400");
    statusMsg.classList.remove("hidden");
}

function pickFile(file) {
    if (!file) return;
    if (!file.type.startsWith("image/")) { setStatus("Only image files allowed.", true); return; }
    if (file.size > 5 * 1024 * 1024)    { setStatus("Max file size is 5 MB.", true); return; }
    chosenFile = file;
    imgPreview.src = URL.createObjectURL(file);
    imgPreview.classList.remove("hidden");
    resultBox.classList.add("hidden");
    submitBtn.disabled = false;
    setStatus("");
}

// open / close
uploadBtn.addEventListener("click", openModal);
modalClose.addEventListener("click", closeModal);
modal.addEventListener("click", e => { if (e.target === modal) closeModal(); });

// file input
dropZone.addEventListener("click", () => fileInput.click());
fileInput.addEventListener("change", e => pickFile(e.target.files[0]));

// drag-and-drop
dropZone.addEventListener("dragover", e => { e.preventDefault(); dropZone.classList.add("border-green-400"); });
dropZone.addEventListener("dragleave", ()  => dropZone.classList.remove("border-green-400"));
dropZone.addEventListener("drop", e => {
    e.preventDefault();
    dropZone.classList.remove("border-green-400");
    pickFile(e.dataTransfer.files[0]);
});

// upload
submitBtn.addEventListener("click", async () => {
    if (!chosenFile) return;
    submitBtn.disabled = true;
    submitBtn.textContent = "Uploading…";
    setStatus("Uploading…");

    const fd = new FormData();
    fd.append("photo", chosenFile);

    try {
        const res  = await fetch("api/upload_photo.php", { method: "POST", body: fd });
        const data = await res.json();

        if (!res.ok || data.error) {
            setStatus("Error: " + (data.error || res.status), true);
            submitBtn.disabled = false;
            submitBtn.textContent = "Upload";
            return;
        }

        urlInput.value = data.url;
        resultBox.classList.remove("hidden");
        setStatus("");
        submitBtn.textContent = "✓ Done";

    } catch (err) {
        setStatus("Network error: " + err.message, true);
        submitBtn.disabled = false;
        submitBtn.textContent = "Upload";
    }
});

// copy URL to clipboard
copyBtn.addEventListener("click", () => {
    navigator.clipboard.writeText(urlInput.value).then(() => {
        copyBtn.textContent = "Copied!";
        setTimeout(() => copyBtn.textContent = "Copy", 2000);
    });
});

// insert <img> tag at cursor position in the editor
insertBtn.addEventListener("click", () => {
    const tag = `<img src="${urlInput.value}" alt="" style="max-width:100%;">`;
    editor.replaceSelection(tag);
    updatePreview();
    closeModal();
});