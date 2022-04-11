<?php
error_reporting(0);
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        // Load employee model
        $this->load->model('Employee_model','model');
        
        // Load form validation library
        $this->load->library('form_validation');
        $this->load->library('pagination');
        // Load file helper
        $this->load->helper('file');
		$this->load->helper('url');


    }
    
    public function index(){
        $data = array();
        
        // Get messages from the session
        if($this->session->userdata('success_msg')){
            $data['success_msg'] = $this->session->userdata('success_msg');
            $this->session->unset_userdata('success_msg');
        }
        if($this->session->userdata('error_msg')){
            $data['error_msg'] = $this->session->userdata('error_msg');
            $this->session->unset_userdata('error_msg');
        }
	
		$config['total_rows'] = $this->model->get_count();
        $config["per_page"] = 20;
        $config["uri_segment"] = 2;
        $config["base_url"] = base_url() . "Employee";

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        $data["links"] = $this->pagination->create_links();
        // Get rows
        $data['employees'] = $this->model->getRows($params=array("limit"=>$config["per_page"], "start"=>$page));
        
        // Load the list page view
        $this->load->view('employee', $data);
    }
    
    public function import(){
        $data = array();
        $empData = array();
        
        // If import request is submitted
        if($this->input->post('importSubmit')){
            // Form field validation rules
            $this->form_validation->set_rules('file', 'CSV file', 'callback_file_check');
            
            // Validate submitted form data
            if($this->form_validation->run() == true){
                $insertCount = $updateCount = $rowCount = $notAddCount = 0;
                
                // If file uploaded
                if(is_uploaded_file($_FILES['file']['tmp_name'])){
                    // Load CSV reader library
                    $this->load->library('CSVReader');
                    
                    // Parse data from CSV file
                    $data['csvFields'] = $this->csvreader->parse_csv($_FILES['file']['tmp_name'])[csvFields];
					$data['count'] = count($data['csvFields']);
					$data['csvData'] = $this->csvreader->parse_csv($_FILES['file']['tmp_name'])[csvData];

					                       // echo "<PRE>";print_r(get_defined_vars());exit;    
										   $config['total_rows'] = $this->model->get_count();
					$config["per_page"] = 20;
					$config["uri_segment"] = 2;
					$config["base_url"] = base_url() . "Employee";

					$this->pagination->initialize($config);

					$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

					$data["links"] = $this->pagination->create_links();
					// Get rows
					$data['employees'] = $this->model->getRows($params=array("limit"=>$config["per_page"], "start"=>$page));
					$this->load->view('employee', $data);

                   // 
                   
                }else{
                    $this->session->set_userdata('error_msg', 'Error on file upload, please try again.');
					redirect('employee');
                }
            }else{
                $this->session->set_userdata('error_msg', 'Invalid file, please select only CSV file.');
				redirect('employee');
            }
        }
        //
    }
	
	public function upload_csv_to_db(){

			$insertCount = $updateCount = $rowCount = $notAddCount = 0;
                
     // POST data
		$postData = $this->input->post();
		$code=		json_decode(htmlspecialchars_decode($postData['code']),true);
		$name=		json_decode(htmlspecialchars_decode($postData['name']),true);
		$dep=		json_decode(htmlspecialchars_decode($postData['dep']),true);
		$dob=		json_decode(htmlspecialchars_decode($postData['dob']),true);
		$joining_date = json_decode(htmlspecialchars_decode($postData['joining_date']), true);
		$data = json_decode(htmlspecialchars_decode($postData['data']), true);
		if(!empty($data)){			   		
    

				foreach($data as $row){   
							$rowCount++;
					// Prepare data for DB insertion
					$empData = array(
						'code' => $row[$code],
						'name' => $row[$name],
						'department' => $row[$dep],
						'dob' => date('Y-m-d',strtotime($row[$dob])),
						'email' => $row['email'],
						'joining_date' =>  date('Y-m-d',strtotime($row[$joining_date])),
					);
					if(!strtotime($row[$dob]) || !strtotime($row[$joining_date])){
							echo 'Please select only date in Age and experience fields';exit;
					}
						
					// Check whether email already exists in the database
					$con = array(
						'where' => array(
							'email' => $row['Email']
						),
						'returnType' => 'count'
					);
					$prevCount = $this->model->getRows($con);
					
					if($prevCount > 0){	

						// Update Employee data
						$condition = array('email' => $row['Email']);
						$update = $this->model->update($empData, $condition);
						
						if($update){
							$updateCount++;
						}
					}else{					//echo "<pRE>sss";print_r($empData);exit;

						// Insert Employee data
						$insert = $this->model->insert($empData);
						
						if($insert){
							$insertCount++;
						}
					}
				}
                        
				// Status message with imported data count
				$notAddCount = ($rowCount - ($insertCount + $updateCount));
			   // $successMsg = 'Employees imported successfully. Total Rows ('.$rowCount.') | Inserted ('.$insertCount.') | Updated ('.$updateCount.') | Not Inserted ('.$notAddCount.')';
				$successMsg = 'Employees imported successfully. Total Rows ('.$rowCount.')';
				$this->session->set_userdata('success_msg', $successMsg);
			}
	 		//}
		 // Insert/update CSV data into database
                    echo "Success";
	}
    
    /*
     * Callback function to check file value and type during validation
     */
    public function file_check($str){
        $allowed_mime_types = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != ""){
            $mime = get_mime_by_extension($_FILES['file']['name']);
            $fileAr = explode('.', $_FILES['file']['name']);
            $ext = end($fileAr);
            if(($ext == 'csv') && in_array($mime, $allowed_mime_types)){
                return true;
            }else{
                $this->form_validation->set_message('file_check', 'Please select only CSV file to upload.');
                return false;
            }
        }else{
            $this->form_validation->set_message('file_check', 'Please select a CSV file to upload.');
            return false;
        }
    }
	
	public function memb(){
		        $this->load->view('welcome_message',);

	}
}
?>