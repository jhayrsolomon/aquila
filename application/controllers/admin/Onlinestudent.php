<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Onlinestudent extends Admin_Controller
{

    public $sch_setting_detail = array();
    public function __construct()
    {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('encoding_lib');
        $this->load->library('email');
        $this->load->model("classteacher_model");
        $this->load->model("timeline_model");
        $this->load->model("feesessiongroup_model");
        $this->load->model('setting_model');
        $this->load->model('class_model');
        $this->load->model('onlinestudent_model');
        $this->load->model('vehroute_model');
        $this->load->model('hostel_model');
        $this->load->model('student_model');
        $this->load->model('feegroup_model');
        $this->load->model('feediscount_model');
        $this->load->model('section_model');
        $this->load->model('houselist_model');
        $this->load->model('category_model');

        $this->blood_group        = $this->config->item('bloodgroup');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->role;
    }

    public function index()
    {
         if (!$this->rbac->hasPrivilege('online_admission', 'can_view')) {
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'onlinestudent');
        $data['title']  = 'Student List';
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        
        if(!empty($data['classlist'])) {
            foreach ($data['classlist'] as $key => $value) {
                $carray[] = $value['id']; 
            }
        }

        $student_result = $this->onlinestudent_model->get(null, $carray);
        $data['studentlist'] = $student_result;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/onlinestudent/studentList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function download($doc)
    {
        $this->load->helper('download');
        $filepath = "./uploads/student_documents/online_admission_doc/" . urldecode($doc);
        $data     = file_get_contents($filepath);
        $name     = $this->uri->segment(6);
        force_download($name, $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('online_admission', 'can_delete')) {
            access_denied();
        }
        $this->onlinestudent_model->remove($id);

        redirect('admin/onlinestudent');
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('online_admission', 'can_edit')) {
            access_denied();
        }        

        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['title']           = 'Edit Student';
        $data['id']              = $id;
        $student                 = $this->onlinestudent_model->get($id);
        $genderList              = $this->customlib->getGender();
        $data['student']         = $student;
        $data['genderList']      = $genderList;
        $session                 = $this->setting_model->getCurrentSession();
        $vehroute_result         = $this->vehroute_model->get();
        $data['vehroutelist']    = $vehroute_result;
        $class                   = $this->class_model->get();
        $setting_result          = $this->setting_model->get();
		$data["bloodgroup"]         = $this->blood_group;
        $data["student_categorize"] = 'class';
        $data['classlist']          = $class;
        $category                   = $this->category_model->get();
        $data['categorylist']       = $category;
        $hostelList                 = $this->hostel_model->get();
        $data['hostelList']         = $hostelList;
        $houses                     = $this->houselist_model->get();
        $data['houses']             = $houses;
        $data['enrollment_type_list'] = $this->onlinestudent_model->GetEnrollmentTypes();
        $data['payment_mode_list'] = $this->onlinestudent_model->GetModesOfPayment();
        $siblings                   = $this->student_model->getMySiblings($student['parent_id'], $student['id']);
        $data['siblings']           = $siblings;
        $data['siblings_counts']    = count($siblings);

        $data['fees_master_list'] = $this->feegroup_model->getFeesByGroupFiltered(); //$this->feegroup_model->get();
        // var_dump($data['fees_master_list']);die;
        $data['discount_list'] = $this->feediscount_model->get();
        $data['payment_scheme_list'] = $this->onlinestudent_model->GetPaymentSchemes();


        $this->form_validation->set_rules('firstname', $this->lang->line('required'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('lastname', $this->lang->line('required'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('guardian_is', $this->lang->line('required'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', $this->lang->line('required'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('required'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('required'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', $this->lang->line('required'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('guardian_name', $this->lang->line('required'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('rte', $this->lang->line('required'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('guardian_phone', $this->lang->line('required'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('guardian_email', $this->lang->line('required'), 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('enrollment_type', $this->lang->line('required'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('mode_of_payment', $this->lang->line('required'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('preferred_education_mode', $this->lang->line('required'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('fees_assessment', $this->lang->line('fees_assessment'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('payment_scheme', $this->lang->line('required'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) 
        {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/onlinestudent/studentEdit', $data);
            $this->load->view('layout/footer', $data);
        } 
        else 
        {
            $student_id     = $this->input->post('student_id');
            $class_id       = $this->input->post('class_id');
            $section_id     = $this->input->post('section_id');
            //$hostel_room_id = $this->input->post('hostel_room_id');
            $fees_discount  = $this->input->post('fees_discount');
            //$vehroute_id    = $this->input->post('vehroute_id');
            //$class_section_id = $this->onlinestudent_model->GetClassSectionID($class_id, $section_id);
            
            // if (empty($vehroute_id)) {
            //     $vehroute_id = 0;
            // }
            // if (empty($hostel_room_id)) {
            //     $hostel_room_id = 0;
            // }

            // var_dump($student_id);die;

            $data = array(
                'sibling_id'          => $this->input->post('sibling_id'),
                'id'                  => $student_id,
                'admission_no'        => $this->input->post('admission_no'),
                'firstname'           => $this->input->post('firstname'),
                'lastname'            => $this->input->post('lastname'),
                //'rte'                 => $this->input->post('rte'),
                'mobileno'            => $this->input->post('mobileno'),
                'email'               => $this->input->post('email'),
                'state'               => $this->input->post('state'),
                'city'                => $this->input->post('city'),
                'previous_school'     => $this->input->post('previous_school'),
                'guardian_is'         => $this->input->post('guardian_is'),
                // 'pincode'             => $this->input->post('pincode'),
                'measurement_date'    => date('Y-m-d', strtotime($this->input->post('measurement_date'))),
                'religion'            => $this->input->post('religion'),
                'dob'                 => date('Y-m-d', strtotime($this->input->post('dob'))),
                'admission_date'      => date('Y-m-d', strtotime($this->input->post('admission_date'))),
                'current_address'     => $this->input->post('current_address'),
                'permanent_address'   => $this->input->post('permanent_address'),
                'image'               => 'uploads/student_images/no_image.png',
                // 'category_id'         => $this->input->post('category_id'),
                // 'adhar_no'            => $this->input->post('adhar_no'),
                // 'samagra_id'          => $this->input->post('samagra_id'),
                // 'bank_account_no'     => $this->input->post('bank_account_no'),
                // 'bank_name'           => $this->input->post('bank_name'),
                // 'ifsc_code'           => $this->input->post('ifsc_code'),
                // 'cast'                => $this->input->post('cast'),
                'father_name'         => $this->input->post('father_name'),
                'father_phone'        => $this->input->post('father_phone'),
                'father_occupation'   => $this->input->post('father_occupation'),
                'mother_name'         => $this->input->post('mother_name'),
                'mother_phone'        => $this->input->post('mother_phone'),
                'mother_occupation'   => $this->input->post('mother_occupation'),
                'guardian_occupation' => $this->input->post('guardian_occupation'),
                'guardian_email'      => $this->input->post('guardian_email'),
                'gender'              => $this->input->post('gender'),
                'guardian_name'       => $this->input->post('guardian_name'),
                'guardian_relation'   => $this->input->post('guardian_relation'),
                'guardian_phone'      => $this->input->post('guardian_phone'),
                'guardian_address'    => $this->input->post('guardian_address'),
                // 'vehroute_id'         => $vehroute_id,
                // 'hostel_room_id'      => $hostel_room_id,
                // 'school_house_id'     => $this->input->post('house'),
                'blood_group'         => $this->input->post('blood_group'),
                'height'              => $this->input->post('height'),
                'weight'              => $this->input->post('weight'),
                'note'                => $this->input->post('note'),
                'class_section_id'    => $section_id,
                'enrollment_type'     => strtolower($this->input->post('enrollment_type')),
                'mode_of_payment'     => $this->input->post('mode_of_payment'),
                'middlename'          => $this->input->post('middlename'),
                'lrn_no'              => $this->input->post('lrn_no'),
                'roll_no'             => $this->input->post('roll_no'),

                'father_company_name'              => $this->input->post('father_company_name'),
                'father_company_position'          => $this->input->post('father_company_position'),
                'father_nature_of_business'        => $this->input->post('father_nature_of_business'),
                'father_mobile'                    => $this->input->post('father_mobile'),
                'father_dob'                       => date('Y-m-d', strtotime($this->input->post('father_dob'))),
                'father_citizenship'               => $this->input->post('father_citizenship'),
                'father_religion'                  => $this->input->post('father_religion'),
                'father_highschool'                => $this->input->post('father_highschool'),
                'father_college'                   => $this->input->post('father_college'),
                'father_college_course'            => $this->input->post('father_college_course'),
                'father_post_graduate'             => $this->input->post('father_post_graduate'),
                'father_post_course'               => $this->input->post('father_post_course'),
                'father_prof_affiliation'          => $this->input->post('father_prof_affiliation'),
                'father_prof_affiliation_position' => $this->input->post('father_prof_affiliation_position'),
                'father_tech_prof'                 => $this->input->post('father_tech_prof'),
                'father_tech_prof_other'           => $this->input->post('father_tech_prof_other'),

                'mother_company_name'              => $this->input->post('mother_company_name'),
                'mother_company_position'          => $this->input->post('mother_company_position'),
                'mother_nature_of_business'        => $this->input->post('mother_nature_of_business'),
                'mother_mobile'                    => $this->input->post('mother_mobile'),
                'mother_dob'                       => date('Y-m-d', strtotime($this->input->post('mother_dob'))),
                'mother_citizenship'               => $this->input->post('mother_citizenship'),
                'mother_religion'                  => $this->input->post('mother_religion'),
                'mother_highschool'                => $this->input->post('mother_highschool'),
                'mother_college'                   => $this->input->post('mother_college'),
                'mother_college_course'            => $this->input->post('mother_college_course'),
                'mother_post_graduate'             => $this->input->post('mother_post_graduate'),
                'mother_post_course'               => $this->input->post('mother_post_course'),
                'mother_prof_affiliation'          => $this->input->post('mother_prof_affiliation'),
                'mother_prof_affiliation_position' => $this->input->post('mother_prof_affiliation_position'),
                'mother_tech_prof'                 => $this->input->post('mother_tech_prof'),
                'mother_tech_prof_other'           => $this->input->post('mother_tech_prof_other'),

                'marriage'                   => $this->input->post('marriage'),
                'dom'                        => date('Y-m-d', strtotime($this->input->post('dom'))),
                'church'                     => $this->input->post('church'),
                'family_together'            => $this->input->post('family_together'),
                'parents_away'               => $this->input->post('parents_away'),
                'parents_away_state'         => $this->input->post('parents_away_state'),
                'parents_civil_status'       => $this->input->post('parents_civil_status'),
                'parents_civil_status_other' => $this->input->post('parents_civil_status_other'),

                'guardian_address_is_current_address' => $this->input->post('guardian_address_is_current_address') == "on" ? 1 : 0,
                'permanent_address_is_current_address' => $this->input->post('permanent_address_is_current_address') == "on" ? 1 : 0,
                'living_with_parents' => $this->input->post('living_with_parents'),
                'living_with_parents_specify' => $this->input->post('living_with_parents_specify'),
                'has_siblings_enrolled' => $this->input->post('has_siblings_enrolled'),
                // 'siblings_specify' => $this->input->post('siblings_specify'),
                'preferred_education_mode' => $this->input->post('preferred_education_mode'),
                'feesmaster' => $this->input->post('feesmaster[]'),
                'feesdiscount' => $this->input->post('discount[]'),

                'payment_scheme' => $this->input->post('payment_scheme'),
            );

            // var_dump($data);die;
            // print_r("EMN Debug Mode");die();

            $response = $this->onlinestudent_model->update($data, $this->input->post('save'));
          
            if ($response) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
                redirect('admin/onlinestudent');
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('please_check_student_admission_no').'</div>');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public function getByClass()
    {
        $class_id = $this->input->post('class_id');
        $data     = $this->section_model->getClassBySection($class_id);
        $this->jsonlib->output(200, $data);
    }    
}
