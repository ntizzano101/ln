<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClientesIVA extends CI_Controller {

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
    
    ##CLIENTES_IVA
    public function index()
    {
        $this->load->model('clientesIVA_model');
        $data["lista_empresa"]=$this->clientesIVA_model->lista_empresa("");
        $data["lista_mes"]=$this->clientesIVA_model->lista_mes("");
        $data["empresa"]=$this->clientesIVA_model->lista_empresa($this->input->post('bsq_emp'));
        
        $data["bsq_mes"]=$this->input->post('bsq_mes');
        $data["bsq_anio"]=$this->input->post('bsq_anio');
        $data["bsq_emp"]=$this->input->post('bsq_emp');
        
        $this->load->view('encabezado.php');
        $this->load->view('menu.php');
        $this->load->view('clientesIVA/clientesIVA.php',$data);
    }
    
    public function listado()
    {
        $this->load->model('clientesIVA_model');
        $data["mes"]=$this->clientesIVA_model->nombre_mes($this->input->post('bsq_mes'));
        $data["anio"]=$this->input->post('bsq_anio');
        $data["empresa"]=$this->clientesIVA_model->lista_empresa($this->input->post('bsq_emp'));
        
        $data["bsq_mes"]=$this->input->post('bsq_mes');
        $data["bsq_anio"]=$this->input->post('bsq_anio');
        $data["bsq_emp"]=$this->input->post('bsq_emp');
        
        
        
        $mesAnio=str_pad($this->input->post('bsq_mes'), 2, "0", STR_PAD_LEFT). $this->input->post('bsq_anio');
        $data["lista"]=$this->clientesIVA_model->lista_iva($this->input->post('bsq_emp'),$mesAnio);
        $this->load->view('encabezado.php');
        $this->load->view('menu.php');
        $this->load->view('clientesIVA/listaIVA.php',$data);
    }
    
    
}
   
?>