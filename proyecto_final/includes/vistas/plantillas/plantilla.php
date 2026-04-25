<?php
$params['app']->doInclude('/vistas/helpers/plantilla.php');
$mensajes = mensajesPeticionAnterior();
$tituloPagina = htmlspecialchars((string) ($params['tituloPagina'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$rutaCss = htmlspecialchars($params['app']->resuelve('/css/estilo.css'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $tituloPagina ?></title>
	<link rel="stylesheet" type="text/css" href="<?= $rutaCss ?>">
</head>
<body>
<?= $mensajes ?>
<div id="contenedor">
<?php
$params['app']->doInclude('/vistas/comun/cabecera.php', $params);
$params['app']->doInclude('/vistas/comun/sidebarIzq.php', $params);
?>
	<main>
		<article>
			<?= $params['contenidoPrincipal'] ?>
		</article>
	</main>
<?php
$params['app']->doInclude('/vistas/comun/sidebarDer.php', $params);
$params['app']->doInclude('/vistas/comun/pie.php', $params);
?>
</div>
</body>
</html>
