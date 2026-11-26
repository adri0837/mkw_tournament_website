# Mario Kart World Tournament Website

### Backup de la Versión Final v2.0 — Pakorro x Duckman Tournament

Este repositorio contiene la versión limpia, optimizada y final del sitio web de gestión y visualización de torneos para **Mario Kart World**. Está completamente listo para ser desplegado en **Hostinger** o ejecutado en un entorno local (**XAMPP**).

---

## Características de la Versión
* **Lista para Producción:** Estructura limpia y optimizada para servidores compartidos.
* **Sin Archivos Basura:** Se entrega sin los archivos multimedia temporales subidos por los usuarios (videos `.mp4` de clips y logos de equipos `.png`), listos para que la base de datos comience desde cero.
* **Base de Datos Incluida:** Se adjunta el script [bd_structure.sql] con la estructura SQL exacta para MySQL.

---

## Guía de Despliegue

### 1. Entorno de Producción (Hostinger)
Para subir el sitio a Hostinger, simplemente carga los archivos al directorio `public_html`.
> [!NOTE]
> **Límites de Carga:** No necesitas modificar ningún parámetro de carga de archivos en Hostinger; por defecto, el plan basico permite subidas de hasta **240 MB**, lo cual es ideal para los clips de video.

### 2. Entorno Local (XAMPP / PHP.ini)
Si ejecutas el sitio de manera local, debes configurar el límite máximo de subida de archivos en PHP para permitir que los usuarios suban sus replays de clips pesados sin problemas.

1. Abre tu panel de control de XAMPP.
2. Haz clic en **Config** al lado de Apache y selecciona `php.ini`.
3. Busca y modifica las siguientes líneas para cambiarlas a **200M**:
   ```ini
   upload_max_filesize = 200M
   post_max_size = 200M
   ```
4. Guarda el archivo y reinicia el servidor Apache en XAMPP.

---

## Configuración de Zona Horaria (Fecha de los Clips)

Debido a la diferencia de zonas horarias entre servidores de producción y locales, se incluye una pequeña sugerencia en el archivo `admin/clips.php` para la visualización de la variable `date`:

### Opción A: Desarrollo Local (Zona Horaria Local)
Si estás probando el proyecto localmente con XAMPP, se recomienda usar este formato para sincronizar con tu reloj local:

```javascript
const date = new Date(clip.uploaded_at).toLocaleString('es-MX', {
    day:    '2-digit',
    month:  'short',
    year:   'numeric',
    hour:   '2-digit',
    minute: '2-digit',
    hour12: true
});
```

### Opción B: Producción (Hostinger / Zona Horaria UTC)
En Hostinger, el servidor MySQL maneja por defecto la zona horaria en **UTC**. Para que las horas de los clips se muestren de forma correcta ajustadas a los visitantes en es-MX, añade el sufijo `' UTC'` en la creación de la fecha:

```javascript
const date = new Date(clip.uploaded_at + ' UTC').toLocaleString('es-MX', {
    day:    '2-digit',
    month:  'short',
    year:   'numeric',
    hour:   '2-digit',
    minute: '2-digit',
    hour12: true
});
```
