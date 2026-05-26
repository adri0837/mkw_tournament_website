<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    .bracket-section {
        color: #f4f4f4;
        font-family: 'RacersMK', sans-serif;
    }

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

    .bracket-outer {
        background: #1a1a1a;
        border-radius: 15px;
        padding: 30px 20px;
        overflow-x: auto;
        width: 100%;
    }

    .bracket-tree {
        display: flex;
        align-items: stretch;
        justify-content: center;
        gap: 0;
        min-width: 1600px;
        position: relative;
    }

    .b-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-shrink: 0;
    }

    .b-col-header {
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #555;
        text-align: center;
        white-space: nowrap;
    }

    .b-col-label {
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #555;
        text-align: center;
        white-space: nowrap;
        margin-bottom: 6px;
    }

    .b-col-content {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
        flex: 1;
        width: 100%;
        gap: 0;
    }

    .b-slot {
        width: 130px;
        height: 48px;
        background: #2a2a2a;
        border: 2px solid #3a3a3a;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0 8px;
        margin: 5px 0;
        transition: all 0.2s;
        position: relative;
        cursor: default;
    }

    .b-slot.draggable {
        cursor: grab;
        border-color: #444;
    }
    .b-slot.draggable:active { cursor: grabbing; }

    .b-slot.empty {
        border-style: dashed;
        border-color: #2a2a2a;
    }

    .b-slot.drag-over {
        border-color: #e60012;
        background: rgba(230,0,18,0.1);
        transform: scale(1.03);
    }

    .b-slot.dragging { opacity: 0.35; }

    .b-slot.semi {
        width: 130px;
        height: 52px;
        border-color: #555;
    }

    .b-slot.final-slot {
        width: 145px;
        height: 58px;
        border-color: #ffd700;
        box-shadow: 0 0 15px rgba(255,215,0,0.15);
    }

    .b-slot.final-slot.empty { border-color: #443300; border-style: dashed; }

    .b-slot.third-slot {
        width: 145px;
        height: 58px;
        border-color: #cd7f32;
        box-shadow: 0 0 15px rgba(205,127,50,0.15);
    }

    .b-slot.third-slot.empty { border-color: #3a2a00; border-style: dashed; }

    .b-logo {
        width: 30px; height: 30px;
        border-radius: 6px;
        background: #3a3a3a;
        border: 1px solid #444;
        overflow: hidden;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .b-logo img { width: 100%; height: 100%; object-fit: cover; }

    .b-name {
        font-size: 10px;
        color: #fff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
        letter-spacing: 0.5px;
    }

    .b-empty-label {
        font-size: 9px;
        color: #333;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .b-group-badge {
        position: absolute;
        top: -7px; left: 6px;
        background: #e60012;
        color: #fff;
        font-size: 7px;
        padding: 1px 5px;
        border-radius: 4px;
        letter-spacing: 1px;
    }

    .conn {
        width: 35px;
        flex: 1;
        position: relative;
        min-height: 58px;
    }

    .conn.right::before {
        content: '';
        position: absolute;
        right: 0; top: 25%;
        width: 17px; height: 50%;
        border-top: 2px solid #3a3a3a;
        border-right: 2px solid #3a3a3a;
        border-bottom: 2px solid #3a3a3a;
    }
    .conn.right::after {
        content: '';
        position: absolute;
        left: 18px; top: 50%;
        width: 17px; height: 2px;
        background: #3a3a3a;
    }

    .conn.left::before {
        content: '';
        position: absolute;
        left: 0; top: 25%;
        width: 17px; height: 50%;
        border-top: 2px solid #3a3a3a;
        border-left: 2px solid #3a3a3a;
        border-bottom: 2px solid #3a3a3a;
    }
    .conn.left::after {
        content: '';
        position: absolute;
        right: 18px; top: 50%;
        width: 17px; height: 2px;
        background: #3a3a3a;
    }

    .conn.single-right::before { display: none; }
    .conn.single-right::after {
        content: '';
        position: absolute;
        left: 0; top: 50%;
        width: 35px; height: 2px;
        background: #3a3a3a;
    }

    .conn.single-left::before { display: none; }
    .conn.single-left::after {
        content: '';
        position: absolute;
        right: 0; top: 50%;
        width: 35px; height: 2px;
        background: #3a3a3a;
    }

    .center-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0 15px;
        align-self: center;
    }

    .trophy-img {
        width: 55px; height: 55px;
        margin: 8px 0;
    }

    .save-toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        padding: 14px 22px;
        border-radius: 10px;
        font-size: 12px;
        font-family: 'RacersMK', sans-serif;
        letter-spacing: 1px;
        z-index: 500;
        display: none;
        animation: slideUpToast 0.3s ease-out;
    }
    .save-toast.success {
        background: rgba(0,200,100,0.15);
        border: 1px solid #00c864;
        color: #00c864;
    }
    .save-toast.error {
        background: rgba(230,0,18,0.15);
        border: 1px solid #e60012;
        color: #ff4444;
    }

    /* RASTRO VISUAL */
    .b-slot.trail {
        opacity: 0.45;
        filter: grayscale(0.8);
        cursor: default;
        border-color: #333;
        border-style: dashed;
    }

    .b-slot.trail .b-name {
        color: #777;
    }

    /* BOTÓN REINICIAR */
    .btn-reset-bracket {
        background: rgba(230,0,18,0.1);
        border: 1.5px solid rgba(230,0,18,0.3);
        color: #ff4444;
        padding: 10px 20px;
        border-radius: 10px;
        font-family: 'RacersMK', sans-serif;
        font-size: 11px;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 20px;
    }

    .btn-reset-bracket:hover {
        background: rgba(230,0,18,0.2);
        border-color: #e60012;
    }

    /* NOTIF OVERLAY — mismo estilo que grupos.php */
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
    .notif-btn.secondary {
        background: #2a2a2a;
        color: #aaa;
        border: 2px solid #444;
    }
    .notif-btn.secondary:hover { border-color: #fff; color: #fff; }

    h2 {
        font-size: 20px;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #2a2a2a;
    }

    h2 span { color: #e60012; }

    @keyframes slideUpToast {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── RESPONSIVE MÓVIL ── */
    @media (max-width: 600px) {
        body { padding: 15px 10px; }
        h2 { font-size: 15px; letter-spacing: 1px; }
        .stats-bar { flex-direction: column; gap: 10px; }
        .stat-card { padding: 12px 15px; }
        .stat-number { font-size: 22px; }
        .clips-grid,
        .admin-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        .clip-info { flex-wrap: wrap; gap: 8px; }
        .btn-delete-clip { width: 100%; text-align: center; }
        .confirm-box { padding: 25px 18px; margin: 0 15px; }
        .confirm-actions { flex-direction: column; }
    }
</style>

<div class="bracket-section">
    <h2>Admin — <span>Brackets</span></h2>

    <div class="info-banner">
        💡 Los equipos aparecen automáticamente en su posición de grupo. Arrastra un equipo hacia un slot de la <strong>siguiente ronda de su rama</strong> para avanzarlo. El rastro de su recorrido quedará visible.
    </div>

    <button class="btn-reset-bracket" onclick="askResetBracket()">
        🔄 Reiniciar Bracket
    </button>

    <div class="notif-overlay" id="confirmResetOverlay">
        <div class="notif-box confirm">
            <span class="notif-icon">🔄</span>
            <div class="notif-title">¿Reiniciar Bracket?</div>
            <div class="notif-text">Se borrará todo el progreso del bracket.<br>Los equipos volverán a sus posiciones iniciales de grupo.<br>Esta acción es permanente.</div>
            <div class="notif-actions">
                <button class="notif-btn secondary" onclick="closeResetConfirm()">Cancelar</button>
                <button class="notif-btn primary" onclick="confirmResetBracket()">Sí, Reiniciar</button>
            </div>
        </div>
    </div>

    <div class="bracket-outer">
        <div class="bracket-tree" id="bracketTree"></div>
    </div>

</div>

<div class="save-toast" id="saveToast"></div>

<script>
(function() {
    let allTeams    = [];
    let bracketData = {};
    let draggedTeam = null;
    let dragSource  = null;

    const SLOT_IDS = [
        'group_A-1','group_A-2','group_A-3','group_A-4',
        'group_A_winner-1','group_A_winner-2',
        'winner_A-1',
        'group_B-1','group_B-2','group_B-3','group_B-4',
        'group_B_winner-1','group_B_winner-2',
        'winner_B-1',
        'group_C-1','group_C-2','group_C-3','group_C-4',
        'group_C_winner-1','group_C_winner-2',
        'winner_C-1',
        'group_D-1','group_D-2','group_D-3','group_D-4',
        'group_D_winner-1','group_D_winner-2',
        'winner_D-1',
        'semi_left-1','semi_right-1',
        'final-1','third-1'
    ];

    let progressData = []; // historial de movimientos

    async function init() {
        const [teamsRes, bracketRes] = await Promise.all([
            fetch('../api/teams/get.php'),
            fetch('../api/bracket/get.php')
        ]);
        allTeams = await teamsRes.json();
        const data = await bracketRes.json();

        // Slots base
        bracketData = {};
        data.slots.forEach(s => {
            const key = s.round + '-' + s.slot_number;
            if (s.team_id) {
                const team = allTeams.find(t => t.id == s.team_id);
                if (team) bracketData[key] = team;
            }
        });

        // Progreso
        progressData = data.progress || [];

        autoPlaceGroupTeams();
        applyProgress();
        renderBracket();
    }

    function applyProgress() {
        // Aplicar cada movimiento del historial al bracketData
        progressData.forEach(p => {
            const team = allTeams.find(t => t.id == p.team_id);
            if (team) bracketData[p.slot_key] = team;
        });
    }

    function autoPlaceGroupTeams() {
        ['A','B','C','D'].forEach(g => {
            const groupTeams = allTeams.filter(t => t.group_name === g);
            groupTeams.forEach(team => {
                const key = `group_${g}-${team.slot_number}`;
                if (!bracketData[key]) bracketData[key] = team;
            });
        });
    }

    function renderBracket() {
        const tree = document.getElementById('bracketTree');
        tree.innerHTML = '';

        tree.appendChild(makeGroupsCol('A', 'C', 'Grupos A & C'));
        tree.appendChild(makeConnCol('right', 4));
        tree.appendChild(makeWinnersCol('A', 'C'));
        tree.appendChild(makeConnCol('right', 2));
        tree.appendChild(makeGroupWinnerCol('A', 'C'));
        tree.appendChild(makeConnCol('right', 1));
        tree.appendChild(makeSemiCol('left'));
        tree.appendChild(makeSingleConn('right', 1));
        tree.appendChild(makeCenterCol());
        tree.appendChild(makeSingleConn('left', 1));
        tree.appendChild(makeSemiCol('right'));
        tree.appendChild(makeConnCol('left', 1));
        tree.appendChild(makeGroupWinnerCol('B', 'D'));
        tree.appendChild(makeConnCol('left', 2));
        tree.appendChild(makeWinnersCol('B', 'D'));
        tree.appendChild(makeConnCol('left', 4));
        tree.appendChild(makeGroupsCol('B', 'D', 'Grupos B & D'));

        setupDragDrop();
    }

    function createCol(labelText) {
        const col = document.createElement('div');
        col.className = 'b-col';
        
        const header = document.createElement('div');
        header.className = 'b-col-header';
        header.innerHTML = labelText ? labelText : '&nbsp;';
        col.appendChild(header);

        const content = document.createElement('div');
        content.className = 'b-col-content';
        col.appendChild(content);

        return { col, content };
    }

    function makeGroupsCol(g1, g2, label) {
        const { col, content } = createCol(label);
        [g1, g2].forEach(g => {
            for (let i = 1; i <= 4; i++) {
                const key = `group_${g}-${i}`;
                content.appendChild(makeSlot(key, bracketData[key] || null, g));
            }
        });
        return col;
    }

    function makeWinnersCol(g1, g2) {
        const { col, content } = createCol('Ganadores');
        [g1, g2].forEach(g => {
            for (let i = 1; i <= 2; i++) {
                const key = `group_${g}_winner-${i}`;
                content.appendChild(makeSlot(key, bracketData[key] || null, g));
            }
        });
        return col;
    }

    function makeGroupWinnerCol(g1, g2) {
        const { col, content } = createCol('Ganador Grupo');
        [g1, g2].forEach(g => {
            const key = `winner_${g}-1`;
            content.appendChild(makeSlot(key, bracketData[key] || null, g));
        });
        return col;
    }

    function makeSemiCol(side) {
        const { col, content } = createCol('Semifinal');
        const key = side === 'left' ? 'semi_left-1' : 'semi_right-1';
        content.appendChild(makeSlot(key, bracketData[key] || null, null, true));
        return col;
    }

    function makeCenterCol() {
        const col = document.createElement('div');
        col.className = 'center-col';
        addLabel(col, 'Campeón');
        col.appendChild(makeSlot('final-1', bracketData['final-1'] || null, null, false, true));
        const trophy = document.createElement('img');
        trophy.className = 'trophy-img';
        trophy.src = '../assets/icons-random/GrandPrix.png';
        trophy.alt = 'trofeo';
        col.appendChild(trophy);
        addLabel(col, 'Subcampeón');
        col.appendChild(makeSlot('third-1', bracketData['third-1'] || null, null, false, false, true));
        return col;
    }

    function addLabel(col, text) {
        const lbl = document.createElement('div');
        lbl.className   = 'b-col-label';
        lbl.textContent = text;
        col.appendChild(lbl);
    }

    function makeSlot(key, team, group = null, isSemi = false, isFinal = false, isThird = false) {
        const slot = document.createElement('div');
        let cls = 'b-slot';
        if (isFinal)      cls += ' final-slot';
        else if (isThird) cls += ' third-slot';
        else if (isSemi)  cls += ' semi';

        // ¿Este slot es parte del rastro de algún equipo?
        const isTrail = progressData.some(p => p.slot_key === key) && !bracketData[key];

        if (isTrail) {
            cls += ' trail';
        } else if (team) {
            cls += ' draggable';
        } else {
            cls += ' empty';
        }

        slot.className      = cls;
        slot.dataset.slotId = key;

        // Determinar qué equipo mostrar en el rastro
        const displayTeam = team || (isTrail
            ? allTeams.find(t => t.id == progressData.find(p => p.slot_key === key)?.team_id)
            : null);

        if (group && displayTeam) {
            const badge = document.createElement('div');
            badge.className   = 'b-group-badge';
            badge.textContent = 'G' + group;
            slot.appendChild(badge);
        }

        if (displayTeam) {
            const logo = document.createElement('div');
            logo.className = 'b-logo';
            if (displayTeam.logo_path) logo.innerHTML = `<img src="../${displayTeam.logo_path}" alt="logo">`;
            slot.appendChild(logo);
            const name = document.createElement('div');
            name.className   = 'b-name';
            name.textContent = displayTeam.name;
            slot.appendChild(name);
        } else {
            const empty = document.createElement('div');
            empty.className   = 'b-empty-label';
            empty.textContent = isFinal ? '🏆 Campeón' : isThird ? '🥈 Subcampeón' : 'Vacío';
            slot.appendChild(empty);
        }

        return slot;
    }

    function makeConnCol(direction, count) {
        const { col, content } = createCol();
        col.style.width = '35px';
        for (let i = 0; i < count; i++) {
            const c = document.createElement('div');
            c.className = `conn ${direction}`;
            content.appendChild(c);
        }
        return col;
    }

    function makeSingleConn(direction, count) {
        const { col, content } = createCol();
        col.style.width = '35px';
        for (let i = 0; i < count; i++) {
            const c = document.createElement('div');
            c.className = `conn single-${direction}`;
            content.appendChild(c);
        }
        return col;
    }


    // Mapa de movimientos permitidos por rama
    const ALLOWED_MOVES = {
        'group_A-1': ['group_A_winner-1'],
        'group_A-2': ['group_A_winner-1'],
        'group_A-3': ['group_A_winner-2'],
        'group_A-4': ['group_A_winner-2'],
        'group_C-1': ['group_C_winner-1'],
        'group_C-2': ['group_C_winner-1'],
        'group_C-3': ['group_C_winner-2'],
        'group_C-4': ['group_C_winner-2'],
        'group_B-1': ['group_B_winner-1'],
        'group_B-2': ['group_B_winner-1'],
        'group_B-3': ['group_B_winner-2'],
        'group_B-4': ['group_B_winner-2'],
        'group_D-1': ['group_D_winner-1'],
        'group_D-2': ['group_D_winner-1'],
        'group_D-3': ['group_D_winner-2'],
        'group_D-4': ['group_D_winner-2'],
        'group_A_winner-1': ['winner_A-1'],
        'group_A_winner-2': ['winner_A-1'],
        'group_C_winner-1': ['winner_C-1'],
        'group_C_winner-2': ['winner_C-1'],
        'group_B_winner-1': ['winner_B-1'],
        'group_B_winner-2': ['winner_B-1'],
        'group_D_winner-1': ['winner_D-1'],
        'group_D_winner-2': ['winner_D-1'],
        'winner_A-1': ['semi_left-1'],
        'winner_C-1': ['semi_left-1'],
        'winner_B-1': ['semi_right-1'],
        'winner_D-1': ['semi_right-1'],
        'semi_left-1':  ['final-1','third-1'],
        'semi_right-1': ['final-1','third-1'],
    };

    function isValidMove(sourceKey, targetKey) {
        const allowed = ALLOWED_MOVES[sourceKey] || [];
        return allowed.includes(targetKey);
    }

    // Slots que son posiciones iniciales (no se pueden recibir drops)
    const INITIAL_SLOTS = [
        'group_A-1','group_A-2','group_A-3','group_A-4',
        'group_B-1','group_B-2','group_B-3','group_B-4',
        'group_C-1','group_C-2','group_C-3','group_C-4',
        'group_D-1','group_D-2','group_D-3','group_D-4',
    ];

    function setupDragDrop() {
        document.querySelectorAll('.b-slot').forEach(slot => {
            const key = slot.dataset.slotId;

            // Solo los slots con equipo y que NO sean rastro pueden arrastrarse
            if (slot.classList.contains('draggable') && !slot.classList.contains('trail')) {
                slot.draggable = true;
                slot.addEventListener('dragstart', e => {
                    draggedTeam = bracketData[key];
                    dragSource  = key;
                    slot.classList.add('dragging');
                    e.dataTransfer.effectAllowed = 'move';
                });
                slot.addEventListener('dragend', () => slot.classList.remove('dragging'));
            }

            // Los slots iniciales no reciben drops
            if (INITIAL_SLOTS.includes(key)) return;

            slot.addEventListener('dragover', e => {
                if (!draggedTeam) return;
                if (!isValidMove(dragSource, key)) return;
                e.preventDefault();
                slot.classList.add('drag-over');
            });

            slot.addEventListener('dragleave', () => slot.classList.remove('drag-over'));

            slot.addEventListener('drop', async e => {
                e.preventDefault();
                slot.classList.remove('drag-over');
                if (!draggedTeam) return;
                if (!isValidMove(dragSource, key)) {
                    showToast('❌ Movimiento no permitido en esta rama', 'error');
                    draggedTeam = null;
                    dragSource  = null;
                    return;
                }
                if (bracketData[key]) {
                    showToast('❌ Este slot ya tiene un equipo', 'error');
                    draggedTeam = null;
                    dragSource  = null;
                    return;
                }

                // Registrar en progreso
                progressData.push({ team_id: draggedTeam.id, slot_key: key });
                bracketData[key] = draggedTeam;

                const movedTeam = draggedTeam;
                const movedFrom = dragSource;
                draggedTeam = null;
                dragSource  = null;

                renderBracket();
                await saveProgress(movedTeam.id, key);
            });
        });
    }

    async function saveProgress(teamId, slotKey) {
        try {
            const res  = await fetch('../api/bracket/progress_update.php', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({ team_id: teamId, slot_key: slotKey })
            });
            const data = await res.json();
            if (data.success) showToast('✅ Progreso guardado', 'success');
            else              showToast('❌ Error al guardar', 'error');
        } catch(e) {
            showToast('❌ Error de conexión', 'error');
        }
    }

    function askResetBracket() {
        document.getElementById('confirmResetOverlay').classList.add('open');
    }

    function closeResetConfirm() {
        document.getElementById('confirmResetOverlay').classList.remove('open');
    }

    async function confirmResetBracket() {
        closeResetConfirm();
        try {
            const res  = await fetch('../api/bracket/reset.php', {
                method: 'POST'
            });
            const data = await res.json();
            if (data.success) {
                progressData = [];
                // Resetear bracketData a solo posiciones de grupo
                bracketData  = {};
                autoPlaceGroupTeams();
                renderBracket();
                showToast('✅ Bracket reiniciado', 'success');
            } else {
                showToast('❌ Error al reiniciar', 'error');
            }
        } catch(e) {
            showToast('❌ Error de conexión', 'error');
        }
    }

    function showToast(msg, type) {
        const toast       = document.getElementById('saveToast');
        toast.textContent = msg;
        toast.className   = 'save-toast ' + type;
        toast.style.display = 'block';
        setTimeout(() => toast.style.display = 'none', 2500);
    }

    init();
    window.askResetBracket     = askResetBracket;
    window.closeResetConfirm   = closeResetConfirm;
    window.confirmResetBracket = confirmResetBracket;
})();

</script>