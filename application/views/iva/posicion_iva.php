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
                <div class="panel-heading">POSICION IVA</div>     
                <form class="navbar-form navbar-left" role="search" method="POST" action="<?php echo base_url(); ?>iva/posicion">                                           
                Periodo <input type="text" class="form-control" name="peri" value="<?=$peri?>" id="buscar" placeholder="aaaamm">
                <select name="empresa" id="empresa" class="form-control">
                                    <option value="">Seleccione una empresa</option>
                                <?php foreach ($lista_empresas as $emp) {?>
                                    <option value="<?=$emp->id_empresa?>">
                                        <?=$emp->razon_soc?>
                                    </option>
                                <?php }?>        
                                </select>
                <button type="submit" class="btn btn-primary">Filtrar</button>	
                </form>
                <table class="table">                 
                        <?php 
                        $total=$debito1[0]->iva;
                        //debito1
                        ?>	
                                <tr>
                                    <td></td>
                                    <td></td>                                                                  
                                </tr>
                                <tr>
                                    <td>IVA Ventas</td>
                                    <td align="right"><?=number_format($debito1[0]->iva,2,".",","); ?></td>                                                                  
                                </tr>
                                <?php $total=$total-$credito2[0]->iva; ?>
                                <tr>
                                    <td>IVA Compras</td>                                                                                           
                                    <td align="right"><?=number_format($credito2[0]->iva,2,".",","); ?></td>                                             
                                </tr>
                                <tr>
                                <th>SUBTOTAL</th>                                                        
                                <td align="right"><?=number_format($total,2,".",",")?></td>                                       
                                </tr>

                                <?php $total=$total-$credito1[0]->iva; ?>
                                <tr>
                                    <td>  Retenciones</td>                                                        
                                    <td align="right"><?=number_format($credito1[0]->iva,2,".",","); ?></td>                                                                                                   
                                </tr>
                                <?php $total=$total-$credito2[0]->per_iva; ?>                               
                                <tr>
                                    <td> Percepciones</td>                                                        
                                    <td align="right"><?=number_format($credito2[0]->per_iva,2,".",","); ?></td>                                             
                                </tr>
                                <?php $total=$total-$credito2[0]->itc; ?>                               
                                <tr>
                                    <td>  ITC</td>                                                                                        
                                    <td align="right"><?=number_format($credito2[0]->itc,2,".",","); ?></td>                                                                                 
                                </tr>
                                <tr>
                                <th>TOTAL</th>                                                        
                                <td align="right"><?=number_format($total,2,".",",")?></td>                                       
                                </tr>
                        

                  </tbody>
                </table>
            </div>
        </div>
    </div>
</div>    