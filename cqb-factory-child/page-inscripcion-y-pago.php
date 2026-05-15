<?php
/**
 * Template Name: Inscripcion y Pago
 * Description: Pagina independiente para flujo de pago de partidas CQB.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main>
    <section class="section" id="inscripcion-pago">
        <div class="wrap reveal">
            <span class="eyebrow">Centro de Operaciones CQB</span>
            <h1 class="section-title" style="font-size: clamp(2.4rem, 6vw, 4rem);">Inscripcion y Pago</h1>
            <p class="section-sub">
                Esta pantalla te permite confirmar la partida, seleccionar equipo y cerrar el pago externo para asegurar tu cupo.
            </p>

            <div class="payment-box reveal" style="margin-top: 26px;">
                <div class="payment-steps">
                    <article class="step">
                        <h4>Paso 1: Confirma tu inscripcion</h4>
                        <div class="summary">
                            <div><span>Partida</span><strong>Domination: Sector Norte</strong></div>
                            <div><span>Fecha</span><strong>Sabado 11:30</strong></div>
                            <div><span>Equipo</span><strong>Rojo</strong></div>
                            <div><span>Total a pagar</span><strong>CLP 10.000</strong></div>
                        </div>
                    </article>

                    <article class="step">
                        <h4>Paso 2: Paga con proveedor externo</h4>
                        <p class="form-note">Al volver de la pasarela de pago, confirma tu operacion para validar tu cupo.</p>
                        <div class="hero-actions" style="margin-top: 16px;">
                            <button class="btn btn-primary" type="button">Iniciar pago externo</button>
                            <button class="btn btn-outline" type="button">Ya pague, confirmar inscripcion</button>
                        </div>
                    </article>
                </div>
            </div>

            <div class="panel reveal" style="margin-top: 18px;">
                <h3>Estado de tu reserva</h3>
                <p class="form-note">Si tu pago aun no aparece, espera 1 a 3 minutos y vuelve a intentar confirmar.</p>
                <div class="summary">
                    <div><span>Estado</span><strong>Pendiente de confirmacion</strong></div>
                    <div><span>Codigo de reserva</span><strong>CQB-2026-0418</strong></div>
                    <div><span>Soporte</span><strong>soporte@cqbfactory.cl</strong></div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
