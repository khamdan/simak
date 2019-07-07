<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Ion Auth Model
 * @property Bcrypt $bcrypt The Bcrypt library
 * @property Ion_auth $ion_auth The Ion_auth library
 */
class Person_model extends CI_Model{
	public $title;
    public $content;
    public $date;
    public 		$table='person';


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

    public function create($person_code = FALSE, $person_name = '', $additional_data = array())
	{
		
		// bail if the group name was not passed
		if(!$person_code)
		{
			$this->set_error('group_name_required');
			return FALSE;
		}

		// bail if the group name already exists
		$existing_group = $this->db->get_where($this->table, array('person_code' => $person_code))->num_rows();
		if($existing_group !== 0)
		{
			$this->ion_auth->set_error('group_already_exists');
			return FALSE;
		}

		$data = array('person_code'=>$person_code,'person_name'=>$person_name);

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
	 * update_group
	 *
	 * @param int|string|bool $group_id
	 * @param string|bool     $group_name
	 * @param string|array    $additional_data IMPORTANT! This was string type $description; strings are still allowed
	 *                                         to maintain backward compatibility. New projects should pass an array of
	 *                                         data instead.
	 *
	 * @return bool
	 * @author aditya menon
	 */
	public function update($id,$person_code = FALSE, $person_name = '', $additional_data = array())
	{
		if (empty($person_code))
		{
			return FALSE;
		}

		$data = array();

		if (!empty($person_name))
		{
			// we are changing the name, so do some checks

			// bail if the group name already exists
			$existing_course = $this->db->get_where($this->table, array('id' => $id))->row();
			if (isset($existing_course->id) && $existing_course->id != $id)
			{
				$this->set_error('group_already_exists');
				return FALSE;
			}

			$data['person_code'] = $person_code;
			$data['person_name'] = $person_name;
		}



		$this->db->update($this->table, $data, array('id' => $id));

		$this->ion_auth->set_message('group_update_successful');

		return TRUE;
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

	public function delete($id = NULL)
	{
		

		if(isset($id)) {
	     $query = $this->db->delete($this->table, array('id' => $id));
	    return $query;
	    } 
	}

			







}