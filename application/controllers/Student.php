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

class Student extends CI_Controller
{
    
    
    function __construct()
    {
        parent::__construct();
		$this->load->database();
        $this->load->library('session');
        /*cache control*/
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($this->session->userdata('student_login') == 1)
            redirect(base_url() . 'index.php?student/dashboard', 'refresh');
    }
    
    /***ADMIN DASHBOARD***/
    function dashboard()
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('student_dashboard');
        $this->load->view('backend/index', $page_data);
    }
    
    
    /****MANAGE TEACHERS*****/
    function teacher_list($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_teacher_id'] = $param2;
        }
        $page_data['teachers']   = $this->db->get('teacher')->result_array();
        $page_data['page_name']  = 'teacher';
        $page_data['page_title'] = get_phrase('manage_teacher');
        $this->load->view('backend/index', $page_data);
    }
    
    
    /***********************************************************************************************************/
    
    
    
    /****MANAGE SUBJECTS*****/
    function subject($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');
        
        $student_profile         = $this->db->get_where('student', array(
            'student_id' => $this->session->userdata('student_id')
        ))->row();
        $student_class_id        = $student_profile->class_id;
        $page_data['subjects']   = $this->db->get_where('subject', array(
            'class_id' => $student_class_id
        ))->result_array();
        $page_data['page_name']  = 'subject';
        $page_data['page_title'] = get_phrase('manage_subject');
        $this->load->view('backend/index', $page_data);
    }
    
    
    
    function student_marksheet($student_id = '') {
        if ($this->session->userdata('student_login') != 1)
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
        if ($this->session->userdata('student_login') != 1)
            redirect('login', 'refresh');
        $class_id     = $this->db->get_where('student' , array('student_id' => $student_id))->row()->class_id;
        $class_name   = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;

        $page_data['student_id'] =   $student_id;
        $page_data['class_id']   =   $class_id;
        $page_data['exam_id']    =   $exam_id;
        $this->load->view('backend/student/student_marksheet_print_view', $page_data);
    }
    
    
    /**********MANAGING CLASS ROUTINE******************/
    function class_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');
        
        $student_profile         = $this->db->get_where('student', array(
            'student_id' => $this->session->userdata('student_id')
        ))->row();
        $page_data['class_id']   = $student_profile->class_id;
        $page_data['page_name']  = 'class_routine';
        $page_data['page_title'] = get_phrase('manage_class_routine');
        $this->load->view('backend/index', $page_data);
    }
    
    /******MANAGE BILLING / INVOICES WITH STATUS*****/
    function invoice($param1 = '', $param2 = '', $param3 = '')
    {
        //if($this->session->userdata('student_login')!=1)redirect(base_url() , 'refresh');
        if ($param1 == 'make_payment') {
            $invoice_id      = $this->input->post('invoice_id');
            $system_settings = $this->db->get_where('settings', array(
                'type' => 'paypal_email'
            ))->row();
            $invoice_details = $this->db->get_where('invoice', array(
                'invoice_id' => $invoice_id
            ))->row();
            
            /****TRANSFERRING USER TO PAYPAL TERMINAL****/
            $this->paypal->add_field('rm', 2);
            $this->paypal->add_field('no_note', 0);
            $this->paypal->add_field('item_name', $invoice_details->title);
            $this->paypal->add_field('amount', $invoice_details->amount);
            $this->paypal->add_field('custom', $invoice_details->invoice_id);
            $this->paypal->add_field('business', $system_settings->description);
            $this->paypal->add_field('notify_url', base_url() . 'index.php?student/invoice/paypal_ipn');
            $this->paypal->add_field('cancel_return', base_url() . 'index.php?student/invoice/paypal_cancel');
            $this->paypal->add_field('return', base_url() . 'index.php?student/invoice/paypal_success');
            
            $this->paypal->submit_paypal_post();
            // submit the fields to paypal
        }
        if ($param1 == 'paypal_ipn') {
            if ($this->paypal->validate_ipn() == true) {
                $ipn_response = '';
                foreach ($_POST as $key => $value) {
                    $value = urlencode(stripslashes($value));
                    $ipn_response .= "\n$key=$value";
                }
                $data['payment_details']   = $ipn_response;
                $data['payment_timestamp'] = strtotime(date("m/d/Y"));
                $data['payment_method']    = 'paypal';
                $data['status']            = 'paid';
                $invoice_id                = $_POST['custom'];
                $this->db->where('invoice_id', $invoice_id);
                $this->db->update('invoice', $data);

                $data2['method']       =   'paypal';
                $data2['invoice_id']   =   $_POST['custom'];
                $data2['timestamp']    =   strtotime(date("m/d/Y"));
                $data2['payment_type'] =   'income';
                $data2['title']        =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->title;
                $data2['description']  =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->description;
                $data2['student_id']   =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->student_id;
                $data2['amount']       =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->amount;
                $this->db->insert('payment' , $data2);
            }
        }
        if ($param1 == 'paypal_cancel') {
            $this->session->set_flashdata('flash_message', get_phrase('payment_cancelled'));
            redirect(base_url() . 'index.php?student/invoice/', 'refresh');
        }
        if ($param1 == 'paypal_success') {
            $this->session->set_flashdata('flash_message', get_phrase('payment_successfull'));
            redirect(base_url() . 'index.php?student/invoice/', 'refresh');
        }
        $student_profile         = $this->db->get_where('student', array(
            'student_id'   => $this->session->userdata('student_id')
        ))->row();
        $student_id              = $student_profile->student_id;
        $page_data['invoices']   = $this->db->get_where('invoice', array(
            'student_id' => $student_id
        ))->result_array();
        $page_data['page_name']  = 'invoice';
        $page_data['page_title'] = get_phrase('manage_invoice/payment');
        $this->load->view('backend/index', $page_data);
    }
    
    /**********MANAGE LIBRARY / BOOKS********************/
    function book($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect('login', 'refresh');
        
        $page_data['books']      = $this->db->get('book')->result_array();
        $page_data['page_name']  = 'book';
        $page_data['page_title'] = get_phrase('manage_library_books');
        $this->load->view('backend/index', $page_data);
        
    }
	
	
	
    /**********MANAGE TRANSPORT / VEHICLES / ROUTES********************/
    function transport($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect('login', 'refresh');
        
        $page_data['transports'] = $this->db->get('transport')->result_array();
        $page_data['page_name']  = 'transport';
        $page_data['page_title'] = get_phrase('manage_transport');
        $this->load->view('backend/index', $page_data);
        
    }
	
	  /**********MANAGE TRANSPORT / VEHICLES / ROUTES********************/
    function assignment($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect('login', 'refresh');
        
        $page_data['assignments'] = $this->db->get('assignment')->result_array();
        $page_data['page_name']  = 'assignment';
        $page_data['page_title'] = get_phrase('manage_assignment');
        $this->load->view('backend/index', $page_data);
        
    }
	
	
	  /**********MANAGE HELP LINKS ********************/
    function help_link($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect('login', 'refresh');
        
        $page_data['help_links'] = $this->db->get('help_link')->result_array();
        $page_data['page_name']  = 'help_link';
        $page_data['page_title'] = get_phrase('manage_help_link');
        $this->load->view('backend/index', $page_data);
        
    }
	
	
	 /**********MANAGING MEDIA HERE*******************/
    function media($param1 = '', $param2 = '' , $param3 = '')
    {
       if ($this->session->userdata('student_login') != 1)
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
            redirect(base_url() . 'index.php?student/media' , 'refresh');
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
            redirect(base_url() . 'index.php?student/media/'.$data['media_id'], 'refresh');
			}
			
       if ($param1 == 'delete') {
            $this->db->where('media_id' , $param2);
            $this->db->delete('media');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?student/media' , 'refresh');
        }
		
        $page_data['page_name']  = 'media';
        $page_data['page_title'] = get_phrase('manage_media');
        $page_data['medias']  = $this->db->get('media')->result_array();
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	  /**********MANAGE TODAYS' THOUGHT ********************/
    function todays_thought($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect('login', 'refresh');
        
        $page_data['todays_thoughts'] = $this->db->get('todays_thought')->result_array();
        $page_data['page_name']  = 'todays_thought';
        $page_data['page_title'] = get_phrase('manage_todays_thought');
        $this->load->view('backend/index', $page_data);
        
    }
	
	
	  /**********MANAGE HOLIDAY ********************/
    function holiday($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect('login', 'refresh');
        
        $page_data['holidays'] = $this->db->get('holiday')->result_array();
        $page_data['page_name']  = 'holiday';
        $page_data['page_title'] = get_phrase('manage_holiday');
        $this->load->view('backend/index', $page_data);
        
    }
	
	
	/****MANAGE HELP DESK*****/
    function help_desk($param1 = '', $param2 = '')
    {
         if ($this->session->userdata('student_login') != 1)
            redirect('login', 'refresh');
			
        if ($param1 == 'create') {
            
			$data['name']         = $this->input->post('name');
            $data['purpose'] = $this->input->post('purpose');
            $data['content'] = $this->input->post('content');
            
            $this->db->insert('help_desk', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('help_submitted_successfully'));
            redirect(base_url() . 'index.php?student/help_desk', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']         = $this->input->post('name');
            $data['purpose'] = $this->input->post('purpose');
            $data['content'] = $this->input->post('content');
            
            $this->db->where('helpdesk_id', $param2);
            $this->db->update('help_desk', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?student/help_desk', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('help_desk', array(
                'helpdesk_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('helpdesk_id', $param2);
            $this->db->delete('help_desk');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?student/help_desk', 'refresh');
        }
        $page_data['help_desk']    = $this->db->get('help_desk')->result_array();
        $page_data['page_name']  = 'help_desk';
        $page_data['page_title'] = get_phrase('manage_help_desk');
        $this->load->view('backend/index', $page_data);
    }
	
	
	
	  /**********MANAGE TRANSPORT / VEHICLES / ROUTES********************/
    function news($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect('login', 'refresh');
        
        $page_data['newss'] = $this->db->get('news')->result_array();
        $page_data['page_name']  = 'news';
        $page_data['page_title'] = get_phrase('manage_news');
        $this->load->view('backend/index', $page_data);
        
    }
	
	
    /**********MANAGE DORMITORY / HOSTELS / ROOMS ********************/
    function dormitory($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect('login', 'refresh');
        
        $page_data['dormitories'] = $this->db->get('dormitory')->result_array();
        $page_data['page_name']   = 'dormitory';
        $page_data['page_title']  = get_phrase('manage_dormitory');
        $this->load->view('backend/index', $page_data);
        
    }
    
    /**********WATCH NOTICEBOARD AND EVENT ********************/
    function noticeboard($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect('login', 'refresh');
        
        $page_data['notices']    = $this->db->get('noticeboard')->result_array();
        $page_data['page_name']  = 'noticeboard';
        $page_data['page_title'] = get_phrase('noticeboard');
        $this->load->view('backend/index', $page_data);
        
    }
    
    /**********MANAGE DOCUMENT / home work FOR A SPECIFIC CLASS or ALL*******************/
    function document($do = '', $document_id = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect('login', 'refresh');
        
        $page_data['page_name']  = 'manage_document';
        $page_data['page_title'] = get_phrase('manage_documents');
        $page_data['documents']  = $this->db->get('document')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    /* private messaging */

    function message($param1 = 'message_home', $param2 = '', $param3 = '') {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'send_new') {
            $message_thread_code = $this->crud_model->send_new_private_message();
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(base_url() . 'index.php?student/message/message_read/' . $message_thread_code, 'refresh');
        }

        if ($param1 == 'send_reply') {
            $this->crud_model->send_reply_message($param2);  //$param2 = message_thread_code
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(base_url() . 'index.php?student/message/message_read/' . $param2, 'refresh');
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
    
    /******MANAGE OWN PROFILE AND CHANGE PASSWORD***/
    function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($param1 == 'update_profile_info') {
            $data['name']        = $this->input->post('name');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('student_id', $this->session->userdata('student_id'));
            $this->db->update('student', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $this->session->userdata('student_id') . '.jpg');
            $this->session->set_flashdata('flash_message', get_phrase('account_updated'));
            redirect(base_url() . 'index.php?student/manage_profile/', 'refresh');
        }
        if ($param1 == 'change_password') {
            $data['password']             = $this->input->post('password');
            $data['new_password']         = $this->input->post('new_password');
            $data['confirm_new_password'] = $this->input->post('confirm_new_password');
            
            $current_password = $this->db->get_where('student', array(
                'student_id' => $this->session->userdata('student_id')
            ))->row()->password;
            if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
                $this->db->where('student_id', $this->session->userdata('student_id'));
                $this->db->update('student', array(
                    'password' => $data['new_password']
                ));
                $this->session->set_flashdata('flash_message', get_phrase('password_updated'));
            } else {
                $this->session->set_flashdata('flash_message', get_phrase('password_mismatch'));
            }
            redirect(base_url() . 'index.php?student/manage_profile/', 'refresh');
        }
        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        $page_data['edit_data']  = $this->db->get_where('student', array(
            'student_id' => $this->session->userdata('student_id')
        ))->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    /*****************SHOW STUDY MATERIAL / for students of a specific class*******************/
    function study_material($task = "", $document_id = "")
    {
        if ($this->session->userdata('student_login') != 1)
        {
            $this->session->set_userdata('last_page' , current_url());
            redirect(base_url(), 'refresh');
        }
        
        $data['study_material_info']    = $this->crud_model->select_study_material_info_for_student();
        $data['page_name']              = 'study_material';
        $data['page_title']             = get_phrase('study_material');
        $this->load->view('backend/index', $data);
    }

// COMPUTER BASED TEST START HERE
	
	 function exam($cur_page, $subject_id = '', $session = '') {
        if ($this->session->userdata('student_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        $student_profile = $this->db->get_where('student', array(
                    'student_id' => $this->session->userdata('student_id')
                ))->row();
        $student_class_id = $student_profile->class_id;

        if ($cur_page == 'first') {
            $sql = "select b.class_id, b.name class, a.student_id, a.name student, c.subject_id, c.name subject, d.*, "
                    . "sum(if(e.answer=d.correct_answers, 1, 0)) marks, count(e.question_id) result_count "
                    . "from student a "
                    . "left join class b on a.class_id=b.class_id "
                    . "left join subject c on b.class_id=c.class_id "
                    . "left join question d on a.class_id=d.class_id and c.subject_id=d.subject_id "
                    . "LEFT JOIN exam_result e ON d.question_id=e.question_id AND a.`student_id`=e.`student_id` "
                    . "where a.class_id=" . $student_class_id . " "
                    . "and d.date=CURDATE() and a.student_id=" . $this->session->userdata('student_id')
                    . " GROUP BY a.`student_id`, c.subject_id, d.date, d.duration, d.session "
                    . "order by b.class_id, a.student_id, c.subject_id";

//            $sql = "select a.*, b.subject_id, b.name from question a "
//                    . "inner join subject b on a.subject_id=b.subject_id "
//                    . "where a.class_id='" . $student_class_id . "' and a.date=CURDATE() group by a.subject_id order by a.subject_id";
            $data['subject_list'] = $this->db->query($sql)->result_array();
            $data['page_name'] = 'exam_first';
            $data['page_title'] = get_phrase('exam');
            $this->load->view('backend/index', $data);
        } else if ($cur_page == 'second') {
//            $sql = "delete exam_result.* from exam_result "
//                    . "inner join question on exam_result.question_id=question.question_id "
//                    . "where question.class_id=" . $student_class_id . " and question.subject_id=" . $subject_id
//                    . " and question.date=CURDATE() "
//                    . "and question.session='" . $session . "' and exam_result.student_id=" . $this->session->userdata('student_id');
//            $this->db->query($sql);

            $sql = "select count(question_id) question_count, b.subject_id, b.name, a.* from question a "
                    . "inner join subject b on a.subject_id=b.subject_id "
                    . "where a.class_id='" . $student_class_id . "' and a.date=CURDATE() and a.subject_id='" . $subject_id . "'";
            $result = $this->db->query($sql)->result_array();
            $data['data'] = $result[0];
            $data['page_name'] = 'exam_second';
            $data['page_title'] = get_phrase('exam');
            $this->load->view('backend/index', $data);
        }
    }

    function exam_site($mode) {
        if ($this->session->userdata('student_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        $cur_data = array();
        if ($this->session->userdata('cur_exam_data'))
            $cur_data = $this->session->userdata('cur_exam_data');
        if ($mode == 'cur_exam') {
            $cur_exam_data = array();
            $cur_exam_data['answer'] = $this->input->post('answer');
            $cur_exam_data['cur_num'] = $this->input->post('cur_num');
            $cur_exam_data['question_id'] = $this->input->post('question_id');

            $num_array = array();
            for ($i = 0; $i < sizeof($cur_data); $i++) {
                array_push($num_array, $cur_data[$i]['cur_num']);
            }

            if (in_array($cur_exam_data['cur_num'], $num_array)) {
                $index = array_search($cur_exam_data['cur_num'], $num_array);
                $cur_data[$index] = $cur_exam_data;
            } else {
                array_push($cur_data, $cur_exam_data);
            }

            if ($cur_exam_data['answer'] != '') {
                $this->session->set_userdata('cur_exam_data', $cur_data);
                $this->session->set_userdata('cur_data', json_encode($cur_data));
                $this->session->set_userdata('cur_num', $this->input->post('cur_num'));
            }
            $this->session->set_userdata('countDownDate', $this->input->post('countDownDate'));
            exit($this->session->userdata('cur_data'));
        } else if ($mode == 'finish_exam') {
            $exam_result = $this->session->userdata('cur_exam_data');
            if ($exam_result) {
                if (sizeof($exam_result) > 0) {
                    $sql = "insert into exam_result(student_id, question_id, answer) values ";
                    foreach ($exam_result as $row) {
                        $value_sql .=",('" . $this->session->userdata('student_id') . "','" . $row['question_id'] . "','" . $row['answer'] . "')";
                    }
                    $value_sql = substr($value_sql, 1);
                    $sql .= $value_sql;
                    $this->db->query($sql);
                }
            }
            $param = $this->session->userdata('exam_data');
            redirect(base_url() . 'index.php?student/exam_result/' . $param['question_count'] . '/' . $param['class_id'] . '/' . $param['subject_id'] . '/' . $param['date'] . '/' . $param['duration'] . '/' . $param['session'], 'refresh');
            exit;
        }

        if (!$this->session->userdata('exam_data')) {
            $class_id = $this->input->post('class_id');
            $subject_id = $this->input->post('subject_id');
            $date = $this->input->post('date');
            $duration = $this->input->post('duration');
            $session = $this->input->post('session');
            $question_count = $this->input->post('question_count');

            $question_temp = array();
            $sql = "select * from question "
                    . "where class_id=" . $class_id . " and subject_id=" . $subject_id . " and date='" . $date . "' "
                    . "and duration='" . $duration . "' and session='" . $session . "' "
                    . "order by question_id";
            $result = $this->db->query($sql)->result_array();

            foreach ($result as $row) {
                $temp = $row;
                $sql = "select * from answer "
                        . "where question_id=" . $row["question_id"]
                        . " order by label";
                $result1 = $this->db->query($sql)->result_array();
                $temp['answers'] = $result1;
                array_push($question_temp, $temp);
            }

            $question_data = array();
            $norepeat_nums = array();
            for ($i = 0; $i < $question_count; $i++) {
                do {
                    $num = rand(0, sizeof($question_temp) - 1);
                } while (in_array($num, $norepeat_nums));
                array_push($norepeat_nums, $num);

                array_push($question_data, $question_temp[$num]);

                if (sizeof($norepeat_nums) == sizeof($question_temp)) {
                    $norepeat_nums = array();
                }
            }
            $data['class_id'] = $class_id;
            $data['subject_id'] = $subject_id;
            $data['date'] = $date;
            $data['duration'] = $duration;
            $data['session'] = $session;
            $data['question_count'] = $question_count;
            $data['question_data_json'] = json_encode($question_data);
            $data['question_data'] = $question_data;
            $data['page_name'] = 'exam_site';
            $data['page_title'] = get_phrase('exam_site');
            $this->session->set_userdata('exam_data', $data);
        }

        $this->load->view('backend/index', $this->session->userdata('exam_data'));
    }

    function exam_result($question_count, $class_id, $subject_id, $date, $duration, $session) {
        if ($this->session->userdata('student_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        $sql = "select a.*, b.question, b.correct_answers, c.content as answer_content, d.content as correct_content, if(c.content=d.content, 1, 0) marks  "
                . "from exam_result a "
                . "inner join question b on a.question_id=b.question_id "
                . "inner join answer c on a.question_id=c.question_id and a.answer=c.label "
                . "inner join answer d on b.question_id=d.question_id and b.correct_answers=d.label "
                . "where b.class_id=" . $class_id . " and b.subject_id=" . $subject_id
                . " and b.date='" . $date . "' and b.duration='" . $duration . "' "
                . "and b.session='" . $session . "' and a.student_id=" . $this->session->userdata('student_id');
        $data['result'] = $this->db->query($sql)->result_array();
        $data['question_count'] = $question_count;

        $this->session->unset_userdata('cur_exam_data');
        $this->session->unset_userdata('cur_data');
        $this->session->unset_userdata('cur_num');
        $this->session->unset_userdata('countDownDate');
        $this->session->unset_userdata('exam_data');

        $data['page_name'] = 'exam_result';
        $data['page_title'] = get_phrase('exam_result');
        $this->load->view('backend/index', $data);
    }

    function exam_review() {
        if ($this->session->userdata('student_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

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
                    . "where a.student_id=" . $this->session->userdata('student_id')
                    . " GROUP BY a.`student_id`, c.subject_id, d.date, d.duration, d.session "
                    . "order by b.class_id, a.student_id, c.subject_id";
            $result = $this->db->query($sql)->result_array();
            exit(json_encode($result));
        }

        $data['page_name'] = 'exam_review';
        $data['page_title'] = get_phrase('exam_review');
        $this->load->view('backend/index', $data);
    }

    function exam_result_detail() {
        if ($this->session->userdata('student_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        if (!$this->input->post('class_id') || !$this->input->post('subject_id') || !$this->input->post('student_id') || !$this->input->post('date')) {
            redirect(base_url() . 'index.php?student/exam_review', 'refresh');
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
