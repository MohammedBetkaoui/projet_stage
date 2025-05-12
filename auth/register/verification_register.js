// Intégration du code de vérification du certificat
const API_KEY = "AIzaSyB9V45g5Ax-Voqjw1J7X61P2gJqkWagS_4";
const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${API_KEY}`;
let mediaStream = null;

// Get current year from server
const currentYear = new Date().getFullYear();
const academicYear = `${currentYear-1}-${currentYear}`;
const academicYearPattern = `${currentYear-1}-${currentYear}`;

document.addEventListener('DOMContentLoaded', function() {
    // File upload handling
    document.getElementById('certificate-file').addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Reset verification result
        document.getElementById('verification-result').style.display = 'none';
        document.getElementById('next-after-verification').style.display = 'none';
    });

    // Camera functionality
    const cameraBtn = document.getElementById('camera-btn');
    if (cameraBtn) {
        cameraBtn.addEventListener('click', openCamera);
    }

    document.getElementById('close-camera').addEventListener('click', closeCamera);
    document.getElementById('capture-photo').addEventListener('click', capturePhoto);
    document.getElementById('verify-certificate').addEventListener('click', verifyCertificateHandler);
});

async function verifyCertificateHandler() {
    const fileInput = document.getElementById('certificate-file');
    const file = fileInput.files[0];

    if (!file) {
        alert('Veuillez sélectionner un fichier ou prendre une photo avant de vérifier.');
        return;
    }

    const resultContainer = document.getElementById('verification-result');
    const messageDiv = document.getElementById('verification-message');

    resultContainer.style.display = 'block';
    messageDiv.innerHTML = '<div class="processing-message">Vérification en cours...</div>';

    try {
        let text = '';

        if (file.type === 'application/pdf') {
            text = await extractTextFromPdf(file);
        } else if (file.type.startsWith('image/')) {
            text = await extractTextFromImage(file);
        } else {
            throw new Error('Format de fichier non supporté');
        }

        // Store extracted text in hidden field for server-side validation if needed
        document.getElementById('extracted-text').value = text;

        const isCertificate = await verifyCertificate(text);
        const isValidYear = checkAcademicYear(text);

        if (isCertificate && isValidYear) {
            messageDiv.innerHTML = `
                <div style="color: green;">
                    <p>✔️ Certificat de scolarité valide!</p>
                    <p>✔️ Année académique valide: ${academicYear}</p>
                </div>`;
            document.getElementById('certificate_verified').value = '1';
            document.getElementById('next-after-verification').style.display = 'inline-block';

            // Stocker en session pour la validation côté serveur
            await fetch('store_verification.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    verified: true,
                    academicYear: academicYear
                }),
            });
        } else if (isCertificate && !isValidYear) {
            messageDiv.innerHTML = `
                <div style="color: orange;">
                    <p>✔️ Document est un certificat de scolarité</p>
                    <p>✖️ L'année académique n'est pas valide (attendu: ${academicYear})</p>
                </div>`;
            document.getElementById('certificate_verified').value = '0';
        } else {
            messageDiv.innerHTML = '<p style="color: red;">✖️ Le document n\'est pas un certificat de scolarité valide.</p>';
            document.getElementById('certificate_verified').value = '0';
        }
    } catch (error) {
        console.error('Erreur:', error);
        messageDiv.innerHTML = `<p style="color: red;">Erreur lors de la vérification: ${error.message}</p>`;
    }
}

// Open camera stream
async function openCamera() {
    try {
        // Show camera interface
        document.getElementById('camera-container').classList.remove('hidden');

        // Access the device camera
        mediaStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: "environment" }, // Use the rear camera if available
            audio: false
        });

        // Connect camera stream to video element
        const videoElement = document.getElementById('camera-preview');
        videoElement.srcObject = mediaStream;
    } catch (error) {
        console.error("Camera access error:", error);
        alert("Could not access camera. Please make sure your device has a camera and you've granted permission.");
        document.getElementById('camera-container').classList.add('hidden');
    }
}

// Close camera stream
function closeCamera() {
    document.getElementById('camera-container').classList.add('hidden');

    // Stop the media stream
    if (mediaStream) {
        mediaStream.getTracks().forEach(track => track.stop());
        mediaStream = null;
    }
}

// Capture photo from camera
function capturePhoto() {
    const video = document.getElementById('camera-preview');
    const canvas = document.getElementById('captured-canvas');

    // Set canvas dimensions to match video
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    // Draw the current video frame to the canvas
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Convert canvas to blob (file)
    canvas.toBlob(async (blob) => {
        // Create file object from blob
        const capturedImage = new File([blob], "captured-image.jpg", { type: "image/jpeg" });

        // Close camera
        closeCamera();

        // Set the captured image as the file input value
        const fileInput = document.getElementById('certificate-file');
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(capturedImage);
        fileInput.files = dataTransfer.files;

        // Trigger change event
        const event = new Event('change');
        fileInput.dispatchEvent(event);
    }, "image/jpeg", 0.95);
}

async function extractTextFromPdf(file) {
    const PDF_URL = URL.createObjectURL(file);
    const pdf = await pdfjsLib.getDocument(PDF_URL).promise;
    let allText = '';

    for (let i = 1; i <= pdf.numPages; i++) {
        const page = await pdf.getPage(i);
        const content = await page.getTextContent();
        allText += content.items.map(item => item.str).join(' ') + '\n\n';
    }

    URL.revokeObjectURL(PDF_URL);
    return allText;
}

async function extractTextFromImage(file) {
    const imageUrl = URL.createObjectURL(file);
    const result = await Tesseract.recognize(
        imageUrl,
        'fra', // French language
        {
            logger: m => {
                if (m.status === 'recognizing text') {
                    const messageDiv = document.getElementById('verification-message');
                    if (messageDiv) {
                        messageDiv.innerHTML = `<div class="processing-message">OCR processing: ${Math.round(m.progress * 100)}%</div>`;
                    }
                }
            }
        }
    );

    URL.revokeObjectURL(imageUrl);
    return result.data.text;
}

function checkAcademicYear(text) {
    // Check for current academic year pattern (e.g. 2023-2024)
    const yearRegex = new RegExp(`(20\\d{2}[\\s/-]20\\d{2}|${currentYear-1}[\\s/-]${currentYear})`, 'i');
    return yearRegex.test(text);
}

async function verifyCertificate(text) {
    const prompt = `Analyze the text below and answer ONLY "yes" or "no": is this specifically a "certificat de scolarité" (certificate of enrollment/school certificate)?
Look for key indicators like:
- The explicit title "certificat de scolarité" or "attestation de scolarité"
- Student information (name, date of birth)
- Academic institution details
- Current academic year (${academicYear})
- Official stamps or signatures mentioned

Here's the text:
---
${text}`;

    const response = await fetch(API_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            contents: [{
                parts: [{ text: prompt }]
            }],
            generationConfig: {
                temperature: 0.2,
                maxOutputTokens: 20
            }
        })
    });

    const data = await response.json();
    const reply = data?.candidates?.[0]?.content?.parts?.[0]?.text?.toLowerCase() || "";

    return reply.includes("yes");
}