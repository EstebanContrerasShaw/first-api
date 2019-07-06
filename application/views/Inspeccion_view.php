<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Inspeccion</title>

        <style type="text/css">

            ::selection { background-color: #E13300; color: white; }
            ::-moz-selection { background-color: #E13300; color: white; }

            body {
                background-color: #FFF;
                margin: 30px;
                font-family: sans-serif;
                font-size: 10pt;
                color: #4F5155;
            }

            h1 {
                color: #444;
                background-color: transparent;
                border-bottom: 1px solid #D0D0D0;
                font-size: 24px;
                font-weight: normal;
                margin: 0 0 14px 0;
                padding: 14px 15px 10px 15px;
            }

            table{
                border-collapse: collapse;
                width: 100%
            }
            table, th, td{
                border: 1px solid #0069d9;
            }
            article{
                border: 2px solid #0069d9;
            }
            #cabecera{
                border: inherit;
                padding: 15px;
            }

            #tablaCaberera {
                text-align: center;
                border-collapse: initial;
                border: inherit;
            }


            #datosInspeccion{
                width: 40%;
                text-align: left;
            }

            #tablaCliente td, #tablaInfo td{
                border-color: #ffffff;
            }


            .nombreCat{
                border: none;
                text-align: left;
                padding-left: 35px;
            }

            .nombreCampo{
                border: none;
            }

            .valor{
                width: 6%;
                text-align: center;
            }
            .observacion{
                width: 38%;
            }
            #obsGenerales{
                height: 80px;
            }

            #fotos{
                border: inherit;
            }

            #tablaFotos td{
                width: 50%
            }
            #campos td{
                padding: 1px;

            }
            .importancia{
                background-color: #ed7669;
            }

        </style>

    </head>

    <body>
        <section >
            <article id="cabecera">
                <table id="tablaCaberera">
                    <tr>
                        <!--<td><img src="<?php echo '/anexos/gpswox/logo.png' ?>"  height="80"></td>-->
                        <td><img src="<?php echo '/anexos/'.$empresa.'/logo.png' ?>"  height="80"></td>
                        <td ><label>FORMULARIO INSPECCIÓN</label></td>
                        <td id="datosInspeccion"><b>Fecha:  </b><?php echo date('Y-m-d') ?><br>
                            <b>RUT inspector:  </b><?php echo $mecanico['rut'] . '-' . $mecanico['dv'] ?><br>
                            <b>Nombre Inspector:  </b><?php echo $mecanico['nombres'] . '  ' . $mecanico['apellidos'] ?><br>
                            <b>Codigo interno:  </b><?php echo $inspeccion['numero_de_orden'] ?><br></td>
                    </tr>
                </table>
            </article>
        </section>
        <br>
        <section id="formulario">
            <article id="datoCliente">
                <b>Datos del Cliente</b>
                <table id="tablaCliente">
                    <tr>
                        <td>Nombre Cliente:</td>
                        <td><?php echo $cliente['nombres'] . ' ' . $cliente['apellidos'] ?></td>
                        <td>Rut Cliente:</td>
                        <td><?php echo $cliente['rut'] . '-' . $cliente['dv'] ?></td>
                    </tr>
                    <tr>
                        <td>Email Contacto:</td>
                        <td><?php echo $cliente['email'] ?></td>
                        <td>Telefono Contacto:</td>
                        <td><?php echo $cliente['celular'] ?></td>
                    </tr>
                </table>
            </article>

            <!-- Aqui va un FOR para categorias-->
            <?php foreach ($detalle as $det): ?>
                <article id="detalle">
                    <table id="campos">
                        <tr>
                            <th class="nombreDet"><?php echo $det['orden'] . ' ' . $det['nombre'] ?></th>
                            <th class="valor">Cumple</th>
                            <th class="valor">N/A</th>
                            <th class="valor">NO Cumple</th>
                            <th class="observacion">Observaciones</th>
                        </tr>
                        <!-- Aqui va un FOR anidado para para los campos-->   
                        <?php foreach ($det['campos'] as $valor): ?>
                            <tr>

                                <td class="nombreCampo"><?php echo $valor['orden'] . ' ' . $valor['nombre'] ?></td>

                                <td class="valor"><?php
                                    if ($valor['valor'] == 'Cumple') {
                                        echo 'X';
                                    }
                                    ?></td>
                                <td class="valor"><?php
                                    if ($valor['valor'] == 'No Aplica') {
                                        echo 'X';
                                    }
                                    ?></td>
                                <td class="valor <?php if ($valor['valor']=='No Cumple' && $valor['importancia'] == 1) {
                                echo ' importancia';
                            }
                                    ?>" > <?php
                                        if ($valor['valor'] == 'No Cumple') {
                                            echo 'X';
                                        }
                                        ?></td>
                                <td class="observacion"><?php echo $valor['observacion'] ?></td>
                            </tr>

    <?php endforeach; ?>


                    </table>
                </article>
                <br>
<?php endforeach; ?>
        </section>
        <section>
            <article>
                <label>Observaciones generales de la inspeccion:</label>
                <div id="obsGenerales">
<?php echo $inspeccion['observaciones'] ?>
                </div>
            </article>
        </section>

    </body>
</html>
