<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Locations | AutoGestion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        :root {
    --bg-color: #f8fafc;
    --text-color: #1e293b;
    --card-bg: #ffffff;
    --primary: #3b82f6; /* Ble pi modèn */
    --shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
}

body {
    transition: background-color 0.3s ease, color 0.3s ease;
    background-color: var(--bg-color);
    color: var(--text-color);
}

/* Appliquer aussi aux cartes pour une transition cohérente */
.card {
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}
      

        [data-theme='dark'] {
            --bg-color: #0f172a;
            --text-color: #f1f5f9;
            --card-bg: #1e293b;
            --primary: #f472b6;
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

        
        /* Grid & Card Style */
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 20px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .card { background: var(--card-bg); padding: 20px; border-radius: 15px; box-shadow: var(--shadow); border: 1px solid rgba(0,0,0,0.05); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .title { font-size: 1.2rem; font-weight: bold; }
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; }
        .b-approuve { background: #dcfce7; color: #166534; }
        .b-refuse { background: #fee2e2; color: #991b1b; }
        .b-attente { background: #fef3c7; color: #92400e; }
        .b-expire { background: #e2e8f0; color: #475569; }
        .price { font-weight: bold; color: var(--primary); font-size: 1.1rem; }
        
        #theme-toggle { background: rgba(128,128,128,0.1); border: none; padding: 10px; border-radius: 50%; color: var(--text-color); cursor: pointer; }
        .btn-logout { background: #ef4444; color: white; border: none; padding: 10px 15px; border-radius: 10px; cursor: pointer; }
    </style>
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
    <h2>🔑 Mes Locations</h2>

    @if($loans->isEmpty())
        <div class="card" style="text-align: center;">
            <p>Aucune location enregistrée pour le moment.</p>
        </div>
    @else
        <div class="grid">
            @foreach($loans as $loan)
            <div class="card">
                <div class="header">
                    <span class="title">{{ $loan->vehicle->brand }} {{ $loan->vehicle->model }}</span>
                    @php
                        $map = [
                            'approved' => ['class' => 'b-approuve', 'label' => 'Approuvé'],
                            'expired'  => ['class' => 'b-expire', 'label' => 'Expiré'],
                            'rejected' => ['class' => 'b-refuse', 'label' => 'Refusé'],
                            'pending'  => ['class' => 'b-attente', 'label' => 'En attente']
                        ];
                        $s = $map[$loan->status] ?? ['class' => '', 'label' => $loan->status];
                    @endphp
                    <span class="badge {{ $s['class'] }}">{{ $s['label'] }}</span>
                </div>
                <div style="font-size: 0.9rem;">
                    <p><i class="fas fa-calendar-alt"></i> <strong>Début:</strong> {{ \Carbon\Carbon::parse($loan->start_date)->format('d/m/Y') }}</p>
                    <p><i class="fas fa-calendar-check"></i> <strong>Fin:</strong> {{ \Carbon\Carbon::parse($loan->end_date)->format('d/m/Y') }}</p>
                    <p><i class="fas fa-hourglass-half"></i> <strong>Durée:</strong> {{ $loan->duration_days }} jours</p>
                </div>
                <div style="margin-top: 15px; border-top: 1px solid #eee; padding-top: 10px;">
                    <span class="price">{{ number_format($loan->total_amount, 2) }} USD</span>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</main>


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

</body>
</html>