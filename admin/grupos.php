<?php
// Obtener lista de iconos de personajes disponibles
$icons_dir = '../assets/character-icons/';
$icons = array_filter(scandir($icons_dir), function($f) {
    return in_array(pathinfo($f, PATHINFO_EXTENSION), ['png','jpg','webp']);
});
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin Grupos | MK Tournament</title>
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

        /* DRAG & DROP SLOTS */
        .slot-row {
            transition: all 0.2s;
        }

        .slot-row.drag-over-slot {
            border: 2px dashed #e60012;
            background: rgba(230,0,18,0.08);
            transform: scale(1.02);
        }

        .slot-row.dragging-slot {
            opacity: 0.4;
            cursor: grabbing;
        }

        .drag-handle {
            color: #444;
            font-size: 16px;
            cursor: grab;
            padding: 0 5px;
            flex-shrink: 0;
            user-select: none;
        }
        .drag-handle:hover { color: #888; }

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

        /* GRID DE GRUPOS */
        .groups-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .group-panel {
            background: #1e1e1e;
            border-radius: 15px;
            padding: 20px;
            border-left: 6px solid #444;
        }

        .group-panel.group-a { border-left-color: #e22828; }
        .group-panel.group-b { border-left-color: #df3614; }
        .group-panel.group-c { border-left-color: #e9ec37; }
        .group-panel.group-d { border-left-color: #bc64f7; }

        .group-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .group-header h3 {
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .group-header img {
            width: 45px;
            height: 45px;
        }

        /* SLOTS */
        .slot-row {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #2a2a2a;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 10px;
            min-height: 60px;
        }

        .slot-logo {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #3a3a3a;
            border: 2px solid #444;
            object-fit: cover;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            color: #666;
        }

        .slot-logo img {
            width: 100%;
            height: 100%;
            border-radius: 6px;
            object-fit: cover;
        }

        .slot-info {
            flex: 1;
        }

        .slot-name {
            font-size: 13px;
            color: #fff;
        }

        .slot-empty {
            font-size: 11px;
            color: #555;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .btn-slot {
            padding: 7px 14px;
            border-radius: 8px;
            border: none;
            font-family: 'RacersMK', sans-serif;
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-edit {
            background: #2a2a2a;
            color: #fff;
            border: 1px solid #555;
        }
        .btn-edit:hover { background: #3a3a3a; border-color: #888; }

        .btn-register {
            background: #e60012;
            color: #fff;
        }
        .btn-register:hover { background: #ff1a2e; }

        .btn-delete {
            background: #3a3a3a;
            color: #ff4444;
            border: 1px solid #ff4444;
        }
        .btn-delete:hover { background: rgba(255,68,68,0.15); }

        /* MODAL */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.85);
            z-index: 100;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.open { display: flex; }

        @keyframes popIn {
            from { opacity: 0; transform: scale(0.85) translateY(20px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal {
            animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: #1e1e1e;
            border: 3px solid #e60012;
            border-radius: 20px;
            padding: 35px;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        .modal h3 {
            font-size: 16px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 25px;
            color: #fff;
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #3a3a3a;
            border: none;
            color: #fff;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
        }

        /* FORM */
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #aaa;
        }

        .form-group input[type="text"] {
            padding: 12px 15px;
            background: #2a2a2a;
            border: 2px solid #444;
            border-radius: 10px;
            color: #fff;
            font-family: 'RacersMK', sans-serif;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-group input[type="text"]:focus { border-color: #e60012; }

        /* LOGO UPLOAD */
        .logo-upload-area {
            width: 90px;
            height: 90px;
            border: 3px dashed #444;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            overflow: hidden;
            position: relative;
        }
        .logo-upload-area:hover { border-color: #e60012; }
        .logo-upload-area img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            inset: 0;
        }
        .logo-upload-area span {
            font-size: 9px;
            color: #666;
            text-align: center;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .logo-upload-area input {
            display: none;
        }

        /* JUGADORES */
        .players-section {
            margin-top: 20px;
        }

        .players-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .players-header label {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #aaa;
        }

        .btn-add-player {
            background: #2a2a2a;
            border: 2px solid #444;
            color: #fff;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .btn-add-player:hover { border-color: #e60012; color: #e60012; }

        .player-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            background: #2a2a2a;
            padding: 10px;
            border-radius: 10px;
        }

        .player-icon-pick {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #3a3a3a;
            border: 2px solid #444;
            cursor: pointer;
            overflow: hidden;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .player-icon-pick img { width: 100%; height: 100%; object-fit: cover; }
        .player-icon-pick span { font-size: 9px; color: #666; text-align: center; }

        .player-row input {
            flex: 1;
            padding: 10px 12px;
            background: #3a3a3a;
            border: 2px solid #444;
            border-radius: 8px;
            color: #fff;
            font-family: 'RacersMK', sans-serif;
            font-size: 12px;
            outline: none;
        }
        .player-row input:focus { border-color: #e60012; }

        .btn-remove-player {
            background: none;
            border: none;
            color: #ff4444;
            cursor: pointer;
            font-size: 18px;
            flex-shrink: 0;
        }

        /* ICON PICKER */
        .icon-picker-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.9);
            z-index: 200;
            align-items: center;
            justify-content: center;
        }
        .icon-picker-overlay.open { display: flex; }

        .icon-picker {
            background: #1e1e1e;
            border: 2px solid #444;
            border-radius: 15px;
            padding: 25px;
            max-width: 500px;
            width: 100%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .icon-picker h4 {
            font-size: 13px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 15px;
            color: #aaa;
        }

        .icons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
            gap: 10px;
        }

        .icon-option {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            border: 2px solid #3a3a3a;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.2s;
        }
        .icon-option:hover { border-color: #e60012; transform: scale(1.1); }
        .icon-option img { width: 100%; height: 100%; object-fit: cover; }

        /* BTN SUBMIT */
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #e60012, #8b0000);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-family: 'RacersMK', sans-serif;
            font-size: 15px;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 25px;
            transition: all 0.3s;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(230,0,18,0.4); }
        .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        .msg {
            padding: 12px;
            border-radius: 8px;
            font-size: 12px;
            letter-spacing: 1px;
            margin-top: 15px;
            text-align: center;
            display: none;
        }
        .msg.success { background: rgba(0,200,100,0.15); border: 1px solid #00c864; color: #00c864; }
        .msg.error   { background: rgba(230,0,18,0.15);  border: 1px solid #e60012; color: #ff4444; }

        /* ── RESPONSIVE MÓVIL ── */
        @media (max-width: 768px) {
            .groups-grid {
                grid-template-columns: 1fr; /* de 2 columnas a 1 */
            }
        }

        @media (max-width: 600px) {
            body { padding: 15px 10px; }
            h2 { font-size: 15px; letter-spacing: 1px; }
            .info-banner { font-size: 11px; padding: 10px 12px; }

            /* Slots de equipos */
            .slot-row {
                flex-wrap: wrap;
                gap: 8px;
            }
            .slot-info {
                flex: 1;
                min-width: 0; /* evita overflow del nombre */
            }
            .slot-name {
                font-size: 12px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            /* Botones edit/delete en slot */
            .slot-row > div:last-child {
                width: 100%;
                display: flex;
                gap: 8px;
            }
            .btn-slot {
                flex: 1;
                text-align: center;
            }

            /* Modal */
            .modal {
                margin: 10px;
                padding: 20px 15px;
                max-height: 90vh;
                overflow-y: auto;
            }
            .form-row {
                flex-direction: column;
                align-items: center;
            }
            .form-group {
                width: 100%;
            }

            /* Player rows dentro del modal */
            .player-row {
                flex-wrap: wrap;
                gap: 6px;
            }
            .player-row input {
                flex: 1;
                min-width: 120px;
            }
            .btn-remove-player {
                flex-shrink: 0;
            }

            /* Btn submit del modal */
            .btn-submit {
                width: 100%;
            }

            /* Icon picker */
            .icon-picker {
                margin: 15px;
                padding: 15px;
                max-height: 80vh;
                overflow-y: auto;
            }
            .icons-grid {
                grid-template-columns: repeat(4, 1fr); /* menos columnas en móvil */
            }

            /* Notificaciones */
            .notif-box {
                padding: 25px 18px;
                margin: 0 15px;
            }
            .notif-actions {
                flex-direction: column;
                gap: 8px;
            }
            .notif-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    
    <h2>Admin — <span>Grupos</span></h2>

    <!-- GRID DE GRUPOS -->
    <div class="groups-grid" id="groupsGrid">
        <!-- Se carga dinámicamente -->
    </div>

    <!-- MODAL REGISTRAR EQUIPO -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal">
            <button class="modal-close" onclick="closeModal()">✕</button>
            <h3>Registrar Equipo</h3>

            <div class="form-row">
                <!-- Logo -->
                <div style="display:flex; flex-direction:column; align-items:center; gap:8px;">
                    <label style="font-size:10px; letter-spacing:2px; color:#aaa; text-transform:uppercase;">Logo</label>
                    <div class="logo-upload-area" onclick="document.getElementById('logoInput').click()">
                        <img id="logoPreview" src="" alt="" style="display:none;">
                        <span id="logoPlaceholder">Subir<br>imagen</span>
                        <input type="file" id="logoInput" accept="image/*" onchange="previewLogo(this)">
                    </div>
                </div>
                <!-- Nombre -->
                <div class="form-group">
                    <label>Nombre del equipo</label>
                    <input type="text" id="teamName" placeholder="Ej: Equipo Alpha">
                </div>
            </div>

            <!-- Jugadores -->
            <div class="players-section">
                <div class="players-header">
                    <label>Jugadores (mín. 6)</label>
                    <button class="btn-add-player" onclick="addPlayerRowCheck()">+</button>
                </div>
                <div id="playersList"></div>
            </div>

            <button class="btn-submit" id="btnSubmit" onclick="submitTeam()">
                Registrar Equipo
            </button>
            <div class="msg" id="formMsg"></div>
        </div>
    </div>

    <!-- ICON PICKER -->
    <div class="icon-picker-overlay" id="iconPickerOverlay">
        <div class="icon-picker">
            <h4>Selecciona un personaje</h4>
            <div class="icons-grid" id="iconsGrid">
                <?php foreach ($icons as $icon): ?>
                <div class="icon-option" onclick="pickIcon('<?= htmlspecialchars($icon) ?>')">
                    <img src="../assets/character-icons/<?= htmlspecialchars($icon) ?>" alt="<?= htmlspecialchars($icon) ?>">
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        const GROUPS = [
            { key: 'A', label: 'Grupo A', cls: 'group-a', cup: '../assets/cups-icons/mushroom-cup.png' },
            { key: 'B', label: 'Grupo B', cls: 'group-b', cup: '../assets/cups-icons/flower-cup.png'   },
            { key: 'C', label: 'Grupo C', cls: 'group-c', cup: '../assets/cups-icons/star-cup.png'     },
            { key: 'D', label: 'Grupo D', cls: 'group-d', cup: '../assets/cups-icons/special-cup.png'  },
        ];

        let currentGroup  = null;
        let currentSlot   = null;
        let activeIconBtn = null;
        let teams         = [];

        // ── CARGAR GRUPOS ──────────────────────────────────────────
        async function loadGroups() {
            const res  = await fetch('../api/teams/get.php');
            teams      = await res.json();
            renderGroups();
        }

        function renderGroups() {
            const grid = document.getElementById('groupsGrid');
            grid.innerHTML = '';

            GROUPS.forEach(g => {
                const groupTeams = teams.filter(t => t.group_name === g.key);
                let slotsHTML    = '';

                for (let slot = 1; slot <= 4; slot++) {
                    const team = groupTeams.find(t => t.slot_number === slot);
                    if (team) {
                        const logo = team.logo_path
                            ? `<div class="slot-logo"><img src="../${team.logo_path}" alt="logo"></div>`
                            : `<div class="slot-logo"><span style="font-size:9px;color:#666;">SIN<br>LOGO</span></div>`;
                        slotsHTML += `
                            <div class="slot-row"
                                draggable="true"
                                data-team-id="${team.id}"
                                data-group="${g.key}"
                                data-slot="${slot}">
                                <span class="drag-handle">⠿</span>
                                ${logo}
                                <div class="slot-info">
                                    <div class="slot-name">${team.name}</div>
                                    <div style="font-size:10px;color:#666;">${team.players.length} jugadores</div>
                                </div>
                                <div style="display:flex;gap:8px;">
                                    <button class="btn-slot btn-edit" onclick="openEditModal(${team.id})">Editar</button>
                                    <button class="btn-slot btn-delete" onclick="deleteTeam(${team.id})">Eliminar</button>
                                </div>
                            </div>`;
                    } else {
                        slotsHTML += `
                            <div class="slot-row slot-row-empty"
                                data-group="${g.key}"
                                data-slot="${slot}">
                                <div class="slot-logo"><span>SLOT<br>${slot}</span></div>
                                <div class="slot-info">
                                    <div class="slot-empty">Slot disponible</div>
                                </div>
                                <button class="btn-slot btn-register" onclick="openModal('${g.key}', ${slot})">+ Registrar</button>
                            </div>`;
                    }
                }

                grid.innerHTML += `
                    <div class="group-panel ${g.cls}">
                        <div class="group-header">
                            <h3>${g.label}</h3>
                            <img src="${g.cup}" alt="copa">
                        </div>
                        ${slotsHTML}
                    </div>`;
            });

            // Al final de renderGroups(), antes del cierre de la función
            setupSlotDragDrop();
        }

        // ── MODAL ──────────────────────────────────────────────────
        function openModal(group, slot) {
            currentGroup = group;
            currentSlot  = slot;
            document.getElementById('teamName').value = '';
            document.getElementById('logoPreview').style.display    = 'none';
            document.getElementById('logoPlaceholder').style.display = 'block';
            document.getElementById('logoInput').value = '';
            document.getElementById('playersList').innerHTML = '';
            document.getElementById('formMsg').style.display = 'none';
            for (let i = 0; i < 6; i++) addPlayerRow();

            // Resetear botón a modo registro
            const btn   = document.getElementById('btnSubmit');
            btn.textContent = 'Registrar Equipo';
            btn.onclick     = submitTeam;

            document.getElementById('modalOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('open');
            document.getElementById('iconPickerOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }

        // ── LOGO PREVIEW ───────────────────────────────────────────
        function previewLogo(input) {
            const file = input.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById('logoPreview');
                img.src   = e.target.result;
                img.style.display = 'block';
                document.getElementById('logoPlaceholder').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }

        // ── JUGADORES ──────────────────────────────────────────────
        let playerCount = 0;

        function addPlayerRow(initialName = '', initialIcon = '') {
            playerCount++;
            const id  = 'player_' + playerCount;
            const row = document.createElement('div');
            row.className = 'player-row';
            row.id = id;

            const iconPreview = initialIcon
                ? `<img src="../${initialIcon}" alt="icono">`
                : '<span>Icono</span>';

            row.innerHTML = `
                <div class="player-icon-pick" onclick="openIconPicker('${id}')">
                    ${iconPreview}
                </div>
                <input type="text" placeholder="Nombre del jugador" data-player-name value="${initialName}">
                <input type="hidden" data-player-icon value="${initialIcon}">
                <button class="btn-remove-player" onclick="removePlayer('${id}')">✕</button>`;
            document.getElementById('playersList').appendChild(row);
        }

        function removePlayer(id) {
            const rows = document.querySelectorAll('.player-row');
            if (rows.length <= 6) {
                showMsg('Mínimo 6 jugadores requeridos', 'error');
                return;
            }
            document.getElementById(id).remove();
        }

        // ── ICON PICKER ────────────────────────────────────────────
        function openIconPicker(playerId) {
            activeIconBtn = playerId;
            document.getElementById('iconPickerOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function pickIcon(iconFile) {
            if (!activeIconBtn) return;
            const row      = document.getElementById(activeIconBtn);
            const iconPick = row.querySelector('.player-icon-pick');
            const hidden   = row.querySelector('[data-player-icon]');
            iconPick.innerHTML = `<img src="../assets/character-icons/${iconFile}" alt="${iconFile}">`;
            hidden.value       = 'assets/character-icons/' + iconFile;
            document.getElementById('iconPickerOverlay').classList.remove('open');
            activeIconBtn = null;
        }

        // Cerrar icon picker al hacer click fuera
        document.getElementById('iconPickerOverlay').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('open');
            }
        });

        // ── SUBMIT ─────────────────────────────────────────────────
        async function submitTeam() {
            const name    = document.getElementById('teamName').value.trim();
            const logoFile = document.getElementById('logoInput').files[0];
            const btn     = document.getElementById('btnSubmit');

            if (!name) { showMsg('El nombre del equipo es requerido', 'error'); return; }

            const players = [];
            document.querySelectorAll('.player-row').forEach(row => {
                const pname = row.querySelector('[data-player-name]').value.trim();
                const picon = row.querySelector('[data-player-icon]').value.trim();
                if (pname) players.push({ name: pname, character_icon: picon });
            });

            if (players.length < 6) { showMsg('Mínimo 6 jugadores requeridos', 'error'); return; }

            const formData = new FormData();
            formData.append('name',        name);
            formData.append('group_name',  currentGroup);
            formData.append('slot_number', currentSlot);
            formData.append('players',     JSON.stringify(players));
            if (logoFile) formData.append('logo', logoFile);

            btn.disabled    = true;
            btn.textContent = 'Registrando...';

            try {
                const hasProg = await checkBracketProgress();
                if (hasProg) {
                    btn.disabled    = false;
                    btn.textContent = 'Registrar Equipo';
                    showBracketWarning(async () => {
                        await resetBracketSilently();
                        await doSubmitTeam(formData, name, btn);
                    });
                    return;
                }
                await doSubmitTeam(formData, name, btn);
            } catch (e) {
                showMsg('Error de conexión', 'error');
            } finally {
                btn.disabled    = false;
                btn.textContent = 'Registrar Equipo';
            }
        }

        async function doSubmitTeam(formData, name, btn) {
            btn.disabled    = true;
            btn.textContent = 'Registrando...';
            try {
                const res  = await fetch('../api/teams/create.php', { method: 'POST', body: formData });
                const data = await res.json();
                if (data.success) {
                    closeModal();
                    loadGroups();
                    setTimeout(() => showNotif({
                        type:  'success',
                        icon:  '🏎️',
                        title: '¡Equipo Registrado!',
                        text:  `"${name}" fue agregado correctamente al Grupo ${currentGroup}.`,
                        actions: [{ label: '¡Genial!', style: 'primary green', action: null }]
                    }), 300);
                } else {
                    showMsg(data.error || 'Error al registrar', 'error');
                }
            } catch(e) {
                showMsg('Error de conexión', 'error');
            } finally {
                btn.disabled    = false;
                btn.textContent = 'Registrar Equipo';
            }
        }
        
        // ── DELETE ─────────────────────────────────────────────────
        async function deleteTeam(id) {
            const team   = teams.find(t => t.id === id);
            const nombre = team ? team.name : 'este equipo';

            showNotif({
                type:  'confirm',
                icon:  '⚠️',
                title: '¿Eliminar Equipo?',
                text:  `"${nombre}" y todos sus jugadores serán eliminados permanentemente. Esta acción no se puede deshacer.`,
                actions: [
                    {
                        label: 'Cancelar',
                        style: 'secondary',
                        action: null
                    },
                    {
                        label: 'Sí, Eliminar',
                        style: 'primary',
                        action: async () => {
                            const hasProg = await checkBracketProgress();
                            const doDelete = async () => {
                                const res  = await fetch('../api/teams/delete.php', {
                                    method:  'DELETE',
                                    headers: { 'Content-Type': 'application/json' },
                                    body:    JSON.stringify({ id })
                                });
                                const data = await res.json();
                                if (data.success) {
                                    loadGroups();
                                    showNotif({
                                        type:  'success',
                                        icon:  '🗑️',
                                        title: 'Equipo Eliminado',
                                        text:  `"${nombre}" fue eliminado correctamente.`,
                                        actions: [{ label: 'OK', style: 'primary green', action: null }]
                                    });
                                } else {
                                    showNotif({
                                        type:  'error',
                                        icon:  '❌',
                                        title: 'Error',
                                        text:  data.error || 'No se pudo eliminar el equipo.',
                                        actions: [{ label: 'Cerrar', style: 'secondary', action: null }]
                                    });
                                }
                            };
                            if (hasProg) {
                                showBracketWarning(async () => {
                                    await resetBracketSilently();
                                    await doDelete();
                                });
                            } else {
                                await doDelete();
                            }
                        },
                    }
                ]
            });
        }

        // ── EDIT ─────────────────────────────────────────────────

        function openEditModal(teamId) {
            const team = teams.find(t => t.id === teamId);
            if (!team) return;

            // Reusar el mismo modal pero en modo edición
            currentGroup = team.group_name;
            currentSlot  = team.slot_number;

            document.getElementById('teamName').value = team.name;

            // Logo preview
            if (team.logo_path) {
                const img = document.getElementById('logoPreview');
                img.src   = '../' + team.logo_path;
                img.style.display = 'block';
                document.getElementById('logoPlaceholder').style.display = 'none';
            } else {
                document.getElementById('logoPreview').style.display    = 'none';
                document.getElementById('logoPlaceholder').style.display = 'block';
            }

            document.getElementById('logoInput').value = '';
            document.getElementById('playersList').innerHTML = '';
            document.getElementById('formMsg').style.display = 'none';

            // Cargar jugadores existentes
            team.players.forEach(p => {
                addPlayerRow(p.name, p.character_icon);
            });

            // Cambiar botón submit a modo edición
            const btn = document.getElementById('btnSubmit');
            btn.textContent = 'Guardar Cambios';
            btn.onclick     = () => submitEdit(teamId);

            document.getElementById('modalOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        async function submitEdit(teamId) {
            const name     = document.getElementById('teamName').value.trim();
            const logoFile = document.getElementById('logoInput').files[0];
            const btn      = document.getElementById('btnSubmit');

            if (!name) { showMsg('El nombre del equipo es requerido', 'error'); return; }

            const players = [];
            document.querySelectorAll('.player-row').forEach(row => {
                const pname = row.querySelector('[data-player-name]').value.trim();
                const picon = row.querySelector('[data-player-icon]').value.trim();
                if (pname) players.push({ name: pname, character_icon: picon });
            });

            if (players.length < 6) { showMsg('Mínimo 6 jugadores requeridos', 'error'); return; }

            const formData = new FormData();
            formData.append('id',      teamId);
            formData.append('name',    name);
            formData.append('players', JSON.stringify(players));
            if (logoFile) formData.append('logo', logoFile);

            btn.disabled    = true;
            btn.textContent = 'Guardando...';

            try {
                const res  = await fetch('../api/teams/update.php', { method: 'POST', body: formData });
                const data = await res.json();
                if (data.success) {
                    closeModal();
                    loadGroups();
                    setTimeout(() => showNotif({
                        type:  'success',
                        icon:  '✅',
                        title: '¡Cambios Guardados!',
                        text:  `El equipo "${name}" fue actualizado correctamente.`,
                        actions: [{ label: 'Perfecto', style: 'primary green', action: null }]
                    }), 300);
                } else {
                    showMsg(data.error || 'Error al guardar', 'error');
                }
            } catch(e) {
                showMsg('Error de conexión', 'error');
            } finally {
                btn.disabled    = false;
                btn.textContent = 'Guardar Cambios';
            }
        }

        function addPlayerRowCheck() {
            const rows = document.querySelectorAll('.player-row');
            if (rows.length >= 10) {
                showMsg('Máximo 10 jugadores por equipo', 'error');
                return;
            }
            addPlayerRow();
        }

        // ── SISTEMA DE NOTIFICACIONES ──────────────────────────────
        function showNotif({ type = 'success', icon, title, text, actions }) {
            const overlay = document.getElementById('notifOverlay');
            const box     = document.getElementById('notifBox');

            document.getElementById('notifIcon').textContent  = icon;
            document.getElementById('notifTitle').textContent = title;
            document.getElementById('notifText').textContent  = text;
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

        // ── DRAG & DROP ENTRE SLOTS ────────────────────────────────
        let draggedTeamId  = null;
        let draggedGroup   = null;
        let draggedSlot    = null;

        // ── VERIFICACIÓN DE BRACKET ────────────────────────────────────
        async function checkBracketProgress() {
            const res  = await fetch('../api/bracket/has_progress.php');
            const data = await res.json();
            return data.has_progress;
        }

        function showBracketWarning(onConfirm) {
            showNotif({
                type:  'confirm',
                icon:  '⚠️',
                title: '¡Bracket en Progreso!',
                text:  'Hay avances registrados en el bracket. Al realizar este cambio en los grupos, el bracket se reiniciará automáticamente. ¿Deseas continuar?',
                actions: [
                    { label: 'Cancelar', style: 'secondary', action: null },
                    { label: 'Sí, Continuar', style: 'primary', action: onConfirm }
                ]
            });
        }

        async function resetBracketSilently() {
            await fetch('../api/bracket/reset.php', { method: 'POST' });
        }

        function setupSlotDragDrop() {
            // Slots con equipo — draggables
            document.querySelectorAll('.slot-row[draggable="true"]').forEach(row => {
                row.addEventListener('dragstart', e => {
                    draggedTeamId = parseInt(row.dataset.teamId);
                    draggedGroup  = row.dataset.group;
                    draggedSlot   = parseInt(row.dataset.slot);
                    row.classList.add('dragging-slot');
                    e.dataTransfer.effectAllowed = 'move';
                });

                row.addEventListener('dragend', () => {
                    row.classList.remove('dragging-slot');
                });
            });

            // Todos los slots — drop targets
            document.querySelectorAll('.slot-row').forEach(row => {
                row.addEventListener('dragover', e => {
                    e.preventDefault();
                    if (!draggedTeamId) return;
                    row.classList.add('drag-over-slot');
                    e.dataTransfer.dropEffect = 'move';
                });

                row.addEventListener('dragleave', () => {
                    row.classList.remove('drag-over-slot');
                });

                row.addEventListener('drop', async e => {
                    e.preventDefault();
                    row.classList.remove('drag-over-slot');

                    if (!draggedTeamId) return;

                    const targetGroup = row.dataset.group;
                    const targetSlot  = parseInt(row.dataset.slot);
                    const targetTeamId = row.dataset.teamId ? parseInt(row.dataset.teamId) : null;

                    // Si es el mismo slot, no hacer nada
                    if (draggedGroup === targetGroup && draggedSlot === targetSlot) return;

                    await swapTeamSlots(
                        draggedTeamId, draggedGroup, draggedSlot,
                        targetTeamId,  targetGroup,  targetSlot
                    );

                    draggedTeamId = null;
                    draggedGroup  = null;
                    draggedSlot   = null;
                });
            });
        }

        async function swapTeamSlots(teamIdA, groupA, slotA, teamIdB, groupB, slotB) {
            const doSwap = async () => {
                try {
                    const resA = await fetch('../api/teams/move.php', {
                        method:  'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body:    JSON.stringify({ team_id: teamIdA, group_name: groupB, slot_number: slotB })
                    });
                    const dataA = await resA.json();
                    if (!dataA.success) throw new Error(dataA.error);

                    if (teamIdB) {
                        const resB = await fetch('../api/teams/move.php', {
                            method:  'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body:    JSON.stringify({ team_id: teamIdB, group_name: groupA, slot_number: slotA })
                        });
                        const dataB = await resB.json();
                        if (!dataB.success) throw new Error(dataB.error);
                    }

                    loadGroups();
                } catch(err) {
                    showNotif({
                        type:  'error',
                        icon:  '❌',
                        title: 'Error al mover equipo',
                        text:  err.message || 'No se pudo completar el movimiento.',
                        actions: [{ label: 'Cerrar', style: 'secondary', action: null }]
                    });
                }
            };

            const hasProg = await checkBracketProgress();
            if (hasProg) {
                showBracketWarning(async () => {
                    await resetBracketSilently();
                    await doSwap();
                });
            } else {
                await doSwap();
            }
        }

        // Cerrar modal al hacer click fuera
        document.getElementById('modalOverlay').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        loadGroups();
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