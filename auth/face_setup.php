<?php
session_start();
require_once '../includes/auth_guard.php';
check_auth(['admin', 'faculty']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Face ID - Cyberpunk Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .camera-container {
            position: relative;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.2);
            border: 2px solid var(--neon-blue);
        }
        video {
            width: 100%;
            height: auto;
            display: block;
        }
        canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .overlay-msg {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h2 class="text-white mb-4"><i class="bi bi-person-bounding-box text-neon-blue"></i> Face ID Setup</h2>
                <p class="text-secondary mb-4">Position your face in the center of the camera. We will scan your face to enable passwordless login.</p>
                
                <div class="camera-container mb-4" id="setupCamera" style="position:relative; overflow: hidden; border-radius: 10px;">
                    <video id="video" autoplay muted playsinline style="width: 100%; border-radius: 10px;"></video>
                    <canvas id="canvas" style="position: absolute; top:0; left:0; width: 100%; height:100%; z-index: 15;"></canvas>
                    <div class="scanner-laser"></div>
                    <div class="face-box-overlay"></div>
                    <div id="status-msg" class="overlay-msg" style="z-index: 20;">Loading models...</div>
                </div>

                <button id="capture-btn" class="btn btn-primary px-5 py-2 fw-bold" disabled>Capture Face</button>
                <a href="../<?php echo $_SESSION['role']; ?>/dashboard.php" class="btn btn-outline-secondary ms-3">Cancel</a>
            </div>
        </div>
    </div>

    <script src="../assets/js/face-api.min.js"></script>
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('capture-btn');
        const statusMsg = document.getElementById('status-msg');
        let stream = null;

        async function initFaceAPI() {
            try {
                statusMsg.textContent = "Loading AI models...";
                await Promise.all([
                    faceapi.nets.ssdMobilenetv1.loadFromUri('../assets/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('../assets/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('../assets/models')
                ]);
                statusMsg.textContent = "Requesting camera access...";
                startVideo();
            } catch (err) {
                console.error(err);
                statusMsg.textContent = "Error loading models: " + err.message;
            }
        }

        function startVideo() {
            navigator.mediaDevices.getUserMedia({ video: {} })
                .then(s => {
                    stream = s;
                    video.srcObject = stream;
                })
                .catch(err => {
                    console.error(err);
                    statusMsg.textContent = "Camera access denied.";
                });
        }

        video.addEventListener('play', () => {
            statusMsg.textContent = "Scanning face... Please wait.";
            document.getElementById('setupCamera').classList.add('scanner-active');
            
            const displaySize = { width: video.videoWidth || 600, height: video.videoHeight || 450 };
            faceapi.matchDimensions(canvas, displaySize);

            const interval = setInterval(async () => {
                if (video.paused || video.ended) return clearInterval(interval);
                
                const detections = await faceapi.detectSingleFace(video).withFaceLandmarks().withFaceDescriptor();
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                if (detections) {
                    const resizedDetections = faceapi.resizeResults(detections, displaySize);
                    faceapi.draw.drawDetections(canvas, resizedDetections);
                    faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
                    
                    statusMsg.textContent = "Face detected! Click Capture.";
                    document.getElementById('setupCamera').classList.remove('scanner-active');
                    captureBtn.disabled = false;
                    captureBtn.onclick = () => saveFace(detections.descriptor);
                } else {
                    statusMsg.textContent = "No face detected. Please look at the camera.";
                    document.getElementById('setupCamera').classList.add('scanner-active');
                    captureBtn.disabled = true;
                }
            }, 100);
        });

        async function saveFace(descriptor) {
            statusMsg.textContent = "Saving face data...";
            captureBtn.disabled = true;
            
            try {
                // Convert Float32Array to regular Array for JSON
                const descArray = Array.from(descriptor);
                const response = await fetch('../api/register_face.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ descriptor: descArray })
                });
                
                const data = await response.json();
                if (data.success) {
                    statusMsg.textContent = "Face registered successfully!";
                    statusMsg.style.background = "rgba(0,255,0,0.7)";
                    setTimeout(() => {
                        window.location.href = `../<?php echo $_SESSION['role']; ?>/dashboard.php`;
                    }, 2000);
                } else {
                    statusMsg.textContent = data.message || "Error saving face.";
                    captureBtn.disabled = false;
                }
            } catch (e) {
                statusMsg.textContent = "Network error.";
                captureBtn.disabled = false;
            }
        }

        initFaceAPI();
    </script>
</body>
</html>
