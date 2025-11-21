<?php
// ... (Tu código PHP de lógica permanece igual) ...
include_once("../configuracion.php");
include_once("../Control/ABMProducto.php");

$abmProducto = new ABMProducto();

// Validar ID recibido
if (!isset($_GET['id'])) {
 echo "ID de auto no especificado.";
  exit;
}

$id = intval($_GET['id']);

// Asumo que tu método buscar retorna un array, y si tiene éxito, $auto[0] es el objeto Producto
$autoArr = $abmProducto->buscar(['idproducto' => $id]); 

if (!$autoArr) {
 echo "Auto no encontrado.";
  exit;
}

$auto = $autoArr[0]; // Objeto Producto
$rutaImg = "../imagenes/autos/" . $id . ".jpg";
?>

<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <title><?php echo $auto->getProNombre(); ?></title>
  <link rel="stylesheet" href="../../css/semantic.min.css">
 <style>
  /* Estilos personalizados para ajustar el diseño al resto de tu web */
  body {
   background-color: #f7f9fa; /* Color de fondo muy claro, similar al de tu catálogo */
  }
  .main-segment {
   padding: 30px;
   margin-top: 40px;
   box-shadow: 0 4px 6px rgba(0,0,0,0.1); /* Sombra sutil para destacar */
  }
        .detail-header {
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
 </style>
</head>

<body>
<?php include("estructura/header.php"); ?>
<div class="ui container">
    <div class="ui stacked segment main-segment">
        <h1 class="ui huge detail-header"><?php echo $auto->getProNombre(); ?></h1>

        <div class="ui two column stackable grid">
            <div class="ten wide column">
                <?php if (file_exists($rutaImg)) { ?>
                    <img class="ui fluid rounded image" src="<?php echo $rutaImg; ?>" alt="<?php echo $auto->getProNombre(); ?>">
                <?php } else { ?>
                    <div class="ui card">
                        <div class="image">
                            <div class="ui placeholder">
                                <div class="square image"></div>
                            </div>
                        </div>
                        <div class="content"><p>Imagen no disponible</p></div>
                    </div>
                <?php } ?>
            </div>

            <div class="six wide column">
                <h3 class="ui dividing header">Detalles del Vehículo</h3>
                
                <div class="ui basic segment">
                    <h4 class="ui header">Descripción</h4>
                    <p><?php echo nl2br(htmlspecialchars($auto->getProDetalle())); ?></p>
                </div>

                <div class="ui basic segment">
                    <h4 class="ui header">
                        <i class="warehouse icon"></i>
                        Stock disponible:
                    </h4> 
                    <p class="ui large <?php echo ($auto->getProCantStock() > 0 ? 'green' : 'red'); ?> text">
                        <strong><?php echo $auto->getProCantStock(); ?> unidades</strong>
                    </p>
                </div>
                <div class="ui basic segment">
                    <h4 class="ui header">
                        <i class="warehouse icon"></i>
                        Valor:
                    </h4> 
                    <p class="ui large <?php echo ($auto->getProCantStock() > 0 ? 'green' : 'red'); ?> text">
                        <strong>$<?php echo 10000 ; ?> El dia</strong>
                    </p>
                </div>
                
                <div class="ui section divider"></div>

                <div class="ui action buttons">
                    <?php if ($auto->getProCantStock() > 0): ?>
                        <a href="../control/agregarCarrito.php?id=<?php echo $id; ?>" class="ui big positive button">
                            <i class="cart plus icon"></i>
                            Alquilar Ahora
                        </a>
                    <?php else: ?>
                        <button class="ui big disabled red button">
                            Agotado
                        </button>
                    <?php endif; ?>

                    <a href="../vista/index.php" class="ui basic button">
                         Volver al Catálogo
                    </a>
                </div>
            </div>
        </div>
    </div>
    
</div>

</body>
</html>