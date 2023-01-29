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
                <div class="panel-heading">Ventas , Comprobantes </div>
                <?php if(isset($mensaje)){?>
                <div class="row">
                    <div class="col-md-12">
                        <?=$mensaje?>
                    </div>
                </div>
                <?php }?>
                <div class="panel-body">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>ventas/ingresar">Nueva Venta</a>
                    <br>
                    <form class="navbar-form navbar-left" role="search" method="POST" action="<?php echo base_url(); ?>ventas/buscar">
                    <input type="text" class="form-control" name="buscar" placeholder="Buscar..">
                    <button type="submit" class="btn btn-default">Buscar</button>								
                    </form>	
                </div>
                
                <table class="table">
                  <thead>
                        <tr>
                          <th>Id</th>
                          <th>Empresa</th>
                          <th>Cliente</th>
                          <th>Fecha</th>
                          <th>Comprobante</th>
                          <th>Total</th>
                          <th>Acciones</th>
                        </tr>
                  </thead>
                  <tbody>
                        <?php 
                        foreach($facturas as $fact){ 
                            $color=' class= "text-info" '   ;
                            $mult=1;
                            if(strpos($fact->nombre,"NC")>0){
                                $color=' class = "text-danger" ';
                                $mult=-1;
                                }
                                ?>	
                                <tr <?php echo $color ?> >
                                    <td><?=$fact->id ?></td>
                                    <td><?=$fact->datos ?></td>
                                    <td><?=$fact->cliente ?></td>
                                    <td><?=$fact->fecha ?></td>
                                    <td><?php echo $fact->nombre . " " .  str_pad($fact->puerto,5,"0",STR_PAD_LEFT)."-".  str_pad($fact->numero,8,"0",STR_PAD_LEFT)  ?></td>
                                    <td align="right"><?php printf("$ %0.2f", $fact->total * $mult) ?></td>
                                    <td>
                                        <a class="btn-default fa fa-eye" title="Ver Comprobante" 
                                            href="<?php echo base_url(); ?>ventas/comprobante/<?=$fact->id?>" target="blank_">
                                        </a>
                                        
                                        &nbsp; &nbsp;
                                        <?php if($fact->id_tipo_comp=='1' or $fact->id_tipo_comp=='2'){ 
                                            if($fact->id_comp_asoc==0) { ?>
                                            <a class="btn-default fa fa-money" title="Nota De Credito"
                                            onclick="verNC(<?=$fact->id?>, '<?=$fact->cliente?>')" >                              
                                            </a>
                                            <?php }else{ ?>
                                                <a class="btn-default fa fa-eye" title="Ver NC" 
                                            href="<?php echo base_url(); ?>ventas/comprobante/<?=$fact->id_comp_asoc?>" target="blank_">
                                        </a>

                                            <?php } ?>
                                        <?php } elseif($fact->id_tipo_comp=='5' and $fact->id_comp_asoc=='0')   { ?>    
                                        <a class="btn-default fa fa-eraser" title="Borrar" 
                                           onclick="verBorrar(<?=$fact->id?>, '<?=$fact->cliente?>')" >  
                                        </a>                                                                               
                                        <?php }   
                                        elseif($fact->id_tipo_comp == '5' and $fact->id_comp_asoc >'0') { ?>    
                                            <a class="btn-default fa fa-eye" title="Ver Comprobante" 
                                            href="<?php echo base_url(); ?>ventas/comprobante/<?=$fact->id_comp_asoc?>" target="blank_">
                                        </a>                                      
                                        <?php } 
                                        elseif($fact->id_tipo_comp == '3') { ?>    
                                            <a class="btn-default fa fa-eye" title="Ver Factura Asociada" 
                                            href="<?php echo base_url(); ?>ventas/comprobante/<?=$fact->id_comp_asoc?>" target="blank_">
                                        </a>                                      
                                        <?php } ?>
                                     </td>
                                </tr>
                        <?php	
                        }
                        ?>
                  </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!MODALS !>
    <div class="modal fade" id="mdlVerBorrar">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title">Borrar</h1>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <label id="msjBorrar"></label>   
                        
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-default btn-danger" id="hrefBorrar" href="">Borrar
                    
                </a>
            </div>

          </div>
        </div>
    </div>    
    
</div>

<script>
    
$(document).ready(function(){
    
    
});

function verBorrar(id,cliente){
    $("#msjBorrar").html("¿Está seguro de borrar el comprobante "+ id + " del cliente " + cliente + " ?");
    $("#hrefBorrar").attr("href","<?php echo base_url()?>ventas/borrar/" + id );
    $("#mdlVerBorrar").modal("show");
}

    
</script>