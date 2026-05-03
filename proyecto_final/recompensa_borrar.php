<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$gestor = require_role('gerente');

$recompensaId = get_positive_int('id');
if ($recompensaId === null) {
    flash_set('error', 'Recompensa invalida.');
    redirect_to('recompensas.php');
}

$recompensa = RecompensaRepository::findById($recompensaId);
if (!$recompensa) {
    flash_set('error', 'Recompensa no encontrada.');
    redirect_to('recompensas.php');
}

if (is_post()) {
    if (!verify_csrf()) {
        flash_set('error', 'Token CSRF invalido.');
    } else {
        $ok = RecompensaRepository::delete($recompensaId);
        if ($ok) {
            flash_set('ok', 'Recompensa borrada correctamente.');
        } else {
            flash_set('error', 'No se pudo borrar la recompensa.');
        }
        redirect_to('recompensas.php');
    }
}

$contenido = <<<HTML
<section>
  <h2>Borrar recompensa</h2>
  <p>Vas a borrar la recompensa del producto <strong>{producto}</strong> ({coins} BistroCoins).</p>
  <form method="post" action="{action}">
    {csrf}
    <button class="btn btn-danger" type="submit">Confirmar borrado</button>
    <a class="btn" href="{volver}">Cancelar</a>
  </form>
</section>
HTML;

$contenido = str_replace(
    ['{producto}', '{coins}', '{action}', '{csrf}', '{volver}'],
    [
        h((string) $recompensa['producto_nombre']),
        (string) (int) $recompensa['bistrocoins'],
        h(base_url('recompensa_borrar.php?id=' . $recompensaId)),
        csrf_field(),
        h(base_url('recompensas.php')),
    ],
    $contenido
);

render_page('Borrar recompensa', $contenido);
