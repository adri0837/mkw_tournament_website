
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Clips | MK Tournament</title>
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
            margin-bottom: 10px;
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
            margin-top: 15px;
        }

        /* STATS BAR */
        .stats-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: #1e1e1e;
            border-radius: 12px;
            padding: 18px 25px;
            flex: 1;
            text-align: center;
            border: 2px solid #2a2a2a;
        }

        .stat-number {
            font-size: 28px;
            color: #e60012;
            display: block;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 4px;
        }

        /* CLIPS GRID */
        .clips-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }

        .clip-card {
            background: #1e1e1e;
            border-radius: 15px;
            overflow: hidden;
            border: 2px solid #2a2a2a;
            transition: border-color 0.2s;
            position: relative;
        }

        .clip-card:hover { border-color: #3a3a3a; }

        /* VIDEO */
        .clip-video {
            width: 100%;
            aspect-ratio: 16/9;
            background: #0a0a0a;
            display: block;
        }

        /* INFO */
        .clip-info {
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .clip-character {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #2a2a2a;
            border: 2px solid #3a3a3a;
            overflow: hidden;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .clip-character img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .clip-character span {
            font-size: 9px;
            color: #555;
        }

        .clip-meta {
            flex: 1;
        }

        .clip-player {
            font-size: 13px;
            color: #fff;
            letter-spacing: 1px;
        }

        .clip-date {
            font-size: 10px;
            color: #555;
            letter-spacing: 1px;
            margin-top: 3px;
        }

        /* DELETE BTN */
        .btn-delete-clip {
            background: rgba(230,0,18,0.15);
            border: 2px solid rgba(230,0,18,0.4);
            color: #ff4444;
            padding: 8px 16px;
            border-radius: 8px;
            font-family: 'RacersMK', sans-serif;
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .btn-delete-clip:hover {
            background: rgba(230,0,18,0.3);
            border-color: #e60012;
        }

        /* DELETING STATE */
        .clip-card.deleting {
            opacity: 0.4;
            pointer-events: none;
        }

        /* EMPTY STATE */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: #444;
        }

        .empty-state p {
            font-size: 13px;
            letter-spacing: 1px;
            margin-top: 10px;
            line-height: 2;
        }

        /* CONFIRM MODAL */
        .confirm-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.85);
            z-index: 100;
            align-items: center;
            justify-content: center;
        }
        .confirm-overlay.open { display: flex; }

        .confirm-box {
            background: #1e1e1e;
            border: 3px solid #e60012;
            border-radius: 20px;
            padding: 35px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .confirm-box h3 {
            font-size: 16px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 12px;
            color: #fff;
        }

        .confirm-box p {
            font-size: 12px;
            color: #888;
            letter-spacing: 1px;
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .confirm-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn-confirm-yes {
            padding: 12px 28px;
            background: linear-gradient(135deg, #e60012, #8b0000);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-family: 'RacersMK', sans-serif;
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-confirm-yes:hover { transform: translateY(-2px); }

        .btn-confirm-no {
            padding: 12px 28px;
            background: #2a2a2a;
            border: 2px solid #444;
            border-radius: 10px;
            color: #aaa;
            font-family: 'RacersMK', sans-serif;
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-confirm-no:hover { border-color: #fff; color: #fff; }

        /* TOAST */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 14px 22px;
            border-radius: 10px;
            font-size: 12px;
            letter-spacing: 1px;
            z-index: 200;
            display: none;
            animation: slideUp 0.3s ease-out;
        }
        .toast.success {
            background: rgba(0,200,100,0.15);
            border: 1px solid #00c864;
            color: #00c864;
        }
        .toast.error {
            background: rgba(230,0,18,0.15);
            border: 1px solid #e60012;
            color: #ff4444;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    
    <h2>Admin — <span>Clips</span></h2>

    <div class="info-banner">
        💡 Aquí puedes moderar los clips subidos por los usuarios. Al eliminar un clip se borra permanentemente del servidor y de la base de datos.
    </div>

    <!-- STATS -->
    <div class="stats-bar">
        <div class="stat-card">
            <span class="stat-number" id="statTotal">—</span>
            <div class="stat-label">Clips Totales</div>
        </div>
        <div class="stat-card">
            <span class="stat-number" id="statHoy">—</span>
            <div class="stat-label">Subidos Hoy</div>
        </div>
    </div>

    <!-- CLIPS GRID -->
    <div class="clips-grid" id="clipsGrid">
        <div class="empty-state"><p>Cargando clips...</p></div>
    </div>

    <!-- CONFIRM MODAL -->
    <div class="confirm-overlay" id="confirmOverlay">
        <div class="confirm-box">
            <h3>¿Eliminar Clip?</h3>
            <p>Esta acción es permanente.<br>El archivo MP4 se borrará del servidor<br>y no podrá recuperarse.</p>
            <div class="confirm-actions">
                <button class="btn-confirm-no"  onclick="closeConfirm()">Cancelar</button>
                <button class="btn-confirm-yes" onclick="confirmDelete()">Sí, Eliminar</button>
            </div>
        </div>
    </div>

    <!-- TOAST -->
    <div class="toast" id="toast"></div>

    <script>
        let clips         = [];
        let pendingDelete = null;

        // ── CARGAR CLIPS ───────────────────────────────────────────
        async function loadClips() {
            const res = await fetch('../api/clips/get.php');
            clips     = await res.json();
            renderClips();
            updateStats();
        }

        // ── RENDER ─────────────────────────────────────────────────
        function renderClips() {
            const grid = document.getElementById('clipsGrid');

            if (clips.length === 0) {
                grid.innerHTML = `
                    <div class="empty-state">
                        <p>No hay clips subidos aún.<br>
                        Cuando los usuarios suban sus replays aparecerán aquí.</p>
                    </div>`;
                return;
            }

            grid.innerHTML = clips.map(clip => {
                const date = new Date(clip.uploaded_at).toLocaleDateString('es-MX', {
                    day:   '2-digit',
                    month: 'short',
                    year:  'numeric',
                    hour:  '2-digit',
                    minute:'2-digit'
                });

                const charIcon = clip.character_icon
                    ? `<img src="../${clip.character_icon}" alt="personaje">`
                    : `<span>?</span>`;

                return `
                    <div class="clip-card" id="clip-${clip.id}">
                        <video class="clip-video" controls preload="metadata">
                            <source src="../${clip.file_path}" type="video/mp4">
                        </video>
                        <div class="clip-info">
                            <div class="clip-character">${charIcon}</div>
                            <div class="clip-meta">
                                <div class="clip-player">${clip.player_name}</div>
                                <div class="clip-date">${date}</div>
                            </div>
                            <button class="btn-delete-clip" onclick="askDelete(${clip.id})">
                                Eliminar
                            </button>
                        </div>
                    </div>`;
            }).join('');
        }

        // ── STATS ──────────────────────────────────────────────────
        function updateStats() {
            document.getElementById('statTotal').textContent = clips.length;

            const hoy   = new Date().toDateString();
            const today = clips.filter(c => new Date(c.uploaded_at).toDateString() === hoy).length;
            document.getElementById('statHoy').textContent = today;
        }

        // ── CONFIRMAR BORRADO ──────────────────────────────────────
        function askDelete(id) {
            pendingDelete = id;
            document.getElementById('confirmOverlay').classList.add('open');
        }

        function closeConfirm() {
            pendingDelete = null;
            document.getElementById('confirmOverlay').classList.remove('open');
        }

        async function confirmDelete() {
            if (!pendingDelete) return;
            const id   = pendingDelete;
            const card = document.getElementById('clip-' + id);
            closeConfirm();

            card.classList.add('deleting');

            try {
                const res  = await fetch('../api/clips/delete.php', {
                    method:  'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body:    JSON.stringify({ id })
                });
                const data = await res.json();

                if (data.success) {
                    clips = clips.filter(c => c.id != id);
                    renderClips();
                    updateStats();
                    showToast('Clip eliminado correctamente', 'success');
                } else {
                    card.classList.remove('deleting');
                    showToast(data.error || 'Error al eliminar', 'error');
                }
            } catch(e) {
                card.classList.remove('deleting');
                showToast('Error de conexión', 'error');
            }
        }

        // ── TOAST ──────────────────────────────────────────────────
        function showToast(msg, type) {
            const toast       = document.getElementById('toast');
            toast.textContent = msg;
            toast.className   = 'toast ' + type;
            toast.style.display = 'block';
            setTimeout(() => toast.style.display = 'none', 3000);
        }

        // Cerrar confirm al click fuera
        document.getElementById('confirmOverlay').addEventListener('click', function(e) {
            if (e.target === this) closeConfirm();
        });

        loadClips();
    </script>

</body>
</html>