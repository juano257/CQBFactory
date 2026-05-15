<?php
/**
 * Portada principal CQB Factory.
 */

if (!defined('ABSPATH')) {
    exit;
}

$payment_page = get_page_by_path('inscripcion-y-pago');
$payment_url = $payment_page ? get_permalink($payment_page) : home_url('/inscripcion-y-pago/');
$can_moderate = function_exists('cqb_factory_user_can_moderate') && cqb_factory_user_can_moderate();

get_header();
?>
<main>
    <section class="hero" id="inicio">
        <div class="wrap hero-grid">
            <div class="reveal">
                <span class="eyebrow">Centro de Operaciones CQB</span>
                <h1>La experiencia CQB mas cerca de ti</h1>
                <p>
                    Somos una comunidad enfocada 100% en partidas CQB. Reserva tu lugar, crea tu cuenta y registra tu progreso
                    para escalar de recluta a operador.
                </p>
                <div class="hero-actions">
                    <a href="#cuenta" class="btn btn-primary">Abrir mi cuenta</a>
                    <a href="#cuenta" class="btn btn-outline">Ya soy operador</a>
                </div>
            </div>

            <aside class="hero-panel reveal" aria-label="Metricas de la comunidad">
                <div class="metric">
                    <strong>+240</strong>
                    <span>Jugadores activos</span>
                </div>
                <div class="metric">
                    <strong>12</strong>
                    <span>Eventos este mes</span>
                </div>
                <div class="metric">
                    <strong>4.9/5</strong>
                    <span>Valoracion media</span>
                </div>
            </aside>
        </div>
    </section>

    <section class="section" id="descripcion">
        <div class="wrap reveal">
            <h2 class="section-title">Combate cercano, estrategia real y ritmo intenso</h2>
            <p class="section-sub">
                En CQB cada decision cuenta. Las partidas se viven en distancias cortas, escenarios cerrados y con reaccion rapida,
                donde la coordinacion del equipo marca la diferencia entre aguantar la posicion o perder el objetivo.
            </p>

            <ul class="bullet-list">
                <li>Actividad fija viernes, sabado y domingo</li>
                <li>Partidas por objetivos: Domination, Team Deathmatch y Bomb Defuse</li>
                <li>Seguridad obligatoria y arbitraje en cada encuentro</li>
            </ul>

            <div class="grid-2">
                <article class="card reveal">
                    <img class="feature-image" src="https://images.unsplash.com/photo-1520105072000-f44fc083e508?auto=format&fit=crop&w=1200&q=80" alt="Operadores en sesion nocturna de airsoft" loading="lazy" />
                    <div class="card-body">
                        <h3>Viernes: Sesion nocturna CQB</h3>
                        <p>Partidas rapidas, focos tacticos y escenario full adrenalina para cerrar la semana en modo operador.</p>
                    </div>
                </article>

                <article class="card reveal">
                    <img class="feature-image" src="https://images.unsplash.com/photo-1547355253-ff0740f6e8c1?auto=format&fit=crop&w=1200&q=80" alt="Jornada diurna de entrenamiento y juego" loading="lazy" />
                    <div class="card-body">
                        <h3>Sabado y Domingo: Jornadas completas para nuevos jugadores y operadores avanzados</h3>
                        <p>Bloques por nivel, coaching inicial y rotacion de modos para que cada escuadra mejore su desempeno.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="section" id="servicios">
        <div class="wrap reveal">
            <h2 class="section-title">Servicios Operativos</h2>
            <p class="section-sub">Una plataforma pensada para reservar, jugar seguro y escalar tu nivel dentro de la comunidad.</p>

            <div class="services-grid">
                <article class="service reveal">
                    <h3>Tipos de partidas</h3>
                    <p>Publicas, por niveles, privadas y eventos especiales con cupos controlados por escuadra.</p>
                </article>

                <article class="service reveal">
                    <h3>Rental disponible</h3>
                    <p>Replica, careta y proteccion para nuevos jugadores que quieran vivir su primera experiencia CQB.</p>
                </article>

                <article class="service reveal">
                    <h3>Arriendo para eventos</h3>
                    <p>Cancha para cumpleanos, actividades de empresa y entrenamientos tacticos personalizados.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section" id="partidas">
        <div class="wrap reveal">
            <h2 class="section-title">Proximas partidas</h2>
            <p class="section-sub">Calendario tactico con disponibilidad en tiempo real y reserva directa de cupos.</p>

            <div class="events-grid">
                <article class="event-card reveal">
                    <h3>Operacion Cerrojo Nocturno</h3>
                    <div class="event-meta">
                        <span>Fecha y hora: Viernes 21:30</span>
                        <span>Precio: CLP 8.000</span>
                        <span>Cupos disponibles: 14</span>
                    </div>
                    <div class="event-row"><span>Equipo Rojo</span><span class="tag tag-red">7 cupos</span></div>
                    <div class="event-row"><span>Equipo Azul</span><span class="tag tag-blue">7 cupos</span></div>
                    <a class="btn btn-primary" href="<?php echo esc_url($payment_url); ?>">Inscribirme</a>
                </article>

                <article class="event-card reveal">
                    <h3>Operacion Punto de Quiebre</h3>
                    <div class="event-meta">
                        <span>Fecha y hora: Sabado 10:00</span>
                        <span>Precio: CLP 10.000</span>
                        <span>Cupos disponibles: 20</span>
                    </div>
                    <div class="event-row"><span>Equipo Rojo</span><span class="tag tag-red">10 cupos</span></div>
                    <div class="event-row"><span>Equipo Azul</span><span class="tag tag-blue">10 cupos</span></div>
                    <a class="btn btn-primary" href="<?php echo esc_url($payment_url); ?>">Inscribirme</a>
                </article>

                <article class="event-card reveal">
                    <h3>Operacion Sombra de Acero</h3>
                    <div class="event-meta">
                        <span>Fecha y hora: Sabado 21:00</span>
                        <span>Precio: CLP 11.000</span>
                        <span>Cupos disponibles: 18</span>
                    </div>
                    <div class="event-row"><span>Equipo Rojo</span><span class="tag tag-red">9 cupos</span></div>
                    <div class="event-row"><span>Equipo Azul</span><span class="tag tag-blue">9 cupos</span></div>
                    <a class="btn btn-primary" href="<?php echo esc_url($payment_url); ?>">Inscribirme</a>
                </article>

                <article class="event-card reveal">
                    <h3>Operacion Amanecer Silente</h3>
                    <div class="event-meta">
                        <span>Fecha y hora: Domingo 10:00</span>
                        <span>Precio: CLP 9.000</span>
                        <span>Cupos disponibles: 16</span>
                    </div>
                    <div class="event-row"><span>Equipo Rojo</span><span class="tag tag-red">8 cupos</span></div>
                    <div class="event-row"><span>Equipo Azul</span><span class="tag tag-blue">8 cupos</span></div>
                    <a class="btn btn-primary" href="<?php echo esc_url($payment_url); ?>">Inscribirme</a>
                </article>

                <article class="event-card reveal">
                    <h3>Operacion Ultimo Bastion</h3>
                    <div class="event-meta">
                        <span>Fecha y hora: Domingo 20:00</span>
                        <span>Precio: CLP 10.000</span>
                        <span>Cupos disponibles: 12</span>
                    </div>
                    <div class="event-row"><span>Equipo Rojo</span><span class="tag tag-red">6 cupos</span></div>
                    <div class="event-row"><span>Equipo Azul</span><span class="tag tag-blue">6 cupos</span></div>
                    <a class="btn btn-primary" href="<?php echo esc_url($payment_url); ?>">Inscribirme</a>
                </article>
            </div>
        </div>
    </section>

    <section class="section" id="cuenta">
        <div class="wrap reveal">
            <h2 class="section-title">Cuenta de jugador</h2>
            <p class="section-sub">Crea tu perfil de operador, administra tus reservas y monitorea tu progreso dentro de la comunidad.</p>

            <div class="account-grid">
                <article class="panel reveal">
                    <div class="tabs" role="tablist" aria-label="Acceso de cuenta">
                        <button class="tab-btn active" role="tab" data-target="tab-crear" aria-selected="true" type="button">Crear cuenta</button>
                        <button class="tab-btn" role="tab" data-target="tab-login" aria-selected="false" type="button">Iniciar sesion</button>
                    </div>

                    <div class="tab-panel active" id="tab-crear" role="tabpanel">
                        <p class="form-note">Registrate para reservar cupos y guardar tu historial de partidas.</p>
                        <form class="field-grid" action="#" method="post">
                            <div>
                                <label class="label" for="nombre-crear">Nombre completo</label>
                                <input id="nombre-crear" name="nombre" type="text" placeholder="Ej: Felipe Soto" required />
                            </div>
                            <div>
                                <label class="label" for="correo-crear">Correo</label>
                                <input id="correo-crear" name="correo" type="email" placeholder="tuemail@dominio.cl" required />
                            </div>
                            <div>
                                <label class="label" for="pass-crear">Contrasena</label>
                                <input id="pass-crear" name="contrasena" type="password" placeholder="Minimo 8 caracteres" required />
                            </div>
                            <button class="btn btn-primary" type="submit">Crear mi cuenta</button>
                        </form>
                    </div>

                    <div class="tab-panel" id="tab-login" role="tabpanel">
                        <p class="form-note">Ingresa con tu cuenta para inscribirte en proximas partidas y revisar tus estadisticas.</p>
                        <form class="field-grid" action="#" method="post">
                            <div>
                                <label class="label" for="nombre-login">Nombre</label>
                                <input id="nombre-login" name="nombre_login" type="text" placeholder="Tu nombre de operador" required />
                            </div>
                            <div>
                                <label class="label" for="correo-login">Correo</label>
                                <input id="correo-login" name="correo_login" type="email" placeholder="tuemail@dominio.cl" required />
                            </div>
                            <div>
                                <label class="label" for="pass-login">Contrasena</label>
                                <input id="pass-login" name="pass_login" type="password" placeholder="Tu contrasena" required />
                            </div>
                            <button class="btn btn-outline" type="submit">Entrar al sistema</button>
                        </form>
                    </div>
                </article>

                <aside class="panel reveal" aria-label="Panel de progreso del jugador">
                    <h3>Tu progreso</h3>
                    <p class="form-note">Datos sincronizados con tus reservas y rendimiento semanal.</p>
                    <div class="stats">
                        <div class="stat"><strong>28</strong><span>Partidas jugadas</span></div>
                        <div class="stat"><strong>3</strong><span>Reservas activas</span></div>
                        <div class="stat"><strong>16</strong><span>Victorias</span></div>
                        <div class="stat"><strong>12</strong><span>Derrotas</span></div>
                        <div class="stat"><strong>1.33</strong><span>Ratio V/D</span></div>
                        <div class="stat"><strong>Recluta</strong><span>Rango actual</span></div>
                    </div>
                </aside>
            </div>

        </div>
    </section>

    <?php if ($can_moderate) : ?>
        <section class="section" id="moderadores">
            <div class="wrap reveal">
                <h2 class="section-title">Panel de moderadores</h2>
                <p class="section-sub">Gestion operativa para partidas, arbitraje y control de incidencias de la jornada.</p>

                <div class="services-grid">
                    <article class="service reveal">
                        <h3>Control de inscripciones</h3>
                        <p>Aprueba listas finales, bloquea cupos sobrevendidos y reasigna jugadores por equilibrio de equipos.</p>
                    </article>

                    <article class="service reveal">
                        <h3>Bitacora de seguridad</h3>
                        <p>Registra incidentes, pausas de partida y observaciones de equipamiento obligatorio en cancha.</p>
                    </article>

                    <article class="service reveal">
                        <h3>Comunicados rapidos</h3>
                        <p>Publica avisos de cambios de horario, condiciones del campo o ajustes de reglas para operadores.</p>
                    </article>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="section" id="contacto">
        <div class="wrap reveal">
            <h2 class="section-title">Contacto y reservas privadas</h2>
            <p class="section-sub">Coordinamos arriendos para grupos, empresas y celebraciones con soporte operativo en terreno.</p>

            <div class="contact-grid">
                <article class="contact-card reveal">
                    <h3>Escribenos</h3>
                    <form class="field-grid" action="#" method="post">
                        <div>
                            <label class="label" for="contacto-nombre">Nombre</label>
                            <input id="contacto-nombre" name="contacto_nombre" type="text" required />
                        </div>
                        <div>
                            <label class="label" for="contacto-correo">Correo</label>
                            <input id="contacto-correo" name="contacto_correo" type="email" required />
                        </div>
                        <div>
                            <label class="label" for="contacto-mensaje">Mensaje</label>
                            <textarea id="contacto-mensaje" name="contacto_mensaje" rows="5" placeholder="Cuentanos cuantas personas son y que tipo de jornada buscan" required></textarea>
                        </div>
                        <button class="btn btn-primary" type="submit">Enviar solicitud</button>
                    </form>
                </article>

                <aside class="contact-card reveal">
                    <h3>Reservas privadas</h3>
                    <p>
                        Disponible para grupos cerrados desde 10 jugadores, activaciones de marca y entrenamientos de equipo con arbitraje.
                    </p>
                    <div class="summary" style="margin-top: 14px;">
                        <div><span>Correo</span><strong>reservas@cqbfactory.cl</strong></div>
                        <div><span>WhatsApp</span><strong>+56 9 7654 3210</strong></div>
                        <div><span>Ubicacion</span><strong>Santiago, Region Metropolitana</strong></div>
                    </div>
                    <div class="cta">Agenda tu proxima partida CQB</div>
                </aside>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
