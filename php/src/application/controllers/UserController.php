<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("Authentication");

        if ($this->session->userdata("auth_user")->user_type !== "1") {
            redirect(base_url("admin/home"));
        }

        $this->load->library('password');
    }

    public function index()
    {
        $data = null;
        if (!empty($this->session->flashdata('error'))) {
            $data['error'] = $this->session->flashdata('error');
        } else if (!empty($this->session->flashdata('success'))) {
            $data['success'] = $this->session->flashdata('success');
        }

        $this->load->view('admin/users/index', $data);
    }

    public function list()
    {
        $postData = $this->input->post();
        $data = $this->User->getRecords($postData);
        echo json_encode($data);
    }

    public function create()
    {
        $data = null;
        if (!empty($this->session->flashdata('error'))) {
            $data['error'] = $this->session->flashdata('error');
        } else if (!empty($this->session->flashdata('success'))) {
            $data['success'] = $this->session->flashdata('success');
        }
        if (!empty($this->session->tempdata('username_value'))) {
            $data['username_value'] = $this->session->tempdata('username_value');
        }
        if (!empty($this->session->tempdata('user_type_value'))) {
            $data['user_type_value'] = $this->session->tempdata('user_type_value');
        }

        $this->load->view('admin/users/create', $data);

        $this->session->unset_tempdata('username_value');
        $this->session->unset_tempdata('user_type_value');
    }

    public function store()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('user_type', 'User Type', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');

        // Validate Form
        if (!$this->form_validation->run()) {
            $this->form_validation->set_error_delimiters('<p class="mb-0 mt-2">', '</p>');
            $this->session->set_flashdata('error', '<div class="alert alert-danger"><strong>Error: </strong>' . validation_errors() . '</div>');
            $this->session->set_tempdata('username_value', set_value('username'));
            $this->session->set_tempdata('user_type_value', set_value('user_type'));

            return redirect(base_url("admin/users/create"));
        }

        $data = [
            'user_name' => $this->input->post('username'),
            'user_password' => $this->input->post('password'),
            'user_type' => $this->input->post('user_type'),
        ];

        // Check if username already exists
        $exists = $this->User->checkExists($data['user_name']);
        if ($exists) {
            $this->session->set_flashdata('success', '<div class="alert alert-danger"><strong>Error: </strong> Username already exists.</div>');
            $this->session->set_tempdata('username_value', set_value('username'));
            $this->session->set_tempdata('user_type_value', set_value('user_type'));

            return redirect(base_url("admin/users/create"));
        }

        // Validate password strength
        $passwordErrors = $this->password->validateStrength($data['user_password']);
        if (count($passwordErrors)) {
            $this->form_validation->set_error_delimiters('<p class="mb-0 mt-2">', '</p>');
            $errors = '';
            foreach ($passwordErrors as $passwordError) {
                $errors .= "<p class='mb-1'>{$passwordError}</p>";
            }
            $this->session->set_flashdata('error', '<div class="alert alert-danger"><strong>Error: </strong>' . $errors . '</div>');
            $this->session->set_tempdata('username_value', set_value('username'));
            $this->session->set_tempdata('user_type_value', set_value('user_type'));

            return redirect(base_url("admin/users/create"));
        }

        $this->User->create($data);

        $this->session->set_flashdata('success', '<div class="alert alert-success"><strong>Success!</strong> New user created.</div>');

        return redirect(base_url("admin/users"));
    }

    public function edit($id)
    {
        // Check if auth user exists
        $user = $this->User->findById($id);
        if (!$user) {
            $this->session->set_flashdata('error', "<div class='alert alert-danger'><strong>Error: </strong> User doesn't exist.</div>");
        }

        $data = [
            "user" => $user
        ];

        if (!empty($this->session->flashdata('error'))) {
            $data['error'] = $this->session->flashdata('error');
        } else if (!empty($this->session->flashdata('success'))) {
            $data['success'] = $this->session->flashdata('success');
        }

        $this->load->view('admin/users/edit', $data);
    }

    public function update($id)
    {
        // Check if auth user exists
        $user = $this->User->findById($id);
        if (!$user) {
            $this->session->set_flashdata('error', "<div class='alert alert-danger'><strong>Error: </strong> User doesn't exists.</div>");
        }

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('user_type', 'User Type', 'trim|required');

        // Validate Form
        if (!$this->form_validation->run()) {
            $this->form_validation->set_error_delimiters('<p class="mb-0 mt-2">', '</p>');
            $this->session->set_flashdata('error', '<div class="alert alert-danger"><strong>Error: </strong>' . validation_errors() . '</div>');
            $this->session->set_tempdata('username_value', set_value('username'));
            $this->session->set_tempdata('user_type_value', set_value('user_type'));

            return redirect(base_url("admin/users/edit/{$id}"));
        }

        $data = [
            "user_name" => $this->input->post('username'),
            "user_type" => $this->input->post('user_type'),
        ];

        $this->User->update($data, $id);

        $this->session->set_flashdata('success', '<div class="alert alert-success"><strong>Success!</strong> User updated.</div>');

        return redirect(base_url("admin/users/edit/{$id}"));
    }

    public function changePassword($id)
    {
        // Check if auth user exists
        $user = $this->User->findById($id);
        if (!$user) {
            $this->session->set_flashdata('error', "<div class='alert alert-danger'><strong>Error: </strong> User doesn't exist.</div>");
        }

        $data = [
            "user" => $user
        ];

        if (!empty($this->session->flashdata('error'))) {
            $data['error'] = $this->session->flashdata('error');
        } else if (!empty($this->session->flashdata('success'))) {
            $data['success'] = $this->session->flashdata('success');
        }

        $this->load->view('admin/users/change-password', $data);
    }

    public function savePassword($id)
    {
        // Check if auth user exists
        $user = $this->User->findById($id);
        if (!$user) {
            $this->session->set_flashdata('error', "<div class='alert alert-danger'><strong>Error: </strong> User doesn't exists.</div>");
        }

        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');

        // Validate Form
        if (!$this->form_validation->run()) {
            $this->form_validation->set_error_delimiters('<p class="mb-0 mt-2">', '</p>');
            $this->session->set_flashdata('error', '<div class="alert alert-danger"><strong>Error: </strong>' . validation_errors() . '</div>');

            return redirect(base_url("admin/users/change-password/{$id}"));
        }

        // Validate password strength
        $passwordErrors = $this->password->validateStrength($this->input->post('password'));
        if (count($passwordErrors)) {
            $this->form_validation->set_error_delimiters('<p class="mb-0 mt-2">', '</p>');
            $errors = '';
            foreach ($passwordErrors as $passwordError) {
                $errors .= "<p class='mb-1'>{$passwordError}</p>";
            }
            $this->session->set_flashdata('error', '<div class="alert alert-danger"><strong>Error: </strong>' . $errors . '</div>');
            $this->session->set_tempdata('username_value', set_value('username'));
            $this->session->set_tempdata('user_type_value', set_value('user_type'));

            return redirect(base_url("admin/users/change-password/{$id}"));
        }

        // Save to db
        $this->User->update(["user_password" => $this->input->post("password")], $id);

        $this->session->set_flashdata('success', '<div class="alert alert-success"><strong>Success!</strong> Password updated.</div>');

        return redirect(base_url("admin/users/change-password/{$id}"));
    }

    public function destroy($id)
    {
        // Check if auth user exists
        $user = $this->User->findById($id);
        if (!$user) {
            $this->session->set_flashdata('error', "<div class='alert alert-danger'><strong>Error: </strong> User doesn't exists.</div>");

            return redirect(base_url("admin/users"));
        }

        // Check if auth user is the selected id
        if ($id === $this->session->userdata("auth_user")->id) {
            $this->session->set_flashdata('error', "<div class='alert alert-danger'><strong>Error: </strong> Cannot delete user.</div>");

            return redirect(base_url("admin/users"));
        }

        $this->User->deleteUser($id);
        $this->session->set_flashdata('success', '<div class="alert alert-success"><strong>Success!</strong> User deleted.</div>');

        return redirect(base_url("admin/users"));
    }
}
