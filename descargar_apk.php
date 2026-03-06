<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descarga Sisvaqr</title>
    <style>
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            color: oklch(0.145 0 0);
        }

        body { 
            background-color: #f4f6f8; 
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* Navbar Refinado */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 64px;
            background-color: oklch(0.278 0.033 256.848);
            border-bottom: 2px solid oklch(0.21 0.034 264.665);
            display: flex;
            align-items: center;
            padding: 0 24px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar .logo {
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            font-weight: 600;
            color: #fff;
            
        }

        /* Contenedor Principal (Card) */
        .container {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            text-align: center;
            max-width: 400px;
            width: 90%;
            margin-top: 80px;
        }

        .icon-box {
            background-color: oklch(0.95 0.01 256);
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        h1 {
            font-size: 1.5rem;
            margin-bottom: 8px;
            font-weight: 700;
        }

        p {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        /* Botón de Descarga */
        .btn-download {
            display: inline-block;
            background-color: oklch(0.278 0.033 256.848);
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(28, 45, 92, 0.2);
        }

        .btn-download:hover {
            transform: translateY(-2px);
            background-color: oklch(0.35 0.04 256);
            box-shadow: 0 6px 15px rgba(28, 45, 92, 0.3);
        }

        .btn-download:active {
            transform: translateY(0);
        }

        .file-info {
            margin-top: 20px;
            font-size: 0.8rem;
            color: #94a3b8;
        }

        .btn-volver {
            background-color: white;
            border: 2px solid oklch(0.707 0.022 261.325);
            width: 200px;
            height: 52px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            margin-top: 25px;
            transition: background-color 0.2s;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">Sisva Project</div>
    </nav>

    <div class="container">
        <div class="icon-box">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="oklch(0.278 0.033 256.848)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
        </div>
        
        <h1>Instalar Sisvaqr</h1>
        <p>Obtén la última versión de la aplicación para gestionar tus accesos de forma rápida y segura.</p>

        <a href="https://drive.google.com/file/d/1gULmcGeH-HogmoMIRFPTLvL7KlphC1ZP/view?usp=drive_link" class="btn-download">
            Descargar APK
        </a>

        <div class="file-info">
            Versión 1.0.0 • Android
        </div>
    </div>

     <button class="btn-volver" type="button" onclick="window.location.href='panel_administrador.php'">
            VOLVER AL PANEL
        </button>

</body>
</html>
