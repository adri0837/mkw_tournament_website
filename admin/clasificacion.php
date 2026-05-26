
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Clasificación | MK Tournament</title>
    <style>
        @font-face {
            font-family: 'RacersMK';
            src: url('../assets/fonts/RACERSDELIGHT.OTF') format('opentype');
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'RacersMK', sans-serif;
            background: #121212;
            background-image: radial-gradient(#333 1px, transparent 1px);
            background-size: 20px 20px;
            color: #f4f4f4;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #aaa;
            text-decoration: none;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 25px;
            transition: color 0.2s;
        }
        .back-btn:hover { color: #fff; }

        h2 {
            font-size: 20px;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2a2a2a;
        }
        h2 span { color: #e60012; }

        .info-banner {
            background: rgba(230,0,18,0.1);
            border: 1px solid rgba(230,0,18,0.3);
            border-radius: 10px;
            padding: 14px 18px;
            font-size: 12px;
            color: #aaa;
            letter-spacing: 1px;
            margin-bottom: 25px;
        }

        /* TABLA */
        .table-wrapper {
            background: #1e1e1e;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        th {
            color: #666;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 10px 15px;
            text-align: center;
        }
        th:first-child { text-align: left; }

        td {
            padding: 10px 15px;
            background: #2a2a2a;
            text-align: center;
            vertical-align: middle;
        }
        tr td:first-child { border-radius: 10px 0 0 10px; text-align: left; }
        tr td:last-child  { border-radius: 0 10px 10px 0; }

        .team-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .team-logo {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #3a3a3a;
            border: 2px solid #444;
            object-fit: cover;
            flex-shrink: 0;
        }

        .team-name {
            font-size: 13px;
            color: #fff;
        }

        /* INPUTS EDITABLES */
        .stat-input {
            width: 60px;
            padding: 8px 6px;
            background: #3a3a3a;
            border: 2px solid #444;
            border-radius: 8px;
            color: #fff;
            font-family: 'RacersMK', sans-serif;
            font-size: 13px;
            text-align: center;
            outline: none;
            transition: border-color 0.2s;
        }
        .stat-input:focus { border-color: #e60012; }

        .points-input {
            width: 70px;
            background: #1a1a1a;
            border-color: #e60012;
            color: #ffd700;
            font-size: 15px;
        }

        /* BTN GUARDAR */
        .btn-save {
            padding: 9px 18px;
            background: linear-gradient(135deg, #e60012, #8b0000);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-family: 'RacersMK', sans-serif;
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(230,0,18,0.4); }
        .btn-save:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        .save-indicator {
            font-size: 11px;
            color: #00c864;
            letter-spacing: 1px;
            display: none;
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 50px;
            color: #555;
        }
        .empty-state p {
            font-size: 13px;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        /* GLOBAL SAVE */
        .global-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            align-items: center;
            margin-top: 20px;
        }

        .btn-save-all {
            padding: 13px 30px;
            background: linear-gradient(135deg, #e60012, #8b0000);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-family: 'RacersMK', sans-serif;
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-save-all:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(230,0,18,0.4); }
        .btn-save-all:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        /* NOTIFICACIONES */
        .notif-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.85);
            z-index: 300;
            align-items: center;
            justify-content: center;
        }
        .notif-overlay.open { display: flex; }

        .notif-box {
            background: #1e1e1e;
            border-radius: 20px;
            padding: 35px 40px;
            max-width: 380px;
            width: 100%;
            text-align: center;
            animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .notif-box.success { border: 3px solid #00c864; }
        .notif-box.error   { border: 3px solid #e60012; }
        .notif-box.warning { border: 3px solid #ffd700; }
        .notif-box.confirm { border: 3px solid #e60012; }

        .notif-icon {
            font-size: 40px;
            margin-bottom: 15px;
            display: block;
        }

        .notif-title {
            font-size: 16px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #fff;
            margin-bottom: 10px;
        }

        .notif-text {
            font-size: 12px;
            color: #888;
            letter-spacing: 1px;
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .notif-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .notif-btn {
            padding: 12px 28px;
            border-radius: 10px;
            font-family: 'RacersMK', sans-serif;
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .notif-btn.primary {
            background: linear-gradient(135deg, #e60012, #8b0000);
            color: #fff;
        }
        .notif-btn.primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(230,0,18,0.4); }

        .notif-btn.primary.green {
            background: linear-gradient(135deg, #00c864, #006432);
        }
        .notif-btn.primary.green:hover { box-shadow: 0 5px 15px rgba(0,200,100,0.4); }

        .notif-btn.secondary {
            background: #2a2a2a;
            color: #aaa;
            border: 2px solid #444;
        }
        .notif-btn.secondary:hover { border-color: #fff; color: #fff; }

        @keyframes popIn {
            from { opacity: 0; transform: scale(0.85) translateY(20px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>
</head>
<body>

    
    <h2>Admin — <span>Clasificación</span></h2>

    <div class="info-banner">
        💡 Edita directamente los valores de cada equipo. Los puntos se calculan automáticamente (W×3 + E×1). Haz clic en <strong>Guardar Todo</strong> cuando termines.
    </div>

    <div class="table-wrapper">
        <table id="standingsTable">
            <thead>
                <tr>
                    <th>Equipo</th>
                    <th>Puntos</th>
                    <th>PJ</th>
                    <th>W</th>
                    <th>E</th>
                    <th>L</th>
                </tr>
            </thead>
            <tbody id="standingsBody">
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <p>Cargando clasificación...</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="global-actions">
        <button class="btn-save-all" id="btnSaveAll" onclick="saveAll()">
            Guardar Todo
        </button>
    </div>

    <script>
        let standings = [];

        async function loadStandings() {
            const res  = await fetch('../api/standings/get.php');
            standings  = await res.json();
            renderTable();
        }

        function renderTable() {
            const tbody = document.getElementById('standingsBody');

            if (standings.length === 0) {
                tbody.innerHTML = `
                    <tr><td colspan="6">
                        <div class="empty-state">
                            <p>No hay equipos registrados aún.</p>
                            <p style="margin-top:5px;">Registra equipos en la sección de Grupos primero.</p>
                        </div>
                    </td></tr>`;
                return;
            }

            tbody.innerHTML = standings.map((s, i) => {
                const logo = s.logo_path
                    ? `<img class="team-logo" src="../${s.logo_path}" alt="logo">`
                    : `<div class="team-logo" style="display:inline-flex;align-items:center;justify-content:center;font-size:9px;color:#666;">LOGO</div>`;

                return `
                    <tr data-team-id="${s.team_id}">
                        <td>
                            <div class="team-cell">
                                ${logo}
                                <span class="team-name">${s.team_name}</span>
                            </div>
                        </td>
                        <td>
                            <input class="stat-input points-input"
                                type="number" min="0"
                                value="${s.points}"
                                data-field="points"
                                readonly
                                title="Se calcula automáticamente">
                        </td>
                        <td>
                            <input class="stat-input"
                                type="number" min="0"
                                value="${s.played}"
                                data-field="played"
                                onchange="recalcPoints(this)">
                        </td>
                        <td>
                            <input class="stat-input"
                                type="number" min="0"
                                value="${s.wins}"
                                data-field="wins"
                                onchange="recalcPoints(this)">
                        </td>
                        <td>
                            <input class="stat-input"
                                type="number" min="0"
                                value="${s.draws}"
                                data-field="draws"
                                onchange="recalcPoints(this)">
                        </td>
                        <td>
                            <input class="stat-input"
                                type="number" min="0"
                                value="${s.losses}"
                                data-field="losses"
                                onchange="recalcPoints(this)">
                        </td>
                    </tr>`;
            }).join('');
        }

        // Calcular puntos automáticamente: W×3 + E×1
        function recalcPoints(input) {
            const row    = input.closest('tr');
            const wins   = parseInt(row.querySelector('[data-field="wins"]').value)   || 0;
            const draws  = parseInt(row.querySelector('[data-field="draws"]').value)  || 0;
            const losses = parseInt(row.querySelector('[data-field="losses"]').value)  || 0;
            const played = parseInt(row.querySelector('[data-field="played"]').value)  || 0;
            const points = (wins * 3) + (draws * 1);
            row.querySelector('[data-field="points"]').value = points;

            // Validación en tiempo real (feedback visual)
            if (wins + draws + losses !== played) {
                row.querySelectorAll('.stat-input:not(.points-input)').forEach(inp => {
                    inp.style.borderColor = '#ff4444';
                    inp.style.boxShadow = '0 0 5px rgba(255, 68, 68, 0.3)';
                });
            } else {
                row.querySelectorAll('.stat-input:not(.points-input)').forEach(inp => {
                    inp.style.borderColor = '#444';
                    inp.style.boxShadow = 'none';
                });
            }
        }

        async function saveAll() {
            const btn    = document.getElementById('btnSaveAll');
            const rows   = document.querySelectorAll('#standingsBody tr[data-team-id]');

            // Validación: W + E + L = PJ
            let invalidTeams = [];
            for (let row of rows) {
                const teamName = row.querySelector('.team-name').textContent.trim();
                const played   = parseInt(row.querySelector('[data-field="played"]').value) || 0;
                const wins     = parseInt(row.querySelector('[data-field="wins"]').value)   || 0;
                const draws    = parseInt(row.querySelector('[data-field="draws"]').value)  || 0;
                const losses   = parseInt(row.querySelector('[data-field="losses"]').value) || 0;

                if (wins + draws + losses !== played) {
                    invalidTeams.push(`"${teamName}"`);
                }
            }

            if (invalidTeams.length > 0) {
                showNotif({
                    type:  'error',
                    icon:  '❌',
                    title: 'Inconsistencia de Datos',
                    text:  `La suma de Ganados (W), Empates (E) y Perdidos (L) debe ser igual a los Partidos Jugados (PJ) para:<br><br><strong>${invalidTeams.join(', ')}</strong>`,
                    actions: [{ label: 'Entendido', style: 'primary', action: null }]
                });
                return;
            }

            btn.disabled    = true;
            btn.textContent = 'Guardando...';

            const promises = Array.from(rows).map(async row => {
                const team_id = parseInt(row.dataset.teamId);
                const points  = parseInt(row.querySelector('[data-field="points"]').value) || 0;
                const played  = parseInt(row.querySelector('[data-field="played"]').value) || 0;
                const wins    = parseInt(row.querySelector('[data-field="wins"]').value)   || 0;
                const draws   = parseInt(row.querySelector('[data-field="draws"]').value)  || 0;
                const losses  = parseInt(row.querySelector('[data-field="losses"]').value) || 0;

                const res = await fetch('../api/standings/update.php', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body:    JSON.stringify({ team_id, points, played, wins, draws, losses })
                });

                if (!res.ok) {
                    const errData = await res.json().catch(() => ({}));
                    throw new Error(errData.error || 'Error del servidor');
                }
            });

            try {
                await Promise.all(promises);
                showNotif({
                    type:  'success',
                    icon:  '✅',
                    title: '¡Clasificación Guardada!',
                    text:  'Todos los datos de la clasificación han sido actualizados exitosamente.',
                    actions: [{ label: 'Perfecto', style: 'primary green', action: null }]
                });
            } catch (e) {
                showNotif({
                    type:  'error',
                    icon:  '❌',
                    title: 'Error al Guardar',
                    text:  e.message || 'Ocurrió un problema al guardar los datos.',
                    actions: [
                        { label: 'Reintentar', style: 'primary', action: () => saveAll() },
                        { label: 'Cerrar', style: 'secondary', action: null }
                    ]
                });
            } finally {
                btn.disabled    = false;
                btn.textContent = 'Guardar Todo';
            }
        }

        // ── SISTEMA DE NOTIFICACIONES ──────────────────────────────
        function showNotif({ type = 'success', icon, title, text, actions }) {
            const overlay = document.getElementById('notifOverlay');
            const box     = document.getElementById('notifBox');

            document.getElementById('notifIcon').textContent  = icon;
            document.getElementById('notifTitle').textContent = title;
            document.getElementById('notifText').innerHTML    = text;
            document.getElementById('notifActions').innerHTML = '';

            box.className = 'notif-box ' + type;

            actions.forEach(a => {
                const btn = document.createElement('button');
                btn.className   = 'notif-btn ' + (a.style || 'primary');
                btn.textContent = a.label;
                btn.onclick     = () => {
                    overlay.classList.remove('open');
                    if (a.action) a.action();
                };
                document.getElementById('notifActions').appendChild(btn);
            });

            overlay.classList.add('open');
        }

        function closeNotif() {
            document.getElementById('notifOverlay').classList.remove('open');
        }

        loadStandings();
    </script>

    <!-- SISTEMA DE NOTIFICACIONES -->
    <div class="notif-overlay" id="notifOverlay">
        <div class="notif-box" id="notifBox">
            <span class="notif-icon" id="notifIcon"></span>
            <div class="notif-title" id="notifTitle"></div>
            <div class="notif-text"  id="notifText"></div>
            <div class="notif-actions" id="notifActions"></div>
        </div>
    </div>
</body>
</html>