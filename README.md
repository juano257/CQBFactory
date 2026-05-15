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
- Para produccion, conecta formularios y botones con plugins o endpoints reales (por ejemplo: Fluent Forms, WPForms, WooCommerce o pasarela externa).