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
                <div class="panel-heading">MAYOR CLIENTES</div>     
                <form class="navbar-form navbar-left" role="search" method="POST" action="<?php echo base_url(); ?>clientes/mayor">                           
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
                          <th>Cliente</th>
                          <th>Cuit</th>
                          <th>Debe</th>
                          <th>Haber</th>
                          <th>Saldo</th>                          
                        </tr>
                  </thead>
                  <tbody>
                        <?php 
                        $total=0;
                        foreach($mayor as $cta){                             
                            $total+=$cta->debe - $cta->haber;
                            $cta->saldo=$cta->debe - $cta->haber;
                            ?>	
                                <tr>
                                    <td><?=$cta->cliente ?></td>
                                    <td><?=$cta->cuit ?></td>                                    
                                    <td align="right"><?=number_format($cta->debe,2,".",",")?></td>
                                    <td align="right"><?=number_format($cta->haber,2,".",",")?></td>
                                    <td align="right"><?=number_format($cta->saldo,2,".",",")?></td>
                                </tr>
                        <?php	
                        }                        
                        ?>                        
                        <tr>
                            <td colspan="4"></td>                            
                            <td  align="right"><?=number_format($total,2,".",",")?></td>

                        </tr>

                  </tbody>
                </table>
            </div>
        </div>
    </div>
</div>    