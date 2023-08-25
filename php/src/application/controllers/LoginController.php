<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LoginController extends CI_Controller
{
	public function index()
	{
		if ($this->session->has_userdata("auth_user")) {
			redirect(base_url("admin/home"));
		}

		$data = null;
		if (!empty($this->session->flashdata('error'))) {
			$data['error'] = $this->session->flashdata('error');
		}
		if (!empty($this->session->tempdata('username_value'))) {
			$data['username_value'] = $this->session->tempdata('username_value');
		}

		$this->load->view('login', $data);

		$this->session->unset_tempdata('username_value');
	}

	public function login()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');


		if (!$this->form_validation->run()) {
			$this->form_validation->set_error_delimiters('<p class="mb-0 mt-2">', '</p>');
			$this->session->set_flashdata('error', '<div class="alert alert-danger"><strong>Error: </strong>' . validation_errors() . '</div>');
			$this->session->set_tempdata('username_value', set_value('username'));

			return redirect(base_url("/"));
		}

		$data = [
			"username" => $this->input->post('username'),
			"password" => $this->input->post('password')
		];

		$user = (new User)->loginUser($data);
		if (!$user) {
			$this->session->set_flashdata('error', '<div class="alert alert-danger"><strong>Error: </strong><p class="mb-0 mt-2">Invalid username or password</p></div>');
			$this->session->set_tempdata('username_value', set_value('username'));

			return redirect(base_url("/"));
		}

		$this->session->set_userdata("auth_user", $user);

		return redirect(base_url("admin/home"));
	}

	public function logout()
	{
		$this->session->unset_userdata("auth_user");
		redirect(base_url("/"));
	}
}
