const video = document.getElementById("myVideo");
const myBtn = document.getElementById("myBtn");
const takeBtn = document.getElementById("takePhoto");
const capturedImage = document.getElementById("capturedImage");
const canvas = document.getElementById("overlay");
const context = canvas.getContext("2d");
let fileInput = document.getElementById("fileInput");
const cameraModal = new bootstrap.Modal(document.getElementById("cameraModal"));
const cameraList = document.getElementById("cameraList");

let currentStream;
let selectedDeviceId;
let withFaceLandmarks = false;
let withBoxes = true;

// Carregar os modelos face-api.js
async function loadModels() {
    await faceapi.nets.tinyFaceDetector.loadFromUri("/porto/face-api/weights");
    await faceapi.nets.faceLandmark68Net.loadFromUri("/porto/face-api/weights");
    await faceapi.nets.faceRecognitionNet.loadFromUri(
        "/porto/face-api/weights"
    );
}

loadModels();

async function detectFaces() {
    const options = new faceapi.TinyFaceDetectorOptions();
    const results = await faceapi
        .detectAllFaces(video, options)
        .withFaceLandmarks()
        .withFaceDescriptors();
    return results;
}

function dataURLtoFile(dataurl, filename) {
    var arr = dataurl.split(","),
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]),
        n = bstr.length,
        u8arr = new Uint8Array(n);
    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new File([u8arr], filename, { type: mime });
}

async function startVideo(deviceId) {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        noty('', "Seu navegador não suporta este recurso", 'warning');
        return;
    }

    try {
        // Parar qualquer stream existente
        if (currentStream) {
            currentStream.getTracks().forEach((track) => track.stop());
        }

        const constraints = {
            video: {
                deviceId: deviceId ? { exact: deviceId } : undefined,
            },
        };

        const stream = await navigator.mediaDevices.getUserMedia(constraints);
        currentStream = stream;

        video.srcObject = stream;
        video.play();
        capturedImage.style.display = "none"; // Esconder a imagem quando a câmera for ligada
        video.style.display = "block"; // Mostrar o vídeo

        video.onloadedmetadata = () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
        };

        const interval = setInterval(async () => {
            const facesDetected = await detectFaces();
            context.clearRect(0, 0, canvas.width, canvas.height); // Limpar o canvas

            if (facesDetected.length === 1) {
                const dims = faceapi.matchDimensions(canvas, video, true);
                const resizedResults = faceapi.resizeResults(
                    facesDetected,
                    dims
                );
                if (withBoxes) {
                    faceapi.draw.drawDetections(canvas, resizedResults);
                }
                if (withFaceLandmarks) {
                    faceapi.draw.drawFaceLandmarks(canvas, resizedResults);
                }
                console.log("Um rosto encontrado.");
                takeBtn.disabled = false;
            } else {
                console.log(`${facesDetected.length} rostos encontrados.`);
                takeBtn.disabled = true;
            }
        }, 500);

        video.onpause = () => clearInterval(interval);
        video.onended = () => clearInterval(interval);
    } catch (error) {
        console.error("Erro desconhecido:", error);
        handleCameraError(error);
    }
}

function handleCameraError(error) {
    if (error.name === "NotAllowedError") {
        noty('', "Permissão para acessar a câmera foi negada.", 'warning');
    } else if (error.name === "NotFoundError") {
        noty('', "Nenhuma câmera foi encontrada.", 'warning');
    } else if (
        error.name === "NotReadableError" ||
        error.name === "TrackStartError"
    ) {
        noty('', "A câmera está sendo usada por outro aplicativo.", 'warning');
    } else {
        noty('', "Erro ao acessar a câmera: " + error.message, 'warning');
    }
}

async function listCameras() {
    try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        const videoDevices = devices.filter(
            (device) => device.kind === "videoinput"
        );

        cameraList.innerHTML = "";
        videoDevices.forEach((device) => {
            const listItem = document.createElement("li");
            listItem.classList.add("list-group-item");
            listItem.textContent = device.label || `Camera ${device.deviceId}`;
            listItem.addEventListener("click", () => {
                selectedDeviceId = device.deviceId;
                cameraModal.hide();
                startVideo(selectedDeviceId);
            });
            cameraList.appendChild(listItem);
        });
    } catch (error) {
        console.error("Erro ao listar câmeras:", error);
        noty('', "Erro ao listar câmeras: " + error.message, 'warning');
    }
}

myBtn.addEventListener("click", (e) => {
    e.preventDefault();
    listCameras();
    cameraModal.show();
});

takeBtn.addEventListener("click", async (e) => {
    let photo = document.getElementById("photo");
    e.preventDefault();
    const facesDetected = await detectFaces();

    if (facesDetected.length !== 1) {
        noty(
            '', "Por favor, certifique-se de que há apenas um rosto visível na câmera.", 'warning'
        );
        return;
    }

    canvas.height = video.videoHeight;
    canvas.width = video.videoWidth;
    context.drawImage(video, 0, 0);

    const imageData = canvas.toDataURL("image/png");
    capturedImage.src = imageData;
    capturedImage.style.display = "block";
    video.style.display = "none";

    const file = dataURLtoFile(imageData, "capturedImage.png");

    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    photo.files = dataTransfer.files;
    // fileInput = dataTransfer.files;
});

fileInput.addEventListener("change", (e) => {
    const file = e.target.files[0];
    if (!file) {
        return;
    }

    const reader = new FileReader();
    reader.onload = (event) => {
        const img = new Image();
        img.onload = () => {
            canvas.width = img.width;
            canvas.height = img.height;
            context.drawImage(img, 0, 0, img.width, img.height);
            const imageData = canvas.toDataURL("image/png");
            capturedImage.src = imageData;
            capturedImage.style.display = "block";
            video.style.display = "none";
        };
        img.src = event.target.result;
    };
    reader.readAsDataURL(file);
});
