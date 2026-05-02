<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/clases/FormularioOferta.php';

use es\ucm\fdi\aw\FormularioOferta;

$gerente = require_exact_role('gerente');

$ofertaId = get_positive_int('id');
$oferta = null;
if ($ofertaId !== null) {
    $oferta = OfertaRepository::findByIdWithProducts($ofertaId);
    if (!$oferta) {
        flash_set('error', 'Oferta no encontrada.');
        redirect_to('ofertas.php');
    }
}

$form = new FormularioOferta($oferta);
$contenidoPrincipal = $form->gestiona();

$params = [
    'tituloPagina' => $oferta ? 'Editar Oferta' : 'Crear Oferta',
    'cabecera' => $oferta ? 'Editar Oferta' : 'Crear Oferta',
    'contenidoPrincipal' => $contenidoPrincipal,
];

$app->generaVista('/plantillas/plantilla.php', $params);