<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EmployeeController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("Authentication");

        if ($this->session->userdata("auth_user")->user_type !== "1") {
            redirect(base_url("admin/home"));
        }

        $this->load->library('qr');
    }


    public function index()
    {
        $data = null;
        if (!empty($this->session->flashdata('error'))) {
            $data['error'] = $this->session->flashdata('error');
        } else if (!empty($this->session->flashdata('success'))) {
            $data['success'] = $this->session->flashdata('success');
        }

        $this->load->view('admin/employees/index', $data);
    }

    public function list()
    {
        $postData = $this->input->post();
        $data = $this->Employee->getRecords($postData);
        echo json_encode($data);
    }

    public function store()
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');

        // Validate Form
        if (!$this->form_validation->run()) {
            $this->form_validation->set_error_delimiters('<p class="mb-0 mt-2">', '</p>');
            $this->session->set_flashdata('error', '<div class="alert alert-danger"><strong>Error: </strong>' . validation_errors() . '</div>');

            return redirect(base_url("admin/employees"));
        }

        $data = [
            "first_name" => $this->input->post('first_name'),
            "last_name" => $this->input->post('last_name')
        ];

        // Check employe if already exists
        $employeeExists = $this->Employee->checkExists($data);
        if ($employeeExists) {
            $this->session->set_flashdata('error', '<div class="alert alert-danger"><strong>Error: </strong> Employee already exists.</div>');

            return redirect(base_url("admin/employees"));
        }

        $employeeId = $this->Employee->createEmployee($data);

        // Generate QR after creating employee
        $fileName = $this->qr->generateQR($employeeId);
        $this->Employee->updateEmployee(["qr_image" => $fileName], $employeeId);

        $this->session->set_flashdata('success', '<div class="alert alert-success"><strong>Success!</strong> New employee created.</div>');

        return redirect(base_url("admin/employees"));
    }

    public function update($id)
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');

        // Validate form
        if (!$this->form_validation->run()) {
            $this->form_validation->set_error_delimiters('<p class="mb-0 mt-2">', '</p>');
            $this->session->set_flashdata('error', '<div class="alert alert-danger"><strong>Error: </strong>' . validation_errors() . '</div>');

            return redirect(base_url("admin/employees"));
        }

        $data = [
            "first_name" => $this->input->post('first_name'),
            "last_name" => $this->input->post('last_name')
        ];

        // Check if employee exists
        $employee = $this->Employee->findById($id);
        if (!$employee) {
            $this->session->set_flashdata('error', "<div class='alert alert-danger'><strong>Error: </strong> Employee doesn't exists.</div>");

            return redirect(base_url("admin/employees"));
        }

        $this->Employee->updateEmployee($data, $id);
        $this->session->set_flashdata('success', '<div class="alert alert-success"><strong>Success!</strong> Employee updated.</div>');
        return redirect(base_url("admin/employees"));
    }

    public function destroy($id)
    {
        // Check if employee exists
        $employee = $this->Employee->findById($id);
        if (!$employee) {
            $this->session->set_flashdata('error', "<div class='alert alert-danger'><strong>Error: </strong> Employee doesn't exists.</div>");

            return redirect(base_url("admin/employees"));
        }

        $this->Employee->deleteEmployee($id);
        $this->session->set_flashdata('success', '<div class="alert alert-success"><strong>Success!</strong> Employee deleted.</div>');

        return redirect(base_url("admin/employees"));
    }
}
