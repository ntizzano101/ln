<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" /> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>


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
                    <form class="navbar-form navbar-left"  method="POST" action="<?php echo base_url(); ?>clientesIVA/listado">
                        <label for="bsq_mes">Mes</label> 
                        <select name="bsq_mes" id="bsq_mes" class="form-control">
                                <?php foreach ($lista_mes as $mes) {
                                    ?>
                                <option value="<?=$mes["nro"]?>" 
                                    <?php if ($mes["nro"]==$bsq_mes){echo "selected";}?> >
                                    <?=$mes["nmb"]?>
                                </option>
                            <?php }?>  
                        </select>
                        <br><br>
                        <label for="bsq_anio">Año</label> 
                        <input type="text" name="bsq_anio" id="bsq_anio" class="form-control" value="<?=$bsq_anio ?>"/> 
                        <br><br>
                        <div class="form-inline">
                            <label for="bsq_emp">Empresa</label>
                            <select name="bsq_emp" id="bsq_emp" class="form-control">
                                <?php foreach ($lista_empresa as $empresa) {?>
                                <option value="<?=$empresa->id_empresa?>" 
                                    <?php if ($empresa->id_empresa==$bsq_emp){echo "selected";}?> >
                                    <?=$empresa->razon_soc?>
                                </option>
                            <?php }?>  
                            </select>
                        </div>
                        <br><br>
                        <button type="submit" class="btn btn-default">Buscar</button>
                    </form>	
                </div>
            </div>
        </div>
    </div>
    
       
    
</div>

<script>
    
$(document).ready(function(){
    
    $('#bsq_anio').mask('9999');
    $('#bsq_anio').keyup(function(){ $('#bsq_anio').mask('9999');});   
});

    
</script>