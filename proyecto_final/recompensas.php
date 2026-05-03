<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuarioActual = require_role('gerente');
$recompensas = RecompensaRepository::all(true);

$filas = '';
foreach ($recompensas as $recompensa) {
    $id = (int) $recompensa['id'];
    $precioFinal = round(
        (float) $recompensa['precio'] * (1 + ((float) $recompensa['iva'] / 100)),
        2
    );
    $estado = (int) $recompensa['activo'] === 1 ? 'Activa' : 'Inactiva';
    $estadoProducto = ((int) $recompensa['ofertado'] === 1 && (int) $recompensa['disponible'] === 1)
        ? 'Disponible'
        : 'No disponible';

    $acciones = '<a href="' . h(base_url('recompensa_form.php?id=' . $id)) . '">Editar</a> ';
    $acciones .=
        '<a href="' . h(base_url('recompensa_borrar.php?id=' . $id)) . '">Borrar</a>';

    $filas .= '<tr>' .
        '<td>' . $id . '</td>' .
        '<td>' . h((string) $recompensa['producto_nombre']) . '</td>' .
        '<td>' . h(money_eur($precioFinal)) . '</td>' .
        '<td>' . (int) $recompensa['bistrocoins'] . '</td>' .
        '<td>' . h($estado) . '</td>' .
        '<td>' . h($estadoProducto) . '</td>' .
        '<td>' . $acciones . '</td>' .
        '</tr>';
}

if ($filas === '') {
    $filas = '<tr><td colspan="7">No hay recompensas registradas.</td></tr>';
}

$contenido = <<<HTML
<section>
  <h2>Gestion de recompensas</h2>
  <p><a class="btn btn-primary" href="{nueva}">Crear nueva recompensa</a></p>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Producto</th>
        <th>Precio producto</th>
        <th>BistroCoins</th>
        <th>Estado</th>
        <th>Estado producto</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      {$filas}
    </tbody>
  </table>
</section>
HTML;

$contenido = str_replace(
    '{nueva}',
    h(base_url('recompensa_form.php')),
    $contenido
);

render_page('Gestion de recompensas', $contenido);
