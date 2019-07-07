<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Person extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('admin/person');
        $this->load->model('admin/Person_model', 'm_person');

        /* Title Page :: Common */
		$this->page_title->push(lang('menu_person'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_person'), 'admin/person');
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

            $this->data['courses'] = $this->m_person->get_ten_entries();
            //$this->data['groups'] = $this->ion_auth->groups()->result();

            /* Load Template */
            $this->template->admin_render('admin/person/index', $this->data);
        }
    }


	public function create()
	{
		if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_course_create'), 'admin/person/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

		/* Validate form input */
		$this->form_validation->set_rules('person_code', 'lang:person_code', 'required|alpha_dash');
		$this->form_validation->set_rules('person_name', 'lang:person_name', 'required|alpha_dash');

		if ($this->form_validation->run() == TRUE)
		{
			$new_course_id = $this->m_person->create($this->input->post('person_code'), $this->input->post('person_name'));
			if ($new_course_id)
			{
				$this->session->set_flashdata('message', $this->lang->line('person_add_saved'));
				redirect('person');
					//$this->index();
			}
			else{
				$this->session->set_flashdata('message', $this->lang->line('person_error'));
				redirect('person');
			}
		}
		else
		{
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['person_code'] = array(
				'name'  => 'person_code',
				'id'    => 'person_code',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('person_code')
			);
			$this->data['person_name'] = array(
				'name'  => 'person_name',
				'id'    => 'person_name',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('person_name')
			);

            /* Load Template */
            $this->template->admin_render('admin/person/create', $this->data);
		}
	}





	public function edit($id)
	{
		if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin() OR ! $id OR empty($id))
		{
			redirect('auth', 'refresh');
		}

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_person_edit'), 'admin/person/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */

		$courses = $this->m_person->course_by_id($id);

		/* Validate form input */
        $this->form_validation->set_rules('person_code', $this->lang->line('person_code'), 'required|alpha_dash');
        $this->form_validation->set_rules('person_name', $this->lang->line('person_name'), 'required');

		if (isset($_POST) && ! empty($_POST))
		{
			if ($this->form_validation->run() == TRUE)
			{
				$course_update = $this->m_person->update($id, $_POST['person_code'], $_POST['person_name']);

				if ($course_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('course_edit_saved'));

                   
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}

				redirect('person');
				//$this->index();
			}
		}


		    $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['person_code'] = array(
				'name'  => 'person_code',
				'id'    => 'person_code',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('person_code',$courses->person_code),
				'readonly' => 'readonly'
			);
			$this->data['person_name'] = array(
				'name'  => 'person_name',
				'id'    => 'person_name',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('person_name',$courses->person_name)
			);

            /* Load Template */
            $this->template->admin_render('admin/person/edit', $this->data);
	}


	public function delete($id)
	{
		if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin() OR ! $id OR empty($id))
		{
			redirect('auth', 'refresh');
		}

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_person_delete'), 'admin/person/delete');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */

		$courses = $this->m_person->course_by_id($id);

		/* Validate form input */
        $this->form_validation->set_rules('person_code', $this->lang->line('person_code'), 'required|alpha_dash');
        $this->form_validation->set_rules('person_name', $this->lang->line('person_name'), 'required');

		if (isset($_POST) && ! empty($_POST))
		{
			if ($this->form_validation->run() == TRUE)
			{
				$course_deleted = $this->m_person->delete($id);

				if ($course_deleted)
				{
					$this->session->set_flashdata('message', $this->lang->line('course_deleted'));

                   
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}

				redirect('person');
				//$this->index();
			}
		}


		    $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['person_code'] = array(
				'name'  => 'person_code',
				'id'    => 'person_code',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('person_code',$courses->person_code),
				'readonly' => 'readonly'
			);
			$this->data['person_name'] = array(
				'name'  => 'person_name',
				'id'    => 'person_name',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('person_name',$courses->person_name),
				'readonly' => 'readonly'
			);

            /* Load Template */
            $this->template->admin_render('admin/person/delete', $this->data);
	}
}
