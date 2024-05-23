<html>
<style>
table, th, td {
border: 1px solid black;
border-collapse: collapse;
padding: 10px;
}
</style>
<?php
$opago=$pago->opago[0];
$facturas=$pago->opago_facturas;
$pagos=$pago->opago_pagos;
?>
<h2>RECIBO</h2>
<h3><?=$opago->razon_soc?></h3>
<table>    
    <tr>
            <td>RECIBO</td><td><?=$opago->id;?></td>
            <td>Fecha</td><td><?=$opago->fecha;?></td>
            <td>Cliente</td><td><?=$opago->cliente;?>(<?=$opago->cuit;?>)</td>
    </tr>
<table>
<h4>Comprobantes Cancelados</h4>
<table>    
    <tr>
            <td>Fecha</td>
            <td>Letra</td>
            <td>Puerto</td>
            <td>Numero</td>
            <td>Codigo</td>
            <td>Monto Cancelado</td>
    </tr>
<?php foreach($facturas as $f) {?>    
    <tr>
            <td><?=$f->fecha?></td>
            <td><?=$f->letra?></td>
            <td><?=$f->puerto?></td>
            <td><?=$f->numero?></td>
            <td><?=$f->codigo_comp?></td>
            <td><?=$f->monto?></td>
    </tr>
<?php } ?>    
<table>

<h4>Medios de Pago</h4>
<table>    
    <tr>
            <td>Medio</td>
            <td>Comprobante</td>
            <td>Banco</td>
            <td>Cuenta</td>
            <td>Cheq Nro</td>
            <td>Cheq.Vence</td>
            <td>Monto</td>
    </tr>
    <?php foreach($pagos as $f) {?>    
    <tr>
            <td><?=$f->mpago?></td>
            <td><?=$f->nro_comprobante?></td>
            <td><?=$f->banco?></td>
            <td><?=$f->cuenta?></td>
            <td><?=$f->numero?></td>
            <td><?=$f->vence?></td>
            <td><?=$f->monto?></td>
    </tr>
<?php } ?> 
<table>
<h3>TOTAL RECIBO $<?=$opago->total?></h3>

<html>
