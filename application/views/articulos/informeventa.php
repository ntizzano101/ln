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
                <div class="panel-heading">ARTICULOS VENDIDOS</div>     
                <form class="navbar-form navbar-left" role="search" method="POST" action="<?php echo base_url(); ?>articulos/infomeventa">                           
                Desde  <input type="date" class="form-control" name="desde" value="<?=$desde?>" id="buscar" placeholder="dd/mm/yyyy">
                Hasta  <input type="date" class="form-control" name="hasta" value="<?=$hasta?>" id="buscar" placeholder="dd/mm/yyyy">
                <select name="empresa" id="empresa" class="form-control">
                                    <option value="">Seleccione una empresa</option>
                                <?php foreach ($lista_empresas as $emp) {?>
                                    <option value="<?=$emp->id_empresa?>"  
                                        <?php
                                            if($empresa==$emp->id_empresa){echo 'selected="selected"';}
                                        ?>>
                                        <?=$emp->razon_soc?>
                                    </option>
                                <?php }?>        
                                </select>
                <button type="submit" class="btn btn-primary">Filtrar</button>	                
                </form>
                <table class="table">
                  <thead>
                        <tr>
                          <th>Articulo</th>
                          <th>Cantidad</th>
                          <th>Importe</th>                          
                        </tr>
                  </thead>
                  <tbody>
                        <?php 
                        $total=0;
                        foreach($ventas as $cta){                             
                            ?>	
                                <tr>
                                    <td><?=$cta->articulo ?></td>
                                    <td align="right"><?=$cta->cantidad ?></td>                                    
                                    <td align="right"><?=number_format($cta->precio,2,".",",")?></td>
                                </tr>
                        <?php	
                        }                        
                        ?>                        

                  </tbody>
                </table>
            </div>
        </div>
    </div>
</div>    