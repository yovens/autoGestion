<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | AutoGestion</title>
 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <header class="navbar">
    <div class="nav-wrapper">
        <a href="{{ route('client.dashboard') }}" class="logo">AutoGestion</a>
        <nav class="nav-links">
            <a href="{{ route('client.dashboard') }}" class=""><i class="fas fa-home"></i> Dashboard</a>
            <a href="{{ route('client.vehicles') }}"><i class="fas fa-car"></i> Véhicules</a>
            <a href="{{ route('client.cart') }}"><i class="fas fa-shopping-cart"></i> Panier</a>
            <a href="{{ route('client.loan') }}"><i class="fas fa-key"></i> Locations</a>
               <a href="{{ route('client.transactions') }}"><i class="fas fa-exchange-alt"></i> Transactions</a>

              <a href="{{ route('client.contact') }}"><i class="fas fa-headset"></i> Contact</a>
        </nav>
        <div class="nav-actions">
            <button id="theme-toggle"><i class="fas fa-moon"></i></button>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout"><i class="fas fa-power-off"></i></button>
            </form>
        </div>
    </div>
</header>

    <main class="container">
        @yield('content')
    </main>

    <script src="{{ asset('js/theme.js') }}"></script>
</body>
</html>

 
<style>
:root {
    --bg-color: #f8fafc;
    --text-color: #1e293b;
    --card-bg: #ffffff;
    --primary: #3b82f6; /* Ble pi modèn */
    --shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
}

[data-theme='dark'] {
    --bg-color: #0f172a;
    --text-color: #f1f5f9;
    --card-bg: #1e293b;
    --primary: #f472b6;
}
body {
    transition: background-color 0.3s ease, color 0.3s ease;
    background-color: var(--bg-color);
    color: var(--text-color);
}
/* Navbar pwofesyonèl */
.navbar { 
    background: var(--card-bg); 
    padding: 0.8rem 0; 
    border-bottom: 1px solid rgba(0,0,0,0.05);
    position: sticky; top: 0; z-index: 1000;
}
.nav-wrapper { display: flex; align-items: center; justify-content: space-between; max-width: 1200px; margin: 0 auto; padding: 0 20px; }

.logo { font-weight: 800; font-size: 1.5rem; color: var(--primary); text-decoration: none; }

.nav-links { display: flex; gap: 5px; }
.nav-links a { 
    text-decoration: none; color: var(--text-color); font-weight: 500; font-size: 0.9rem;
    padding: 10px 15px; border-radius: 10px; transition: 0.3s;
}
.nav-links a:hover, .nav-links a.active { background: var(--primary); color: white; }

/* Aksyon (Bouton) */
.nav-actions { display: flex; gap: 15px; align-items: center; }
#theme-toggle { background: rgba(128,128,128,0.1); border: none; padding: 10px; border-radius: 50%; color: var(--text-color); }
.btn-logout { background: #ef4444; color: white; border: none; padding: 10px 15px; border-radius: 10px; cursor: pointer; }

/* Grid Dashboard */
.container { max-width: 1200px; margin: 2rem auto; padding: 0 20px; }
.dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; }
.card {
    background: var(--card-bg); padding: 25px; border-radius: 20px;
    box-shadow: var(--shadow); border: 1px solid rgba(0,0,0,0.05);
}
</style>


<script>
// Remplacez votre script actuel par celui-ci pour gérer l'icône et le rendu fluide
const toggleBtn = document.getElementById('theme-toggle');
const icon = toggleBtn.querySelector('i');
const body = document.body;

function setTheme(theme) {
    body.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
}

// Initialisation
const savedTheme = localStorage.getItem('theme') || 'light';
setTheme(savedTheme);

toggleBtn.addEventListener('click', () => {
    const currentTheme = body.getAttribute('data-theme');
    setTheme(currentTheme === 'dark' ? 'light' : 'dark');
    
    // Si vous utilisez Chart.js, déclenchez une mise à jour des couleurs ici
    if (typeof updateCharts === 'function') {
        updateCharts(); 
    }
});
</script>







<style>
    
    /* On utilise la classe card (globale) + contact-card pour les ajustements */
    .contact-card { 
        background-color: var(--card-bg); 
        border-radius: 20px; 
        box-shadow: var(--shadow);
        border: 1px solid rgba(0,0,0,0.05);
    }

    .form-label { 
        display: block; 
        margin-bottom: 8px; 
        font-weight: 600;
        color: var(--text-color);
    }

    .form-control { 
        width: 100%; 
        padding: 12px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        background-color: var(--bg-color);
        color: var(--text-color);
        box-sizing: border-box; 
        margin-bottom: 20px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: var(--primary);
        outline: none;
    }

    .btn-submit { 
        width: 100%;
        padding: 12px;
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
        transition: opacity 0.3s;
    }

    .btn-submit:hover {
        opacity: 0.9;
    }
.contact-card { 
    background-color: var(--card-bg) !important; 
    border-radius: 20px !important; 
    box-shadow: var(--shadow) !important;
}
.form-control { 
    display: block !important; /* Force l'affichage en bloc */
    width: 100% !important;    /* Force la largeur totale */
    padding: 12px !important;
    margin-bottom: 20px !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 10px !important;
    background-color: var(--bg-color) !important;
    color: var(--text-color) !important;
    box-sizing: border-box !important; 
}
    /* 1. Assurer que la carte a de l'espace interne */
</style>







