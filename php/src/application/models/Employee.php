<?php

class Employee extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get()
    {
        $query = $this->db->get('employees');
        return $query->result_array();
    }

    public function getRecords($postData = null)
    {
        $response = array();
        if (!$postData) {
            return;
        }

        ## Read value
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
        $records = $this->db->get('employees')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        $records = $this->db->get('employees')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('employees')->result();

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

    public function createEmployee($data)
    {
        $data = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'created_by' => $this->session->userdata("auth_user")->id
        ];

        $this->db->insert('employees', $data);

        return $this->db->insert_id();
    }

    public function findById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('employees')->row();

        if ($query) {
            return $query;
        }

        return false;
    }

    public function checkExists($data)
    {
        $this->db->where('first_name', $data['first_name']);
        $this->db->where('last_name', $data['last_name']);
        $query = $this->db->get('employees');

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }

    public function updateEmployee($data, $id)
    {
        $this->db->update("employees", $data, ["id" => $id]);
    }

    public function deleteEmployee($id)
    {
        $this->db->delete("employees", ["id" => $id]);
    }
}
