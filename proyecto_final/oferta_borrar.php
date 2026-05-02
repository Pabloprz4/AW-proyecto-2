<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$gerente = require_exact_role('gerente');

$ofertaId = get_positive_int('id');
if ($ofertaId === null) {
    flash_set('error', 'Oferta inválida.');
    redirect_to('ofertas.php');
}

$oferta = OfertaRepository::findById($ofertaId);
if (!$oferta) {
    flash_set('error', 'Oferta no encontrada.');
    redirect_to('ofertas.php');
}

if (is_post()) {
    if (!verify_csrf()) {
        flash_set('error', 'Token CSRF inválido.');
    } else {
        OfertaRepository::delete($ofertaId);
        flash_set('ok', 'Oferta borrada.');
        redirect_to('ofertas.php');
    }
}

$contenidoPrincipal = '<h1>Borrar Oferta</h1>';
$contenidoPrincipal .= '<p>¿Estás seguro de que quieres borrar la oferta "' . h($oferta['nombre']) . '"?</p>';
$contenidoPrincipal .= '<form method="post" action="' . h(base_url('oferta_borrar.php?id=' . $ofertaId)) . '">';
$contenidoPrincipal .= csrf_field();
$contenidoPrincipal .= '<button type="submit">Borrar</button>';
$contenidoPrincipal .= ' <a href="' . h(base_url('ofertas.php')) . '">Cancelar</a>';
$contenidoPrincipal .= '</form>';

$params = [
    'tituloPagina' => 'Borrar Oferta',
    'cabecera' => 'Borrar Oferta',
    'contenidoPrincipal' => $contenidoPrincipal,
];

$app->generaVista('/plantillas/plantilla.php', $params);