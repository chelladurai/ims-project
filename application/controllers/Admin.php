<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 *	@author 	: Optimum Linkup Universal Concepts
 *	date		: 27 June, 2016
 *	Optimum Linkup Universal Concepts
 *	http://optimumlinkup.com.ng/school/Optimum Linkup Universal Concepts
 *	optimumproblemsolver@gmail.com
 */

class Admin extends CI_Controller
{
    
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');
		
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($this->session->userdata('admin_login') == 1)
            redirect(base_url() . 'index.php?admin/dashboard', 'refresh');
    }
    
    /***ADMIN DASHBOARD***/
    function dashboard()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('admin_dashboard');
        $this->load->view('backend/index', $page_data);
    }
    
    /****MANAGE STUDENTS CLASSWISE*****/
	function student_add()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
		$page_data['page_name']  = 'student_add';
		$page_data['page_title'] = get_phrase('add_student');
		$this->load->view('backend/index', $page_data);
	}
	
	function student_bulk_add($param1 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
		if ($param1 == 'import_excel')
		{
			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_import.xlsx');
			// Importing excel sheet for bulk student uploads

			include 'simplexlsx.class.php';
			
			$xlsx = new SimpleXLSX('uploads/student_import.xlsx');
			
			list($num_cols, $num_rows) = $xlsx->dimension();
			$f = 0;
			foreach( $xlsx->rows() as $r ) 
			{
				// Ignore the inital name row of excel file
				if ($f == 0)
				{
					$f++;
					continue;
				}
				for( $i=0; $i < $num_cols; $i++ )
				{
					if ($i == 0)	    $data['name']			=	$r[$i];
					else if ($i == 1)	$data['birthday']		=	$r[$i];
					else if ($i == 2)	$data['sex']		    =	$r[$i];
					else if ($i == 3)	$data['address']		=	$r[$i];
					else if ($i == 4)	$data['phone']			=	$r[$i];
					else if ($i == 5)	$data['email']			=	$r[$i];
					else if ($i == 6)	$data['password']		=	$r[$i];
					else if ($i == 7)	$data['roll']			=	$r[$i];
				}
				$data['class_id']	=	$this->input->post('class_id');
				
				$this->db->insert('student' , $data);
				//print_r($data);
			}
			redirect(base_url() . 'index.php?admin/student_information/' . $this->input->post('class_id'), 'refresh');
		}
		$page_data['page_name']  = 'student_bulk_add';
		$page_data['page_title'] = get_phrase('add_bulk_student');
		$this->load->view('backend/index', $page_data);
	}
	
	function student_information($class_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
			
		$page_data['page_name']  	= 'student_information';
		$page_data['page_title'] 	= get_phrase('student_information'). " - ".get_phrase('class')." : ".
											$this->crud_model->get_class_name($class_id);
		$page_data['class_id'] 	= $class_id;
		$this->load->view('backend/index', $page_data);
	}

    function student_marksheet($student_id = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        $class_id     = $this->db->get_where('student' , array('student_id' => $student_id))->row()->class_id;
        $student_name = $this->db->get_where('student' , array('student_id' => $student_id))->row()->name;
        $class_name   = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
        $page_data['page_name']  =   'student_marksheet';
        $page_data['page_title'] =   get_phrase('marksheet_for') . ' ' . $student_name . ' (' . get_phrase('class') . ' ' . $class_name . ')';
        $page_data['student_id'] =   $student_id;
        $page_data['class_id']   =   $class_id;
        $this->load->view('backend/index', $page_data);
    }

    function student_marksheet_print_view($student_id , $exam_id) {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        $class_id     = $this->db->get_where('student' , array('student_id' => $student_id))->row()->class_id;
        $class_name   = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;

        $page_data['student_id'] =   $student_id;
        $page_data['class_id']   =   $class_id;
        $page_data['exam_id']    =   $exam_id;
        $this->load->view('backend/admin/student_marksheet_print_view', $page_data);
    }
	
    function student($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['name']           = $this->input->post('name');
            $data['birthday']       = $this->input->post('birthday');
            $data['sex']            = $this->input->post('sex');
            $data['address']        = $this->input->post('address');
            $data['phone']          = $this->input->post('phone');
            $data['email']          = $this->input->post('email');
            $data['password']       = $this->input->post('password');
            $data['class_id']       = $this->input->post('class_id');
            if ($this->input->post('section_id') != '') {
                $data['section_id'] = $this->input->post('section_id');
            }
            $data['parent_id']      = $this->input->post('parent_id');
            $data['dormitory_id']   = $this->input->post('dormitory_id');
            $data['transport_id']   = $this->input->post('transport_id');
            $data['roll']           = $this->input->post('roll');
            $this->db->insert('student', $data);
            $student_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $student_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('student', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/student_add/' . $data['class_id'], 'refresh');
        }
        if ($param2 == 'do_update') {
            $data['name']           = $this->input->post('name');
            $data['birthday']       = $this->input->post('birthday');
            $data['sex']            = $this->input->post('sex');
            $data['address']        = $this->input->post('address');
            $data['phone']          = $this->input->post('phone');
            $data['email']          = $this->input->post('email');
            $data['class_id']       = $this->input->post('class_id');
            $data['section_id']     = $this->input->post('section_id');
            $data['parent_id']      = $this->input->post('parent_id');
            $data['dormitory_id']   = $this->input->post('dormitory_id');
            $data['transport_id']   = $this->input->post('transport_id');
            $data['roll']           = $this->input->post('roll');
            
            $this->db->where('student_id', $param3);
            $this->db->update('student', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $param3 . '.jpg');
            $this->crud_model->clear_cache();
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/student_information/' . $param1, 'refresh');
        } 
		
        if ($param2 == 'delete') {
            $this->db->where('student_id', $param3);
            $this->db->delete('student');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/student_information/' . $param1, 'refresh');
        }
    }
     /****MANAGE PARENTS CLASSWISE*****/
    function parent($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['name']        			= $this->input->post('name');
            $data['email']       			= $this->input->post('email');
            $data['password']    			= $this->input->post('password');
            $data['phone']       			= $this->input->post('phone');
            $data['address']     			= $this->input->post('address');
            $data['profession']  			= $this->input->post('profession');
            $this->db->insert('parent', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('parent', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/parent/', 'refresh');
        }
        if ($param1 == 'edit') {
            $data['name']                   = $this->input->post('name');
            $data['email']                  = $this->input->post('email');
            $data['phone']                  = $this->input->post('phone');
            $data['address']                = $this->input->post('address');
            $data['profession']             = $this->input->post('profession');
            $this->db->where('parent_id' , $param2);
            $this->db->update('parent' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/parent/', 'refresh');
        }
        if ($param1 == 'delete') {
            $this->db->where('parent_id' , $param2);
            $this->db->delete('parent');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/parent/', 'refresh');
        }
        $page_data['page_title'] 	= get_phrase('all_parents');
        $page_data['page_name']  = 'parent';
        $this->load->view('backend/index', $page_data);
    }
	
    
    /****MANAGE TEACHERS*****/
    function teacher($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['password']    = $this->input->post('password');
            $this->db->insert('teacher', $data);
            $teacher_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $teacher_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('teacher', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/teacher/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('teacher_id', $param2);
            $this->db->update('teacher', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/teacher/', 'refresh');
        } else if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_teacher_id'] = $param2;
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('teacher', array(
                'teacher_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('teacher_id', $param2);
            $this->db->delete('teacher');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/teacher/', 'refresh');
        }
        $page_data['teachers']   = $this->db->get('teacher')->result_array();
        $page_data['page_name']  = 'teacher';
        $page_data['page_title'] = get_phrase('manage_teacher');
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	 /****MANAGE ALUMNI*****/
    function alumni($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
			$data['sex']         = $this->input->post('sex');
 			$data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['address']     = $this->input->post('address');
            $data['profession']        = $this->input->post('profession');
            $data['marital_status']    = $this->input->post('marital_status');
			$data['g_year']         = $this->input->post('g_year');
            $data['club']    	 	= $this->input->post('club');
            $data['interest']       = $this->input->post('interest');
			
			
            $this->db->insert('alumni', $data);
            $alumni_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/alumni_image/' . $alumni_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('alumni', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/alumni', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
			$data['sex']         = $this->input->post('sex');
 			$data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['address']     = $this->input->post('address');
            $data['profession']        = $this->input->post('profession');
            $data['marital_status']    = $this->input->post('marital_status');
			$data['g_year']         = $this->input->post('g_year');
            $data['club']    	 	= $this->input->post('club');
            $data['interest']       = $this->input->post('interest');
            
            $this->db->where('alumni_id', $param2);
            $this->db->update('alumni', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/alumni_image/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/alumni', 'refresh');
        } else if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_alumni_id'] = $param2;
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('alumni', array(
                'alumni_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('alumni_id', $param2);
            $this->db->delete('alumni');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/alumni', 'refresh');
        }
        $page_data['alumni']   = $this->db->get('alumni')->result_array();
        $page_data['page_name']  = 'alumni';
        $page_data['page_title'] = get_phrase('manage_alumni');
        $this->load->view('backend/index', $page_data);
    }
	
	
	/****MANAGE TEACHERS*****/
    function teacher_id_card($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['password']    = $this->input->post('password');
            $this->db->insert('teacher', $data);
            $teacher_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $teacher_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('teacher', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/teacher_id_card/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('teacher_id', $param2);
            $this->db->update('teacher', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/teacher_id_card/', 'refresh');
        } else if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_teacher_id'] = $param2;
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('teacher', array(
                'teacher_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('teacher_id', $param2);
            $this->db->delete('teacher');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/teacher_idcard/', 'refresh');
        }
        $page_data['teachers']   = $this->db->get('teacher')->result_array();
        $page_data['page_name']  = 'teacher_id_card';
        $page_data['page_title'] = get_phrase('manage_teacher_idcard');
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	
	/****MANAGE TEACHERS generateidcard*****/
    function generateidcard($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['password']    = $this->input->post('password');
            $this->db->insert('teacher', $data);
            $teacher_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $teacher_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('teacher', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/generateidcard/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('teacher_id', $param2);
            $this->db->update('teacher', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/generateidcard/', 'refresh');
        } else if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_teacher_id'] = $param2;
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('teacher', array(
                'teacher_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('teacher_id', $param2);
            $this->db->delete('teacher');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/generateidcard/', 'refresh');
        }
        $page_data['teachers']   = $this->db->get('teacher')->result_array();
        $page_data['page_name']  = 'teacher_idcard';
        $page_data['page_title'] = get_phrase('teacher_idcard');
        $this->load->view('backend/index', $page_data);
    }
	
	
	 
	/****MANAGE LIBRARIANS*****/
    function librarian($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['password']    = $this->input->post('password');
            $this->db->insert('librarian', $data);
            $librarian_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/librarian_image/' . $librarian_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('librarian', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/librarian/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('librarian_id', $param2);
            $this->db->update('librarian', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/librarian_image/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/librarian/', 'refresh');
        } else if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_librarian_id'] = $param2;
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('librarian', array(
                'librarian_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('librarian_id', $param2);
            $this->db->delete('librarian');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/librarian/', 'refresh');
        }
        $page_data['librarians']   = $this->db->get('librarian')->result_array();
        $page_data['page_name']  = 'librarian';
        $page_data['page_title'] = get_phrase('manage_librarian');
        $this->load->view('backend/index', $page_data);
    }
	
	
	/****MANAGE LIBRARIANS ID CARDS*****/
    function librarian_id_card($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['password']    = $this->input->post('password');
            $this->db->insert('librarian', $data);
            $librarian_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/librarian_image/' . $librarian_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('librarian', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/librarian_id_card/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('librarian_id', $param2);
            $this->db->update('librarian', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/librarian_image/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/librarian_id_card/', 'refresh');
        } else if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_librarian_id'] = $param2;
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('librarian', array(
                'librarian_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('librarian_id', $param2);
            $this->db->delete('librarian');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/librarian_id_card/', 'refresh');
        }
        $page_data['librarians']   = $this->db->get('librarian')->result_array();
        $page_data['page_name']  = 'librarian_id_card';
        $page_data['page_title'] = get_phrase('manage_librarian_ID_card');
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	
	/****MANAGE BANNER *****/
    function banar($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['b_namea']        = $this->input->post('b_namea');
            $data['b_nameb']    = $this->input->post('b_nameb');
			
            $this->db->insert('banar', $data);
            $banar_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/banner_image/' . $banar_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/banar', 'refresh');
        }
        if ($param1 == 'do_update') {
             $data['b_namea']        = $this->input->post('b_namea');
            $data['b_nameb']    = $this->input->post('b_nameb');
            
            $this->db->where('banar_id', $param2);
            $this->db->update('banar', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/banner_image/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/banar', 'refresh');
        } else if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_banar_id'] = $param2;
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('banar', array(
                'banar_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('banar_id', $param2);
            $this->db->delete('banar');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/banar', 'refresh');
        }
        $page_data['banars']   = $this->db->get('banar')->result_array();
        $page_data['page_name']  = 'banar';
        $page_data['page_title'] = get_phrase('manage_banar');
        $this->load->view('backend/index', $page_data);
    }
	
	
	  // ACADEMIC SYLLABUS
    function academic_syllabus($class_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        // detect the first class
        if ($class_id == '')
            $class_id           =   $this->db->get('class')->first_row()->class_id;

        $page_data['page_name']  = 'academic_syllabus';
        $page_data['page_title'] = get_phrase('academic_syllabus');
        $page_data['class_id']   = $class_id;
        $this->load->view('backend/index', $page_data);
    }

    function upload_academic_syllabus()
    {
        $data['academic_syllabus_code'] =   substr(md5(rand(0, 1000000)), 0, 7);
        $data['title']                  =   $this->input->post('title');
        $data['description']            =   $this->input->post('description');
        $data['class_id']               =   $this->input->post('class_id');
        $data['subject_id']             =   $this->input->post('subject_id');
        $data['uploader_type']          =   $this->session->userdata('login_type');
        $data['uploader_id']            =   $this->session->userdata('login_user_id');
        $data['session']                   =   $this->db->get_where('settings',array('type'=>'session'))->row()->description;
        $data['timestamp']              =   strtotime(date("Y-m-d H:i:s"));
        //uploading file using codeigniter upload library
        $files = $_FILES['file_name'];
        $this->load->library('upload');
        $config['upload_path']   =  'uploads/syllabus/';
        $config['allowed_types'] =  '*';
        $_FILES['file_name']['name']     = $files['name'];
        $_FILES['file_name']['type']     = $files['type'];
        $_FILES['file_name']['tmp_name'] = $files['tmp_name'];
        $_FILES['file_name']['size']     = $files['size'];
        $this->upload->initialize($config);
        $this->upload->do_upload('file_name');

        $data['file_name'] = $_FILES['file_name']['name'];

        $this->db->insert('academic_syllabus', $data);
        $this->session->set_flashdata('flash_message' , get_phrase('syllabus_uploaded'));
        redirect(base_url() . 'index.php?admin/academic_syllabus/' . $data['class_id'] , 'refresh');

    }
	
	 		function delete ($id) {
            $this->db->where('id', $id);
            $this->db->delete('academic_syllabus');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/academic_syllabus', 'refresh');
        }
	
    function download_academic_syllabus($academic_syllabus_code)
    {
        $file_name = $this->db->get_where('academic_syllabus', array(
            'academic_syllabus_code' => $academic_syllabus_code
        ))->row()->file_name;
        $this->load->helper('download');
        $data = file_get_contents("uploads/syllabus/" . $file_name);
        $name = $file_name;

        force_download($name, $data);
    }

	
	/****MANAGE ACCOUNTANT*****/
    function accountant($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['password']    = $this->input->post('password');
            $this->db->insert('accountant', $data);
            $accountant_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/accountant_image/' . $accountant_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('accountant', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/accountant/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('accountant_id', $param2);
            $this->db->update('accountant', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/accountant_image/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/accountant/', 'refresh');
        } else if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_accountant_id'] = $param2;
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('accountant', array(
                'accountant_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('accountant_id', $param2);
            $this->db->delete('accountant');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/accountant/', 'refresh');
        }
        $page_data['accountants']   = $this->db->get('accountant')->result_array();
        $page_data['page_name']  = 'accountant';
        $page_data['page_title'] = get_phrase('manage_accountant');
        $this->load->view('backend/index', $page_data);
    }
	
	
	/****MANAGE ACCOUNTANT*****/
    function accountant_id_card($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['password']    = $this->input->post('password');
            $this->db->insert('accountant', $data);
            $accountant_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/accountant_image/' . $accountant_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('accountant', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/accountant_id_card/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('accountant_id', $param2);
            $this->db->update('accountant', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/accountant_image/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/accountant_id_card/', 'refresh');
        } else if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_accountant_id'] = $param2;
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('accountant', array(
                'accountant_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('accountant_id', $param2);
            $this->db->delete('accountant');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/accountant_id_card/', 'refresh');
        }
        $page_data['accountants']   = $this->db->get('accountant')->result_array();
        $page_data['page_name']  = 'accountant_id_card';
        $page_data['page_title'] = get_phrase('manage_accountant');
        $this->load->view('backend/index', $page_data);
    }
	
	
	/****MANAGE HOSTEL*****/
    function hostel($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['password']    = $this->input->post('password');
            $this->db->insert('hostel', $data);
            $hostel_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/hostel_image/' . $hostel_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('hostel', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/hostel/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('hostel_id', $param2);
            $this->db->update('hostel', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/hostel_image/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/hostel/', 'refresh');
        } else if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_hostel_id'] = $param2;
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('hostel', array(
                'hostel_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('hostel_id', $param2);
            $this->db->delete('hostel');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/hostel/', 'refresh');
        }
        $page_data['hostels']   = $this->db->get('hostel')->result_array();
        $page_data['page_name']  = 'hostel';
        $page_data['page_title'] = get_phrase('manage_hostel');
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	/****MANAGE HOSTE ID CARD*****/
    function hostel_id_card($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['password']    = $this->input->post('password');
            $this->db->insert('hostel', $data);
            $hostel_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/hostel_image/' . $hostel_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('hostel', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/hostel_id_card/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('hostel_id', $param2);
            $this->db->update('hostel', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/hostel_image/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/hostel_id_card/', 'refresh');
        } else if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_hostel_id'] = $param2;
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('hostel', array(
                'hostel_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('hostel_id', $param2);
            $this->db->delete('hostel');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/hostel_id_card/', 'refresh');
        }
        $page_data['hostels']   = $this->db->get('hostel')->result_array();
        $page_data['page_name']  = 'hostel_id_card';
        $page_data['page_title'] = get_phrase('manage_hostel_id_card');
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	// STUDENT PROMOTION
    function student_promotion($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

       
        $page_data['page_title']    = get_phrase('student_promotion');
        $page_data['page_name']  = 'student_promotion';
        $this->load->view('backend/index', $page_data);
    }

   
	
	
	/****MANAGE HOSTE ID CARD*****/
    function generate_hostel_id_card($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['password']    = $this->input->post('password');
            $this->db->insert('hostel', $data);
            $hostel_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/hostel_image/' . $hostel_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('hostel', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/generate_hostel_id_card/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('hostel_id', $param2);
            $this->db->update('hostel', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/hostel_image/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/generate_hostel_id_card/', 'refresh');
        } else if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['generate_hostel_id_card'] = $param2;
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('hostel', array(
                'hostel_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('hostel_id', $param2);
            $this->db->delete('hostel');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/generate_hostel_id_card/', 'refresh');
        }
        $page_data['hostels']   = $this->db->get('hostel')->result_array();
        $page_data['page_name']  = 'hostel_id_card';
        $page_data['page_title'] = get_phrase('generate_hostel_id_card');
        $this->load->view('backend/index', $page_data);
    }
	
	


    
    /****MANAGE SUBJECTS*****/
    function subject($param1 = '', $param2 = '' , $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']       = $this->input->post('name');
            $data['class_id']   = $this->input->post('class_id');
            $data['teacher_id'] = $this->input->post('teacher_id');
            $this->db->insert('subject', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/subject/'.$data['class_id'], 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']       = $this->input->post('name');
            $data['class_id']   = $this->input->post('class_id');
            $data['teacher_id'] = $this->input->post('teacher_id');
            
            $this->db->where('subject_id', $param2);
            $this->db->update('subject', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/subject/'.$data['class_id'], 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('subject', array(
                'subject_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('subject_id', $param2);
            $this->db->delete('subject');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/subject/'.$param3, 'refresh');
        }
		 $page_data['class_id']   = $param1;
        $page_data['subjects']   = $this->db->get_where('subject' , array('class_id' => $param1))->result_array();
        $page_data['page_name']  = 'subject';
        $page_data['page_title'] = get_phrase('manage_subject');
        $this->load->view('backend/index', $page_data);
    }
    
    /****MANAGE CLASSES*****/
    function classes($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']         = $this->input->post('name');
            $data['name_numeric'] = $this->input->post('name_numeric');
            $data['teacher_id']   = $this->input->post('teacher_id');
            $this->db->insert('class', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/classes/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']         = $this->input->post('name');
            $data['name_numeric'] = $this->input->post('name_numeric');
            $data['teacher_id']   = $this->input->post('teacher_id');
            
            $this->db->where('class_id', $param2);
            $this->db->update('class', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/classes/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('class', array(
                'class_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('class_id', $param2);
            $this->db->delete('class');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/classes/', 'refresh');
        }
        $page_data['classes']    = $this->db->get('class')->result_array();
        $page_data['page_name']  = 'class';
        $page_data['page_title'] = get_phrase('manage_class');
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	/****MANAGE SESSION HERE *****/
    function session($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']         = $this->input->post('name');
            $this->db->insert('session', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/session', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']         = $this->input->post('name');
            
            $this->db->where('session_id', $param2);
            $this->db->update('session', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/session', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('session', array(
                'session_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('session_id', $param2);
            $this->db->delete('session');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/session', 'refresh');
        }
        $page_data['sessions']    = $this->db->get('session')->result_array();
        $page_data['page_name']  = 'session';
        $page_data['page_title'] = get_phrase('manage_session');
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	/****MANAGE HELPFUL LINK*****/
    function help_link($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            
			$data['title']         = $this->input->post('title');
            $data['link'] = $this->input->post('link');
            
            $this->db->insert('help_link', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/help_link', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['title']         = $this->input->post('title');
            $data['link'] = $this->input->post('link');
            
            $this->db->where('helplink_id', $param2);
            $this->db->update('help_link', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/help_link', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('help_link', array(
                'helplink_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('helplink_id', $param2);
            $this->db->delete('help_link');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/help_link', 'refresh');
        }
        $page_data['help_links']    = $this->db->get('help_link')->result_array();
        $page_data['page_name']  = 'help_link';
        $page_data['page_title'] = get_phrase('manage_help_link');
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	/****MANAGE CLUB*****/
    function club($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            
			$data['club_name']         = $this->input->post('club_name');
            $data['desc'] = $this->input->post('desc');
            
            $this->db->insert('club', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/club', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['club_name']         = $this->input->post('club_name');
            $data['desc'] = $this->input->post('desc');
            
            $this->db->where('club_id', $param2);
            $this->db->update('club', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/club', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('club', array(
                'club_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('club_id', $param2);
            $this->db->delete('club');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/club', 'refresh');
        }
        $page_data['club']    = $this->db->get('club')->result_array();
        $page_data['page_name']  = 'club';
        $page_data['page_title'] = get_phrase('manage_club');
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	/****MANAGE HELP DESK*****/
    function help_desk($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            
			$data['name']         = $this->input->post('name');
            $data['purpose'] = $this->input->post('purpose');
            $data['content'] = $this->input->post('content');
            
            $this->db->insert('help_desk', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/help_desk', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']         = $this->input->post('name');
            $data['purpose'] = $this->input->post('purpose');
            $data['content'] = $this->input->post('content');
            
            $this->db->where('helpdesk_id', $param2);
            $this->db->update('help_desk', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/help_desk', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('help_desk', array(
                'helpdesk_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('helpdesk_id', $param2);
            $this->db->delete('help_desk');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/help_desk', 'refresh');
        }
        $page_data['help_desk']    = $this->db->get('help_desk')->result_array();
        $page_data['page_name']  = 'help_desk';
        $page_data['page_title'] = get_phrase('manage_help_desk');
        $this->load->view('backend/index', $page_data);
    }
	
	
	/****MANAGE HOLIDAY*****/
    function holiday($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            
			$data['title']         = $this->input->post('title');
            $data['holiday'] = $this->input->post('holiday');
            $data['date'] = $this->input->post('date');
            
            $this->db->insert('holiday', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/holiday', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['title']         = $this->input->post('title');
            $data['holiday'] = $this->input->post('holiday');
            $data['date'] = $this->input->post('date');
            
            $this->db->where('holiday_id', $param2);
            $this->db->update('holiday', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/holiday', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('holiday', array(
                'holiday_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('holiday_id', $param2);
            $this->db->delete('holiday');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/holiday', 'refresh');
        }
        $page_data['holiday']    = $this->db->get('holiday')->result_array();
        $page_data['page_name']  = 'holiday';
        $page_data['page_title'] = get_phrase('manage_holiday');
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	
	/****MANAGE circular*****/
    function circular($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            
			$data['subject']        = $this->input->post('subject');
            $data['ref'] 			= $this->input->post('ref');
            $data['content']	 	= $this->input->post('content');
            $data['date'] 			= $this->input->post('date');
            
            $this->db->insert('circular', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/circular', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['subject']        = $this->input->post('subject');
            $data['ref'] 			= $this->input->post('ref');
            $data['content']	 	= $this->input->post('content');
            $data['date'] 			= $this->input->post('date');
            
            $this->db->where('circular_id', $param2);
            $this->db->update('circular', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/circular', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('circular', array(
                'circular_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('circular_id', $param2);
            $this->db->delete('circular');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/circular', 'refresh');
        }
        $page_data['circular']    = $this->db->get('circular')->result_array();
        $page_data['page_name']  = 'circular';
        $page_data['page_title'] = get_phrase('manage_circular');
        $this->load->view('backend/index', $page_data);
    }
	
	
	/****MANAGE TASK MANAGER*****/
    function task_manager($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            
			$data['name']        		= $this->input->post('name');
            $data['description'] 		= $this->input->post('description');
            $data['priority']	 		= $this->input->post('priority');
            $data['date'] 				= $this->input->post('date');
			$data['user']	 			= $this->input->post('user');
            $data['status'] 			= $this->input->post('status');
            
            $this->db->insert('task_manager', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/task_manager', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        		= $this->input->post('name');
            $data['description'] 		= $this->input->post('description');
            $data['priority']	 		= $this->input->post('priority');
            $data['date'] 				= $this->input->post('date');
			$data['user']	 			= $this->input->post('user');
            $data['status'] 			= $this->input->post('status');
            
            $this->db->where('task_manager_id', $param2);
            $this->db->update('task_manager', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/task_manager', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('task_manager', array(
                'task_manager_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('task_manager_id', $param2);
            $this->db->delete('task_manager');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/task_manager', 'refresh');
        }
        $page_data['task_managers']    = $this->db->get('task_manager')->result_array();
        $page_data['page_name']  = 'task_manager';
        $page_data['page_title'] = get_phrase('manage_task_manager');
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	
	/****MANAGE TODAY'S THOUGHT*****/
    function todays_thought($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            
			$data['thought']         = $this->input->post('thought');
           
            
            $this->db->insert('todays_thought', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/todays_thought', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['thought'] = $this->input->post('thought');
            
            $this->db->where('tthought_id', $param2);
            $this->db->update('todays_thought', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/todays_thought', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('tthought_id', array(
                'tthought_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('tthought_id', $param2);
            $this->db->delete('todays_thought');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/todays_thought', 'refresh');
        }
        $page_data['todays_thought']    = $this->db->get('todays_thought')->result_array();
        $page_data['page_name']  = 'todays_thought';
        $page_data['page_title'] = get_phrase('manage_todays_thought');
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	 /****MANAGE ENQUIRY SETTINGS*****/
    function enquiry_setting($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['category']         = $this->input->post('category');
            $data['purpose'] = $this->input->post('purpose');
            $data['whom']   = $this->input->post('whom');
            $this->db->insert('enquiry_category', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/enquiry_setting/', 'refresh');
        }
		
		if ($param1 == 'do_update') {
           $data['category']         = $this->input->post('category');
            $data['purpose'] = $this->input->post('purpose');
            $data['whom']   = $this->input->post('whom');
            
            $this->db->where('enquirycat_id', $param2);
            $this->db->update('enquiry_category', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/enquiry_setting/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('enquiry_category', array(
                'class_id' => $param2
            ))->result_array();
        }
		
		
        if ($param1 == 'delete') {
            $this->db->where('enquirycat_id', $param2);
            $this->db->delete('enquiry_category');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/enquiry_setting/', 'refresh');
        }
        $page_data['enquiry_setting']    = $this->db->get('enquiry_category')->result_array();
        $page_data['page_name']  = 'enquiry_setting';
        $page_data['page_title'] = get_phrase('manage_enquiry_category');
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	
	
	
	
		 /****MANAGE AAL ENQUIRY SETTINGS*****/
    function enquiry($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['category']       = $this->input->post('category');
            $data['mobile']		  	= $this->input->post('mobile');
            $data['purpose']		= $this->input->post('purpose');
            $data['name']		  	= $this->input->post('name');
            $data['whom']   		= $this->input->post('whom');
            $this->db->insert('enquiry', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/enquiry/', 'refresh');
        }
		
		if ($param1 == 'do_update') {
          	$data['category']       = $this->input->post('category');
            $data['mobile']		  	= $this->input->post('mobile');
            $data['purpose']		= $this->input->post('purpose');
            $data['name']		  	= $this->input->post('name');
            $data['whom']   		= $this->input->post('whom');
            
            $this->db->where('enquiry_id', $param2);
            $this->db->update('enquiry', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/enquiry/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('enquiry', array(
                'enquiry_id' => $param2
            ))->result_array();
        }
		
        if ($param1 == 'delete') {
            $this->db->where('enquiry_id', $param2);
            $this->db->delete('enquiry');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/enquiry/', 'refresh');
        }
        $page_data['enquiry_setting']    = $this->db->get('enquiry')->result_array();
        $page_data['page_name']  = 'enquiry';
        $page_data['page_title'] = get_phrase('manage_enquiries');
        $this->load->view('backend/index', $page_data);
    }
	

    /****MANAGE SECTIONS*****/
    function section($class_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        // detect the first class
        if ($class_id == '')
            $class_id           =   $this->db->get('class')->first_row()->class_id;

        $page_data['page_name']  = 'section';
        $page_data['page_title'] = get_phrase('manage_sections');
        $page_data['class_id']   = $class_id;
        $this->load->view('backend/index', $page_data);    
    }

    function sections($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']       =   $this->input->post('name');
            $data['nick_name']  =   $this->input->post('nick_name');
            $data['class_id']   =   $this->input->post('class_id');
            $data['teacher_id'] =   $this->input->post('teacher_id');
            $this->db->insert('section' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/section/' . $data['class_id'] , 'refresh');
        }

        if ($param1 == 'edit') {
            $data['name']       =   $this->input->post('name');
            $data['nick_name']  =   $this->input->post('nick_name');
            $data['class_id']   =   $this->input->post('class_id');
            $data['teacher_id'] =   $this->input->post('teacher_id');
            $this->db->where('section_id' , $param2);
            $this->db->update('section' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/section/' . $data['class_id'] , 'refresh');
        }

        if ($param1 == 'delete') {
            $this->db->where('section_id' , $param2);
            $this->db->delete('section');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/section' , 'refresh');
        }
    }

    function get_class_section($class_id)
    {
        $sections = $this->db->get_where('section' , array(
            'class_id' => $class_id
        ))->result_array();
        foreach ($sections as $row) {
            echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_class_subject($class_id)
    {
        $subjects = $this->db->get_where('subject' , array(
            'class_id' => $class_id
        ))->result_array();
        foreach ($subjects as $row) {
            echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_class_students($class_id)
    {
        $students = $this->db->get_where('student' , array(
            'class_id' => $class_id
        ))->result_array();
        foreach ($students as $row) {
            echo '<option value="' . $row['student_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_class_students_mass($class_id)
    {
        $students = $this->db->get_where('student' , array(
            'class_id' => $class_id
        ))->result_array();
        echo '<div class="form-group">
                <label class="col-sm-3 control-label">' . get_phrase('students') . '</label>
                <div class="col-sm-9">';
        foreach ($students as $row) {
            echo '<div class="checkbox">
                    <label><input type="checkbox" class="check" name="student_id[]" value="' . $row['student_id'] . '">' . $row['name'] .'</label>
                </div>';
        }
        echo '<br><button type="button" class="btn btn-default" onClick="select()">'.get_phrase('select_all').'</button>';
        echo '<button style="margin-left: 5px;" type="button" class="btn btn-default" onClick="unselect()"> '.get_phrase('select_none').' </button>';
        echo '</div></div>';
    }


 /*********MANAGE STUDY MATERIAL************/
    function study_material($task = "", $document_id = "")
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            $this->session->set_userdata('last_page' , current_url());
            redirect(base_url(), 'refresh');
        }
                
        if ($task == "create")
        {
            $this->crud_model->save_study_material_info();
            $this->session->set_flashdata('flash_message' , get_phrase('study_material_info_saved_successfuly'));
            redirect(base_url() . 'index.php?admin/study_material' , 'refresh');
        }
        
        if ($task == "update")
        {
            $this->crud_model->update_study_material_info($document_id);
            $this->session->set_flashdata('flash_message' , get_phrase('study_material_info_updated_successfuly'));
            redirect(base_url() . 'index.php?admin/study_material' , 'refresh');
        }
        
        if ($task == "delete")
        {
            $this->crud_model->delete_study_material_info($document_id);
            redirect(base_url() . 'index.php?admin/study_material');
        }
        
        $data['study_material_info']    = $this->crud_model->select_study_material_info();
        $data['page_name']              = 'study_material';
        $data['page_title']             = get_phrase('study_material');
        $this->load->view('backend/index', $data);
    }
	

    /****MANAGE EXAMS*****/
    function exam($param1 = '', $param2 = '' , $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']    = $this->input->post('name');
            $data['date']    = $this->input->post('date');
            $data['comment'] = $this->input->post('comment');
            $this->db->insert('exam', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/exam/', 'refresh');
        }
        if ($param1 == 'edit' && $param2 == 'do_update') {
            $data['name']    = $this->input->post('name');
            $data['date']    = $this->input->post('date');
            $data['comment'] = $this->input->post('comment');
            
            $this->db->where('exam_id', $param3);
            $this->db->update('exam', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/exam/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('exam', array(
                'exam_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('exam_id', $param2);
            $this->db->delete('exam');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/exam/', 'refresh');
        }
        $page_data['exams']      = $this->db->get('exam')->result_array();
        $page_data['page_name']  = 'exam';
        $page_data['page_title'] = get_phrase('manage_exam');
        $this->load->view('backend/index', $page_data);
    }
	
	/****MANAGE NEWS*****/
    function news($param1 = '', $param2 = '' , $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['news_title']    = $this->input->post('news_title');
            $data['date']    = $this->input->post('date');
            $data['news_content'] = $this->input->post('news_content');
            $this->db->insert('news', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/news/', 'refresh');
        }
        if ($param1 == 'edit' && $param2 == 'do_update') {
            $data['news_title']    = $this->input->post('news_title');
            $data['date']    = $this->input->post('date');
            $data['news_content'] = $this->input->post('news_content');
            
            $this->db->where('news_id', $param3);
            $this->db->update('news', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/news/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('news', array(
                'news_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('news_id', $param2);
            $this->db->delete('news');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/news/', 'refresh');
        }
        $page_data['news']      = $this->db->get('news')->result_array();
        $page_data['page_name']  = 'news';
        $page_data['page_title'] = get_phrase('manage_news');
        $this->load->view('backend/index', $page_data);
    }


 /**********MANAGE AASIGNMENTS *******************/
    function assignment($param1 = '', $param2 = '' , $param3 = '')
    {
       if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
		
		    $data['timestamp']     = $this->input->post('timestamp');
            $data['title']         = $this->input->post('title');
            $data['description']     = $this->input->post('description');
			$data['file_name'] 	= $_FILES["file_name"]["name"];
            $data['class_id']       = $this->input->post('class_id');
            $data['file_type']       = $this->input->post('file_type');
            $this->db->insert('assignment', $data);
            $assignment_id = $this->db->insert_id();
			
            move_uploaded_file($_FILES["file_name"]["tmp_name"], "uploads/assignment/" . $_FILES["file_name"]["name"]);
            redirect(base_url() . 'index.php?admin/assignment' , 'refresh');
        }
		if ($param1 == 'do_update') {
             $data['timestamp']     = $this->input->post('timestamp');
            $data['title']         = $this->input->post('title');
            $data['description']     = $this->input->post('description');
            $data['class_id']       = $this->input->post('class_id');
            $data['file_type']       = $this->input->post('file_type');
            
            $this->db->where('assignment_id', $param2);
            $this->db->update('assignment', $data);
			 $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/assignment/'.$data['assignment_id'], 'refresh');
			}
			
       if ($param1 == 'delete') {
            $this->db->where('assignment_id' , $param2);
            $this->db->delete('assignment');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/assignment' , 'refresh');
        }
		
        $page_data['page_name']  = 'assignment';
        $page_data['page_title'] = get_phrase('manage_assignment');
        $page_data['assignments']  = $this->db->get('assignment')->result_array();
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	/**********MANAGE AASIGNMENTS *******************/
    function examquestion($param1 = '', $param2 = '' , $param3 = '')
    {
       if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
		
		    $data['timestamp']     = $this->input->post('timestamp');
            $data['name']         = $this->input->post('name');
            $data['title']         = $this->input->post('title');
            $data['description']     = $this->input->post('description');
			$data['file_name'] 	= $_FILES["file_name"]["name"];
            $data['class_id']       = $this->input->post('class_id');
            $data['file_type']       = $this->input->post('file_type');
	        $data['status']         = $this->input->post('status');
            $this->db->insert('examquestion', $data);
            $examquestion_id = $this->db->insert_id();
			
            move_uploaded_file($_FILES["file_name"]["tmp_name"], "uploads/examquestion/" . $_FILES["file_name"]["name"]);
            redirect(base_url() . 'index.php?admin/examquestion' , 'refresh');
        }
		if ($param1 == 'do_update') {
             $data['timestamp']     = $this->input->post('timestamp');
            $data['title']         = $this->input->post('title');
            $data['description']     = $this->input->post('description');
            $data['class_id']       = $this->input->post('class_id');
            $data['status']         = $this->input->post('status');

            
            $this->db->where('examquestion_id', $param2);
            $this->db->update('examquestion', $data);
			 $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/examquestion'.$data['examquestion_id'], 'refresh');
			}
			
       if ($param1 == 'delete') {
            $this->db->where('examquestion_id' , $param2);
            $this->db->delete('examquestion');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/examquestion' , 'refresh');
        }
		
        $page_data['page_name']  = 'examquestion';
        $page_data['page_title'] = get_phrase('manage_exam_questions');
        $page_data['examquestions']  = $this->db->get('examquestion')->result_array();
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	/**********MANAGE LOAN *******************/
    function loan_applicant($param1 = '', $param2 = '' , $param3 = '')
    {
       if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
		
		    $data['staff_name']     	= $this->input->post('staff_name');
            $data['amount']        	 	= $this->input->post('amount');
            $data['purpose']    	  	= $this->input->post('purpose');
            $data['l_duration']       	= $this->input->post('l_duration');
			
            $data['mop']       			= $this->input->post('mop');
			
			$data['g_name']     		= $this->input->post('g_name');
            $data['g_relationship']     = $this->input->post('g_relationship');
            $data['g_number']     		= $this->input->post('g_number');
			
			$data['g_address']     		= $this->input->post('g_address');
            $data['g_country']         	= $this->input->post('g_country');
            $data['c_name']     		= $this->input->post('c_name');
			
			$data['c_type']     		= $this->input->post('c_type');
            $data['model']         		= $this->input->post('model');
            $data['make']     			= $this->input->post('make');
			
			$data['serial_number']     	= $this->input->post('serial_number');
            $data['value']   			= $this->input->post('value');
            $data['condition']     		= $this->input->post('condition');
			$data['date']         		= $this->input->post('date');
            $data['status']     		= $this->input->post('status');
			
            $this->db->insert('loan', $data);
            $assignment_id = $this->db->insert_id();
			
            move_uploaded_file($_FILES["file_name"]["tmp_name"], "uploads/loan_applicant/" . $_FILES["file_name"]["name"]);
			$this->session->set_flashdata('flash_message' , get_phrase('loan_application_submitted_successfully'));
            redirect(base_url() . 'index.php?admin/loan_applicant' , 'refresh');
        }
		if ($param1 == 'do_update') {
             $data['staff_name']     	= $this->input->post('staff_name');
            $data['amount']        	 	= $this->input->post('amount');
            $data['purpose']    	  	= $this->input->post('purpose');
            $data['l_duration']       	= $this->input->post('l_duration');
			
            $data['mop']       			= $this->input->post('mop');
			
			$data['g_name']     		= $this->input->post('g_name');
            $data['g_relationship']     = $this->input->post('g_relationship');
            $data['g_number']     		= $this->input->post('g_number');
			
			$data['g_address']     		= $this->input->post('g_address');
            $data['g_country']         	= $this->input->post('g_country');
            $data['c_name']     		= $this->input->post('c_name');
			
			$data['c_type']     		= $this->input->post('c_type');
            $data['model']         		= $this->input->post('model');
            $data['make']     			= $this->input->post('make');
			
			$data['serial_number']     	= $this->input->post('serial_number');
            $data['value']   			= $this->input->post('value');
            $data['condition']     		= $this->input->post('condition');
			$data['date']         		= $this->input->post('date');
            $data['status']     		= $this->input->post('status');
            
            $this->db->where('loan_id', $param2);
            $this->db->update('loan', $data);
			 $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/loan_applicant/'.$data['assignment_id'], 'refresh');
			}
			
       if ($param1 == 'delete') {
            $this->db->where('loan_id' , $param2);
            $this->db->delete('loan');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/loan_applicant' , 'refresh');
        }
		
        $page_data['page_name']  = 'loan_applicant';
        $page_data['page_title'] = get_phrase('manage_loan_applicants');
        $page_data['loan_applicants']  = $this->db->get('loan')->result_array();
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	/**********MANAGE LOAN *******************/
    function loan_approval($param1 = '', $param2 = '' , $param3 = '')
    {
       if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
		
		    $data['staff_name']     	= $this->input->post('staff_name');
            $data['amount']        	 	= $this->input->post('amount');
            $data['purpose']    	  	= $this->input->post('purpose');
            $data['l_duration']       	= $this->input->post('l_duration');
			
            $data['mop']       			= $this->input->post('mop');
			
			$data['g_name']     		= $this->input->post('g_name');
            $data['g_relationship']     = $this->input->post('g_relationship');
            $data['g_number']     		= $this->input->post('g_number');
			
			$data['g_address']     		= $this->input->post('g_address');
            $data['g_country']         	= $this->input->post('g_country');
            $data['c_name']     		= $this->input->post('c_name');
			
			$data['c_type']     		= $this->input->post('c_type');
            $data['model']         		= $this->input->post('model');
            $data['make']     			= $this->input->post('make');
			
			$data['serial_number']     	= $this->input->post('serial_number');
            $data['value']   			= $this->input->post('value');
            $data['condition']     		= $this->input->post('condition');
			$data['date']         		= $this->input->post('date');
            $data['status']     		= $this->input->post('status');
			
            $this->db->insert('loan', $data);
            $assignment_id = $this->db->insert_id();
			
            move_uploaded_file($_FILES["file_name"]["tmp_name"], "uploads/loan_applicant/" . $_FILES["file_name"]["name"]);
			$this->session->set_flashdata('flash_message' , get_phrase('loan_application_submitted_successfully'));
            redirect(base_url() . 'index.php?admin/loan_applicant' , 'refresh');
        }
		if ($param1 == 'do_update') {
            
            $data['status']     		= $this->input->post('status');
            
            $this->db->where('loan_id', $param2);
            $this->db->update('loan', $data);
			 $this->session->set_flashdata('flash_message' , get_phrase('loan_status_updated_successfully'));
            redirect(base_url() . 'index.php?admin/loan_approval'.$data['assignment_id'], 'refresh');
			}
			
       if ($param1 == 'delete') {
            $this->db->where('loan_id' , $param2);
            $this->db->delete('loan');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/loan_approval' , 'refresh');
        }
		
        $page_data['page_name']  = 'loan_approval';
        $page_data['page_title'] = get_phrase('manage_loan_approval');
        $page_data['loan_approvals']  = $this->db->get('loan')->result_array();
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	 /**********MANAGING MEDIA HERE*******************/
    function media($param1 = '', $param2 = '' , $param3 = '')
    {
       if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
		
		    $data['timestamp']     = $this->input->post('timestamp');
            $data['title']         = $this->input->post('title');
            $data['description']     = $this->input->post('description');
			$data['file_name'] 	= $_FILES["file_name"]["name"];
            $data['class_id']       = $this->input->post('class_id');
            $data['file_type']       = $this->input->post('file_type');
			$data['mlink']     = $this->input->post('mlink');
            $this->db->insert('media', $data);
            $media_id = $this->db->insert_id();
			
            move_uploaded_file($_FILES["file_name"]["tmp_name"], "uploads/media_files/" . $_FILES["file_name"]["name"]);
            redirect(base_url() . 'index.php?admin/media' , 'refresh');
        }
		if ($param1 == 'do_update') {
             $data['timestamp']     = $this->input->post('timestamp');
            $data['title']         = $this->input->post('title');
            $data['description']     = $this->input->post('description');
            $data['class_id']       = $this->input->post('class_id');
            $data['file_type']       = $this->input->post('file_type');
            $data['mlink']     = $this->input->post('mlink');
            
            $this->db->where('media_id', $param2);
            $this->db->update('media', $data);
			 $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/media/'.$data['media_id'], 'refresh');
			}
			
       if ($param1 == 'delete') {
            $this->db->where('media_id' , $param2);
            $this->db->delete('media');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/media' , 'refresh');
        }
		
        $page_data['page_name']  = 'media';
        $page_data['page_title'] = get_phrase('manage_media');
        $page_data['medias']  = $this->db->get('media')->result_array();
        $this->load->view('backend/index', $page_data);
    }
	
    
	
	    /*****FRONT_END *********/
    function front_end($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        
        if ($param1 == 'do_update') {
			 
            $data['description'] = $this->input->post('about_us');
            $this->db->where('type' , 'about_us');
            $this->db->update('front_end' , $data);

            $data['description'] = $this->input->post('vission');
            $this->db->where('type' , 'vission');
            $this->db->update('front_end' , $data);

            $data['description'] = $this->input->post('mission');
            $this->db->where('type' , 'mission');
            $this->db->update('front_end' , $data);

            $data['description'] = $this->input->post('goal');
            $this->db->where('type' , 'goal');
            $this->db->update('front_end' , $data);

            $data['description'] = $this->input->post('services');
            $this->db->where('type' , 'services');
            $this->db->update('front_end' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated')); 
            redirect(base_url() . 'index.php?admin/front_end/', 'refresh');
        }
      
       
        $page_data['page_name']  = 'front_end';
        $page_data['page_title'] = get_phrase('front_ends');
        $page_data['settings']   = $this->db->get('front_end')->result_array();
        $this->load->view('backend/index', $page_data);
    }
	

    /****** SEND EXAM MARKS VIA SMS ********/
    function exam_marks_sms($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'send_sms') {

            $exam_id    =   $this->input->post('exam_id');
            $class_id   =   $this->input->post('class_id');
            $receiver   =   $this->input->post('receiver');

            // get all the students of the selected class
            $students = $this->db->get_where('student' , array(
                'class_id' => $class_id
            ))->result_array();
            // get the marks of the student for selected exam
            foreach ($students as $row) {
                if ($receiver == 'student')
                    $receiver_phone = $row['phone'];
                if ($receiver == 'parent' && $row['parent_id'] != '') 
                    $receiver_phone = $this->db->get_where('parent' , array('parent_id' => $row['parent_id']))->row()->phone;
                

                $this->db->where('exam_id' , $exam_id);
                $this->db->where('student_id' , $row['student_id']);
                $marks = $this->db->get('mark')->result_array();
                $message = '';
                foreach ($marks as $row2) {
                    $subject       = $this->db->get_where('subject' , array('subject_id' => $row2['subject_id']))->row()->name;
                    $mark_obtained = $row2['mark_obtained'];  
                    $message      .= $row2['student_id'] . $subject . ' : ' . $mark_obtained . ' , ';
                    
                }
                // send sms
                $this->sms_model->send_sms( $message , $receiver_phone );
            }
            $this->session->set_flashdata('flash_message' , get_phrase('message_sent'));
            redirect(base_url() . 'index.php?admin/exam_marks_sms' , 'refresh');
        }
                
        $page_data['page_name']  = 'exam_marks_sms';
        $page_data['page_title'] = get_phrase('send_marks_by_sms');
        $this->load->view('backend/index', $page_data);
    }

    /****MANAGE EXAM MARKS*****/
    function marks($exam_id = '', $class_id = '', $student_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($this->input->post('operation') == 'selection') {
            $page_data['exam_id']    = $this->input->post('exam_id');
            $page_data['class_id']   = $this->input->post('class_id');
            $page_data['student_id'] = $this->input->post('student_id');
            
            if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0 && $page_data['student_id'] > 0) {
                redirect(base_url() . 'index.php?admin/marks/' . $page_data['exam_id'] . '/' . $page_data['class_id'] . '/' . $page_data['student_id'], 'refresh');
            } else {
                $this->session->set_flashdata('mark_message', 'Choose exam, class and subject');
                redirect(base_url() . 'index.php?admin/marks/', 'refresh');
            }
        }
        if ($this->input->post('operation') == 'update') {
            $subjects = $this->db->get_where('subject' , array('class_id' => $class_id))->result_array();
            foreach($subjects as $row) {
                $data['mark_obtained'] = $this->input->post('mark_obtained_' . $row['subject_id']);
                $data['class_score'] = $this->input->post('class_score_' . $row['subject_id']);
                $data['comment']       = $this->input->post('comment_' . $row['subject_id']);
                
                $this->db->where('mark_id', $this->input->post('mark_id_' . $row['subject_id']));
                $this->db->update('mark', array('mark_obtained' => $data['mark_obtained'] , 'comment' => $data['comment'] , 'class_score' => $data['class_score']));
            }
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/marks/' . $this->input->post('exam_id') . '/' . $this->input->post('class_id') . '/' . $this->input->post('student_id'), 'refresh');
        }
        $page_data['exam_id']    = $exam_id;
        $page_data['class_id']   = $class_id;
        $page_data['student_id'] = $student_id;
        $page_data['subject_id'] = $section_id;
        $page_data['page_info'] = 'Exam marks';
        
        $page_data['page_name']  = 'marks';
        $page_data['page_title'] = get_phrase('manage_exam_marks');
        $this->load->view('backend/index', $page_data);
    }

    function manage_marks()
    {  
        $page_data['exam_id']    = $this->input->post('exam_id');
        $page_data['class_id']   = $this->input->post('class_id');
        $page_data['student_id'] = $this->input->post('student_id');
        if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0 && $page_data['student_id'] > 0) {
            redirect(base_url() . 'index.php?admin/marks/' . $page_data['exam_id'] . '/' . $page_data['class_id'] . '/' . $page_data['student_id'], 'refresh');
        } else {
            $this->session->set_flashdata('mark_message', 'Choose exam, class and subject');
            redirect(base_url() . 'index.php?admin/marks/', 'refresh');
        }
    }


    // TABULATION SHEET
    function tabulation_sheet($class_id = '' , $exam_id = '', $student_id = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($this->input->post('operation') == 'selection') {
            $page_data['exam_id']    = $this->input->post('exam_id');
            $page_data['class_id']   = $this->input->post('class_id');
            $page_data['student_id']   = $this->input->post('student_id');
            
            if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0 && $page_data['student_id'] > 0) {
                redirect(base_url() . 'index.php?admin/tabulation_sheet/' . $page_data['class_id'] . '/' . $page_data['exam_id'] . '/' . $page_data['student_id'] , 'refresh');
            } else {
                $this->session->set_flashdata('mark_message', 'Choose class and exam');
                redirect(base_url() . 'index.php?admin/tabulation_sheet/', 'refresh');
            }
        }
        $page_data['exam_id']    = $exam_id;
        $page_data['class_id']   = $class_id;
        $page_data['student_id'] = $student_id;
        $page_data['page_info'] = 'Exam marks';
        
        $page_data['page_name']  = 'tabulation_sheet';
        $page_data['page_title'] = get_phrase('tabulation_sheet');
        $this->load->view('backend/index', $page_data);
    
    }

    function tabulation_sheet_print_view($class_id , $exam_id) {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['class_id'] = $class_id;
        $page_data['exam_id']  = $exam_id;
        $this->load->view('backend/admin/tabulation_sheet_print_view' , $page_data);
    }

    function tabulation_sheet_print_view_control($class_id , $exam_id) {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['class_id'] = $class_id;
        $page_data['exam_id']  = $exam_id;
        redirect(base_url() . 'index.php?admin/tabulation_sheet_print_view/' . $page_data['class_id'] . '/' . $page_data['exam_id'] , 'refresh');
    }
    
    /****MANAGE GRADES*****/
    function grade($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['grade_point'] = $this->input->post('grade_point');
            $data['mark_from']   = $this->input->post('mark_from');
            $data['mark_upto']   = $this->input->post('mark_upto');
            $data['comment']     = $this->input->post('comment');
            $this->db->insert('grade', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/grade/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['grade_point'] = $this->input->post('grade_point');
            $data['mark_from']   = $this->input->post('mark_from');
            $data['mark_upto']   = $this->input->post('mark_upto');
            $data['comment']     = $this->input->post('comment');
            
            $this->db->where('grade_id', $param2);
            $this->db->update('grade', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/grade/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('grade', array(
                'grade_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('grade_id', $param2);
            $this->db->delete('grade');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/grade/', 'refresh');
        }
        $page_data['grades']     = $this->db->get('grade')->result_array();
        $page_data['page_name']  = 'grade';
        $page_data['page_title'] = get_phrase('manage_grade');
        $this->load->view('backend/index', $page_data);
    }
    
    /**********MANAGING CLASS ROUTINE******************/
    function class_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['class_id']       = $this->input->post('class_id');
            $data['subject_id']     = $this->input->post('subject_id');
            $data['time_start']     = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
            $data['time_end']       = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
            $data['time_start_min'] = $this->input->post('time_start_min');
            $data['time_end_min']   = $this->input->post('time_end_min');
            $data['day']            = $this->input->post('day');
            $this->db->insert('class_routine', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/class_routine/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['class_id']       = $this->input->post('class_id');
            $data['subject_id']     = $this->input->post('subject_id');
            $data['time_start']     = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
            $data['time_end']       = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
            $data['time_start_min'] = $this->input->post('time_start_min');
            $data['time_end_min']   = $this->input->post('time_end_min');
            $data['day']            = $this->input->post('day');
            
            $this->db->where('class_routine_id', $param2);
            $this->db->update('class_routine', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/class_routine/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('class_routine', array(
                'class_routine_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('class_routine_id', $param2);
            $this->db->delete('class_routine');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/class_routine/', 'refresh');
        }
        $page_data['page_name']  = 'class_routine';
        $page_data['page_title'] = get_phrase('manage_class_routine');
        $this->load->view('backend/index', $page_data);
    }
	
	/****** DAILY ATTENDANCE *****************/
	function manage_attendance($date='',$month='',$year='',$class_id='', $section_id='')
	{
		if($this->session->userdata('admin_login')!=1)
            redirect(base_url() , 'refresh');

        $active_sms_service = $this->db->get_where('settings' , array('type' => 'active_sms_service'))->row()->description;

		
		if($_POST)
		{
			// Loop all the students of $class_id
            $students   =   $this->db->get_where('student', array('class_id' => $class_id))->result_array();
            foreach ($students as $row)
            {
                $attendance_status  =   $this->input->post('status_' . $row['student_id']);
                $full_date = $year."-".$month."-".$date;
                $this->db->where('student_id' , $row['student_id']);
                $this->db->where('date' , $full_date);

                $this->db->update('attendance' , array('status' => $attendance_status));

                // if ($attendance_status == 2) {

                //     if ($active_sms_service != '' || $active_sms_service != 'disabled') {
                //         $student_name   = $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->name;
                //         $receiver_phone = $this->db->get_where('parent' , array('parent_id' => $row['parent_id']))->row()->phone;
                //         $message        = 'Your child' . ' ' . $student_name . 'is absent today.';
                //         $this->sms_model->send_sms($message,$receiver_phone);
                //     }
                // }

            }

			$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
			redirect(base_url() . 'index.php?admin/manage_attendance/'.$date.'/'.$month.'/'.$year.'/'.$class_id.'/'.$section_id , 'refresh');
		}
        $page_data['date']     =	$date;
        $page_data['month']    =	$month;
        $page_data['year']     =	$year;
        $page_data['class_id'] =	$class_id;
		$page_data['section_id'] =  $section_id;
        $page_data['page_name']  =	'manage_attendance';
        $page_data['page_title'] =	get_phrase('manage_daily_attendance');
		$this->load->view('backend/index', $page_data);
	}
    function attendance_report($class_id='', $section_id='',$month='',$year='')
    {
        if($this->session->userdata('admin_login')!=1)
            redirect(base_url() , 'refresh');

        $active_sms_service = $this->db->get_where('settings' , array('type' => 'active_sms_service'))->row()->description;

        
        if($_POST)
        {
            redirect(base_url() . 'index.php?admin/attendance_report/'.$class_id.'/'.$section_id.'/'.$month.'/'.$year , 'refresh');
        }
        $classes    =   $this->db->get('class')->result_array();
        foreach ($classes as $row) {
            if(isset($class_id) && $class_id==$row['class_id']) $calss_name = $row['name'];
        }
        $sections    =   $this->db->get('section')->result_array();
        foreach ($sections as $row) {
            if(isset($section_id) && $section_id==$row['section_id']) $section_name = $row['name'];
        }

        $page_data['month']    =    $month;
        $page_data['year']    =    $year;
        $page_data['class_id'] =    $class_id;
        $page_data['section_id'] =  $section_id;
        $page_data['page_name']  =  'attendance_report';
        $page_data['page_title'] =  "Attendance Report Of Class ".$calss_name." : Section ".$section_name;
        $this->load->view('backend/index', $page_data);
    }
    function attendance_report_print_view($class_id='', $section_id='', $month='', $year='')
    {
        $page_data['month']    =    $month;
        $page_data['year']    =    $year;
        $page_data['class_id'] =    $class_id;
        $page_data['section_id'] =  $section_id;
        $this->load->view('backend/attendance_report_print_view.php', $page_data);
    }
	function attendance_selector()
	{  
        $date = $this->input->post('timestamp');
        $date = date_create($date);
        $date = date_format($date,"d/m/Y");
		redirect(base_url() . 'index.php?admin/manage_attendance/'.$date.'/'.$this->input->post('class_id').'/'.$this->input->post('section_id') , 'refresh');
	}
    function attendance_report_view()
    {  
        redirect(base_url() . 'index.php?admin/attendance_report/'.$this->input->post('class_id').'/'.$this->input->post('section_id').'/'.$this->input->post('month').'/'.$this->input->post('year') , 'refresh');
    }
	
    /******MANAGE BILLING / INVOICES WITH STATUS*****/
    function invoice($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($param1 == 'create') {
            $data['student_id']         = $this->input->post('student_id');
            $data['title']              = $this->input->post('title');
            $data['description']        = $this->input->post('description');
            $data['amount']             = $this->input->post('amount');
            $data['amount_paid']        = $this->input->post('amount_paid');
            $data['due']                = $data['amount'] - $data['amount_paid'];
            $data['status']             = $this->input->post('status');
            $data['creation_timestamp'] = strtotime($this->input->post('date'));
            
            $this->db->insert('invoice', $data);
            $invoice_id = $this->db->insert_id();

            $data2['invoice_id']        =   $invoice_id;
            $data2['student_id']        =   $this->input->post('student_id');
            $data2['title']             =   $this->input->post('title');
            $data2['description']       =   $this->input->post('description');
            $data2['payment_type']      =  'income';
            $data2['method']            =   $this->input->post('method');
            $data2['amount']            =   $this->input->post('amount_paid');
            $data2['timestamp']         =   strtotime($this->input->post('date'));

            $this->db->insert('payment' , $data2);

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/student_payment', 'refresh');
        }

        if ($param1 == 'create_mass_invoice') {
            if (!($this->input->post('student_id'))) {
                foreach ($this->input->post('student_id') as $id) {

                    $data['student_id']         = $id;
                    $data['title']              = $this->input->post('title');
                    $data['description']        = $this->input->post('description');
                    $data['amount']             = $this->input->post('amount');
                    $data['amount_paid']        = $this->input->post('amount_paid');
                    $data['due']                = $data['amount'] - $data['amount_paid'];
                    $data['status']             = $this->input->post('status');
                    $data['creation_timestamp'] = strtotime($this->input->post('date'));
                    
                    $this->db->insert('invoice', $data);
                    $invoice_id = $this->db->insert_id();

                    $data2['invoice_id']        =   $invoice_id;
                    $data2['student_id']        =   $id;
                    $data2['title']             =   $this->input->post('title');
                    $data2['description']       =   $this->input->post('description');
                    $data2['payment_type']      =  'income';
                    $data2['method']            =   $this->input->post('method');
                    $data2['amount']            =   $this->input->post('amount_paid');
                    $data2['timestamp']         =   strtotime($this->input->post('date'));

                    $this->db->insert('payment' , $data2);

                }
            }
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/student_payment', 'refresh');
        }

        if ($param1 == 'do_update') {
            $data['student_id']         = $this->input->post('student_id');
            $data['title']              = $this->input->post('title');
            $data['description']        = $this->input->post('description');
            $data['amount']             = $this->input->post('amount');
            $data['status']             = $this->input->post('status');
            $data['creation_timestamp'] = strtotime($this->input->post('date'));
            
            $this->db->where('invoice_id', $param2);
            $this->db->update('invoice', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/invoice', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('invoice', array(
                'invoice_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'take_payment') {
            $data['invoice_id']   =   $this->input->post('invoice_id');
            $data['student_id']   =   $this->input->post('student_id');
            $data['title']        =   $this->input->post('title');
            $data['description']  =   $this->input->post('description');
            $data['payment_type'] =   'income';
            $data['method']       =   $this->input->post('method');
            $data['amount']       =   $this->input->post('amount');
            $data['timestamp']    =   strtotime($this->input->post('timestamp'));
            $this->db->insert('payment' , $data);

            $data2['amount_paid']   =   $this->input->post('amount');
            $this->db->where('invoice_id' , $param2);
            $this->db->set('amount_paid', 'amount_paid + ' . $data2['amount_paid'], FALSE);
            $this->db->set('due', 'due - ' . $data2['amount_paid'], FALSE);
            $this->db->update('invoice');

            $this->session->set_flashdata('flash_message' , get_phrase('payment_successfull'));
            redirect(base_url() . 'index.php?admin/invoice', 'refresh');
        }

        if ($param1 == 'delete') {
            $this->db->where('invoice_id', $param2);
            $this->db->delete('invoice');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/invoice', 'refresh');
        }
        $page_data['page_name']  = 'invoice';
        $page_data['page_title'] = get_phrase('manage_invoice/payment');
        $this->db->order_by('creation_timestamp', 'desc');
        $page_data['invoices'] = $this->db->get('invoice')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    /**********ACCOUNTING********************/
    function income($param1 = '' , $param2 = '')
    {
       if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        $page_data['page_name']  = 'income';
        $page_data['page_title'] = get_phrase('student_payments');
        $this->db->order_by('creation_timestamp', 'desc');
        $page_data['invoices'] = $this->db->get('invoice')->result_array();
        $this->load->view('backend/index', $page_data); 
    }

    function student_payment($param1 = '' , $param2 = '' , $param3 = '') {

        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        $page_data['page_name']  = 'student_payment';
        $page_data['page_title'] = get_phrase('create_student_payment');
        $this->load->view('backend/index', $page_data); 
    }

    function expense($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['title']               =   $this->input->post('title');
            $data['expense_category_id'] =   $this->input->post('expense_category_id');
            $data['description']         =   $this->input->post('description');
            $data['payment_type']        =   'expense';
            $data['method']              =   $this->input->post('method');
            $data['amount']              =   $this->input->post('amount');
            $data['timestamp']           =   strtotime($this->input->post('timestamp'));
            $this->db->insert('payment' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/expense', 'refresh');
        }

        if ($param1 == 'edit') {
            $data['title']               =   $this->input->post('title');
            $data['expense_category_id'] =   $this->input->post('expense_category_id');
            $data['description']         =   $this->input->post('description');
            $data['payment_type']        =   'expense';
            $data['method']              =   $this->input->post('method');
            $data['amount']              =   $this->input->post('amount');
            $data['timestamp']           =   strtotime($this->input->post('timestamp'));
            $this->db->where('payment_id' , $param2);
            $this->db->update('payment' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/expense', 'refresh');
        }

        if ($param1 == 'delete') {
            $this->db->where('payment_id' , $param2);
            $this->db->delete('payment');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/expense', 'refresh');
        }

        $page_data['page_name']  = 'expense';
        $page_data['page_title'] = get_phrase('expenses');
        $this->load->view('backend/index', $page_data); 
    }

    function expense_category($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['name']   =   $this->input->post('name');
            $this->db->insert('expense_category' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/expense_category');
        }
        if ($param1 == 'edit') {
            $data['name']   =   $this->input->post('name');
            $this->db->where('expense_category_id' , $param2);
            $this->db->update('expense_category' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/expense_category');
        }
        if ($param1 == 'delete') {
            $this->db->where('expense_category_id' , $param2);
            $this->db->delete('expense_category');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/expense_category');
        }

        $page_data['page_name']  = 'expense_category';
        $page_data['page_title'] = get_phrase('expense_category');
        $this->load->view('backend/index', $page_data);
    }

    /**********MANAGE LIBRARY / BOOKS********************/
    function book($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['price']       = $this->input->post('price');
            $data['author']      = $this->input->post('author');
            $data['class_id']    = $this->input->post('class_id');
            $data['status']      = $this->input->post('status');
            $this->db->insert('book', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/book', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['price']       = $this->input->post('price');
            $data['author']      = $this->input->post('author');
            $data['class_id']    = $this->input->post('class_id');
            $data['status']      = $this->input->post('status');
            
            $this->db->where('book_id', $param2);
            $this->db->update('book', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/book', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('book', array(
                'book_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('book_id', $param2);
            $this->db->delete('book');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/book', 'refresh');
        }
        $page_data['books']      = $this->db->get('book')->result_array();
        $page_data['page_name']  = 'book';
        $page_data['page_title'] = get_phrase('manage_library_books');
        $this->load->view('backend/index', $page_data);
        
    }
	
    /**********MANAGE TRANSPORT / VEHICLES / ROUTES********************/
    function transport($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['route_name']        = $this->input->post('route_name');
            $data['number_of_vehicle'] = $this->input->post('number_of_vehicle');
            $data['description']       = $this->input->post('description');
            $data['route_fare']        = $this->input->post('route_fare');
            $this->db->insert('transport', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/transport', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['route_name']        = $this->input->post('route_name');
            $data['number_of_vehicle'] = $this->input->post('number_of_vehicle');
            $data['description']       = $this->input->post('description');
            $data['route_fare']        = $this->input->post('route_fare');
            
            $this->db->where('transport_id', $param2);
            $this->db->update('transport', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/transport', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('transport', array(
                'transport_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('transport_id', $param2);
            $this->db->delete('transport');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/transport', 'refresh');
        }
        $page_data['transports'] = $this->db->get('transport')->result_array();
        $page_data['page_name']  = 'transport';
        $page_data['page_title'] = get_phrase('manage_transport');
        $this->load->view('backend/index', $page_data);
        
    }
    /**********MANAGE DORMITORY / HOSTELS / ROOMS ********************/
    function dormitory($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['name']           = $this->input->post('name');
            $data['number_of_room'] = $this->input->post('number_of_room');
            $data['description']    = $this->input->post('description');
            $this->db->insert('dormitory', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/dormitory', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']           = $this->input->post('name');
            $data['number_of_room'] = $this->input->post('number_of_room');
            $data['description']    = $this->input->post('description');
            
            $this->db->where('dormitory_id', $param2);
            $this->db->update('dormitory', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/dormitory', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('dormitory', array(
                'dormitory_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('dormitory_id', $param2);
            $this->db->delete('dormitory');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/dormitory', 'refresh');
        }
        $page_data['dormitories'] = $this->db->get('dormitory')->result_array();
        $page_data['page_name']   = 'dormitory';
        $page_data['page_title']  = get_phrase('manage_dormitory');
        $this->load->view('backend/index', $page_data);
        
    }
    
    /***MANAGE EVENT / NOTICEBOARD, WILL BE SEEN BY ALL ACCOUNTS DASHBOARD**/
    function noticeboard($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($param1 == 'create') {
            $data['notice_title']     = $this->input->post('notice_title');
            $data['notice']           = $this->input->post('notice');
            $data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
            $this->db->insert('noticeboard', $data);

            $check_sms_send = $this->input->post('check_sms');

            if ($check_sms_send == 1) {
                // sms sending configurations

                $parents  = $this->db->get('parent')->result_array();
                $students = $this->db->get('student')->result_array();
                $teachers = $this->db->get('teacher')->result_array();
                $date     = $this->input->post('create_timestamp');
                $message  = $data['notice_title'] . ' ';
                $message .= get_phrase('on') . ' ' . $date;
                foreach($parents as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
                foreach($students as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
                foreach($teachers as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
            }

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/noticeboard/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['notice_title']     = $this->input->post('notice_title');
            $data['notice']           = $this->input->post('notice');
            $data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
            $this->db->where('notice_id', $param2);
            $this->db->update('noticeboard', $data);

            $check_sms_send = $this->input->post('check_sms');

            if ($check_sms_send == 1) {
                // sms sending configurations

                $parents  = $this->db->get('parent')->result_array();
                $students = $this->db->get('student')->result_array();
                $teachers = $this->db->get('teacher')->result_array();
                $date     = $this->input->post('create_timestamp');
                $message  = $data['notice_title'] . ' ';
                $message .= get_phrase('on') . ' ' . $date;
                foreach($parents as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
                foreach($students as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
                foreach($teachers as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
            }

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/noticeboard/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('noticeboard', array(
                'notice_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('notice_id', $param2);
            $this->db->delete('noticeboard');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/noticeboard/', 'refresh');
        }
        $page_data['page_name']  = 'noticeboard';
        $page_data['page_title'] = get_phrase('manage_noticeboard');
        $page_data['notices']    = $this->db->get('noticeboard')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    /* private messaging */

    function message($param1 = 'message_home', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'send_new') {
            $message_thread_code = $this->crud_model->send_new_private_message();
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(base_url() . 'index.php?admin/message/message_read/' . $message_thread_code, 'refresh');
        }

        if ($param1 == 'send_reply') {
            $this->crud_model->send_reply_message($param2);  //$param2 = message_thread_code
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(base_url() . 'index.php?admin/message/message_read/' . $param2, 'refresh');
        }

        if ($param1 == 'message_read') {
            $page_data['current_message_thread_code'] = $param2;  // $param2 = message_thread_code
            $this->crud_model->mark_thread_messages_read($param2);
        }

        $page_data['message_inner_page_name']   = $param1;
        $page_data['page_name']                 = 'message';
        $page_data['page_title']                = get_phrase('private_messaging');
        $this->load->view('backend/index', $page_data);
    }
    
    /*****SITE/SYSTEM SETTINGS*********/
    function system_settings($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        
        if ($param1 == 'do_update') {
			 
            $data['description'] = $this->input->post('system_name');
            $this->db->where('type' , 'system_name');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('system_title');
            $this->db->where('type' , 'system_title');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('address');
            $this->db->where('type' , 'address');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('phone');
            $this->db->where('type' , 'phone');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('paypal_email');
            $this->db->where('type' , 'paypal_email');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('currency');
            $this->db->where('type' , 'currency');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('system_email');
            $this->db->where('type' , 'system_email');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('system_name');
            $this->db->where('type' , 'system_name');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('language');
            $this->db->where('type' , 'language');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('text_align');
            $this->db->where('type' , 'text_align');
            $this->db->update('settings' , $data);
			
			$data['description'] = $this->input->post('running_session');
            $this->db->where('type' , 'session');
            $this->db->update('settings' , $data);
			
			$data['description'] = $this->input->post('system_footer');
            $this->db->where('type' , 'footer');
            $this->db->update('settings' , $data);
			
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated')); 
            redirect(base_url() . 'index.php?admin/system_settings', 'refresh');
        }
        if ($param1 == 'upload_logo') {
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/logo.png');
            $this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
            redirect(base_url() . 'index.php?admin/system_settings', 'refresh');
        }
        if ($param1 == 'change_skin') {
            $data['description'] = $param2;
            $this->db->where('type' , 'skin_colour');
            $this->db->update('settings' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('theme_selected')); 
            redirect(base_url() . 'index.php?admin/system_settings', 'refresh'); 
        }
        $page_data['page_name']  = 'system_settings';
        $page_data['page_title'] = get_phrase('system_settings');
        $page_data['settings']   = $this->db->get('settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }
	
	/***** UPDATE PRODUCT *****/
	
	function update( $task = '', $purchase_code = '' ) {
        
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
            
        // Create update directory.
        $dir    = 'update';
        if ( !is_dir($dir) )
            mkdir($dir, 0777, true);
        
        $zipped_file_name   = $_FILES["file_name"]["name"];
        $path               = 'update/' . $zipped_file_name;
        
        move_uploaded_file($_FILES["file_name"]["tmp_name"], $path);
        
        // Unzip uploaded update file and remove zip file.
        $zip = new ZipArchive;
        $res = $zip->open($path);
        if ($res === TRUE) {
            $zip->extractTo('update');
            $zip->close();
            unlink($path);
        }
        
        $unzipped_file_name = substr($zipped_file_name, 0, -4);
        $str                = file_get_contents('./update/' . $unzipped_file_name . '/update_config.json');
        $json               = json_decode($str, true);
        

			
		// Run php modifications
		require './update/' . $unzipped_file_name . '/update_script.php';
        
        // Create new directories.
        if(!empty($json['directory'])) {
            foreach($json['directory'] as $directory) {
                if ( !is_dir( $directory['name']) )
                    mkdir( $directory['name'], 0777, true );
            }
        }
        
        // Create/Replace new files.
        if(!empty($json['files'])) {
            foreach($json['files'] as $file)
                copy($file['root_directory'], $file['update_directory']);
        }
        
        $this->session->set_flashdata('flash_message' , get_phrase('product_updated_successfully'));
        redirect(base_url() . 'index.php?admin/system_settings');
    }

    /*****SMS SETTINGS*********/
    function sms_settings($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($param1 == 'clickatell') {

            $data['description'] = $this->input->post('clickatell_user');
            $this->db->where('type' , 'clickatell_user');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('clickatell_password');
            $this->db->where('type' , 'clickatell_password');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('clickatell_api_id');
            $this->db->where('type' , 'clickatell_api_id');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/sms_settings/', 'refresh');
        }

        if ($param1 == 'twilio') {

            $data['description'] = $this->input->post('twilio_account_sid');
            $this->db->where('type' , 'twilio_account_sid');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('twilio_auth_token');
            $this->db->where('type' , 'twilio_auth_token');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('twilio_sender_phone_number');
            $this->db->where('type' , 'twilio_sender_phone_number');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/sms_settings/', 'refresh');
        }

        if ($param1 == 'active_service') {

            $data['description'] = $this->input->post('active_sms_service');
            $this->db->where('type' , 'active_sms_service');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/sms_settings/', 'refresh');
        }

        $page_data['page_name']  = 'sms_settings';
        $page_data['page_title'] = get_phrase('sms_settings');
        $page_data['settings']   = $this->db->get('settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    /*****LANGUAGE SETTINGS*********/
    function manage_language($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
		
		if ($param1 == 'edit_phrase') {
			$page_data['edit_profile'] 	= $param2;	
		}
		if ($param1 == 'update_phrase') {
			$language	=	$param2;
			$total_phrase	=	$this->input->post('total_phrase');
			for($i = 1 ; $i < $total_phrase ; $i++)
			{
				//$data[$language]	=	$this->input->post('phrase').$i;
				$this->db->where('phrase_id' , $i);
				$this->db->update('language' , array($language => $this->input->post('phrase'.$i)));
			}
			redirect(base_url() . 'index.php?admin/manage_language/edit_phrase/'.$language, 'refresh');
		}
		if ($param1 == 'do_update') {
			$language        = $this->input->post('language');
			$data[$language] = $this->input->post('phrase');
			$this->db->where('phrase_id', $param2);
			$this->db->update('language', $data);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		if ($param1 == 'add_phrase') {
			$data['phrase'] = $this->input->post('phrase');
			$this->db->insert('language', $data);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		if ($param1 == 'add_language') {
			$language = $this->input->post('language');
			$this->load->dbforge();
			$fields = array(
				$language => array(
					'type' => 'LONGTEXT'
				)
			);
			$this->dbforge->add_column('language', $fields);
			
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		if ($param1 == 'delete_language') {
			$language = $param2;
			$this->load->dbforge();
			$this->dbforge->drop_column('language', $language);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			
			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		$page_data['page_name']        = 'manage_language';
		$page_data['page_title']       = get_phrase('manage_language');
		//$page_data['language_phrases'] = $this->db->get('language')->result_array();
		$this->load->view('backend/index', $page_data);	
    }
    
    /*****BACKUP / RESTORE / DELETE DATA PAGE**********/
    function backup_restore($operation = '', $type = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($operation == 'create') {
            $this->crud_model->create_backup($type);
        }
        if ($operation == 'restore') {
            $this->crud_model->restore_backup();
            $this->session->set_flashdata('backup_message', 'Backup Restored');
            redirect(base_url() . 'index.php?admin/backup_restore/', 'refresh');
        }
        if ($operation == 'delete') {
            $this->crud_model->truncate($type);
            $this->session->set_flashdata('backup_message', 'Data removed');
            redirect(base_url() . 'index.php?admin/backup_restore/', 'refresh');
        }
        
        $page_data['page_info']  = 'Create backup / restore from backup';
        $page_data['page_name']  = 'backup_restore';
        $page_data['page_title'] = get_phrase('manage_backup_restore');
        $this->load->view('backend/index', $page_data);
    }
	
	
	
    
    /******MANAGE OWN PROFILE AND CHANGE PASSWORD***/
    function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($param1 == 'update_profile_info') {
            $data['name']  = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            
            $this->db->where('admin_id', $this->session->userdata('admin_id'));
            $this->db->update('admin', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/admin_image/' . $this->session->userdata('admin_id') . '.jpg');
            $this->session->set_flashdata('flash_message', get_phrase('account_updated'));
            redirect(base_url() . 'index.php?admin/manage_profile/', 'refresh');
        }
        if ($param1 == 'change_password') {
            $data['password']             = $this->input->post('password');
            $data['new_password']         = $this->input->post('new_password');
            $data['confirm_new_password'] = $this->input->post('confirm_new_password');
            
            $current_password = $this->db->get_where('admin', array(
                'admin_id' => $this->session->userdata('admin_id')
            ))->row()->password;
            if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
                $this->db->where('admin_id', $this->session->userdata('admin_id'));
                $this->db->update('admin', array(
                    'password' => $data['new_password']
                ));
                $this->session->set_flashdata('flash_message', get_phrase('password_updated'));
            } else {
                $this->session->set_flashdata('flash_message', get_phrase('password_mismatch'));
            }
            redirect(base_url() . 'index.php?admin/manage_profile/', 'refresh');
        }
        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        $page_data['edit_data']  = $this->db->get_where('admin', array(
            'admin_id' => $this->session->userdata('admin_id')
        ))->result_array();
        $this->load->view('backend/index', $page_data);
    }
	
	
// CBT CUSTOMISATION STARTS FROM HERE
	function exam_list($class_id, $subject_id, $duration, $date, $session = '', $mode = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');

    if ($mode == 'delete') {
        if ($session == '%null')
            $session = '';
        $sql = "select question_id from question where class_id=" . $class_id . " and subject_id=" . $subject_id . " and duration='" . $duration . "' and date='" . $date . "' and session='" . $session . "'";
        $result = $this->db->query($sql)->result_array();

        $sql = "delete from answer where question_id in (";
        foreach ($result as $row) {
            $in_sql .= "," . $row["question_id"];
        }
        $in_sql = substr($in_sql, 1);
        $sql .= $in_sql . ")";
        $this->db->query($sql);

        $sql = "delete from question where class_id=" . $class_id . " and subject_id=" . $subject_id . " and duration='" . $duration . "' and date='" . $date . "' and session='" . $session . "'";
        $this->db->query($sql);
    }

    $page_data['page_name'] = 'exam_list';
    $page_data['page_title'] = get_phrase('exam_list');

    $query = "select a.*, b.name class_name, c.name subject_name from question a "
            . "inner join class b on a.class_id=b.class_id "
            . "inner join subject c on a.subject_id=c.subject_id "
            . "group by a.class_id, a.subject_id, a.date, a.duration, a.session "
            . "order by a.class_id, a.subject_id, a.date, a.question_id";
    $question_data = $this->db->query($query)->result();
    $page_data['question_data'] = $question_data;
    $this->load->view('backend/index', $page_data);
}

function exam_view($class_id, $subject_id, $duration, $date, $session = '', $mode = '', $question_id = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');

    $mode1 = $this->input->post('mode1');

    if ($session == '%null') {
        $session = '';
    }
    if ($mode == 'save') {
//        $question_id = $this->input->post('question_id');
        $data = array();
        $data['question'] = $this->input->post('question');
        $data["correct_answers"] = $this->input->post('correct_answers');
        $this->db->where('question_id', $question_id);
        $this->db->update('question', $data);

        $answers = $this->input->post('answers');
        for ($i = 0; $i < sizeof($answers); $i++) {
            $data = array();
            $this->db->where('question_id', $question_id);
            $ascii_A = ord('A');
            $this->db->where('label', chr($ascii_A + $i));
            $data["content"] = $answers[$i];
            $this->db->update('answer', $data);
        }
    } else if ($mode == 'delete') {
        $this->db->where('question_id', $question_id);
        $this->db->delete('question');
    } else if ($mode1 == 'save_exam') {
        $class_id = $this->input->post('class_id');
        $subject_id = $this->input->post('subject_id');
        $duration = $this->input->post('duration');
        $date = date("Y-m-d", strtotime($this->input->post('date')));
        $session = $this->input->post('session');
        $question_count = $this->input->post('question_count');

        $usersession = $this->session->userdata('exam_data');

        $this->db->where('class_id', $usersession['class_id']);
        $this->db->where('subject_id', $usersession['subject_id']);
        $this->db->where('duration', $usersession['duration']);
        $this->db->where('date', $usersession['date']);
        $this->db->where('session', $usersession['session']);
        $this->db->update('question', array('class_id' => $class_id, 'subject_id' => $subject_id, 'duration' => $duration, 'date' => $date, 'session' => $session, 'question_count' => $question_count));
    }

    if ($session == '%null')
        $session = '';
    $sql = "select max(b.label) as max_label from question a "
            . "inner join answer b on a.question_id=b.question_id "
            . "where a.class_id=" . $class_id . " and a.subject_id=" . $subject_id . " and a.session='" . $session . "' and a.duration='" . $duration . "' and a.date='" . $date . "'";
    $result = $this->db->query($sql)->result_array();
    $page_data['max_label'] = $result[0]['max_label'];

    $sql = "select * from question "
            . "where class_id=" . $class_id . " and subject_id=" . $subject_id . " and session='" . $session . "' and duration='" . $duration . "' and date='" . $date . "'";
    $exam_list = $this->db->query($sql)->result_array();
    $exam_data = array();
    $question_count = 0;
    foreach ($exam_list as $row) {
        $exam = array();
        $exam['question_id'] = $row['question_id'];
        $exam['class_id'] = $row['class_id'];
        $exam['subject_id'] = $row['subject_id'];
        $exam['date'] = $row['date'];
        $exam['session'] = $row['session'];
        $exam['duration'] = $row['duration'];
        $exam['question'] = $row['question'];
        $exam['correct_answers'] = $row['correct_answers'];
        $question_count = $row['question_count'];

        $sql = "select * from answer where question_id=" . $row['question_id'] . " order by label";
        $result = $this->db->query($sql)->result_array();
        foreach ($result as $row1) {
            $exam[$row1['label']] = $row1['content'];
        }
        array_push($exam_data, $exam);
    }
    $page_data['class_id'] = $class_id;
    $page_data['subject_id'] = $subject_id;
    $page_data['duration'] = $duration;

    $dates = explode('-', $date);
    $y = $dates[0];
    $m = $dates[1];
    $d = $dates[2];
    $page_data['date'] = $m . '/' . $d . '/' . $y;

    $page_data['session'] = $session;
    $page_data['question_count'] = $question_count;
    $page_data['classes'] = $this->db->get('class')->result_array();
    $page_data['subjects'] = $this->db->get_where('subject', array('class_id' => $class_id))->result_array();
    $page_data['exam_data'] = $exam_data;

    $session_data = $page_data;
    $session_data['date'] = $date;

    $page_data['page_name'] = 'exam_view';
    $page_data['page_title'] = get_phrase('view_exam');
    $this->session->set_userdata('exam_data', $session_data);
    $this->load->view('backend/index', $page_data);
}

function exam_add($param1 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
    $page_data['error'] = 0;
    if ($param1 == 'error') {
        $page_data['error'] = 1;
    }
    $page_data['page_name'] = 'exam_add';
    $page_data['page_title'] = get_phrase('add_exam');
    $page_data['classes'] = $this->db->get('class')->result_array();
    $page_data['subjects'] = $this->db->get_where('subject', array('class_id' => $param1))->result_array();
    $this->load->view('backend/index', $page_data);
}

function exams($param1 = '') {
    if ($param1 == 'create') {
        $date = date("Y-m-d", strtotime($this->input->post('date')));
        $sql = "select if(count(question_id)>0,true,false) isExam from question where class_id=" . $this->input->post('class_id') . " and subject_id=" . $this->input->post('subject_id') . " and date='$date'";
        $result = $this->db->query($sql)->result_array();
        $isExam = $result[0]['isExam'];
        if (!$isExam) {
            $this->session->set_userdata('exams_header_data', array(
                'class_id' => $this->input->post('class_id'),
                'subject_id' => $this->input->post('subject_id'),
                'date' => $date,
                'session' => $this->input->post('session'),
                'question_count' => $this->input->post('question_count'),
                'duration' => $this->input->post('duration')
            ));
        } else {
            redirect(base_url() . 'index.php?admin/exam_add/error', 'refresh');
        }
    } else if ($param1 == 'add') {
        $date = date("Y-m-d", strtotime($this->input->post('date')));
        $this->session->set_userdata('exams_header_data', array(
            'class_id' => $this->input->post('class_id'),
            'subject_id' => $this->input->post('subject_id'),
            'date' => $date,
            'session' => $this->input->post('session'),
            'question_count' => $this->input->post('question_count'),
            'duration' => $this->input->post('duration')
        ));
    } else if ($param1 == 'save') {
        $data = array();
        $userdatasession = $this->session->userdata('exams_header_data');
        $data["class_id"] = $userdatasession['class_id'];
        $data["subject_id"] = $userdatasession['subject_id'];
        $data["date"] = $userdatasession['date'];
        $data["session"] = $userdatasession['session'];
        $data["question_count"] = $userdatasession['question_count'];
        $data["duration"] = $userdatasession['duration'];
        $data["question"] = $this->input->post('question');
        $data["correct_answers"] = $this->input->post('correct_answers');
        $result = $this->db->query("select max(question_id) max_id from question")->result();
        $question_id = $result[0]->max_id;
        $data["question_id"] = $question_id + 1;
        $this->db->insert('question', $data);
        $answers = $this->input->post('answers');
        for ($i = 0; $i < sizeof($answers); $i++) {
            $data = array();
            $data["question_id"] = $question_id + 1;
            $data["content"] = $answers[$i];
            $ascii_A = ord('A');
            $data["label"] = chr($ascii_A + $i);
            $this->db->insert('answer', $data);
        }
    }

    $page_data['page_name'] = 'exam_create';
    $page_data['page_title'] = get_phrase('add_exam');
    $this->load->view('backend/index', $page_data);
}

function exam_result_list() {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');

    $mode = $this->input->post('mode');
    if ($mode == 'get_list') {
        $class_id = $this->input->post('class_id');
        $subject_id = $this->input->post('subject_id');

        $sql = "select b.class_id, b.name class, a.student_id, a.name student, c.subject_id, c.name subject, d.date, d.duration, d.session, "
                . "d.question_count, sum(if(e.answer=d.correct_answers, 1, 0)) marks from student a "
                . "left join class b on a.class_id=b.class_id "
                . "left join subject c on b.class_id=c.class_id "
                . "left join question d on a.class_id=d.class_id and c.subject_id=d.subject_id "
                . "LEFT JOIN exam_result e ON d.question_id=e.question_id AND a.`student_id`=e.`student_id` "
                . "where a.class_id=" . $class_id . " and c.subject_id=" . $subject_id . " "
                . "GROUP BY a.`student_id`, c.subject_id, d.date, d.duration, d.session "
                . "order by b.class_id, a.student_id, c.subject_id";
        $result = $this->db->query($sql)->result_array();
        exit(json_encode($result));
    }

    $page_data['classes'] = $this->db->get('class')->result_array();
    $page_data['page_name'] = 'exam_result_list';
    $page_data['page_title'] = get_phrase('exam_result');
    $this->load->view('backend/index', $page_data);
}

function exam_result_detail() {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');

    if (!$this->input->post('class_id') || !$this->input->post('subject_id') || !$this->input->post('student_id') || !$this->input->post('date')) {
        redirect(base_url() . 'index.php?admin/exam_result_list', 'refresh');
    }

    $class_id = $this->input->post('class_id');
    $subject_id = $this->input->post('subject_id');
    $student_id = $this->input->post('student_id');
    $duration = $this->input->post('duration');
    $session = $this->input->post('session');
    $date = $this->input->post('date');

    $sql = "select a.*, e.name student,f.name class, g.name subject,b.date, b.question, b.correct_answers, c.content as answer_content, d.content as correct_content, if(c.content=d.content, 1, 0) marks, b.question_count "
            . "from exam_result a "
            . "inner join question b on a.question_id=b.question_id "
            . "inner join answer c on a.question_id=c.question_id and a.answer=c.label "
            . "inner join answer d on b.question_id=d.question_id and b.correct_answers=d.label "
            . "inner join student e on e.student_id=a.student_id "
            . "inner join class f on f.class_id=b.class_id "
            . "inner join subject g on g.subject_id=b.subject_id "
            . "where b.class_id=" . $class_id . " and b.subject_id=" . $subject_id
            . " and b.date='" . $date . "' and b.duration='" . $duration . "' "
            . "and b.session='" . $session . "' and a.student_id=" . $student_id;
    $page_data['detail_list'] = $this->db->query($sql)->result_array();

    $page_data['page_name'] = 'exam_result_detail';
    $page_data['page_title'] = get_phrase('exam_result');
    $this->load->view('backend/index', $page_data);
}

}
