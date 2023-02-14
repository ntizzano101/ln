<?php
class Recibo_model extends CI_Model {
    
    public function __construct()
    {
            // Call the CI_Model constructor
            parent::__construct();
    }
    
    //LISTADOS VARIOS
     public function cliente($id)
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
        
    //cta_cte
    public function cta_cte($id_clie)
        {
            $sql="SELECT DATE_FORMAT(rb.fecha,'%d/%m/%Y') AS fecha, rb.id, rb.total, 0 AS debe, 0 AS haber".
                " FROM recibos rb".
            " WHERE rb.id_cliente=?".	
            " UNION".
            " SELECT DATE_FORMAT(fac.fecha,'%d/%m/%Y') AS fecha, fac.id_factura AS id,".
                " IF(cod.id_tipo_comp=3, fac.total , 0) AS total,".
                " IF(cod.id_tipo_comp<>3, fac.total , 0) AS debe, 0 AS haber".
            " FROM facturas fac".
                " INNER JOIN cod_afip cod on fac.cod_afip = cod.cod_afip".
            " WHERE fac.id_cliente=?".
            " ORDER BY fecha";
            $retorno=$this->db->query($sql, array($id_clie, $id_clie))->result();
            return $retorno;
        }        
          
}
?>
