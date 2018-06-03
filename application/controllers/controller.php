<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->model("model");
		$this->load->library('session');
		$this->load->helper(array('form', 'url'));
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function login()
	{
		$this->load->view('login');
	}

	public function contact()
	{
		$this->load->view('contact');
	}

	public function dashboard()
	{
		$this->load->view('dashboard');
	}

	public function myProjects()
	{
		$this->load->view('myProjects');
	}

	public function myTeam()
	{
		$this->load->view('myTeam');
	}

	public function myTasks()
	{
		$this->load->view('myTasks');
	}

	public function templates()
	{
		$this->load->view('templates');
	}

	public function archives()
	{
		$this->load->view('archives');
	}

	public function newProject()
	{
		$this->load->view('newProject');
	}

// DELETE THIS AFTER
	public function frame()
	{
		$this->load->view('frame');

	}
}
