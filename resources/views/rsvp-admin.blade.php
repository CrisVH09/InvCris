<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
  <meta name="theme-color" content="#071321">
  <title>Centro de misión | Cris</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Montserrat:wght@500;600;700;800&display=swap" rel="stylesheet">
  <style>
    *{box-sizing:border-box}
    body{margin:0;background:#071321;color:#eaf7ff;font-family:Montserrat,sans-serif}
    .top{display:flex;justify-content:space-between;align-items:center;gap:20px;padding:max(24px,env(safe-area-inset-top)) max(5%,env(safe-area-inset-right)) 24px max(5%,env(safe-area-inset-left));border-bottom:1px solid #24587b;background:#091b2c}
    .top h1{margin:0;color:#ed2031;font:2.5rem Bangers,sans-serif;letter-spacing:.06em;text-shadow:2px 2px #fff}
    .top button{min-height:44px;padding:10px 14px;border:1px solid #6389a3;background:transparent;color:#d8edf7;cursor:pointer;font-weight:800}
    .wrap{width:min(1180px,90%);margin:38px auto}
    .stats{display:grid;grid-template-columns:1fr auto 1fr auto 1.08fr;gap:14px;align-items:stretch;margin-bottom:32px}
    .stat{position:relative;min-width:0;padding:22px;border:1px solid #24587b;background:linear-gradient(145deg,#0d2940,#091b2d);box-shadow:4px 4px #ed2031;overflow:hidden}
    .stat:before{content:"";position:absolute;right:-28px;top:-38px;width:105px;height:105px;border:1px solid #2f729b;border-radius:50%;opacity:.3}
    .stat-total{border-color:#ed2031;background:linear-gradient(145deg,#153958,#0b2034);box-shadow:5px 5px #ffd24e}
    .stat-head{position:relative;display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:9px}
    .stat-icon{display:grid;width:38px;height:38px;place-items:center;border:1px solid #397ea7;border-radius:50%;color:#7ed8ff;font-size:1.1rem}
    .stat b{display:block;color:#fff;font:3.15rem/1 Bangers,sans-serif;text-shadow:2px 2px #0a5d99}
    .stat-total b{color:#ffd24e;text-shadow:2px 2px #ed2031}
    .stat-label{position:relative;display:block;color:#86cfee;font-size:.68rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase}
    .stat small{position:relative;display:block;margin-top:7px;color:#789db3;font-size:.58rem;line-height:1.4}
    .stat-operator{display:grid;place-items:center;color:#ed2031;font:2.2rem Bangers,sans-serif;text-shadow:1px 1px #fff}
    .table-wrap{max-width:100%;overflow:auto;border:1px solid #24587b;-webkit-overflow-scrolling:touch}
    table{width:100%;min-width:570px;border-collapse:collapse;background:#0a1c2d;font-size:.76rem}
    th,td{padding:14px;text-align:left;border-bottom:1px solid #173b54;vertical-align:top}
    th{color:#86cfee;background:#0d2940;font-size:.62rem;letter-spacing:.09em;text-transform:uppercase}
    .yes{color:#7de1a5;font-weight:800}.no{color:#ff8993;font-weight:800}a{color:#7ed8ff}
    .empty{padding:40px;text-align:center;color:#8eb2c6}
    .pagination{margin-top:22px;color:#fff;overflow:auto}
    .pagination nav>div:first-child,.pagination nav>div:last-child>div:first-child{display:none}
    .pagination svg{width:18px}.pagination a,.pagination span{display:inline-flex;padding:8px 11px;border:1px solid #24587b;text-decoration:none}.pagination span[aria-current="page"] span{background:#ed2031}
    @media(max-width:800px){.top{align-items:flex-start;padding-left:4%;padding-right:4%}.top h1{font-size:1.75rem;line-height:.9}.top button{padding:8px 10px;font-size:.6rem}.stats{grid-template-columns:1fr 1fr;gap:12px}.stat{padding:18px}.stat-operator{display:none}.stat-total{grid-column:1/-1}.wrap{width:92%;margin-top:22px}th,td{padding:11px}.hide-mobile{display:none}table{min-width:460px}.pagination{padding-bottom:max(16px,env(safe-area-inset-bottom))}}
    @media(max-width:420px){.top{gap:10px}.top h1{font-size:1.5rem}.stats{grid-template-columns:1fr}.stat-total{grid-column:auto}.stat b{font-size:2.8rem}}
  </style>
</head>
<body>
  <header class="top">
    <h1>CENTRO DE MISIÓN · CRIS</h1>
    <form method="POST" action="{{ route('rsvp.logout') }}">@csrf<button type="submit">CERRAR SISTEMA</button></form>
  </header>
  <main class="wrap">
    <section class="stats" aria-label="Resumen de asistentes">
      <article class="stat">
        <div class="stat-head"><span class="stat-icon" aria-hidden="true">★</span><b data-count="{{ $confirmedHosts }}">{{ $confirmedHosts }}</b></div>
        <span class="stat-label">Confirmados principales</span>
        <small>Personas que respondieron que asistirán</small>
      </article>
      <span class="stat-operator" aria-hidden="true">+</span>
      <article class="stat">
        <div class="stat-head"><span class="stat-icon" aria-hidden="true">✦</span><b data-count="{{ $confirmedGuests }}">{{ $confirmedGuests }}</b></div>
        <span class="stat-label">Acompañantes</span>
        <small>Invitados adicionales registrados</small>
      </article>
      <span class="stat-operator" aria-hidden="true">=</span>
      <article class="stat stat-total">
        <div class="stat-head"><span class="stat-icon" aria-hidden="true">◆</span><b data-count="{{ $confirmedHosts + $confirmedGuests }}">{{ $confirmedHosts + $confirmedGuests }}</b></div>
        <span class="stat-label">Total de asistentes</span>
        <small>Confirmados principales más acompañantes</small>
      </article>
    </section>

    <section class="table-wrap">
      @if($rsvps->isEmpty())
        <div class="empty">Aún no hay transmisiones recibidas.</div>
      @else
        <table>
          <thead><tr><th>Fecha</th><th>Nombre</th><th>Respuesta</th><th>Acomp.</th><th>Teléfono</th><th class="hide-mobile">Mensaje</th></tr></thead>
          <tbody>
            @foreach($rsvps as $rsvp)
              <tr>
                <td>{{ $rsvp->created_at->format('d/m/Y H:i') }}</td>
                <td><strong>{{ $rsvp->name }}</strong></td>
                <td class="{{ $rsvp->attending ? 'yes' : 'no' }}">{{ $rsvp->attending ? 'Asistirá' : 'No asistirá' }}</td>
                <td>{{ $rsvp->attending ? $rsvp->guests : '—' }}</td>
                <td>{{ $rsvp->phone ?: '—' }}</td>
                <td class="hide-mobile">{{ $rsvp->message ?: '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </section>
    <div class="pagination">{{ $rsvps->links() }}</div>
  </main>
  <script>
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      document.querySelectorAll('[data-count]').forEach(element => {
        const target = Number(element.dataset.count);
        const start = performance.now();
        const duration = 650;
        element.textContent = '0';

        const tick = now => {
          const progress = Math.min((now - start) / duration, 1);
          element.textContent = Math.round(target * (1 - Math.pow(1 - progress, 3)));
          if (progress < 1) requestAnimationFrame(tick);
        };

        requestAnimationFrame(tick);
      });
    }
  </script>
</body>
</html>
