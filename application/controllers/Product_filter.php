<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_filter extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_filter_model');
	}

	function index()
	{
		$this->load->helper('url'); // чтоб base_url() работал

		$data['brand_data'] = $this->product_filter_model->fetch_filter_type('product_brand');
		$data['ram_data'] = $this->product_filter_model->fetch_filter_type('product_ram');
		$data['product_storage'] = $this->product_filter_model->fetch_filter_type('product_storage');
		$this->load->view('product_filter', $data);
	}

	function fetch_data()
	{
		sleep(1);
		$filters = $this->input->post('filters');

		$minimum_price = $this->input->post('minimum_price');
		$maximum_price = $this->input->post('maximum_price');
		$brand = $this->input->post('brand');
		$this->load->library('pagination');

		$config = array();
		$config['base_url'] = '#';
		$config['total_rows'] = $this->product_filter_model->count_all($minimum_price, $maximum_price, $brand, $filters);
		$config['per_page'] = 8;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = '&gt;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&lt;';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='active'><a href='#'>";
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['num_links'] = 3;
		$this->pagination->initialize($config);
		$page = $this->uri->segment(3);
		$start = ($page - 1) * $config['per_page'];

//		$pyatak = [
//			'vinni' => 'винни',
//			'puh' => 'пух',
//		];


		$output = array(
			'pagination_link'  => $this->pagination->create_links(),
			'product_list'   => $this->product_filter_model->fetch_data($config["per_page"], $start, $minimum_price, $maximum_price, $brand, $filters),
			'pyatak' => $filters

			//'product_list'   => 'vinni'
		);
		echo json_encode($output);
	}

}
