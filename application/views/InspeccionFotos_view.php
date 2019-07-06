<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Inspeccion</title>
    </head>

    <body>
        <br>
        <section>
            <article id="fotos">
                <b>Fotos de la inspección:</b>
                <br><br><br>
                <table id="tablaFotos">
                    <tr>
                        <th>Fotografía</th>
                        <th>Observación</th>
                    </tr>
                    <?php 
                    
                    foreach ($fotos as $imagen): ?>
                        <tr>
                            <td><img src="<?php echo base_url().$imagen['ruta'] ?>" height="180" /></td>
                            <td><?php echo $imagen['comentario'].'--'.base_url().$imagen['ruta'] ?></td>
                        </tr>
                    <?php endforeach; ?>

                </table>
            </article>
        </section>

    </body>
</html>
