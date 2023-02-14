<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recibo extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     * 
     * 
     * 
     */
    public function __construct()
    {
        parent::__construct();
            if(!isset($this->session->usuario)){
                redirect('salir');
                exit;
            }		
    }
    
     ##CLIENTES
    public function index()
    {
        
    }
    
    public function cta_cte($id_clie)
    {
        $this->load->model('recibo_model');
        $data["ctactes"]=$this->recibo_model->cta_cte($id_clie);
        $data["cliente"]=$this->recibo_model->cliente($id_clie);
        $this->load->view('encabezado.php');
        $this->load->view('menu.php');
        $this->load->view('recibo/cta_cte.php',$data);
        
    }
    
    public function opago($id_clie)
    {
        $this->load->model('recibo_model');
        $data["cliente"]=$this->recibo_model->proveedor($id_clie);
        
        $this->load->view('encabezado.php');
        $this->load->view('menu.php');
        print_r($data);
        
        
    }
    
}
 
?>