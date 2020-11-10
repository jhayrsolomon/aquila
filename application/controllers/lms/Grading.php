<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Grading extends General_Controller {

    function __construct() {
        
        parent::__construct();

        $this->session->set_userdata('top_menu', 'Grading');
        $this->writedb = $this->load->database('write_db', TRUE);
        $this->load->model('assessment_model');
        $this->load->model('general_model');
        $this->load->model('lesson_model');
        $this->load->model('setting_model');

        date_default_timezone_set('Asia/Manila');
    
    }


    function index(){
        $data['subjects'] = $this->assessment_model->lms_get('subjects',"","","id,name");
        $data['classes'] = $this->general_model->get_classes();
        $data['sections'] = $this->general_model->get_sections();
        $data['quarters'] = $this->general_model->lms_get('grading_quarter',"","");
        
        $this->db->select("*,grading_class_record.id as id");
        $this->db->from("grading_class_record");
        $this->db->join("classes","classes.id = grading_class_record.grade");
        $this->db->join("subjects","subjects.id = grading_class_record.subject_id");
        $this->db->join("sections","sections.id = grading_class_record.section_id");
        $this->db->join("grading_quarter","grading_quarter.id = grading_class_record.quarter");
        $this->db->where("grading_class_record.disabled",0);
        
        $data['list'] = $this->db->get()->result_array();
        // echo '<pre>';
        // print_r($data['list']);
        // exit;
        $this->load->view('layout/header');
        $this->load->view('lms/grading/index', $data);
        $this->load->view('layout/footer');
    }

    function setup(){

        $data['classes'] = $this->general_model->get_classes();
        $data['sections'] = $this->general_model->get_sections();
        $data['subjects'] = $this->assessment_model->lms_get('subjects',"","","id,name");
        $data['quarters'] = $this->general_model->lms_get('grading_quarter',"","");
        $this->load->view('layout/header');
        $this->load->view('lms/grading/setup', $data);
        $this->load->view('layout/footer');
    }

    

    function post($fields,$table){
        $fields_string = "";
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        $api_url = base_url("api/GradingAPI/");
        $the_url = $api_url."/".$table."/";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$the_url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);

        $server_output = curl_exec($ch);
        
        curl_close ($ch);

        return $server_output;
    }

    function get($fields,$table){
        
        $api_url = base_url("api/GradingAPI/");
        $the_url = $api_url."/".$table."/";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$the_url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        
        curl_close ($ch);

        return $server_output;
    }

    function edit($id) {

        $this->session->set_userdata('top_menu', 'Grading');
        $this->session->set_userdata('sub_menu', 'grading/edit');

        $data['resources'] = site_url('backend/lms/');
        $class_record_id = $id;
        $data['class_record'] = json_decode($this->get($class_record_id,"class_record"))[0];
        $this->db->select("*");
        $this->db->from("grading_criteria");
        $this->db->where("class_record_id",$class_record_id);
        $data['criteria'] = $this->db->get()->result_array();

        $data['full_width'] = 0;
        
        foreach ($data['criteria'] as $criteria_key => $criteria_value) {
            $this->db->select("*");
            $this->db->from("grading_column_section");
            $this->db->where("criteria_id", $criteria_value['id']);
            $this->db->order_by("column_section_order", "asc");
            $data['criteria'][$criteria_key]['column_section'] = $this->db->get()->result_array();
            $data['criteria'][$criteria_key]['criteria_column'] = 0;
            foreach ($data['criteria'][$criteria_key]['column_section'] as $column_section_key => $column_section_value)
            {
                $this->db->select("*");
                $this->db->from("grading_column");
                $this->db->where("column_section_id", $column_section_value['id']);
                $data['criteria'][$criteria_key]['column_section'][$column_section_key]['column'] = $this->db->get()->result_array();
                $data['criteria'][$criteria_key]['column_section'][$column_section_key]['column_count'] = count($data['criteria'][$criteria_key]['column_section'][$column_section_key]['column']);
                $data['criteria'][$criteria_key]['column_section'][$column_section_key]['column_count_td'] = count($data['criteria'][$criteria_key]['column_section'][$column_section_key]['column'])+3;
                $data['criteria'][$criteria_key]['criteria_column'] += $data['criteria'][$criteria_key]['column_section'][$column_section_key]['column_count']+3;
            }
            $data['full_width'] += $data['criteria'][$criteria_key]['criteria_column'];
            // array_push($data['criteria'][$criteria_key]['columnn_section'],$this->db->get()->result_array());

        }


        $data['subjects'] = $this->assessment_model->lms_get('subjects',"","","id,name");
        
        $data['classes'] = $this->general_model->get_classes();
        $data['sections'] = $this->general_model->get_sections();
        $data['quarters'] = $this->general_model->lms_get('grading_quarter',"","");
        $data['transmutation'] = json_encode($this->general_model->lms_get('grading_transmutation',"","min_grade,max_grade,transmuted_grade"));
        $data['quarter'] = $this->general_model->lms_get('grading_quarter',$data['class_record']->quarter,"id")[0]['id'];


        
        $data['school_year'] = $this->general_model->lms_get("sessions",$data['class_record']->school_year,"id")[0]['session'];
        // echo '<pre>';
        // print_r($data['quarter']);
        // exit;
        $data['students'] = $this->general_model->get_students_class_section($data['class_record']->grade,$data['class_record']->section_id);

        $data['the_class'] = $this;
        $current_session = $this->setting_model->getCurrentSession();
        
        
        
        $this->load->view('lms/grading/edit', $data);
    }

    function update_column(){

        $column_id = $_REQUEST['column_id'];
        $student_id = $_REQUEST['student_id'];
        $score = $_REQUEST['score'];
        $id = $this->assessment_model->id_generator("column_score");
        $this->db->select("*");
        $this->db->from("grading_column_scores");
        $this->db->where("column_id",$column_id);
        $this->db->where("student_id",$student_id);
        $column_score = $this->db->get()->result_array();

        if(!count($column_score)){
            $insert_column_score = array(
                "id" => $id,
                "column_id" => $column_id,
                "student_id" => $student_id,
                "score" => $score,
            );
            $inserted_column = $this->general_model->lms_create("grading_column_scores",$insert_column_score);

            print_r($inserted_column);
        }else{
            $update_data = $column_score[0];
            $update_data['score'] = $score;
            $updated_data = $this->general_model->lms_update("grading_column_scores",$update_data);
            print_r($updated_data);
        }

        

    }

    function update_highest_score(){

        $column_id = $_REQUEST['column_id'];
        $highest_score = $_REQUEST['highest_score'];

        
        $update_data['id'] = $column_id;
        $update_data['highest_score'] = $highest_score;
        $updated_data = $this->general_model->lms_update("grading_column",$update_data);
        print_r($update_data);
        

    }
    function update_ws(){

        $column_section = $_REQUEST['column_section'];
        $highest_score = $_REQUEST['highest_score'];

        
        $update_data['id'] = $column_section;
        $update_data['ws'] = $highest_score;
        $updated_data = $this->general_model->lms_update("grading_column_section",$update_data);
        print_r($update_data);
        print_r($updated_data);
        

    }
    function delete($id){

        $id = $id;
        $highest_score = $_REQUEST['highest_score'];

        
        $update_data['id'] = $id;
        $update_data['disabled'] = 1;
        $updated_data = $this->general_model->lms_update("grading_class_record",$update_data);
        redirect(base_url('lms/grading/index/'.$id));
        

    }


    public function get_column_score($column_id,$student_id){

        
        $this->db->select("*");
        $this->db->from("grading_column_scores");
        $this->db->where("column_id",$column_id);
        $this->db->where("student_id",$student_id);
        $column_score = $this->db->get()->result_array();
        
        if(count($column_score)){
            return $column_score[0]['score'];
        }else{
            return '';
        }

        

    }

    function update_grade_section($class_record_id){
        print_r($_REQUEST);
        $update_data = array(
            "id" => $class_record_id,
            "grade" => $_REQUEST['grade'],
            "section_id" => $_REQUEST['section'],
        );
        $updated_data = $this->general_model->lms_update("grading_class_record",$update_data);
        redirect(base_url('lms/grading/edit/'.$class_record_id));
    }

    function update_class_record(){

        $update_data = array(
            "id" => $_REQUEST['id'],
            "region" => $_REQUEST['region'],
            "division" => $_REQUEST['division'],
            "district" => $_REQUEST['district'],
            "school_name" => $_REQUEST['school_name'],
            "school_id" => $_REQUEST['school_id'],
        );
        $updated_data = $this->general_model->lms_update("grading_class_record",$update_data);
        print_r($updated_data);
        // redirect(base_url('lms/grading/edit/'.$class_record_id));
    }

    function create() {

        // echo '<pre>';
        // print_r($_REQUEST);
        // exit;
        $this->session->set_userdata('top_menu', 'Grading');
        $this->session->set_userdata('sub_menu', 'grading/create');
        $data['resources'] = site_url('backend/lms/');
        $class_record_id = $this->assessment_model->id_generator("class_record");
        $criteria_1_id = $this->assessment_model->id_generator("criteria");
        $criteria_2_id = $this->assessment_model->id_generator("criteria");
        $column_section_1_1_id = $this->assessment_model->id_generator("column_section");
        $column_section_1_2_id = $this->assessment_model->id_generator("column_section");
        $column_section_2_1_id = $this->assessment_model->id_generator("column_section");
        $data['account_id'] = $this->general_model->get_account_id();
        
        $class_record = array(
            'id' =>             urlencode($class_record_id),
            'region' =>         urlencode("Region 1"),
            'division' =>       urlencode("Division 2"),
            'district' =>       urlencode("District 1"),
            'school_name' =>    urlencode("San Isidro Catholic School"),
            'school_id' =>      urlencode("1"),
            'school_year' =>    urlencode("15"),
            'quarter' =>        urlencode($_REQUEST['quarter']),
            'section_id' =>     urlencode($_REQUEST['section']),
            'teacher_id' =>     urlencode("1"),
            'subject_id' =>     urlencode($_REQUEST['subject']),
            'grade' =>          urlencode($_REQUEST['grade']),
            'disabled' =>          urlencode(0),
        );
        $this->post($class_record,"class_record");
        // $this->general_model->lms_create("class_record",$class_record);
        
        $criteria_1 = array(
            'id' =>             urlencode($criteria_1_id),
            'name' =>           urlencode("Written Works"),
            'criteria_order' => urlencode("1"),
            'class_record_id' =>urlencode($class_record_id),
            'percentage' =>urlencode(50),    
        );
        $this->post($criteria_1,"criteria");

        $column_section_1_1 = array(
            'id' =>              urlencode($column_section_1_1_id),
            'criteria_id' =>     urlencode($criteria_1_id),
            'label' =>           urlencode("Component 1"),
            'column_section_order' => urlencode("1"),
            'ws' =>              urlencode(30),
        );
        $this->post($column_section_1_1,"column_section");

        $column_section_1_2 = array(
            'id' =>              urlencode($column_section_1_2_id),
            'criteria_id' =>     urlencode($criteria_1_id),
            'label' =>           urlencode("Component 2"),
            'column_section_order' => urlencode("2"),
            'ws' =>              urlencode(20),
        );
        $this->post($column_section_1_2,"column_section");

        //part1
        $column_1_1_1_id = $this->assessment_model->id_generator("column");
        $column_1_1_1 = array(
            'id' =>                 urlencode($column_1_1_1_id),
            'column_section_id' =>  urlencode($column_section_1_1_id),
            'column_code' =>        urlencode($column_1_1_1_id),
            'highest_score' =>      urlencode(10),
        );

        $column_1_1_2_id = $this->assessment_model->id_generator("column");
        $column_1_1_2 = array(
            'id' =>                 urlencode($column_1_1_2_id),
            'column_section_id' =>  urlencode($column_section_1_1_id),
            'column_code' =>        urlencode($column_1_1_2_id),
            'highest_score' =>      urlencode(10),
        );
        $column_1_1_3_id = $this->assessment_model->id_generator("column");
        $column_1_1_3 = array(
            'id' =>                 urlencode($column_1_1_3_id),
            'column_section_id' =>  urlencode($column_section_1_1_id),
            'column_code' =>        urlencode($column_1_1_3_id),
            'highest_score' =>      urlencode(10),
        );
        $column_1_1_4_id = $this->assessment_model->id_generator("column");
        $column_1_1_4 = array(
            'id' =>                 urlencode($column_1_1_4_id),
            'column_section_id' =>  urlencode($column_section_1_1_id),
            'column_code' =>        urlencode($column_1_1_4_id),
            'highest_score' =>      urlencode(10),
        );
        $column_1_1_5_id = $this->assessment_model->id_generator("column");
        $column_1_1_5 = array(
            'id' =>                 urlencode($column_1_1_5_id),
            'column_section_id' =>  urlencode($column_section_1_1_id),
            'column_code' =>        urlencode($column_1_1_5_id),
            'highest_score' =>      urlencode(10),
        );
        $column_1_1_6_id = $this->assessment_model->id_generator("column");
        $column_1_1_6 = array(
            'id' =>                 urlencode($column_1_1_6_id),
            'column_section_id' =>  urlencode($column_section_1_1_id),
            'column_code' =>        urlencode($column_1_1_6_id),
            'highest_score' =>      urlencode(10),
        );
        $column_1_1_7_id = $this->assessment_model->id_generator("column");
        $column_1_1_7 = array(
            'id' =>                 urlencode($column_1_1_7_id),
            'column_section_id' =>  urlencode($column_section_1_1_id),
            'column_code' =>        urlencode($column_1_1_7_id),
            'highest_score' =>      urlencode(10),
        );
        $column_1_1_8_id = $this->assessment_model->id_generator("column");
        $column_1_1_8 = array(
            'id' =>                 urlencode($column_1_1_8_id),
            'column_section_id' =>  urlencode($column_section_1_1_id),
            'column_code' =>        urlencode($column_1_1_8_id),
            'highest_score' =>      urlencode(10),
        );
        $column_1_1_9_id = $this->assessment_model->id_generator("column");
        $column_1_1_9 = array(
            'id' =>                 urlencode($column_1_1_9_id),
            'column_section_id' =>  urlencode($column_section_1_1_id),
            'column_code' =>        urlencode($column_1_1_9_id),
            'highest_score' =>      urlencode(10),
        );
        $column_1_1_10_id = $this->assessment_model->id_generator("column");
        $column_1_1_10 = array(
            'id' =>                 urlencode($column_1_1_10_id),
            'column_section_id' =>  urlencode($column_section_1_1_id),
            'column_code' =>        urlencode($column_1_1_10_id),
            'highest_score' =>      urlencode(10),
        );

        //part2
        $column_1_2_1_id = $this->assessment_model->id_generator("column");
        $column_1_2_1 = array(
            'id' =>                 urlencode($column_1_2_1_id),
            'column_section_id' =>  urlencode($column_section_1_2_id),
            'column_code' =>        urlencode($column_1_2_1_id),
            'highest_score' =>      urlencode(10),
        );


        //part1
        $this->post($column_1_1_1,"column");
        $this->post($column_1_1_2,"column");
        $this->post($column_1_1_3,"column");
        $this->post($column_1_1_4,"column");
        $this->post($column_1_1_5,"column");
        $this->post($column_1_1_6,"column");
        $this->post($column_1_1_7,"column");
        $this->post($column_1_1_8,"column");
        $this->post($column_1_1_9,"column");
        $this->post($column_1_1_10,"column");

        //part2
        $this->post($column_1_2_1,"column");

        
        

        $criteria_2 = array(
            'id' =>           urlencode($criteria_2_id),
            'name' =>            urlencode("Performance Task"),
            'criteria_order' => urlencode("2"),
            'class_record_id' =>urlencode($class_record_id),
            'percentage' =>urlencode(50),    
        );
        $this->post($criteria_2,"criteria");

        $column_section_2_1 = array(
            'id' =>              urlencode($column_section_2_1_id),
            'criteria_id' =>     urlencode($criteria_2_id),
            'label' =>           urlencode("Component 1"),
            'column_section_order' => urlencode("1"),
            'ws' =>              urlencode(50),
        );

        $this->post($column_section_2_1,"column_section");


        $column_2_1_1_id = $this->assessment_model->id_generator("column");
        $column_2_1_1 = array(
            'id' =>                 urlencode($column_2_1_1_id),
            'column_section_id' =>  urlencode($column_section_2_1_id),
            'column_code' =>        urlencode($column_1_1_1_id),
            'highest_score' =>      urlencode(10),
        );

        $column_2_1_2_id = $this->assessment_model->id_generator("column");
        $column_2_1_2 = array(
            'id' =>                 urlencode($column_2_1_2_id),
            'column_section_id' =>  urlencode($column_section_2_1_id),
            'column_code' =>        urlencode($column_1_1_2_id),
            'highest_score' =>      urlencode(10),
        );
        $column_2_1_3_id = $this->assessment_model->id_generator("column");
        $column_2_1_3 = array(
            'id' =>                 urlencode($column_2_1_3_id),
            'column_section_id' =>  urlencode($column_section_2_1_id),
            'column_code' =>        urlencode($column_1_1_3_id),
            'highest_score' =>      urlencode(10),
        );
        $column_2_1_4_id = $this->assessment_model->id_generator("column");
        $column_2_1_4 = array(
            'id' =>                 urlencode($column_2_1_4_id),
            'column_section_id' =>  urlencode($column_section_2_1_id),
            'column_code' =>        urlencode($column_1_1_4_id),
            'highest_score' =>      urlencode(10),
        );
        $column_2_1_5_id = $this->assessment_model->id_generator("column");
        $column_2_1_5 = array(
            'id' =>                 urlencode($column_2_1_5_id),
            'column_section_id' =>  urlencode($column_section_2_1_id),
            'column_code' =>        urlencode($column_1_1_5_id),
            'highest_score' =>      urlencode(10),
        );
        $column_2_1_6_id = $this->assessment_model->id_generator("column");
        $column_2_1_6 = array(
            'id' =>                 urlencode($column_2_1_6_id),
            'column_section_id' =>  urlencode($column_section_2_1_id),
            'column_code' =>        urlencode($column_1_1_6_id),
            'highest_score' =>      urlencode(10),
        );
        $column_2_1_7_id = $this->assessment_model->id_generator("column");
        $column_2_1_7 = array(
            'id' =>                 urlencode($column_2_1_7_id),
            'column_section_id' =>  urlencode($column_section_2_1_id),
            'column_code' =>        urlencode($column_1_1_7_id),
            'highest_score' =>      urlencode(10),
        );
        $column_2_1_8_id = $this->assessment_model->id_generator("column");
        $column_2_1_8 = array(
            'id' =>                 urlencode($column_2_1_8_id),
            'column_section_id' =>  urlencode($column_section_2_1_id),
            'column_code' =>        urlencode($column_1_1_8_id),
            'highest_score' =>      urlencode(10),
        );
        $column_2_1_9_id = $this->assessment_model->id_generator("column");
        $column_2_1_9 = array(
            'id' =>                 urlencode($column_2_1_9_id),
            'column_section_id' =>  urlencode($column_section_2_1_id),
            'column_code' =>        urlencode($column_1_1_9_id),
            'highest_score' =>      urlencode(10),
        );
        $column_2_1_10_id = $this->assessment_model->id_generator("column");
        $column_2_1_10 = array(
            'id' =>                 urlencode($column_2_1_10_id),
            'column_section_id' =>  urlencode($column_section_2_1_id),
            'column_code' =>        urlencode($column_1_1_10_id),
            'highest_score' =>      urlencode(10),
        );

        //part1
        $this->post($column_2_1_1,"column");
        $this->post($column_2_1_2,"column");
        $this->post($column_2_1_3,"column");
        $this->post($column_2_1_4,"column");
        $this->post($column_2_1_5,"column");
        $this->post($column_2_1_6,"column");
        $this->post($column_2_1_7,"column");
        $this->post($column_2_1_8,"column");
        $this->post($column_2_1_9,"column");
        $this->post($column_2_1_10,"column");

        redirect(base_url('lms/grading/edit/'.$class_record_id));

    }

}
 
?>