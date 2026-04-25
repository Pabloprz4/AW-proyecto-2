<?php

use es\ucm\fdi\aw\Aplicacion;

function mensajesPeticionAnterior()
{
    $app = Aplicacion::getInstance();
    $mensajes = $app->getAtributoPeticion('mensajes') ?: [];
    $mensajesNormalizados = [];

    if (!is_array($mensajes)) {
        $mensajes = [$mensajes];
    }

    foreach ($mensajes as $mensaje) {
        $mensajesNormalizados[] = [
            'tipo' => 'info',
            'texto' => (string) $mensaje,
        ];
    }

    if (function_exists('flash_get')) {
        foreach (['ok', 'error'] as $tipo) {
            $mensaje = flash_get($tipo);
            if ($mensaje !== null && $mensaje !== '') {
                $mensajesNormalizados[] = [
                    'tipo' => $tipo,
                    'texto' => $mensaje,
                ];
            }
        }
    }

    $html = '';
    if ($mensajesNormalizados) {
        $html = '<div class="mensajes">';
        $contador = 0;
        foreach ($mensajesNormalizados as $mensaje) {
            $contador++;
            $idMensaje = "mensaje{$contador}";
            $tipo = htmlspecialchars((string) $mensaje['tipo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $texto = htmlspecialchars((string) $mensaje['texto'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $rol = $tipo === 'error' ? 'alert' : 'status';
            $html .= <<<EOS
            <input id="$idMensaje" type="checkbox">
            <div class="mensaje mensaje-$tipo" role="$rol">
                <div class="cabecera"><label for="$idMensaje">×</label></div>
                <div class="contenido">$texto</div>
            </div>
            EOS;
        }
        $html .= '</div>';
    }

    return $html;
}
