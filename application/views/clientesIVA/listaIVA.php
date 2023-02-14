<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" /> 
<style>
.container a:hover, a:visited, a:link, a:active{
    text-decoration: none;
}   
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Clientes</div>
                <?php if(isset($mensaje)){?>
                <div class="row">
                    <div class="col-md-12">
                        <?=$mensaje?>
                    </div>
                </div>
                <?php }?>
                <div class="panel-body">
                    <form class="navbar-form navbar-left"  method="POST" action="<?php echo base_url(); ?>clientesIVA">
                        <label for="mes">Mes</label> 
                        <input type="text" name="mes" id="mes" disabled class="form-control" value="<?=$mes?>"/> 
                        <br><br>
                        <label for="anio">Año</label> 
                        <input type="text" name="anio" id="anio"  disabled class="form-control" value="<?=$anio?>"/> 
                        <br><br>
                        <div class="form-inline">
                            <label for="empresa">Empresa</label>
                            <input type="text" name="empresa" id="empresa" disabled class="form-control" value="<?=$empresa->razon_soc?>"/> 
                        </div>
                        <br>
                        
                    
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha
                                <th>Razon Social
                                <th>CUIT
                                <th>Codigo
                                <th>Comprobante
                                <th>Neto 21%
                                <th>IVA 21% 
                                <th>Neto 10,5%
                                <th>IVA.10.5%
                                <th>Exento
                                <th>Total
                            </tr>
                        </thead>
                                
                        <tbody>
                            <?php 
                            $neto21=0.00; $iva21=0.00;
                            $neto105=0.00; $iva105=0.00;
                            $excento=0.00; $total=0.00;
                            
                            foreach ($lista as $lst) { 
                                $neto21+=$lst->neto21; $iva21+=$lst->iva21;
                                $neto105+=$lst->neto105; $iva105+=$lst->iva105;
                                $excento+=$lst->excento; $total+=$lst->total;
                                ?>
                            <tr>
                            <td><?= $lst->fecha ?>
                            <td><?= $lst->razon_soc ?>
                            <td><?= $lst->cuit ?>
                            <td><?= $lst->codigo ?>
                            <td><?= $lst->comprobante ?>
                            <td><?= number_format($lst->neto21, 2, ",", "") ?>
                            <td><?= number_format($lst->iva21, 2, ",", "") ?>
                            <td><?= number_format($lst->neto105, 2, ",", "") ?>
                            <td><?= number_format($lst->iva105, 2, ",", "") ?>
                            <td><?= number_format($lst->excento, 2, ",", "") ?>
                            <td><?= number_format($lst->total, 2, ",", "") ?>
                            </tr>  
                            <?php } ?>
                            
                            <tr>
                                <td colspan="5" align="right"><b>Total</b>
                                <td><b><?= number_format($neto21, 2, ",", "") ?></b>
                                <td><b><?= number_format($iva21, 2, ",", "") ?></b>
                                <td><b><?= number_format($neto105, 2, ",", "") ?></b>
                                <td><b><?= number_format($iva105, 2, ",", "") ?></b>
                                <td><b><?= number_format($excento, 2, ",", "")  ?></b>
                                <td><b><?= number_format($total, 2, ",", "") ?></b>
                        </tbody>
                    </table>
                    <br>
                    <br>
                    
                        <input type="hidden" name="bsq_mes"  class="form-control" value="<?=$bsq_mes?>"/> <br>
                        <input type="hidden" name="bsq_anio"  class="form-control" value="<?=$bsq_anio?>"/> <br>
                        <input type="hidden" name="bsq_emp"  class="form-control" value="<?=$bsq_emp?>"/> <br>
                        <button type="submit" class="btn btn-primary">Volver</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
       
    
</div>

<script>
    
$(document).ready(function(){
       
});

    
</script>