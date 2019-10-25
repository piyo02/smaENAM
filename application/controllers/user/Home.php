<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends User_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'user';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Courses_services');
		$this->services = new Courses_services;
		$this->load->model(array(
			'm_teacher_profile',
		));
	}

	public function index()
	{
		if ($this->ion_auth->is_teacher()) {
			$data_param['user_id'] = $this->session->userdata('user_id');
			if (!$this->m_teacher_profile->get_profile($data_param)->row()) {
				redirect(base_url('user/profile'));
			}
		}
		$this->data["page_title"] = "Beranda";
		$this->render("admin/dashboard/content");
	}
}
