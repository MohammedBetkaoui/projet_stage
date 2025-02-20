// Configuration globale
const config = {
    apiBaseUrl: '/api',
    csrfToken: document.querySelector('meta[name="csrf-token"]')?.content
};

// Classe principale de l'application
class App {
    constructor() {
        this.initEventListeners();
        this.initForms();
        this.initDatePickers();
        this.initNotifications();
    }

    // Initialisation des écouteurs d'événements
    initEventListeners() {
        // Menu mobile
        document.querySelector('.mobile-menu-btn')?.addEventListener('click', () => {
            document.querySelector('.nav-links').classList.toggle('active');
        });

        // Recherche en temps réel
        document.getElementById('search-input')?.addEventListener('input', this.handleSearch.bind(this));

        // Fermeture des messages flash
        document.querySelectorAll('.alert .close').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.target.closest('.alert').remove();
            });
        });
    }

    // Gestion des formulaires
    initForms() {
        document.querySelectorAll('form[data-ajax]').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                await this.handleAjaxFormSubmit(form);
            });
        });
    }

    // Gestion de la recherche
    async handleSearch(e) {
        const searchTerm = e.target.value;
        const container = document.getElementById('search-results');
        
        if (!container || searchTerm.length < 2) {
            container?.classList.add('hidden');
            return;
        }

        try {
            const response = await fetch(`${config.apiBaseUrl}/search.php?keywords=${encodeURIComponent(searchTerm)}`);
            const data = await response.json();
            
            if (data.success) {
                this.displaySearchResults(data.results, container);
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    }

    // Affichage des résultats de recherche
    displaySearchResults(results, container) {
        container.innerHTML = results.map(result => `
            <div class="search-result-item">
                <h3>${this.escapeHtml(result.title)}</h3>
                <p>${this.escapeHtml(result.company_name)} - ${this.escapeHtml(result.location)}</p>
                <a href="/student/apply.php?id=${result.id}">Voir l'offre</a>
            </div>
        `).join('');
        
        container.classList.remove('hidden');
    }

    // Soumission des formulaires AJAX
    async handleAjaxFormSubmit(form) {
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;

        try {
            submitBtn.innerHTML = '<div class="spinner"></div>';
            submitBtn.disabled = true;

            const response = await fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': config.csrfToken
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showAlert('success', result.message || 'Opération réussie');
                if (form.dataset.redirect) {
                    window.location.href = form.dataset.redirect;
                }
            } else {
                this.showAlert('error', result.error || 'Une erreur est survenue');
            }
        } catch (error) {
            this.showAlert('error', 'Erreur de connexion');
            console.error('Form submit error:', error);
        } finally {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
    }

    // Affichage des notifications
    showAlert(type, message) {
        const alert = document.createElement('div');
        alert.className = `alert ${type}`;
        alert.innerHTML = `
            <span>${this.escapeHtml(message)}</span>
            <button class="close">&times;</button>
        `;
        
        document.body.prepend(alert);
        setTimeout(() => alert.remove(), 5000);
    }

    // Sécurité : échappement HTML
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Initialisation des datepickers
    initDatePickers() {
        document.querySelectorAll('.datepicker').forEach(input => {
            new Datepicker(input, {
                format: 'yyyy-mm-dd',
                autohide: true
            });
        });
    }

    // Gestion des notifications (complément)
    initNotifications() {
        document.getElementById('notifications-bell')?.addEventListener('click', this.toggleNotifications);
    }

    toggleNotifications() {
        document.getElementById('notifications-panel').classList.toggle('hidden');
    }
}

// Initialisation de l'app quand le DOM est prêt
document.addEventListener('DOMContentLoaded', () => {
    window.app = new App();
});

// Fonctions utilitaires globales
function debounce(func, timeout = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('fr-FR', options);
}

// API Helpers
async function fetchData(endpoint, params = {}) {
    const url = new URL(`${config.apiBaseUrl}/${endpoint}`);
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));
    
    const response = await fetch(url);
    return response.json();
}