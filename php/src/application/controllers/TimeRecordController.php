<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TimeRecordController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->model("Authentication");
    }

    public function index()
    {
        $this->load->view('admin/time-records/index');
    }

    public function list()
    {
        $postData = $this->input->post();
        $data = $this->EmployeeTimeRecord->getRecords($postData);
        echo json_encode($data);
    }

    public function scanQR()
    {
        $this->load->view('admin/time-records/read-qr');
    }

    public function processQR()
    {
        $this->form_validation->set_rules('qr_value', 'QR ID', 'trim|required');
        $this->form_validation->set_rules('current_datetime', 'Current Datetime', 'trim|required');

        // Validate Form
        if (!$this->form_validation->run()) {
            $result = json_encode([
                "message" => validation_errors()
            ]);
            return $this->output
                ->set_status_header('422')
                ->set_content_type('application/json')
                ->set_output($result);
        }

        $qrValue = $this->input->post('qr_value');
        $qrName = "employee-qr";

        // Search if qr name exists
        if (!preg_match("/{$qrName}/i", $qrValue)) {
            $result = json_encode([
                "message" => "Incorrect QR"
            ]);
            return $this->output
                ->set_status_header('422')
                ->set_content_type('application/json')
                ->set_output($result);
        }

        $currentDatetime = $this->input->post('current_datetime');
        $employeeId = str_replace("{$qrName}-", "", $qrValue);

        // Check if employee exist
        $employee = $this->Employee->findById($employeeId);
        if (!$employee) {
            $result = json_encode([
                "message" => "Employee doesn't exist"
            ]);
            return $this->output
                ->set_status_header('422')
                ->set_content_type('application/json')
                ->set_output($result);
        }

        $data = [
            "employee_id" => $employeeId,
            "current_datetime" => $currentDatetime
        ];

        try {
            $employeeTimeRecord = $this->EmployeeTimeRecord->save($data);
            echo json_encode([
                "data" => $employeeTimeRecord
            ]);
        } catch (\Throwable $th) {
            $result = json_encode([
                "message" => $th->getMessage()
            ]);

            return $this->output
                ->set_status_header('422')
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }
}
