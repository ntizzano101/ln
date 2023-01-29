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
            $sql="SELECT id, cliente FROM clientes order by cliente";
            $retorno=$this->db->query($sql)->result();
            return $retorno;
        } 
        
    public function lista_empresas()
        {
            $sql="SELECT id_empresa, razon_soc FROM empresas";
            $retorno=$this->db->query($sql)->result();
            return $retorno;
        }     
        
    public function lista_comprobantes($id_empresa,$id_cliente)
        {           
            $sql="SELECT DISTINCT id, cod_afip, cod_afip_t,cod_afip.nombre FROM cod_afip".
            " WHERE id_iva=(SELECT cond_iva FROM empresas WHERE id_empresa=?)".
            " AND id_iva_compra=(SELECT iva FROM clientes WHERE id=?)".
            "  ORDER BY cod_afip";
            $retorno=$this->db->query($sql, array($id_empresa, $id_cliente))->result();
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
                ", b.cliente".
                ", a.puerto,a.numero,a.total,a.codigo_comp,a.letra,c.nombre,e.datos,ca.id_tipo_comp,a.id_comp_asoc".
                " FROM facturas a".
                " INNER JOIN clientes b ON a.id_cliente=b.id".
                " INNER JOIN empresas e ON a.id_empresa=e.id_empresa".
                " INNER JOIN cod_afip ca ON ca.id=a.id_tipo_comp".
                " LEFT JOIN (select distinct cod_afip_t,nombre from cod_afip) as  c on a.codigo_comp=c.cod_afip_t".
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
                    $sql.="  AND (UPPER(b.cliente) LIKE ? or  UPPER(e.datos) LIKE ?)";
                }
            }
             
            $sql.="  order by a.fecha desc ,a.cliente ,a.puerto,a.numero,a.codigo_comp,a.letra LIMIT 50";
            
            $retorno=$this->db->query($sql, array($b,$b))->result();
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
        
        //list($prM,$prA)= explode("/", $obj->periva);
        
        $mtz=array(
            $obj->fecha,    //0
            $obj->factnro1,//1            
            $obj->cod_afip,//2
            $obj->obs,//3
            $obj->formaPago,//4
            $obj->empresa,//5            
            $obj->intImpNeto,//6
            $obj->intIva,//7
            $obj->intPerIngB,//6
            $obj->intPerIva,//9
            $obj->intPerGnc,//10
            $obj->intPerStaFe,//11
            $obj->intImpExto,//12
            $obj->intConNoGrv,//13
            $obj->intTotal,//14            
            $usuario,//15
            $obj->cliente,//16
            $obj->periva,//17
            $obj->items//18
        );
        
      
        $sql="CALL ingfacturaclie(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
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
        $retorno="";
        $sql="DELETE FROM facturas WHERE id_factura=?";
        $this->db->query($sql, array($id));
        $sql="DELETE FROM factura_items WHERE id_factura=?";
        $this->db->query($sql, array($id));
        $retorno ="El artículos se ha eliminado con éxito";
        return $retorno;          
    } 
    public function empresa($id){
        $sql="SELECT e.razon_soc,e.direccion,e.localidad,e.telefono,e.provincia,e.cp,c.cond_iva,e.cuit,e.nro_iibb
                FROM empresas e LEFT JOIN cdiva c on c.codigo=e.cond_iva
                INNER JOIN facturas f on f.id_empresa=e.id_empresa                
                WHERE f.id_factura=? LIMIT 1";  
        $retorno= $this->db->query($sql, array($id))->result();
        return $retorno[0];
         
     }
    public function cliente($id){
        $sql="SELECT e.cliente,e.domicilio,e.cuit,e.telefonos,c.cond_iva,e.iva ,e.dni
                FROM clientes e LEFT JOIN cdiva c on c.codigo=iva
                INNER JOIN facturas f on f.id_cliente=e.id
                WHERE f.id_factura=? LIMIT 1";  
        $retorno=$this->db->query($sql, array($id))->result();
        return $retorno[0];
     }
     public function venta($id){
        $sql="SELECT f.*,c.nombre_c               
                FROM facturas f INNER JOIN cod_afip c on f.id_tipo_comp=c.id
                WHERE f.id_factura=? LIMIT 1"  ;
        $retorno=$this->db->query($sql, array($id))->result();
        return $retorno[0];        
     }
     public function items($id){
        $sql="SELECT i.* 
                FROM  factura_items i where  id_factura=?"  ;
        $retorno=$this->db->query($sql, array($id))->result();
        return $retorno;
     }
 }
?>
