
// Intégration du code de vérification du certificat
const API_KEY = "AIzaSyB9V45g5Ax-Voqjw1J7X61P2gJqkWagS_4";
const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${API_KEY}`;

document.getElementById('verify-certificate').addEventListener('click', async function() {
    const fileInput = document.getElementById('certificate-file');
    const file = fileInput.files[0];
    
    if (!file) {
        alert('Veuillez sélectionner un fichier avant de vérifier.');
        return;
    }
    
    const resultContainer = document.getElementById('verification-result');
    const messageDiv = document.getElementById('verification-message');
    
    resultContainer.style.display = 'block';
    messageDiv.innerHTML = '<p>Vérification en cours...</p>';
    
    try {
        let text = '';
        
        if (file.type === 'application/pdf') {
            text = await extractTextFromPdf(file);
        } else if (file.type.startsWith('image/')) {
            text = await extractTextFromImage(file);
        } else {
            throw new Error('Format de fichier non supporté');
        }
        
        const isCertificate = await verifyCertificate(text);
        
        if (isCertificate) {
            messageDiv.innerHTML = '<p style="color: green;">✔ Certificat de scolarité valide!</p>';
            document.getElementById('certificate_verified').value = '1';
            document.getElementById('next-after-verification').style.display = 'inline-block';
            // Stocker en session pour la validation côté serveur
            fetch('store_verification.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ verified: true }),
            });
        } else {
            messageDiv.innerHTML = '<p style="color: red;">✖ Le document n\'est pas un certificat de scolarité valide.</p>';
            document.getElementById('certificate_verified').value = '0';
        }
    } catch (error) {
        console.error('Erreur:', error);
        messageDiv.innerHTML = `<p style="color: red;">Erreur lors de la vérification: ${error.message}</p>`;
    }
});

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
    const result = await Tesseract.recognize(imageUrl, 'fra');
    URL.revokeObjectURL(imageUrl);
    return result.data.text;
}

async function verifyCertificate(text) {
    const prompt = `Analyze the text below and answer ONLY "yes" or "no": is this specifically a "certificat de scolarité" (certificate of enrollment/school certificate)?
Look for key indicators like:
- The explicit title "certificat de scolarité" or "attestation de scolarité"
- Student information (name, date of birth)
- Academic institution details
- Current academic year
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
