<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Inspeccion</title>


        <style type="text/css">
            #fotos{
                border: inherit;
                padding: 15px 25px; 
            }
            table,td,th {
                border-bottom: 2px solid #0069d9;
                border-top: 2px solid #0069d9;
                border-left: 0;
                border-right: 0;
            }
        </style>

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
                            <td align="center"><img class="imgFoto" src="<?php echo base_url().$imagen['ruta'] ?>" height="180" /></td>
                            <td><?php echo $imagen['comentario'].'--'.base_url().$imagen['ruta'] ?></td>
                        </tr>
                    <?php endforeach; ?>

                </table>
            </article>
        </section>

    </body>
</html>
