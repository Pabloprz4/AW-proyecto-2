<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$gerente = require_exact_role('gerente');
$ofertas = OfertaRepository::all(true);

$contenidoPrincipal = '<h1>Gestión de Ofertas</h1>';
$contenidoPrincipal .= '<p><a href="' . h(base_url('oferta_form.php')) . '">Crear nueva oferta</a></p>';

if (empty($ofertas)) {
    $contenidoPrincipal .= '<p>No hay ofertas.</p>';
} else {
    $contenidoPrincipal .= '<table class="table ofertas-table">';
    $contenidoPrincipal .= '<thead><tr><th>Nombre</th><th>Descripción</th><th>Descuento</th><th>Fechas</th><th>Acciones</th></tr></thead>';
    $contenidoPrincipal .= '<tbody>';
    foreach ($ofertas as $oferta) {
        $id = (int) $oferta['id'];
        $nombre = h($oferta['nombre']);
        $descripcion = h(substr($oferta['descripcion'], 0, 50));
        $descuento = h($oferta['descuento'] . '%');
        $fechas = h($oferta['fecha_inicio'] . ' - ' . $oferta['fecha_fin']);
        $activo = (int) $oferta['activo'] ? 'Activa' : 'Inactiva';

        $acciones = '<a href="' . h(base_url('oferta_form.php?id=' . $id)) . '">Editar</a> | ';
        $acciones .= '<a href="' . h(base_url('oferta_borrar.php?id=' . $id)) . '">Borrar</a>';

        $contenidoPrincipal .= "<tr><td>$nombre</td><td>$descripcion</td><td>$descuento</td><td>$fechas ($activo)</td><td>$acciones</td></tr>";
    }
    $contenidoPrincipal .= '</tbody></table>';
}

$params = [
    'tituloPagina' => 'Ofertas',
    'cabecera' => 'Gestión de Ofertas',
    'contenidoPrincipal' => $contenidoPrincipal,
];

$app->generaVista('/plantillas/plantilla.php', $params);
