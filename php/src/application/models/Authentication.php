<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        if ($this->session->has_userdata("auth_user")) {
            // fetch new data from db
            $authUser = $this->session->userdata("auth_user");
            $user = (new User)->findById($authUser->id);
            unset($user->user_password);

            $this->session->set_userdata("auth_user", $user);

            return;
        }

        $this->session->set_flashdata('error', '<div class="alert alert-danger"><strong>Error: </strong><p class="mb-0 mt-2">Please login first</p></div>');

        return redirect(base_url("/"));
    }
}
