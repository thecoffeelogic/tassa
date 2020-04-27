<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends CI_Controller {

	public function __construct()
	{

		parent ::__construct();
		//load model
		$this->load->library('templatehome');
		$this->load->model('m_productfe');
		$this->load->model('m_orderfe');
		$this->load->model('m_setting');
		$this->load->model('m_faq');
		$this->load->model('m_banner');
		$this->load->model('m_quote');
		$this->load->model('m_footpicture');
		$this->load->model('m_information');
		$this->load->model('m_loginfe');
	}

	public function index()
	{	
		$data['prod'] = $this->m_productfe->get_product_frontend();
		$data['setting'] = $this->m_setting->get_setting();
		$data['banner'] = $this->m_banner->get_banner();
		$data['quote'] = $this->m_quote->get_quote();
		$data['footer'] = $this->m_footpicture->get_footer();
		$this->templatehome->view('home/home', $data);
	}

	public function product()
	{
		$data['prod'] = $this->m_productfe->get_join();
		$data['other'] = $this->m_productfe->other_product();
		$data['setting'] = $this->m_setting->get_setting();
		$this->templatehome->view('home/product', $data);
	}

	// public function cart()
	// {
	// 	$this->templatehome->view('home/cart');
	// }

	public function checkout()
	{
		$data['setting'] = $this->m_setting->get_setting();
		$this->templatehome->view('home/checkout', $data);
	}

	public function details()
	{
		$id = $this->uri->segment('4');
		$color = $this->uri->segment('5');
		$data['prod'] = $this->m_productfe->edit($id);
		$data['picture'] = $this->m_productfe->get_picture($id,$color);
		$data['related'] = $this->m_productfe->get_product_related();
		$data['related'] = $this->m_productfe->get_product_related();
		$data['setting'] = $this->m_setting->get_setting();
		$this->templatehome->view('home/details', $data);
	}

	public function add_to_cart()
    {
		$productid 		= $this->input->post("productid");
		$userid 		= $this->session->userdata('user_id');
		$productname 	= $this->input->post("productname");
		$color 			= $this->input->post("color");
		$qty 			= $this->input->post("qty");
		$date 			= date('Y-m-d H:i:s');
		$login = false;
		if($this->session->userdata('user_id') != null){
			$login = true;
		
			$data = array(
				'userid' 		=> $userid,
				'productid' 	=> $productid,
				'qty' 			=> $qty,
				'color'			=> $color,
				'created_at'  	=> $date
			);
			$this->m_orderfe->add_cart($data);
		
		}

		redirect('beranda/details/'.$productname.'/'.$productid.'/'.$color.'?login='.$login);

	}

	public function cart()
	{
		if($this->session->userdata('user_id') == null){
			redirect('beranda/');
		}
		else {
			$userid = $this->session->userdata('user_id');
			$data['cart'] = $this->m_orderfe->cart($userid);
			$data['user'] = $this->m_orderfe->user($userid);
			$data['setting'] = $this->m_setting->get_setting();
			$this->templatehome->view('home/cart', $data);
		}
		
	}


	public function address()
	{
		if($this->session->userdata('user_id') == null){
			redirect('beranda/');
		}
		else {
			$userid = $this->session->userdata('user_id');
			$data['cart'] = $this->m_orderfe->cart($userid);
			$data['user'] = $this->m_orderfe->user($userid);
			$data['setting'] = $this->m_setting->get_setting();
			$this->templatehome->view('home/address', $data);
		}
	}

	public function insert_address()
    {
		$random		  = rand();
		$name 		  = $this->input->post("name");
		$phone 	 	  = $this->input->post("phone");
		$address	  = $this->input->post("address");
		$province 	  = $this->input->post("province");
		$city 		  = $this->input->post("city");
		$subdistricts = $this->input->post("subdistricts");
		$zip 	 	  = $this->input->post("zip");
		
		$data = array(
			'OrderCode'			=> $random,
			'OrderUserID' 		=> $this->session->userdata('user_id'),
			'OrderShipName' 	=> $name,
			'OrderShipAddress' 	=> $address,
			'OrderCity'			=> $city,
			'OrderSubDistrict' 	=> $subdistricts,
			'OrderProvince' 	=> $province,
			'OrderZip' 			=> $zip,
			'OrderPhone'		=> $phone
		);
		
		$code = $this->m_orderfe->add_orders($data);	
		redirect('beranda/order_detail');

	}

	public function order_detail()
	{
		if($this->session->userdata('user_id') == null){
			redirect('beranda/');
		}
		else {
			$userid = $this->session->userdata('user_id');
			$data['cart'] = $this->m_orderfe->cart($userid);
			$data['order'] = $this->m_orderfe->order($userid);
			$data['setting'] = $this->m_setting->get_setting();
			$this->templatehome->view('home/detail_order', $data);
		}
		
	}

	public function insert_detail_order()
    {
		$id['OrderCode']  = $this->input->post("order");
		$amount	 	  = $this->input->post("amount");
		
		$data = array(
			'OrderAmount' => $amount
		);

		$data_cart = array(
			'orderid' => $id['OrderCode']
		);

		$where = array(
			'userid' => $this->session->userdata('user_id'),
			'orderid' => ''
		);
		
		$this->m_orderfe->update_orders($data,$id);	
		$this->m_orderfe->update_cart($data_cart,$where);	
		redirect('beranda/payment/'.$id['OrderCode']);

	}

	public function payment()
	{
		if($this->session->userdata('user_id') == null){
			redirect('beranda/');
		}
		else {
			$userid = $this->session->userdata('user_id');
			$data['cart'] = $this->m_orderfe->cart($userid);
			$data['order'] = $this->m_orderfe->order($userid);
			$data['setting'] = $this->m_setting->get_setting();
			$this->templatehome->view('home/metode_payment', $data);
		}
	}

	public function detail_checkout()
	{
		$data['setting'] = $this->m_setting->get_setting();
		$this->templatehome->view('home/detail_checkout', $data);
	}

	public function privasi()
	{
		$data['setting'] = $this->m_setting->get_setting();
		$id = $this->session->userdata('user_id');
		$data['user'] = $this->m_loginfe->get_users($id);
		$this->templatehome->view('home/privasi', $data);
	}

	public function information()
	{
		$data['setting'] = $this->m_setting->get_setting();
		$type = $this->uri->segment(3);
		$data['info'] = $this->m_information->get_detail($type);
		$this->templatehome->view('home/information', $data);
	}

	public function faq()
	{
		$data['faq'] = $this->m_faq->get_faq();
		$data['setting'] = $this->m_setting->get_setting();
		$this->templatehome->view('home/faq', $data);
	}

}
