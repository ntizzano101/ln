<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" /> 
<style>
.container a:hover, a:visited, a:link, a:active{
    text-decoration: none;
}   
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" id="panel">
                <div class="panel-heading">FACTURACION AUTOMATICA DESDE SISTEMA DE REMITOS</div>
                    <div class="panel-body">
                            <label for="inputEmail4">Desde</label>                
                            <input type="date" class="form-control" name="desde" value="<?=$desde?>" id="desde" placeholder="dd/mm/yyyy">    
                            <label for="inputEmail4">Hasta</label>                         
                            <input type="date" class="form-control" name="hasta" value="<?=$hasta?>" id="hasta" placeholder="dd/mm/yyyy">
                        <label for="inputEmail4">Cliente</label>                   
                        <select name="cliente" id="cliente" class="form-control">
                                    <option value="">(todos)</option>
                                <?php foreach ($cliente as $cli) {?>
                                    <option value="<?=$cli->id?>">                                          
                                        <?=$cli->cliente?>
                                    </option>
                                <?php }?>        
                        </select>
                        <label for="inputEmail4">Vendedor</label>                   
                        <select name="vendedor" id="vendedor" class="form-control">
                                    <option value="">(todos)</option>
                                <?php foreach ($vendedor as $cli) {?>
                                    <option value="<?=$cli->id?>">                                          
                                        <?=$cli->nombre?>
                                    </option>
                                <?php }?>        
                        </select>
                        <br>
                        <button type="submit" class="btn btn-primary" id="comenzar">Comenzar</button>	                                
                    </div> 
                </div> 
            </div>
        </div>
                <table class="table">
                  <thead>
                        <tr>
                          <th>Vendedor</th>
                          <th>Cliente</th>
                          <th>Cantidad</th>
                          <th>Remitos</th>
                          <th>Total</th>                          
                        </tr>
                  </thead>
                  <tbody id="tablita">              

                  </tbody>
                </table>
           <div id="btnfacturar"></div>
    </div>  
    <!MODALS !>
    <?php
    $this->load->view('ventas/frmRemito');   
    ?>    
    <!MODALS !>
</div>        
<script>
  var CFG = {
        url: '<?php echo $this->config->item('base_url');?>',
        token: '<?php echo $this->security->get_csrf_hash();?>'
    };    
    
$(document).ready(function(){
    
    $.ajaxSetup({data: {token: CFG.token}});
    $(document).ajaxSuccess(function(e,x) {
        var result = $.parseJSON(x.responseText);
        $('input:hidden[name="token"]').val(result.token);
        $.ajaxSetup({data: {token: result.token}});
    });
 
});
    $("#comenzar").on("click", function(){             
            $("#panel").hide();
            $.post(CFG.url + 'ventas/preparacion/',
                {cliente:$("#cliente").val(),
                 vendedor:$("#vendedor").val(),
                 desde:$("#desde").val(),
                 hasta:$("#hasta").val()},   
                function(data){   
                                                             ;
                    $("#tablita").html(data.tabla);                    
                    $("#btnfacturar").html('<button type="submit" class="btn btn-warning" id="procfacturar">COMENZAR PROCESO</button>');
                });
     });
 
  function ver(id){   
    $.post(CFG.url + 'ventas/remito_veo/',
                {id:id },                
                function(data){
                                                                 
                    $("#frmId").html(data.Id);                    
                    $("#frmcliente").val(data.cliente);                    
                    $("#frmvendedor").val(data.vendedor);                    
                    $("#frmitems").html(data.items);                    
                    $("#remito").modal("show");
                });
  }
    
</script>    