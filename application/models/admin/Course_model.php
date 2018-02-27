<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Ion Auth Model
 * @property Bcrypt $bcrypt The Bcrypt library
 * @property Ion_auth $ion_auth The Ion_auth library
 */
class Course_model extends CI_Model{
	public $title;
    public $content;
    public $date;
    public 		$table='course';


    public function __construct()
	{
		// $this->load->database();
		// $this->config->load('ion_auth', TRUE);
		//  $this->load->helper('cookie');
		//  $this->load->helper('date');
		//  $this->lang->load('ion_auth');  
	}

    public function get_ten_entries()
    {
            $query = $this->db->get($this->table);
            return $query->result();
    }

    public function create($course_code = FALSE, $course_name = '', $additional_data = array())
	{
		
		// bail if the group name was not passed
		if(!$course_code)
		{
			$this->set_error('group_name_required');
			return FALSE;
		}

		// bail if the group name already exists
		$existing_group = $this->db->get_where($this->table, array('course_code' => $course_code))->num_rows();
		if($existing_group !== 0)
		{
			$this->ion_auth->set_error('group_already_exists');
			return FALSE;
		}

		$data = array('course_code'=>$course_code,'course_name'=>$course_name);

		// filter out any data passed that doesnt have a matching column in the groups table
		// and merge the set group data and the additional data
		if (!empty($additional_data)) $data = array_merge($this->_filter_data($this->table, $additional_data), $data);

		$this->ion_auth->trigger_events('extra_group_set');

		// insert the new group
		$this->db->insert($this->table, $data);
		$group_id = $this->db->insert_id($this->table . '_id_seq');

		// report success
		$this->ion_auth->set_message('group_creation_successful');
		// return the brand new group id
		return $group_id;
	}


	/**
	 * group
	 *
	 * @param int|string|null $id
	 *
	 * @return static
	 * @author Ben Edmunds
	 */
	public function course_by_id($id = NULL)
	{
		

		if(isset($id)) {
	     $query = $this->db->get_where($this->table, array('id' => $id));
	    return $query->row();
	    } 
	}



}