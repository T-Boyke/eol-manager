document.addEventListener('DOMContentLoaded', function() {
    const apiBase = '/eol-api'; // Base URL for API
    let oceanData = [];

    const dashboardView = document.getElementById('dashboard-view');
    const editorView = document.getElementById('editor-view');
    const oceanList = document.getElementById('ocean-list');
    const editorForm = document.getElementById('editor-form');

    // Fetch Data
    fetch(apiBase + '/data')
        .then(response => response.json())
        .then(data => {
            oceanData = data;
            renderDashboard();
        })
        .catch(err => console.error('Error fetching data:', err));

    function renderDashboard() {
        oceanList.innerHTML = '';
        oceanData.forEach(ocean => {
            const card = document.createElement('div');
            card.className = 'ocean-card';
            card.innerHTML = `
                <div class="ocean-header" style="background-image: url('${ocean.oceanimage}')">
                    <h3 class="ocean-title">${ocean.name}</h3>
                </div>
                <div class="ocean-body">
                    <p>${ocean.description}</p>
                    <button class="btn edit-btn" data-id="${ocean.id}">Bearbeiten</button>
                </div>
            `;
            oceanList.appendChild(card);
        });

        // Attach Event Listeners
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.target.getAttribute('data-id');
                openEditor(id);
            });
        });
    }

    function openEditor(id) {
        const ocean = oceanData.find(o => o.id === id);
        if (!ocean) return;

        // Populate Form
        document.getElementById('edit-id').value = ocean.id;
        document.getElementById('edit-name').value = ocean.name;
        document.getElementById('edit-description').value = ocean.description;

        // Show Editor
        dashboardView.style.display = 'none';
        editorView.style.display = 'block';
    }

    // Cancel Edit
    document.getElementById('cancel-btn').addEventListener('click', () => {
        editorView.style.display = 'none';
        dashboardView.style.display = 'block';
    });

    // Save Data
    editorForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const id = document.getElementById('edit-id').value;
        const oceanIndex = oceanData.findIndex(o => o.id === id);

        if (oceanIndex > -1) {
            // Update Local Data
            oceanData[oceanIndex].name = document.getElementById('edit-name').value;
            oceanData[oceanIndex].description = document.getElementById('edit-description').value;

            // Send to Server
            saveData(oceanData);
        }
    });

    function saveData(data) {
        fetch(apiBase + '/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showMessage('Daten erfolgreich gespeichert!', 'success');
                editorView.style.display = 'none';
                dashboardView.style.display = 'block';
                renderDashboard();
            } else {
                showMessage('Fehler beim Speichern!', 'error');
            }
        })
        .catch(err => {
            console.error('Save error:', err);
            showMessage('Netzwerkfehler beim Speichern.', 'error');
        });
    }

    function showMessage(text, type) {
        const msg = document.createElement('div');
        msg.className = `status-message ${type}`;
        msg.textContent = text;
        msg.style.display = 'block';
        document.querySelector('.eol-dashboard').prepend(msg);
        setTimeout(() => msg.remove(), 3000);
    }
});
