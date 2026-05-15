# CQBFactory

Implementacion web para WordPress de CQB Factory, enfocada en reservas de partidas CQB con estilo tactico, oscuro y profesional.

## Opcion recomendada: tema hijo WordPress

Directorio del tema:

- `cqb-factory-child/`

Archivos clave:

- `cqb-factory-child/style.css`
- `cqb-factory-child/functions.php`
- `cqb-factory-child/header.php`
- `cqb-factory-child/front-page.php`
- `cqb-factory-child/page-inscripcion-y-pago.php`
- `cqb-factory-child/assets/js/theme.js`

### Instalacion

1. Copia `cqb-factory-child` dentro de `wp-content/themes/`.
2. Verifica que el tema padre indicado en `style.css` exista (`twentytwentyfour`).
3. Activa el tema hijo desde Apariencia > Temas.
4. En Ajustes > Lectura, selecciona una portada estatica para usar `front-page.php`.
5. (Opcional) Crea una pagina y asigna la plantilla `Inscripcion y Pago`.

## Opcion alternativa: bloque HTML

Si prefieres implementacion rapida sin tema:

1. Crea una pagina nueva en WordPress.
2. Agrega un bloque HTML personalizado.
3. Copia el contenido completo de `landing-cqb-factory-wordpress.html`.
4. Publica y asigna como portada.

Archivo adicional para flujo separado de pago:

- `inscripcion-y-pago.html`

## Notas de integracion

- El diseno es responsive para desktop y celular.
- La portada incluye estructura de cuenta, reservas y progreso.
- El pago se abre en vista separada al hacer clic en `Inscribirme`.
- La portada ahora soporta registro e inicio de sesion contra una base MySQL externa para guardar jugadores y sus estadisticas.

### Configuracion de MySQL externa

Agrega estas constantes en `wp-config.php` para activar el sistema de jugadores propio:

```php
define('CQB_FACTORY_EXT_DB_HOST', 'tu-host-mysql');
define('CQB_FACTORY_EXT_DB_NAME', 'tu_base_de_datos');
define('CQB_FACTORY_EXT_DB_USER', 'tu_usuario_mysql');
define('CQB_FACTORY_EXT_DB_PASSWORD', 'tu_password_mysql');
define('CQB_FACTORY_EXT_DB_PORT', 3306);
define('CQB_FACTORY_EXT_DB_TABLE', 'cqb_factory_players');
```

Tambien puedes entregar esos mismos valores como variables de entorno con nombres `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD` y `DB_TABLE`.

La tabla se crea automaticamente la primera vez que cargue el tema con credenciales validas.

### Bootstrap de moderador externo

Si quieres que el tema cree automaticamente una cuenta moderador en la base externa al cargar WordPress, agrega estas constantes en `wp-config.php`:

```php
define('CQB_FACTORY_BOOTSTRAP_MOD_NAME', 'Nombre del moderador');
define('CQB_FACTORY_BOOTSTRAP_MOD_EMAIL', 'correo@dominio.com');
define('CQB_FACTORY_BOOTSTRAP_MOD_PASSWORD', 'tu-password-segura');
```

Tambien puedes marcar correos como moderadores externos con:

```php
define('CQB_FACTORY_MODERATOR_EMAILS', 'correo1@dominio.com,correo2@dominio.com');
```

Con eso, si el usuario inicia sesion desde la base externa, tambien vera el panel de moderadores.

Campos gestionados por el sistema:

- `full_name`
- `email`
- `password_hash`
- `victories`
- `defeats`
- `matches_played`
- `active_reservations`
- `ratio`