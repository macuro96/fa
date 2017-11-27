<?php
require_once 'php/F_Session.php';
SessionCrear();

require_once 'php/F_General.php';
require_once 'php/F_DB.php';

$accion      = trim(filter_input(INPUT_GET, 'accion'));
$idModificar = trim(filter_input(INPUT_GET, 'id'));    

$errores     = array();
$titulo      = '';
$anyo        = '';
$sipnosis    = '';
$duracion    = '';
$genero      = '';

$titulo   = trim(filter_input(INPUT_POST, 'titulo'));
$anyo     = trim(filter_input(INPUT_POST, 'anyo'));
$sipnosis = trim(filter_input(INPUT_POST, 'sipnosis'));
$duracion = trim(filter_input(INPUT_POST, 'duracion'));
$genero   = trim(filter_input(INPUT_POST, 'genero'));

try {
    if ($accion !== 'Modificar' && $accion !== 'Insertar'){
        throw new Exception('Acción no válida');
    }

    if ($accion == 'Modificar'){
        $aResultadoSQLPeliculas = DBbuscarPeliculaId($idModificar);
        $stmPeliculas           = $aResultadoSQLPeliculas['salida'];

        $rowPelicula = $stmPeliculas->fetchObject();            

        if (!$rowPelicula){
            throw new Exception('La película que intenta modificar no existe');
        }

        $titulo   = $rowPelicula->titulo;
        $anyo     = $rowPelicula->anyo;
        $sipnosis = $rowPelicula->sipnosis;
        $duracion = $rowPelicula->duracion;
        $genero   = $rowPelicula->genero;

        $sConsultaId = '&id='.$idModificar;

    } // if ($accion == 'Modificar')

    if (!empty($_POST)){
        validar([
            'tituloPelicula'   => $titulo,
            'anyoPelicula'     => $anyo,
            'duracionPelicula' => $duracion,
            'generoPelicula'   => $genero
        ], $errores);

    } // if (!empty($_POST))

} catch (Exception $e){
    $mensaje = $e->getMessage();
    var_dump($mensaje);

    if ($mensaje != null){
        SessionMensajeModificar($e->getMessage());
        header('Location: index.php');   

    } // if ($mensaje != null)

} // catch (Exception $e)

try {
    $aResultadoSQLGeneros = DBbuscarGeneroNombre('');
    $stmGeneros           = $aResultadoSQLGeneros['salida'];

} catch (Exception $e){
    SessionMensajeModificar($e->getMessage());
    header('Location: index.php');   

} // catch (Exception $e)

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Acción película</title>

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">

    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-offset-1 col-lg-4 page-header">
                    <h1><?= h($accion) . ' película' ?></h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-1 col-lg-8">
                    <form action="accion-pelicula.php?accion=<?= h($accion) ?><?= isset($sConsultaId) ? h($sConsultaId) : '' ?>" method="get">
                        <div class="form-group">
                            <label for="titulo">Titulo:</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" value="<?= h($titulo) ?>">
                        </div>
                        <div class="form-group">
                            <label for="anyo">Año:</label>
                            <input type="text" class="form-control" id="anyo" name="anyo" value="<?= h($anyo) ?>">
                        </div>
                        <div class="form-group">
                            <label for="sipnosis">Sipnosis:</label>
                            <textarea class="form-control" rows="5" id="sipnosis" name="sipnosis"><?= h($sipnosis) ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="duracion">Duracion:</label>
                            <input type="text" class="form-control" id="duracion" name="duracion" value="<?= h($duracion) ?>">
                        </div>
                        <div class="form-group">
                            <label for="genero">Género:</label>
                            <select class="form-control" id="genero" name="genero">
                                <option value="<?= h($genero) ?>"><?= h($genero) ?></option>                            
                                <?php
                                while ($rowGenero = $stmGeneros->fetchObject()):
                                    $nombreGenero = $rowGenero->nombre;

                                    if ($nombreGenero !== $genero):?>
                                        <option value="<?= h($nombreGenero) ?>"><?= h($nombreGenero) ?></option>
                                    <?php endif;

                                endwhile;
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default"><?= h($accion) ?></button>
                    </form>                    

                </div>

            </div>

            <?php
            if (!empty($errores)):?>
                <div class="row">
                    <div class="col-lg-offset-1 col-lg-3">
                        <?php foreach ($errores as $error):?>
                            <h3><?= $error ?></h3>                        
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        
        </div>

        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    
    </body>

</html>