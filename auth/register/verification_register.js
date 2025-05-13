// Intégration du code de vérification du certificat
const API_KEY = "AIzaSyB9V45g5Ax-Voqjw1J7X61P2gJqkWagS_4";
const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${API_KEY}`;
let mediaStream = null;

// Get current year from server
const currentYear = new Date().getFullYear();
const academicYear = `${currentYear-1}-${currentYear}`;
const academicYearPattern = `${currentYear-1}-${currentYear}`;

document.addEventListener('DOMContentLoaded', function() {
    // Créer et insérer le conteneur d'aperçu du document
    createDocumentPreviewContainer();

    // File upload handling
    document.getElementById('certificate-file').addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Reset verification result
        document.getElementById('verification-result').style.display = 'none';
        document.getElementById('next-after-verification').style.display = 'none';

        // Afficher l'aperçu du document
        showDocumentPreview(file);
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

// Fonction pour créer et insérer le conteneur d'aperçu du document
function createDocumentPreviewContainer() {
    const verificationContainer = document.getElementById('certificate-verification-container');

    // Créer le conteneur d'aperçu
    const previewContainer = document.createElement('div');
    previewContainer.id = 'document-preview-container';
    previewContainer.className = 'hidden mt-6 p-4 rounded-lg border border-gray-200 bg-gray-50 animate__animated animate__fadeIn';
    previewContainer.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Aperçu du document</h3>
            <button id="close-preview" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="document-preview-content" class="flex justify-center">
            <!-- Le contenu de l'aperçu sera inséré ici -->
        </div>
    `;

    // Insérer le conteneur avant le bouton de vérification
    const verifyButton = document.getElementById('verify-certificate');
    verificationContainer.insertBefore(previewContainer, verifyButton);

    // Ajouter l'événement pour fermer l'aperçu
    document.getElementById('close-preview').addEventListener('click', function() {
        document.getElementById('document-preview-container').classList.add('hidden');
    });
}

async function verifyCertificateHandler() {
    const fileInput = document.getElementById('certificate-file');
    const file = fileInput.files[0];

    if (!file) {
        alert('Veuillez sélectionner un fichier ou prendre une photo avant de vérifier.');
        return;
    }

    // S'assurer que l'aperçu du document est affiché
    if (document.getElementById('document-preview-container').classList.contains('hidden')) {
        showDocumentPreview(file);
    }

    const resultContainer = document.getElementById('verification-result');
    const messageDiv = document.getElementById('verification-message');

    resultContainer.style.display = 'block';
    messageDiv.innerHTML = `
        <div class="processing-message">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Vérification en cours...
        </div>`;

    try {
        let text = '';

        if (file.type === 'application/pdf') {
            messageDiv.innerHTML = `
                <div class="processing-message">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Extraction du texte du PDF...
                </div>`;
            text = await extractTextFromPdf(file);
        } else if (file.type.startsWith('image/')) {
            text = await extractTextFromImage(file);
        } else {
            throw new Error('Format de fichier non supporté');
        }

        // Store extracted text in hidden field for server-side validation if needed
        document.getElementById('extracted-text').value = text;

        messageDiv.innerHTML = `
            <div class="processing-message">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Analyse du document...
            </div>`;

        const isCertificate = await verifyCertificate(text);
        const isValidYear = checkAcademicYear(text);

        if (isCertificate && isValidYear) {
            messageDiv.innerHTML = `
                <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">✔️ Certificat de scolarité valide!</p>
                            <p class="text-sm text-green-700 mt-1">✔️ Année académique valide: ${academicYear}</p>
                        </div>
                    </div>
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
                <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-800">✔️ Document est un certificat de scolarité</p>
                            <p class="text-sm text-yellow-700 mt-1">✖️ L'année académique n'est pas valide (attendu: ${academicYear})</p>
                        </div>
                    </div>
                </div>`;
            document.getElementById('certificate_verified').value = '0';
        } else {
            messageDiv.innerHTML = `
                <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">✖️ Le document n'est pas un certificat de scolarité valide.</p>
                        </div>
                    </div>
                </div>`;
            document.getElementById('certificate_verified').value = '0';
        }
    } catch (error) {
        console.error('Erreur:', error);
        messageDiv.innerHTML = `
            <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">Erreur lors de la vérification: ${error.message}</p>
                    </div>
                </div>
            </div>`;
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

// Fonction pour afficher l'aperçu du document
function showDocumentPreview(file) {
    const previewContainer = document.getElementById('document-preview-container');
    const previewContent = document.getElementById('document-preview-content');

    // Vider le contenu précédent
    previewContent.innerHTML = '';

    // Créer l'élément d'aperçu en fonction du type de fichier
    if (file.type === 'application/pdf') {
        // Aperçu PDF
        const objectElement = document.createElement('object');
        objectElement.data = URL.createObjectURL(file);
        objectElement.type = 'application/pdf';
        objectElement.width = '100%';
        objectElement.height = '500px';
        objectElement.className = 'rounded border border-gray-300';

        // Ajouter un message de secours
        objectElement.innerHTML = `
            <div class="p-4 text-center">
                <p>Votre navigateur ne peut pas afficher ce PDF.</p>
                <a href="${URL.createObjectURL(file)}" target="_blank" class="text-blue-600 hover:underline">Ouvrir le PDF</a>
            </div>
        `;

        previewContent.appendChild(objectElement);
    } else if (file.type.startsWith('image/')) {
        // Aperçu image
        const imgElement = document.createElement('img');
        imgElement.src = URL.createObjectURL(file);
        imgElement.alt = 'Aperçu du certificat';
        imgElement.className = 'max-w-full max-h-[500px] object-contain rounded border border-gray-300';

        previewContent.appendChild(imgElement);
    } else {
        // Type de fichier non pris en charge
        previewContent.innerHTML = `
            <div class="p-4 text-center text-red-600">
                <p>Le format de fichier ${file.type} ne peut pas être affiché.</p>
            </div>
        `;
    }

    // Ajouter des informations sur le fichier
    const fileInfoDiv = document.createElement('div');
    fileInfoDiv.className = 'mt-4 text-sm text-gray-600';
    fileInfoDiv.innerHTML = `
        <p><strong>Nom du fichier:</strong> ${file.name}</p>
        <p><strong>Type:</strong> ${file.type || 'Inconnu'}</p>
        <p><strong>Taille:</strong> ${formatFileSize(file.size)}</p>
    `;

    previewContent.appendChild(fileInfoDiv);

    // Afficher le conteneur d'aperçu
    previewContainer.classList.remove('hidden');
}

// Fonction pour formater la taille du fichier
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';

    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
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

    // Essayer d'abord avec le français
    let result = await Tesseract.recognize(
        imageUrl,
        'fra', // French language
        {
            logger: m => {
                if (m.status === 'recognizing text') {
                    const messageDiv = document.getElementById('verification-message');
                    if (messageDiv) {
                        messageDiv.innerHTML = `<div class="processing-message">OCR traitement (français): ${Math.round(m.progress * 100)}%</div>`;
                    }
                }
            }
        }
    );

    let text = result.data.text;

    // Si le texte est court ou ne contient pas de mots clés en français, essayer avec l'arabe
    if (text.length < 100 || (!text.toLowerCase().includes('certificat') && !text.toLowerCase().includes('scolarité') && !text.toLowerCase().includes('attestation'))) {
        try {
            const messageDiv = document.getElementById('verification-message');
            if (messageDiv) {
                messageDiv.innerHTML = `<div class="processing-message">Essai avec la langue arabe...</div>`;
            }

            // Essayer avec l'arabe
            result = await Tesseract.recognize(
                imageUrl,
                'ara', // Arabic language
                {
                    logger: m => {
                        if (m.status === 'recognizing text') {
                            const messageDiv = document.getElementById('verification-message');
                            if (messageDiv) {
                                messageDiv.innerHTML = `<div class="processing-message">OCR traitement (arabe): ${Math.round(m.progress * 100)}%</div>`;
                            }
                        }
                    }
                }
            );

            // Combiner les deux résultats
            text = text + "\n\n" + result.data.text;
        } catch (error) {
            console.error("Erreur lors de la reconnaissance du texte arabe:", error);
            // Continuer avec le texte français uniquement
        }
    }

    URL.revokeObjectURL(imageUrl);
    return text;
}

function checkAcademicYear(text) {
    // Check for current academic year pattern in various formats

    // Format standard (e.g. 2023-2024, 2023/2024, 2023 2024)
    const standardYearRegex = new RegExp(`(20\\d{2}[\\s/-]20\\d{2}|${currentYear-1}[\\s/-]${currentYear})`, 'i');

    // Format avec chiffres arabes (٢٠٢٣-٢٠٢٤)
    // Conversion des chiffres arabes en chiffres latins pour la comparaison
    const arabicDigits = {'٠': '0', '١': '1', '٢': '2', '٣': '3', '٤': '4', '٥': '5', '٦': '6', '٧': '7', '٨': '8', '٩': '9'};

    // Recherche de l'année académique en format arabe
    let hasArabicYear = false;

    // Recherche de motifs comme "العام الدراسي ٢٠٢٣-٢٠٢٤" (année académique 2023-2024)
    if (text.includes('العام الدراسي') || text.includes('السنة الدراسية') || text.includes('السنة الجامعية')) {
        // Extraire les chiffres arabes qui pourraient représenter une année
        const arabicYearPattern = /[٠١٢٣٤٥٦٧٨٩]{4}[\s/-][٠١٢٣٤٥٦٧٨٩]{4}/g;
        const matches = text.match(arabicYearPattern);

        if (matches) {
            for (const match of matches) {
                // Convertir les chiffres arabes en chiffres latins
                let latinDigits = '';
                for (const char of match) {
                    latinDigits += arabicDigits[char] || char;
                }

                // Vérifier si les années correspondent à l'année académique actuelle
                const years = latinDigits.split(/[\s/-]/);
                if (years.length === 2) {
                    const firstYear = parseInt(years[0]);
                    const secondYear = parseInt(years[1]);

                    if (firstYear === currentYear-1 && secondYear === currentYear) {
                        hasArabicYear = true;
                        break;
                    }
                }
            }
        }
    }

    // Vérifier également si l'année actuelle est mentionnée d'une manière ou d'une autre
    const currentYearArabic = currentYear.toString().split('').map(digit => {
        // Trouver la clé arabe correspondant à ce chiffre
        for (const [arabic, latin] of Object.entries(arabicDigits)) {
            if (latin === digit) return arabic;
        }
        return digit;
    }).join('');

    const prevYearArabic = (currentYear-1).toString().split('').map(digit => {
        for (const [arabic, latin] of Object.entries(arabicDigits)) {
            if (latin === digit) return arabic;
        }
        return digit;
    }).join('');

    const hasArabicYearMention = text.includes(currentYearArabic) && text.includes(prevYearArabic);

    // Retourner vrai si l'une des conditions est remplie
    return standardYearRegex.test(text) || hasArabicYear || hasArabicYearMention;
}

async function verifyCertificate(text) {
    const prompt = `Analyze the text below and answer ONLY "yes" or "no": is this specifically a "certificat de scolarité" (certificate of enrollment/school certificate)?
Look for key indicators in French or Arabic:

French indicators:
- The explicit title "certificat de scolarité" or "attestation de scolarité"
- Student information (name, date of birth)
- Academic institution details
- Current academic year (${academicYear})
- Official stamps or signatures mentioned

Arabic indicators:
- "شهادة مدرسية" (school certificate)
- "شهادة التمدرس" (certificate of schooling)
- "إثبات التسجيل" (proof of registration)
- "شهادة تسجيل" (registration certificate)
- Student information in Arabic
- Academic institution details in Arabic
- Current academic year (${academicYear} or the Arabic equivalent)
- Official stamps or signatures mentioned in Arabic

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

    // Vérification manuelle pour les termes arabes courants dans les certificats de scolarité
    const arabicTerms = [
        'شهادة مدرسية',
        'شهادة التمدرس',
        'إثبات التسجيل',
        'شهادة تسجيل',
        'الشهادة المدرسية',
        'السنة الدراسية',
        'السنة الجامعية',
        'العام الدراسي'
    ];

    const containsArabicTerms = arabicTerms.some(term => text.includes(term));

    return reply.includes("yes") || containsArabicTerms;
}