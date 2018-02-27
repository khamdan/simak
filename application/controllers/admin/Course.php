<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('admin/course');
        $this->load->model('admin/Course_model', 'course');

        /* Title Page :: Common */
        $this->page_title->push(lang('menu_course'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_course'), 'admin/course');
    }


	public function index()
	{
        if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            $this->data['courses'] = $this->course->get_ten_entries();
            //$this->data['groups'] = $this->ion_auth->groups()->result();

            /* Load Template */
            $this->template->admin_render('admin/course/index', $this->data);
        }
    }


	public function create()
	{
		if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_course_create'), 'admin/course/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

		/* Validate form input */
		$this->form_validation->set_rules('course_code', 'lang:course_code', 'required|alpha_dash');
		$this->form_validation->set_rules('course_name', 'lang:course_name', 'required|alpha_dash');

		if ($this->form_validation->run() == TRUE)
		{
			$new_group_id = $this->course->create($this->input->post('course_code'), $this->input->post('course_name'));
			if ($new_group_id)
			{
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('admin/course', 'refresh');
			}
		}
		else
		{
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['course_code'] = array(
				'name'  => 'course_code',
				'id'    => 'course_code',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('course_code')
			);
			$this->data['course_name'] = array(
				'name'  => 'course_name',
				'id'    => 'course_name',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('course_name')
			);

            /* Load Template */
            $this->template->admin_render('admin/course/create', $this->data);
		}
	}


	public function delete()
	{
        if ( ! $this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }
        elseif ( ! $this->ion_auth->is_admin())
		{
            return show_error('You must be an administrator to view this page.');
        }
        else
        {
            $this->load->view('admin/groups/delete');
        }
	}


	public function edit($id)
	{
		if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin() OR ! $id OR empty($id))
		{
			redirect('auth', 'refresh');
		}

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_course_edit'), 'admin/course/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
		$courses = $this->course->course_by_id($id);

		/* Validate form input */
        $this->form_validation->set_rules('course_code', $this->lang->line('course_code'), 'required|alpha_dash');
        $this->form_validation->set_rules('course_name', $this->lang->line('course_name'), 'required|alpha_dash');

		if (isset($_POST) && ! empty($_POST))
		{
			if ($this->form_validation->run() == TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if ($group_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));

                    /* IN TEST */
                    $this->db->update('groups', array('bgcolor' => $_POST['group_bgcolor']), 'id = '.$id);
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}

				redirect('admin/course', 'refresh');
			}
		}


		    $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['course_code'] = array(
				'name'  => 'course_code',
				'id'    => 'course_code',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('course_code')
			);
			$this->data['course_name'] = array(
				'name'  => 'course_name',
				'id'    => 'course_name',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('course_name')
			);

            /* Load Template */
            $this->template->admin_render('admin/course/edit', $this->data);
	}
}
