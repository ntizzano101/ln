<?php
class Clientes_model extends CI_Model {
    
    public function __construct()
    {
            // Call the CI_Model constructor
            parent::__construct();
    }
    
    ##CLIENTES
    
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
                $sql="SELECT * FROM clientes WHERE id <> ? AND cuit = ?";
                $datos=$this->db->query($sql, array($id,$cuit))->result();
                
            }
            else
            {
                $sql="SELECT * FROM clientes WHERE cuit = ?";
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
                " FROM clientes a".
                " LEFT JOIN empresas b ON a.id_empresa=b.id_empresa".
                " LEFT JOIN etiquetas c ON a.id_etiqueta=c.id".    
                " INNER JOIN cdiva d ON a.iva=d.codigo".    
                " WHERE baja IS NULL".
                " AND ( UPPER(a.cliente) LIKE ?".
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
                " FROM clientes a".
                " LEFT JOIN empresas b ON a.id_empresa=b.id_empresa".
                " LEFT JOIN etiquetas c ON a.id_etiqueta=c.id".    
                " INNER JOIN cdiva d ON a.iva=d.codigo".    
                " WHERE a.id=?";
            
            $retorno=$this->db->query($sql, array($id))->row();
            return $retorno;
        } 
        
    public function nuevo()
        {
            $cliente = new stdClass();
            $cliente->id=""; $cliente->cliente=""; $cliente->domicilio=""; $cliente->telefonos=""; $cliente->email="";
            $cliente->cuit=""; $cliente->iva=""; $cliente->localidad=""; $cliente->cp=""; $cliente->id_empresa="";
            $cliente->dni=""; $cliente->baja=""; $cliente->id_etiqueta=""; $cliente->rz="";
            $cliente->contacto_compra=""; $cliente->contacto_pago=""; $cliente->tipo="";
            return $cliente;
        }  
        
    public function ingresar($cliente)
        {
        $mtz=array(
            $cliente->cliente,
            $cliente->domicilio,
            $cliente->telefonos,
            $cliente->email,
            $cliente->cuit,
            $cliente->iva,
            $cliente->localidad,
            $cliente->cp,
            $cliente->id_empresa,
            $cliente->dni,
            $cliente->id_etiqueta,
            $cliente->rz,
            $cliente->contacto_compra,
            $cliente->contacto_pago,
            $cliente->tipo
        );
        
        $sql="INSERT INTO clientes (cliente,domicilio,telefonos,email,cuit,iva,localidad,cp,".
            "id_empresa,dni,id_etiqueta,rz,contacto_compra,contacto_pago,tipo) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        return $this->db->query($sql, $mtz);   
        }    
        
    public function editar($cliente)
        {
        $mtz=array(
            $cliente->cliente,
            $cliente->domicilio,
            $cliente->telefonos,
            $cliente->email,
            $cliente->cuit,
            $cliente->iva,
            $cliente->localidad,
            $cliente->cp,
            $cliente->id_empresa,
            $cliente->dni,
            $cliente->id_etiqueta,
            $cliente->rz,            
            $cliente->contacto_compra,
            $cliente->contacto_pago,
            $cliente->tipo    ,
            $cliente->id
        );
        
        $sql="UPDATE clientes SET cliente=?, domicilio=?, telefonos=?, email=?, cuit=?, iva=?,localidad=?, cp=?,".
            " id_empresa=?, dni=?, id_etiqueta=?, rz=?,contacto_compra=?,contacto_pago=?,tipo=? WHERE id=?";
        return $this->db->query($sql, $mtz);   
        }      
        
    public function borrar($id)
        {
        $retorno="";
        
        $sql="SELECT id_factura FROM facturas WHERE id_cliente = ?";
        $datos=$this->db->query($sql, array($id))->result();
        
        if (count($datos)>0){//seteamos baja
            //$sql="UPDATE clientes SET baja=? WHERE id=?";
            //$this->db->query($sql, array(date("Y-m-d"),$id));
            $retorno="NO se ha dado de baja tiene movimientos";
        }else{//eliminamos
            $sql="DELETE FROM clientes WHERE id=?";
            $this->db->query($sql, array($id));
            $retorno ="El cliente se ha eliminado con éxito";
        }
        
        return $retorno;
           
        }      
        
        
    ##ETIQUETA
    public function etiqueta_listado($b)
        {
            $b="%".trim(strtoupper($b))."%";
            $sql="SELECT * FROM etiquetas WHERE UPPER(etiqueta) LIKE ? ";
            $retorno=$this->db->query($sql, array($b))->result();
            if((is_array($retorno))){
                return $retorno;
            }
            else
            {
                return array();
            }
             
        }
        
    public function etiqueta_buscar($id)
        {
            $sql="SELECT * FROM etiquetas WHERE id = ?";
            $retorno=$this->db->query($sql, array($id))->row();
            return $retorno;
        }    
        
    public function etiqueta_existe($id,$etiqueta)
        {
            $retorno=false;
            $etiqueta=trim(strtoupper($etiqueta));
            if(is_numeric($id)){
                $sql="SELECT * FROM etiquetas WHERE id <> ? AND UPPER(etiqueta) = ?";
                $datos=$this->db->query($sql, array($id,$etiqueta))->result();
                
            }
            else
            {
                $sql="SELECT * FROM etiquetas WHERE UPPER(etiqueta) = ?";
                $datos=$this->db->query($sql, array($etiqueta))->result();
            }
            return count($datos)>0;
        }
        
    
    public function etiqueta_en_cliente_existe($etiqueta)
        {
            $sql="SELECT * FROM clientes WHERE id_etiqueta = ?";
            $datos=$this->db->query($sql, array($etiqueta))->result();
            return count($datos)>0;
        }    
        
    public function etiqueta_ingresar($etiqueta)
        {
        $sql="INSERT INTO etiquetas (etiqueta) VALUES (?)";
        return $this->db->query($sql, array($etiqueta));   
        }  
    
    public function etiqueta_editar($id,$etiqueta)
        {
        $sql="UPDATE etiquetas SET etiqueta= ? WHERE id=?";
        return $this->db->query($sql, array($etiqueta,$id));   
        }
        
    public function etiqueta_borrar($id)
        {
        $sql="DELETE FROM etiquetas WHERE id=?";
        return $this->db->query($sql, array($id));   
        }
    public function mayor($fecha,$empresa)
        {
            $sql="                
            select T1.id_cliente,T1.id_empresa,SUM(debe) as debe ,SUM(haber) as haber,clientes.cliente,clientes.cuit,0 as saldo from      
            (select id_empresa,id_cliente,sum(case when tipo_comp<3 then total else 0 end) as debe ,SUM(case when tipo_comp=3 then total else 0 end) as haber 
                        from facturas where fecha <= ? and id_cliente >0  group by id_cliente,id_empresa   
                    UNION
                select id_empresa,id_cliente,0,sum(total) from opago where fecha <=? and id_cliente > 0 group by id_cliente,id_empresa
             ) as T1  LEFT JOIN clientes on T1.id_cliente = clientes.id where T1.id_empresa=?
             group by T1.id_cliente,T1.id_empresa,clientes.cliente,clientes.cuit  order by clientes.cliente
            ";
            return $this->db->query($sql, array($fecha,$fecha,$empresa))->result();   
        }        
        public function migrar(){
            $sql="truncate table vendedores";
            $this->db->query($sql);
            $sql="insert into vendedores select Id,case when NombreRemito='' then Nombre else NombreRemito end from ives.vendedor where baja='False'";
            $this->db->query($sql);
            $sql="truncate table clientes";
            $this->db->query($sql);
            $sql="SELECT * FROM ives.clientes WHERE upper(Cliente) like '%LIBRE%' or upper(cliente) like '%ABONO%' or upper(Tipo) IN ('ABONO','CTA/CTE')";
            $cli_ori=$this->db->query($sql)->result();
            foreach($cli_ori as $o){
                $d = new stdClass();  
                $d->id=$o->Id;
                $d->cliente=$o->Cliente;
                $d->domicilio=$o->Direccion;  
                $d->telefonos=$o->Telefono;
                $d->email=$o->Email;
                $d->cuit=$o->CUIT;                
                if(strlen($o->CUIT)==11 and $o->CUIT<>'00000000000'){$d->iva=1; $d->dni=0;}
                else{$d->iva=5; $d->dni=$o->CUIT;}        
                $d->localidad="";
                $d->id_empresa=1;
                $d->baja=Null;
                $d->id_vendedor=$o->Vendedor;
                $this->db->insert('clientes',$d);
            }            
        }
            public function clientes_auto(){
                $this->db->select("id,concat(cliente,' ',domicilio) as cliente");
                $this->db->order_by('cliente');
                $query=$this->db->get('clientes');                
                return($query->result());
                
            }
            public function remitos_sin_factura($id_cliente,$id_vendedor){
                /*$this->db->select("NumeroRemito,");
                $this->db->where('nombre');
                return($this->db->get('vendedores'));
                */
               return(Null) ;
            }
            public function vendedores(){
                $this->db->select("id,nombre");
                $this->db->order_by("nombre");
                return($this->db->get('vendedores')->result());
                
            }
        
        
}
?>
