<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$cocinero = require_role('cocinero');
$pedidoId = get_positive_int('id');

if ($pedidoId === null) {
    flash_set('error', 'Pedido inválido.');
    redirect_to('cocina.php');
}

$pedido = PedidoRepository::findById($pedidoId);
if (!$pedido) {
    flash_set('error', 'Pedido no encontrado.');
    redirect_to('cocina.php');
}

$estado = (string) $pedido['estado'];
$permitidos = ['en_preparacion', 'cocinando', 'listo_cocina'];
if (!in_array($estado, $permitidos, true)) {
    flash_set('error', 'Este pedido ya no pertenece al flujo de cocina.');
    redirect_to('cocina.php');
}

$lineas = PedidoRepository::lineasByPedido((int) $pedido['id']);
$pedidoCocineroId = (int) ($pedido['cocinero_id'] ?? 0);
$esCocineroAsignado = $pedidoCocineroId > 0 && $pedidoCocineroId === (int) $cocinero['id'];
$puedePreparar = $estado === 'cocinando' && $esCocineroAsignado;

$lineasHtml = '';
$totalLineas = 0;
$lineasPreparadas = 0;

foreach ($lineas as $linea) {
    $totalLineas++;
    $preparado = (int) ($linea['preparado'] ?? 0) === 1;
    if ($preparado) {
        $lineasPreparadas++;
    }

    $accionLinea = '-';
    if ($puedePreparar && !$preparado) {
        $accionLinea =
            '<form method="post" action="' . h(base_url('cocina_linea_preparar.php')) . '" class="inline">' .
            csrf_field() .
            '<input type="hidden" name="pedido_id" value="' . (int) $pedido['id'] . '">' .
            '<input type="hidden" name="linea_id" value="' . (int) $linea['id'] . '">' .
            '<button class="btn btn-primary btn-lg" type="submit">Marcar preparada</button>' .
            '</form>';
    } elseif ($preparado) {
        $accionLinea = '<span class="badge badge-accion-ok">Hecha</span>';
    } else {
        $accionLinea = '<span class="badge badge-accion-bloqueada">Bloqueada</span>';
    }

    $estadoLinea = $preparado
        ? '<span class="badge badge-linea-preparada">Preparada</span>'
        : '<span class="badge badge-linea-pendiente">Pendiente</span>';

    $lineasHtml .= '<article class="card cocina-linea-card">' .
        '<div class="cocina-linea-header">' .
        '<h4>' . h((string) $linea['producto_nombre']) . '</h4>' .
        $estadoLinea .
        '</div>' .
        '<dl class="cocina-meta cocina-linea-meta">' .
        '<div class="cocina-meta-item"><dt>Cantidad</dt><dd>' . (int) $linea['cantidad'] . '</dd></div>' .
        '<div class="cocina-meta-item"><dt>Precio unidad</dt><dd>' . h(money_eur((float) $linea['precio_final_unitario'])) . '</dd></div>' .
        '<div class="cocina-meta-item cocina-total"><dt>Subtotal</dt><dd>' . h(money_eur((float) $linea['subtotal'])) . '</dd></div>' .
        '</dl>' .
        '<div class="cocina-card-actions cocina-card-actions-lg">' . $accionLinea . '</div>' .
        '</article>';
}

if ($lineasHtml === '') {
    $lineasHtml = '<div class="alert cocina-empty">No hay líneas en este pedido.</div>';
}

$numeroVisible = (int) $pedido['numero_dia'] . '/' . (string) $pedido['fecha_dia'];
$estadoLabel = PedidoRepository::estadoLabel($estado);
$tipoLabel = PedidoRepository::tipoLabel((string) $pedido['tipo']);
$progresoPorcentaje = $totalLineas > 0
    ? (int) round(($lineasPreparadas / $totalLineas) * 100)
    : 0;
$resumenAsignacion = '<span class="badge badge-accion-pendiente">Sin asignar</span>';
$cocineroNombre = (string) $cocinero['nombre_usuario'];
$cocineroAvatar = (string) ($cocinero['avatar'] ?? '');
$cocineroEtiqueta = 'Cocinero disponible';
if ($pedidoCocineroId > 0) {
    $cocineroNombre = trim((string) ($pedido['cocinero_usuario'] ?? ''));
    if ($cocineroNombre === '') {
        $cocineroNombre = 'Cocinero asignado';
    }
    $cocineroAvatar = (string) ($pedido['cocinero_avatar'] ?? '');
    $cocineroEtiqueta = 'Cocinero asignado';
    $resumenAsignacion = $esCocineroAsignado
        ? '<span class="badge badge-accion-ok">Tuyo</span>'
        : '<span class="badge badge-accion-bloqueada">Otro cocinero</span>';
}

$avatarHtml = '<img class="avatar-cocina avatar-cocina-lg" src="' . h(avatar_web_url($cocineroAvatar !== '' ? $cocineroAvatar : null)) . '" alt="Avatar cocinero" width="96" height="96">';
$estadoBadge = '<span class="badge badge-estado-' . h($estado) . '">' . h($estadoLabel) . '</span>';

$accionesPedido = '';
if ($estado === 'en_preparacion' && $pedidoCocineroId === 0) {
    $accionesPedido =
        '<form method="post" action="' . h(base_url('cocina_tomar.php')) . '" class="inline">' .
        csrf_field() .
        '<input type="hidden" name="id" value="' . (int) $pedido['id'] . '">' .
        '<button class="btn btn-primary btn-lg" type="submit">Tomar pedido</button>' .
        '</form>';
}
elseif ($puedePreparar && $totalLineas > 0 && $lineasPreparadas === $totalLineas) {
    $accionesPedido =
        '<form method="post" action="' . h(base_url('cocina_finalizar.php')) . '" class="inline">' .
        csrf_field() .
        '<input type="hidden" name="pedido_id" value="' . (int) $pedido['id'] . '">' .
        '<button class="btn btn-primary btn-lg" type="submit">Finalizar cocina -> Listo cocina</button>' .
        '</form>';
}
elseif ($puedePreparar && $totalLineas === 0) {
    $accionesPedido = '<span class="badge badge-accion-pendiente">Sin líneas</span>';
}
elseif ($puedePreparar) {
    $accionesPedido = '<span class="badge badge-accion-pendiente">Faltan líneas</span>';
}
elseif ($estado === 'cocinando') {
    $accionesPedido = '<span class="badge badge-accion-bloqueada">Otro cocinero</span>';
}
elseif ($estado === 'listo_cocina') {
    $accionesPedido = '<span class="badge badge-accion-ok">Listo camarero</span>';
}

$contenido = <<<HTML
<section class="cocina-panel">
  <div class="card cocina-detalle-hero cocina-card-estado-{estado_codigo}">
    <div class="cocina-detalle-main">
      <div class="cocina-card-header">
        <div>
          <span class="cocina-card-label">Pedido de cocina</span>
          <h2 class="cocina-detalle-title">#{id}</h2>
          <span class="cocina-pedido-numero">Número día {numero_visible}</span>
        </div>
        {estado_badge}
      </div>
      <dl class="cocina-meta cocina-detalle-meta">
        <div class="cocina-meta-item"><dt>Cliente</dt><dd>{cliente}</dd></div>
        <div class="cocina-meta-item"><dt>Tipo</dt><dd>{tipo}</dd></div>
        <div class="cocina-meta-item cocina-total"><dt>Total</dt><dd>{total}</dd></div>
      </dl>
    </div>
    <aside class="cocina-cocinero-card">
      {avatar_cocinero}
      <span class="cocina-card-label">{cocinero_etiqueta}</span>
      <strong>{cocinero_nombre}</strong>
      <span class="cocina-estado-note">{asignacion}</span>
    </aside>
  </div>
</section>

<section class="card cocina-progreso-card">
  <div class="cocina-progreso-header">
    <div>
      <span class="cocina-card-label">Progreso de líneas</span>
      <strong>{lineas_preparadas}/{lineas_totales} preparadas</strong>
    </div>
    <span class="cocina-progreso-numero">{progreso_porcentaje}%</span>
  </div>
  <progress class="cocina-progress" max="100" value="{progreso_porcentaje}">{progreso_porcentaje}%</progress>
  <div class="cocina-card-actions cocina-card-actions-lg">{acciones_pedido}</div>
</section>

<section class="cocina-panel">
  <h3>Líneas del pedido</h3>
  <div class="grid cocina-lineas-grid">{$lineasHtml}</div>
  <p><a class="btn" href="{volver}">Volver al panel de cocina</a></p>
</section>
HTML;

$contenido = str_replace(
    ['{id}', '{numero_visible}', '{estado_codigo}', '{estado_badge}', '{tipo}', '{cliente}', '{total}', '{avatar_cocinero}', '{cocinero_etiqueta}', '{cocinero_nombre}', '{asignacion}', '{lineas_preparadas}', '{lineas_totales}', '{progreso_porcentaje}', '{acciones_pedido}', '{volver}'],
    [
        (string) (int) $pedido['id'],
        h($numeroVisible),
        h($estado),
        $estadoBadge,
        h($tipoLabel),
        h((string) $pedido['cliente_usuario']),
        h(money_eur((float) $pedido['total'])),
        $avatarHtml,
        h($cocineroEtiqueta),
        h($cocineroNombre),
        $resumenAsignacion,
        (string) $lineasPreparadas,
        (string) $totalLineas,
        (string) $progresoPorcentaje,
        $accionesPedido,
        h(base_url('cocina.php')),
    ],
    $contenido
);

render_page('Detalle de cocina', $contenido);
