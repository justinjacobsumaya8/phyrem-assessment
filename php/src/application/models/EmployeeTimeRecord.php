<?php

class EmployeeTimeRecord extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get()
    {
        $query = $this->db->get('employee_time_records');
        return $query->result_array();
    }

    public function getRecords($postData = null)
    {
        $response = array();
        if (!$postData) {
            return;
        }

        ## Read value
        $page = isset($postData['page']) ? $postData['page'] : "";
        $currentDate = isset($postData['current_date']) ? $postData['current_date'] : "";
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (first_name like '%" . $searchValue . "%' or last_name like '%" . $searchValue . "%' ) ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        if ($page === "scan-qr") {
            $this->db->where('date_added', $currentDate);
        }
        $records = $this->db->get('employee_time_records')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if ($page === "scan-qr") {
            $this->db->where('date_added', $currentDate);
        }
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        $this->db->join('employees', 'employees.id = employee_time_records.employee_id', 'left');
        $records = $this->db->get('employee_time_records')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('employee_time_records.*, employees.first_name, employees.last_name');
        if ($page === "scan-qr") {
            $this->db->where('date_added', $currentDate);
        }
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        $this->db->join('employees', 'employees.id = employee_time_records.employee_id', 'left');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('employee_time_records')->result();

        $data = array();

        foreach ($records as $record) {
            $data[] = $record;
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;
    }

    public function save($data)
    {
        $currentDate = date('Y-m-d', strtotime($data['current_datetime']));
        $currentTime = date('H:i:s', strtotime($data['current_datetime']));

        $data = [
            'employee_id' => $data['employee_id'],
            'user_id' => $this->session->userdata("auth_user")->id,
            'date_added' => $currentDate,
        ];

        $this->db->where('employee_id', $data['employee_id']);
        $this->db->where('date_added', $currentDate);

        // Create new if didn't exists
        $employeeTimeRecord = $this->db->get('employee_time_records')->row();
        if (!$employeeTimeRecord) {
            $this->db->insert('employee_time_records', $data);

            $recordId = $this->db->insert_id();
            $this->db->where('id', $recordId);

            $employeeTimeRecord = $this->db->get('employee_time_records')->row();
        }

        // Throw error if time in and time out already have value
        if ($employeeTimeRecord->time_in && $employeeTimeRecord->time_out) {
            throw new Exception("Your time in and out is done for today.");
        }

        $updateData = [];
        if (!$employeeTimeRecord->time_in) {
            $updateData['time_in'] = $currentTime;
        } else if (!$employeeTimeRecord->time_out) {
            $updateData['time_out'] = $currentTime;
        }

        $this->db->update("employee_time_records", $updateData, ["id" => $employeeTimeRecord->id]);

        $this->db->where('id', $employeeTimeRecord->id);

        return $this->db->get('employee_time_records')->row();
    }
}
