<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.html');
    exit;
}

$sections = ['grupos', 'clasificacion', 'progreso', 'clips'];
$active = isset($_GET['section']) && in_array($_GET['section'], $sections)
    ? $_GET['section']
    : 'grupos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin | MK Tournament</title>
    <link rel="icon" type="image/png" href="../assets/icons-random/admin.png">
    <style>
        @font-face {
            font-family: 'RacersMK';
            src: url('../assets/fonts/RACERSDELIGHT.OTF') format('opentype');
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'RacersMK', sans-serif;
            background-color: #121212;
            background-image: radial-gradient(#333 1px, transparent 1px);
            background-size: 20px 20px;
            color: #f4f4f4;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .admin-header {
            background: linear-gradient(135deg, #e60012 0%, #8b0000 100%);
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 4px solid #fff;
            position: sticky;
            top: 0;
            overflow: hidden;
            z-index: 20;
        }

        .admin-header::after {
            content: "";
            position: absolute;
            bottom: 0; left: 0;
            width: 100%; height: 6px;
            background-color: #fff;
            background-image:
                linear-gradient(45deg, #000 25%, transparent 25%, transparent 75%, #000 75%),
                linear-gradient(45deg, #000 25%, transparent 25%, transparent 75%, #000 75%);
            background-size: 12px 12px;
            background-position: 0 0, 6px 6px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-left img { width: 40px; height: 40px; }

        .header-left h1 {
            font-size: 18px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-badge {
            background: rgba(255,255,255,0.2);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .btn-logout {
            background: rgba(0,0,0,0.3);
            border: 2px solid rgba(255,255,255,0.4);
            color: #fff;
            padding: 8px 18px;
            border-radius: 20px;
            cursor: pointer;
            font-family: 'RacersMK', sans-serif;
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .btn-logout:hover { background: rgba(0,0,0,0.6); border-color: #fff; }

        /* LAYOUT */
        .admin-body {
            display: flex;
            flex: 1;
        }

        /* SIDEBAR */
        .sidebar {
            width: 220px;
            background: #1a1a1a;
            border-right: 2px solid #2a2a2a;
            padding: 25px 0;
            flex-shrink: 0;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 10;
        }

        .sidebar-label {
            color: #444;
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            padding: 0 20px;
            margin-bottom: 10px;
        }

        .sidebar-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 14px 20px;
            background: none;
            border: none;
            border-left: 3px solid transparent;
            color: #666;
            font-family: 'RacersMK', sans-serif;
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
            text-decoration: none;
        }

        .sidebar-btn:hover {
            background: #2a2a2a;
            color: #fff;
            border-left-color: #444;
        }

        .sidebar-btn.active {
            background: #2a2a2a;
            color: #e60012;
            border-left-color: #e60012;
        }

        .sidebar-btn img {
            width: 22px;
            height: 22px;
            opacity: 0.4;
        }

        .sidebar-btn:hover img,
        .sidebar-btn.active img { opacity: 1; }

        .sidebar-divider {
            height: 1px;
            background: #2a2a2a;
            margin: 15px 20px;
        }

        .sidebar-back {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            color: #444;
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
            transition: color 0.2s;
        }
        .sidebar-back:hover { color: #888; }

        /* MAIN */
        .admin-main {
            flex: 1;
            padding: 35px;
            overflow-y: auto;
            margin-left: 220px;
        }

        /* NOTIFICACIONES */
        .notif-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.85);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .notif-overlay.open { display: flex; }

        @keyframes popIn {
            from { opacity: 0; transform: scale(0.85) translateY(20px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        .notif-box {
            background: #1e1e1e;
            border-radius: 20px;
            padding: 35px 40px;
            max-width: 380px;
            width: 100%;
            text-align: center;
            animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            z-index: 10000;
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

        /* ── RESPONSIVE ADMIN (MÓVIL) ── */
        .hamburger {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            flex-direction: column;
            gap: 5px;
            padding: 5px;
        }
        .hamburger span {
            display: block;
            width: 22px;
            height: 2px;
            background: #fff;
            border-radius: 2px;
            transition: all 0.3s;
        }

        @media (max-width: 768px) {
            .admin-header {
                padding: 12px 15px;
            }
            .header-left h1 {
                font-size: 13px;
                letter-spacing: 1px;
            }
            .admin-badge {
                display: none;
            }
            .hamburger {
                display: flex;
            }
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 240px;
                z-index: 50;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.6);
                z-index: 40;
            }
            .sidebar-overlay.open {
                display: block;
            }
            .admin-main {
                margin-left: 0;
                padding: 20px 15px;
            }
            iframe {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="admin-header">
        <div class="header-left">
            <img src="../assets/icons-random/GrandPrix.png" alt="logo">
            <h1>Panel de Administración</h1>
        </div>
        <div class="header-right">
            
            <button class="hamburger" onclick="toggleSidebar()" id="hamburgerBtn">
            <span></span>
            <span></span>
            <span></span>
            </button>

            <span class="admin-badge">👤 <?= htmlspecialchars($_SESSION['admin_user']) ?></span>
            <a href="#" class="btn-logout" onclick="doLogout()">
                Cerrar Sesión
            </a>
        </div>
    </header>

    <!-- BODY -->
    <div class="admin-body">
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <p class="sidebar-label">Secciones</p>

            <a href="index.php?section=grupos"
            class="sidebar-btn <?= $active === 'grupos' ? 'active' : '' ?>">
                <img src="../assets/icons-random/VSrace.png" alt="grupos">
                Grupos
            </a>

            <a href="index.php?section=clasificacion"
            class="sidebar-btn <?= $active === 'clasificacion' ? 'active' : '' ?>">
                <img src="../assets/icons-random/150.png" alt="clasificacion">
                Clasificación
            </a>

            <a href="index.php?section=progreso"
            class="sidebar-btn <?= $active === 'progreso' ? 'active' : '' ?>">
                <img src="../assets/icons-random/GrandPrix.png" alt="progreso">
                Brackets
            </a>

            <a href="index.php?section=clips"
            class="sidebar-btn <?= $active === 'clips' ? 'active' : '' ?>">
                <img src="../assets/icons-random/Replays.png" alt="clips">
                Clips
            </a>

            <div class="sidebar-divider"></div>

            
        </aside>

        <!-- MAIN: contenido inyectado por cada subpágina -->
        <main class="admin-main">
            <?php if ($active === 'grupos'): ?>
                <?php include 'grupos.php'; ?>
            <?php elseif ($active === 'clasificacion'): ?>
                <?php include 'clasificacion.php'; ?>
            <?php elseif ($active === 'progreso'): ?>
                <?php include 'progreso.php'; ?>
            <?php elseif ($active === 'clips'): ?>
                <?php include 'clips.php'; ?>
            <?php endif; ?>
        </main>

    </div>

    <script>
        function adjustSidebar() {
            const header       = document.querySelector('.admin-header');
            const sidebar      = document.querySelector('.sidebar');
            const headerHeight = header.offsetHeight;
            sidebar.style.top    = headerHeight + 'px';
            sidebar.style.height = 'calc(100vh - ' + headerHeight + 'px)';
        }

        adjustSidebar();
        window.addEventListener('resize', adjustSidebar);

        function doLogout() {
            document.getElementById('logoutOverlay').classList.add('open');
        }

        function closeNotif() {
            document.getElementById('logoutOverlay').classList.remove('open');
        }

        async function confirmLogout() {
            closeNotif();
            await fetch('../api/auth/logout.php', { method: 'POST' });
            window.location.href = 'login.html';
        }

        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('open');
        }
    </script>
    <!-- NOTIFICACION LOGOUT -->
    <div class="notif-overlay" id="logoutOverlay">
        <div class="notif-box confirm">
            <span class="notif-icon">🏁</span>
            <div class="notif-title">¿Cerrar Sesión?</div>
            <div class="notif-text">Saldrás del panel de administración y tendrás que volver a iniciar sesión.</div>
            <div class="notif-actions">
                <button class="notif-btn secondary" onclick="closeNotif()">Cancelar</button>
                <button class="notif-btn primary"   onclick="confirmLogout()">Sí, Salir</button>
            </div>
        </div>
    </div>
</body>
</html>