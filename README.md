<a name="readme-top"></a>

<br />
<div align="center">
  <a href="https://github.com/T-Boyke/eol-manager">
    <img src="https://getgrav.org/images/grav-logo.svg" alt="Logo" width="120" height="120">
  </a>

  <h1 align="center">EOL Manager Plugin</h1>

  <p align="center">
    <strong>Verwaltet Ozean-Daten, Fakten und Quizfragen für das "Earth Ocean Learning" Projekt.</strong>
    <br />
    <br />
    <a href="#-nutzung">Dokumentation »</a>
    ·
    <a href="https://github.com/T-Boyke/eol-manager/issues">Bug melden</a>
  </p>
</div>

<div align="center">

[![Status](https://img.shields.io/badge/status-active-success.svg)]()
[![GitHub Issues](https://img.shields.io/github/issues/T-Boyke/eol-manager)](https://github.com/T-Boyke/eol-manager/issues)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

</div>

---

<details>
  <summary><strong>📖 Inhaltsverzeichnis</strong> (Klicken zum Ausklappen)</summary>
  <ol>
    <li>
      <a href="#-über-das-projekt">Über das Projekt</a>
    </li>
    <li>
      <a href="#-installation">Installation</a>
    </li>
    <li><a href="#-features">Features</a></li>
    <li><a href="#-entwicklung">Entwicklung</a></li>
    <li><a href="#-lizenz">Lizenz</a></li>
  </ol>
</details>

---

## 💡 Über das Projekt

**EOL Manager** ist ein Grav CMS Plugin, das als Backend für die "Earth Ocean Learning" Angular App dient. Es stellt eine grafische Oberfläche für Lehrer bereit, um:
*   Ozean-Daten zu bearbeiten (Texte, Farben).
*   Bilder hochzuladen und auszuwählen.
*   Quizfragen und Fakten zu verwalten.

Die Daten werden als JSON gespeichert und über eine REST-API (`/eol-api/data`) bereitgestellt.

**Hauptfunktionen:**
* ✅ **Dashboard:** Eigenständige Oberfläche ohne Theme-Abhängigkeit.
* ✅ **API:** JSON-Endpunkte für die Angular App (`GET /eol-api/data`, `POST /eol-api/save`).
* ✅ **CORS Support:** Ermöglicht Zugriff von `localhost:4200`.
* ✅ **Daten-Persistenz:** Speichert sicher in `user/data/eol-manager/`.

<p align="right">(<a href="#readme-top">zurück nach oben</a>)</p>

### 🛠 Technologie Stack

| Komponente | Technologie |
| :--- | :--- |
| **CMS** | [Grav CMS](https://getgrav.org) |
| **Backend** | PHP 8.x |
| **Frontend** | HTML5, JavaScript (Vanilla), Inline CSS |
| **Templating** | Twig |
| **Daten** | JSON Flat File |

<p align="right">(<a href="#readme-top">zurück nach oben</a>)</p>

### 📂 Projektstruktur

```text
user/plugins/eol-manager/
├── admin/                   # Admin-spezifische Assets (falls vorhanden)
├── assets/                  # Bilder und CSS für das Dashboard
├── classes/                 # PHP Klassen (DataService.php)
├── data/                    # Seed-Daten (ocean-data.json)
├── templates/               # Twig Templates (eol_dashboard.html.twig)
├── blueprints.yaml          # Admin Panel Konfiguration
├── eol-manager.php          # Haupt-Plugin-Logik
├── eol-manager.yaml         # Plugin Standard-Konfiguration
└── README.md                # Diese Datei
```

### 🚀 Installation

1.  **Download:**
    Lade das Plugin herunter oder klone es in deinen `user/plugins/` Ordner:
    ```bash
    cd user/plugins
    git clone https://github.com/T-Boyke/eol-manager.git
    ```

2.  **Aktivieren:**
    Das Plugin sollte automatisch erkannt und aktiviert werden. Prüfe dies im Grav Admin Panel unter "Plugins".

3.  **Konfiguration:**
    Rufe die Plugin-Einstellungen auf, um API-Routen oder CORS-Header anzupassen.

<p align="right">(<a href="#readme-top">zurück nach oben</a>)</p>

### 💻 Entwicklung

**Dashboard öffnen:**
Rufe `http://deine-grav-url/eol-dashboard` auf, um die Verwaltungsoberfläche zu sehen.

**API testen:**
*   GET: `http://deine-grav-url/eol-api/data`
*   POST: `http://deine-grav-url/eol-api/save` (Body: JSON)

<p align="right">(<a href="#readme-top">zurück nach oben</a>)</p>

### 📄 Lizenz
Veröffentlicht unter der MIT Lizenz. Siehe LICENSE für weitere Informationen.

<p align="right">(<a href="#readme-top">zurück nach oben</a>)</p>
