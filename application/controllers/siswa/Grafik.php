<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grafik extends Siswa_Controller
{

	public function index()
	{
		$this->data["page_title"] = "Beranda";
		$this->render("siswa/grafik/content");
	}
}
