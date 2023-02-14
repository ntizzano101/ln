<?php
class ClientesIva_model extends CI_Model {
    
    public function __construct()
    {
            // Call the CI_Model constructor
            parent::__construct();
    }
    
    ##LISTADOS
    public function lista_mes()
        {
            $retorno=array();
            $fila=array("nro" => "1", "nmb" => "Enero"); array_push($retorno, $fila);
            $fila=array("nro" => "2", "nmb" => "Febrero"); array_push($retorno, $fila);
            $fila=array("nro" => "3", "nmb" => "Marzo"); array_push($retorno, $fila);
            $fila=array("nro" => "4", "nmb" => "Abril"); array_push($retorno, $fila);
            $fila=array("nro" => "5", "nmb" => "Mayo"); array_push($retorno, $fila);
            $fila=array("nro" => "6", "nmb" => "Junio"); array_push($retorno, $fila);
            $fila=array("nro" => "7", "nmb" => "Julio"); array_push($retorno, $fila);
            $fila=array("nro" => "8", "nmb" => "Agosto"); array_push($retorno, $fila);
            $fila=array("nro" => "9", "nmb" => "Septiembre"); array_push($retorno, $fila);
            $fila=array("nro" => "10", "nmb" => "Octubre"); array_push($retorno, $fila);
            $fila=array("nro" => "11", "nmb" => "Noviembre"); array_push($retorno, $fila);
            $fila=array("nro" => "12", "nmb" => "Diciembre"); array_push($retorno, $fila);
            
            return $retorno;
        }  
        
    public function nombre_mes($mes)
        {
            $retorno="";
            switch ($mes) {
                case 1: $retorno="Enero"; break;
                case 2: $retorno="Febrero"; break;
                case 3: $retorno="Marzo"; break;
                case 4: $retorno="Abril"; break;
                case 5: $retorno="Mayo"; break;
                case 6: $retorno="Junio"; break;
                case 7: $retorno="Julio"; break;
                case 8: $retorno="Agosto"; break;
                case 9: $retorno="Septiembre"; break;
                case 10: $retorno="Octubre"; break;
                case 11: $retorno="Noviembre"; break;
                case 12: $retorno="Diciembre"; break;
                default: break;
            }
            return $retorno;
        }    
    
    
    public function lista_empresa($id)
        {
            if($id==""){
                $sql="SELECT id_empresa, razon_soc FROM empresas ORDER BY razon_soc";
                $retorno=$this->db->query($sql)->result();
            }else{
                $sql="SELECT id_empresa, razon_soc FROM empresas WHERE id_empresa = ? ORDER BY razon_soc";
                $retorno=$this->db->query($sql, array($id))->row();
            }
            
            return $retorno;
        }  
    
    public function lista_iva($id,$mesAnio)
        {
            
            $sql="SELECT DATE_FORMAT(a.fecha,'%d/%m/%Y') AS fecha,".
                " b.razon_soc, b.cuit, a.codigo_comp AS codigo,".
                " CONCAT(a.letra,' ',a.puerto,'-',a.numero) AS comprobante,".
                " a.neto21, a.iva21, a.neto105, a.iva105, a.excento, a.total".
                " FROM facturas a".
                " INNER JOIN empresas b ON a.id_empresa=b.id_empresa".
                " WHERE a.id_empresa = ?".
                " AND DATE_FORMAT(a.fecha,'%m%Y')= ?".
                " ORDER BY a.fecha";
            $retorno=$this->db->query($sql, array($id,$mesAnio))->result();
            
            return $retorno;
        }      
    
}
?>
