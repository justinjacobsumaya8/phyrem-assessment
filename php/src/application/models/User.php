<?php

class User extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->library('password');
    }

    public function loginUser($data)
    {
        $this->db->select('*');
        $this->db->where('user_name', $data['username']);
        $this->db->from('users');
        $this->db->limit(1);

        $user = $this->db->get()->row();

        if ($this->password->verifyHash($data['password'], $user->user_password)) {
            unset($user->user_password);
            return $user;
        }

        return false;
    }

    public function findById($userId)
    {
        $this->db->select('*');
        $this->db->where('id', $userId);
        $this->db->from('users');
        $this->db->limit(1);

        return $this->db->get()->row();
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
        $records = $this->db->get('users')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        $records = $this->db->get('users')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('id, user_name, user_type, datetime_added, datetime_modified');
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('users')->result();

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

    public function checkExists($username)
    {
        $this->db->where('user_name', $username);
        $query = $this->db->get('users');

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }

    public function deleteUser($id)
    {
        $this->db->delete("users", ["id" => $id]);
    }

    public function create($data)
    {
        $data['user_password'] = $this->password->hash($data['user_password']);
        $this->db->insert('users', $data);

        return $this->db->insert_id();
    }

    public function update($data, $id)
    {
        if (isset($data['user_password'])) {
            $data['user_password'] = $this->password->hash($data['user_password']);
        }
        $this->db->update("users", $data, ["id" => $id]);
    }
}
