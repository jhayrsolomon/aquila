<?php

class Question_model extends MY_model {

    function __construct() {
        parent::__construct();
        //-- Load database for writing
        $this->writedb = $this->load->database('write_db', TRUE);
    }

	 public function add($data) {
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->writedb->where('id', $data['id']);
            $this->writedb->update('questions', $data);
			$message      = UPDATE_RECORD_CONSTANT." On  questions id ".$data['id'];
			$action       = "Update";
			$record_id    = $data['id'];
			$this->log($message, $record_id, $action);
			//======================Code End==============================

			$this->writedb->trans_complete(); # Completing transaction
			/*Optional*/

			if ($this->writedb->trans_status() === false) {
				# Something went wrong.
				$this->writedb->trans_rollback();
				return false;

			} else {
				//return $return_value;
			}
        } else {
            $this->writedb->insert('questions', $data);          
			$id=$this->writedb->insert_id();
			$message      = INSERT_RECORD_CONSTANT." On  questions id ".$id;
			$action       = "Insert";
			$record_id    = $id;
			$this->log($message, $record_id, $action);
			//echo $this->writedb->last_query();die;
			//======================Code End==============================

			$this->writedb->trans_complete(); # Completing transaction
			/*Optional*/

			if ($this->writedb->trans_status() === false) {
				# Something went wrong.
				$this->writedb->trans_rollback();
				return false;

			} else {
				//return $return_value;
			}
			return $id;
        }
    }

    public function get($id = null) {
        $this->db->select('questions.*,subjects.name')->from('questions');

        $this->db->join('subjects', 'subjects.id = questions.subject_id');
        if ($id != null) {
            $this->db->where('questions.id', $id);
        } else {
            $this->db->order_by('questions.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function remove($id){
		$this->writedb->trans_start(); # Starting Transaction
        $this->writedb->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->writedb->where('id', $id);
        $this->writedb->delete('questions');
		$message      = DELETE_RECORD_CONSTANT." On questions id ".$id;
        $action       = "Delete";
        $record_id    = $id;
        $this->log($message, $record_id, $action);
		//======================Code End==============================
        $this->writedb->trans_complete(); # Completing transaction
        /*Optional*/
        if ($this->writedb->trans_status() === false) {
            # Something went wrong.
            $this->writedb->trans_rollback();
            return false;
        } else {
        //return $return_value;
        }
    }

    public function image_add($id,$image){

        $this->writedb->where('id', $id);
        $this->writedb->update('questions', $image);

    }

    public function add_option($data){
        if (isset($data['id'])) {
            $this->writedb->where('id', $data['id']);
            $this->writedb->update('question_options', $data);
        } else {
            $this->writedb->insert('question_options', $data);
            return $this->writedb->insert_id();
        }
    }

    public function add_question_answers($data){
 if (isset($data['id'])) {
            $this->writedb->where('id', $data['id']);
            $this->writedb->update('question_answers', $data);
        } else {
            $this->writedb->insert('question_answers', $data);
            return $this->writedb->insert_id();
        }
    }

    public function get_result($id){
        return $this->db->select('*')->from('questions')->join('question_answers','question.id=question_answers.question_id')->get()->row_array();

    }
    public function get_option($id){
        return $this->db->select('id,option')->from('question_options')->where('question_id',$id)->get()->result_array();
    }

    public function get_answer($id){
        return $this->db->select('option_id as answer_id')->from('question_answers')->where('question_id',$id)->get()->row_array();
    }
}