$modelsUrl = "https://raw.githubusercontent.com/justadudewhohacks/face-api.js/master/weights/"
$models = @(
    "ssd_mobilenetv1_model-weights_manifest.json",
    "ssd_mobilenetv1_model-shard1",
    "ssd_mobilenetv1_model-shard2",
    "face_landmark_68_model-weights_manifest.json",
    "face_landmark_68_model-shard1",
    "face_recognition_model-weights_manifest.json",
    "face_recognition_model-shard1",
    "face_recognition_model-shard2"
)

$targetDir = "C:\Users\Administrator\Desktop\project__intern\htdocs\xampp\student attendence tracking protocol\assets\models"
if (-not (Test-Path $targetDir)) {
    New-Item -ItemType Directory -Path $targetDir
}

foreach ($model in $models) {
    $url = $modelsUrl + $model
    $dest = Join-Path $targetDir $model
    if (-not (Test-Path $dest)) {
        Write-Host "Downloading $model..."
        Invoke-WebRequest -Uri $url -OutFile $dest
    } else {
        Write-Host "$model already exists."
    }
}

$jsUrl = "https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"
$jsDest = "C:\Users\Administrator\Desktop\project__intern\htdocs\xampp\student attendence tracking protocol\assets\js\face-api.min.js"
if (-not (Test-Path $jsDest)) {
    Write-Host "Downloading face-api.min.js..."
    Invoke-WebRequest -Uri $jsUrl -OutFile $jsDest
} else {
    Write-Host "face-api.min.js already exists."
}

Write-Host "Done!"
