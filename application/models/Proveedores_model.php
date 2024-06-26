<?php
class Proveedores_model extends CI_Model {
    
    public function __construct()
    {
            // Call the CI_Model constructor
            parent::__construct();
    }
    
    public function lista_iva()
        {
            $sql="SELECT * FROM cdiva";
            $retorno=$this->db->query($sql)->result();
            return $retorno;
        }
    
    public function lista_empresa()
        {
            $sql="SELECT id_empresa, razon_soc FROM empresas";
            $retorno=$this->db->query($sql)->result();
            return $retorno;
        }    
        
    public function lista_etiqueta()
        {
            $sql="SELECT id, etiqueta FROM etiquetas ORDER BY etiqueta";
            $retorno=$this->db->query($sql)->result();
            return $retorno;
        }    
        
    public function existe_cuit($id,$cuit)
        {
            {
            if(is_numeric($id)){
                $sql="SELECT * FROM proveedores WHERE id <> ? AND cuit = ?";
                $datos=$this->db->query($sql, array($id,$cuit))->result();
                
            }
            else
            {
                $sql="SELECT * FROM proveedores WHERE cuit = ?";
                $datos=$this->db->query($sql, array($cuit))->result();
            }
            return count($datos)>0;
        }
    }
    
    
    public function listado($b)
        {
            $b="%".trim(strtoupper($b))."%";
            $sql="SELECT a.*, d.cond_iva AS cdiva_nombre,".
                " IFNULL(b.razon_soc, '') AS empresa_nombre,".
                " IFNULL(c.etiqueta, '') AS etiqueta_nombre".
                " FROM proveedores a".
                " LEFT JOIN empresas b ON a.id_empresa=b.id_empresa".
                " LEFT JOIN etiquetas c ON a.id_etiqueta=c.id".    
                " INNER JOIN cdiva d ON a.iva=d.codigo".    
                " WHERE baja IS NULL".
                " AND ( UPPER(a.proveedor) LIKE ?".
                    " OR UPPER(b.razon_soc) LIKE ?".
                    " OR UPPER(c.etiqueta) LIKE ?)";
            
            $retorno=$this->db->query($sql, array($b,$b,$b))->result();
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
            $sql="SELECT a.*, d.cond_iva AS cdiva_nombre,".
                " IFNULL(b.razon_soc, '') AS empresa_nombre,".
                " IFNULL(c.etiqueta, '') AS etiqueta_nombre,".
                " DATE_FORMAT(a.baja, '%d/%m/%Y') AS fecha_baja".  
                " FROM proveedores a".
                " LEFT JOIN empresas b ON a.id_empresa=b.id_empresa".
                " LEFT JOIN etiquetas c ON a.id_etiqueta=c.id".    
                " INNER JOIN cdiva d ON a.iva=d.codigo".    
                " WHERE a.id=?";
            
            $retorno=$this->db->query($sql, array($id))->row();
            return $retorno;
        } 
        
    public function nuevo()
        {
            $proveedor = new stdClass();
            $proveedor->id=""; $proveedor->proveedor=""; $proveedor->domicilio=""; $proveedor->telefonos=""; $proveedor->email="";
            $proveedor->cuit=""; $proveedor->iva=""; $proveedor->localidad=""; $proveedor->cp=""; $proveedor->id_empresa="";
            $proveedor->dni=""; $proveedor->baja=""; $proveedor->id_etiqueta=""; $proveedor->rz="";
            $proveedor->cbu="";$proveedor->cond_pago="";$proveedor->contacto="";
            return $proveedor;
        }  
        
    public function ingresar($proveedor)
        {
        $mtz=array(
            $proveedor->proveedor,
            $proveedor->domicilio,
            $proveedor->telefonos,
            $proveedor->email,
            $proveedor->cuit,
            $proveedor->iva,
            $proveedor->localidad,
            $proveedor->cp,
            $proveedor->id_empresa,
            $proveedor->dni,
            $proveedor->id_etiqueta,
            $proveedor->rz,
            $proveedor->cond_pago,
            $proveedor->cbu,
            $proveedor->contacto,

        );
        
        $sql="INSERT INTO proveedores (proveedor,domicilio,telefonos,email,cuit,iva,localidad,cp,".
            "id_empresa,dni,id_etiqueta,rz,cond_pago,cbu,contacto) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        return $this->db->query($sql, $mtz);   
        }    
        
    public function editar($proveedor)
        {
        $mtz=array(
            $proveedor->proveedor,
            $proveedor->domicilio,
            $proveedor->telefonos,
            $proveedor->email,
            $proveedor->cuit,
            $proveedor->iva,
            $proveedor->localidad,
            $proveedor->cp,
            $proveedor->id_empresa,
            $proveedor->dni,
            $proveedor->id_etiqueta,
            $proveedor->rz,
            $proveedor->cond_pago,
            $proveedor->cbu,
            $proveedor->contacto,
            $proveedor->id    
        );
        
        $sql="UPDATE proveedores SET proveedor=?, domicilio=?, telefonos=?, email=?, cuit=?, iva=?,localidad=?, cp=?,".
            " id_empresa=?, dni=?, id_etiqueta=?, rz=?,cond_pago=?,cbu=?,contacto=? WHERE id=?";
        return $this->db->query($sql, $mtz);   
        }      
        
    public function borrar($id)
        {
        $retorno="";
        
        $sql="SELECT id_factura FROM facturas WHERE id_proveedor = ?";
        $datos=$this->db->query($sql, array($id))->result();
        
        if (count($datos)>0){//seteamos baja
            /*$sql="UPDATE proveedores SET baja=? WHERE id=?";
            $this->db->query($sql, array(date("Y-m-d"),$id));
            */
            $retorno="NO SE BORRO.  El proveedor Tiene COMPRAS Cargadas";
        }else{//eliminamos
            $sql="DELETE FROM proveedores WHERE id=?";
            $this->db->query($sql, array($id));
            $retorno ="El proveedor se ha eliminado con éxito";
        }
        return $retorno;
        }     
        
        public function mayor($fecha,$empresa)
        {
            $sql="                
            select T1.id_proveedor,T1.id_empresa,SUM(debe) as debe ,SUM(haber) as haber,proveedores.proveedor,proveedores.cuit,0 as saldo from      
            (select id_empresa,id_proveedor,sum(case when tipo_comp<3 then total else 0 end) as debe ,SUM(case when tipo_comp=3 then total else 0 end) as haber 
                        from facturas where fecha <= ? and id_proveedor >0  group by id_proveedor,id_empresa   
                    UNION
                select id_empresa,id_proveedor,0,sum(total) from opago where fecha <=? and id_proveedor > 0 group by id_proveedor,id_empresa
             ) as T1  LEFT JOIN proveedores on T1.id_proveedor = proveedores.id where T1.id_empresa=?
             group by T1.id_proveedor,T1.id_empresa,proveedores.proveedor,proveedores.cuit  order by proveedores.proveedor
            ";
            return $this->db->query($sql, array($fecha,$fecha,$empresa))->result();   
        }      
}
?>
