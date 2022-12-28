<?php
class Ventas_model extends CI_Model {
    
    public function __construct()
    {
            // Call the CI_Model constructor
            parent::__construct();
    }
    
    //LISTADOS VARIOS
    
    public function lista_clientes()
        {
            $sql="SELECT id, cliente FROM clientes";
            $retorno=$this->db->query($sql)->result();
            return $retorno;
        } 
        
    public function lista_empresas()
        {
            $sql="SELECT id_empresa, razon_soc FROM empresas";
            $retorno=$this->db->query($sql)->result();
            return $retorno;
        }     
        
    public function lista_comprobantes($id_empresa,$id_proveedor)
        {
            $sql="SELECT DISTINCT id, cod_afip, cod_afip_t FROM cod_afip".
            " WHERE id_iva=(SELECT cond_iva FROM empresas WHERE id_empresa=?)".
            " AND id_iva_compra=(SELECT iva FROM proveedores WHERE id=?)".
            "  ORDER BY cod_afip";
            $retorno=$this->db->query($sql, array($id_empresa, $id_proveedor))->result();
            return $retorno;
        }    
        
    public function buscar_cliente($id)
        {
            $sql="SELECT a.*, b.cond_iva".
                " FROM clientes a".
                " INNER JOIN cdiva b ON a.iva=b.codigo". 
                " WHERE a.id=?";
            $retorno=$this->db->query($sql, array($id))->row();
            return $retorno;
        }        
    
    public function buscar_item($item)
        {   
            $item="%".trim(strtoupper($item))."%";
            $sql="SELECT *".
                " FROM articulos".
                " WHERE UPPER(codigo) LIKE ? OR  UPPER(articulo) LIKE ?";
            
            $retorno=$this->db->query($sql, array($item, $item))->result();
            return $retorno;
        }       
        
    public function buscar_un_item($id)
        {   
            $sql="SELECT *".
                " FROM articulos".
                " WHERE id_art = ?";
            
            $retorno=$this->db->query($sql, array($id))->row();
            return $retorno;
        }    
        
        
        
    //FACTURAS
    public function listado($b)
        {
            $sql="SELECT a.id_factura AS id".
                ", DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha".
                ", b.proveedor".
                " FROM facturas a".
                " INNER JOIN clientes b ON a.id_cliente=b.id".
                " WHERE TRUE ";
            
            
            if(trim($b) !=""){
                $esFch=false;
                if (substr_count($b,"/")==2){
                    list($dia,$mes,$anio)= explode("/",$b);
                    if(is_numeric($dia) && is_numeric($mes) && is_numeric($anio) ){
                        if(checkdate($mes, $dia, $anio)){
                            $esFch=true;
                            $b=$anio."-".$mes."-".$dia;
                        }
                    }
                }
                
                if($esFch){
                    $sql.=" AND a.fecha=?";
                }else{
                    $b="%".trim(strtoupper($b))."%";
                    $sql.=" AND UPPER(b.cliente) LIKE ?";
                }
            }else{
                $sql.=" AND FALSE";
            }
             
            $sql.=" LIMIT 10";
            
            $retorno=$this->db->query($sql, array($b))->result();
            if((is_array($retorno))){
                return $retorno;
            }
            else
            {
                return array();
            }
             
        }
        
    public function buscar($id)
        {
        $sql="SELECT a.*, DATE_FORMAT(fecha, '%d/%m/%Y') AS fc_format,".
            "SUBSTRING(a.per_iva, 1, 4) AS pi_anio, SUBSTRING(a.per_iva, 5,2) AS pi_mes,".    
            " b.razon_soc AS empresa,".
            " c.cliente AS clie_nombre, c.domicilio AS clie_dir, d.cond_iva AS prov_iva,".
            " e.cod_afip_t AS tp_comprob".    
            " FROM facturas a".
            " INNER JOIN empresas b ON a.id_empresa=b.id_empresa".
            " INNER JOIN clientes c ON a.id_cliente=c.id".  
            " INNER JOIN cdiva d ON c.iva=d.codigo". 
            " INNER JOIN cod_afip e ON a.id_tipo_comp=e.id".    
            " WHERE a.id_factura=?";
        $retorno=$this->db->query($sql, array($id))->row();
        return $retorno;
        }    
    
    public function guardar($obj)
        {
        //$obj->periva=trim($this->input->post('periva'));Falta
        //$usuario="21890143";
        $usuario=$_SESSION["id"];
        if(!(is_numeric($obj->intImpNeto))){$obj->intImpNeto="0.00";}
        if(!(is_numeric($obj->intIva))){$obj->intIva="0.00";}
        if(!(is_numeric($obj->intPerIngB))){$obj->intPerIngB="0.00";}
        if(!(is_numeric($obj->intPerIva))){$obj->intPerIva="0.00";}
        if(!(is_numeric($obj->intPerGnc))){$obj->intPerGnc="0.00";}
        if(!(is_numeric($obj->intPerStaFe))){$obj->intPerStaFe="0.00";}
        if(!(is_numeric($obj->intImpExto))){$obj->intImpExto="0.00";}
        if(!(is_numeric($obj->intConNoGrv))){$obj->intConNoGrv="0.00";}
        if(!(is_numeric($obj->intTotal))){$obj->intTotal="0.00";}
        
        list($prM,$prA)= explode("/", $obj->periva);
        
        $mtz=array(
            $obj->fecha,    //0
            $obj->factnro1,//1
            $obj->factnro2,//2
            $obj->cod_afip,//3
            $obj->obs,//4
            $obj->formaPago,//5
            $obj->empresa,//6
            
            $obj->intImpNeto,//7
            $obj->intIva,//8
            $obj->intPerIngB,//9
            $obj->intPerIva,//10
            $obj->intPerGnc,//11
            $obj->intPerStaFe,//12
            $obj->intImpExto,//13
            $obj->intConNoGrv,//14
            $obj->intTotal,//15
            
            $usuario,//16
            $obj->proveedor,//17
            $prA.$prM,//18
            $obj->items//19
        );
        
        $sql="CALL ingfacturaclie(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data= array();
        try{
        $retorno=$this->db->query($sql, $mtz);
        } catch (Exception $ex) {
            echo "error ". $ex." <br>";
        }
        
        if($retorno){
            $data = $retorno->row_array();
            $retorno->free_result();
            $retorno->next_result();
        }
        return $data;
        
    } 
    
    public function borrar($id)
        {
     /*   $retorno="";
        $sql="DELETE FROM facturas WHERE id_factura=?";
        $this->db->query($sql, array($id));
        $sql="DELETE FROM factura_items WHERE id_factura=?";
        $this->db->query($sql, array($id));
        $retorno ="El artículos se ha eliminado con éxito";
        return $retorno;
     */      
        }  
          
}
?>