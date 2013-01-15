<?php

/**
 * Student's Index Page
 *
 * @author Ozy
 */
require_once '../config/config.php';

class Index extends Controller {
    private $template;
    private $schoolYearSem_model;
    private $student_model;
    private $signatoialList;
    private $signatory_model;
    private $bulletin_model;

    public function __construct() {
        parent::__construct();

        if (Session::user_exist() && Session::get_Account_type() == "Student") {
            $this->template = new Template();
            $this->schoolYearSem_model = new SchoolYearSem();
            $this->student_model = new Student_Model();
            $this->signatoialList = new SignatorialList_Model();
            $this->signatory_model = new Signatory_Model();
            $this->bulletin_model = new Bulletin_Model();
            
            $listOfSchoolYear = $this->schoolYearSem_model->getSchool_Year();
            $stud_course = $this->student_model->getStudent_course(Session::get_user());
            $stud_deptName = $this->student_model->getStudent_department(Session::get_user());
            $stud_deptID = $this->student_model->getStudent_deptID(Session::get_user());
            
            
            $listOfSign_underDeptName = $this->signatoialList->getListofSignatoryByDept($stud_deptID);
            $listOfSignID_underDeptName = $this->signatoialList->getListofSignatory_ID_ByDept($stud_deptID);
            
            
            $this->template->setPageName('Signatory Page');
            $this->template->setContent('StudentDashboard.tpl');

            $this->template->set_username(Session::get_user());
            $this->template->set_surname(Session::get_Surname());
            $this->template->set_firstname(Session::get_Firstname());
            $this->template->set_middlename(Session::get_Middlename());
            $this->template->set_account_type($stud_course);
                       
            $this->template->assign('mySchool_Year', $listOfSchoolYear);
            $this->template->assign('assign_sign', ", " .$stud_deptName);
            $this->template->assign('myListOfSign_underDeptName', $listOfSign_underDeptName);
            $this->template->assign('myKey_signID', $listOfSignID_underDeptName);
        } else {
            header('Location: /SOCS/index.php');
        }
    }
    
    /*----------- for viewing the post of signatory -----------*/
    
    public function viewMessages($Tsign_ID, $page){
        $this->template->setPageName("Messages for a Signatory In Charge");
        $this->template->setContent("Messages.tpl");        
        
        $signName = $this->signatory_model->getSign_Name($Tsign_ID);
        $list_messages = $this->bulletin_model->getListofMessages($Tsign_ID, $page);
        $list_datePosted = $this->getDate($this->bulletin_model->getListofPost_Date($Tsign_ID, $page));
        $list_timePosted = $this->bulletin_model->getListofPost_Time($Tsign_ID, $page);
        $numRows = $this->bulletin_model->getStudMessage_PageSize($Tsign_ID);
        
        
        $this->template->assign('sign_name', $signName);
        $this->template->assign('sign_id', $Tsign_ID);
        $this->template->assign('my_messages', $list_messages);
        $this->template->assign('_date', $list_datePosted);
        $this->template->assign('_time', $list_timePosted);
        $this->template->assign('stud_message_length', $numRows);
        
        //$this->template->assign('stud_message_length', count($list_timePosted));
    }

    public function display() {
        //displaying the UI
        $this->template->display('bootstrap.tpl');
    }
    
    
    /*---------- for Date into word ----------------*/
    
    public function getDate($T_Date){
        $t_month;
        $t_day;
        $t_year;
        $_Date = array();
        
        foreach ($T_Date as $value) {
            $temp_date = explode("-", $value);
            $t_year = trim($temp_date[0]);
            $t_month = trim($this->convertMonth(trim($temp_date[1])));
            $t_day = trim($temp_date[2]);
            
            array_push($_Date, $t_month ." " .$t_day .", " .$t_year);
        }
        
        return $_Date;
    }
    
    public function convertMonth($month){
        $_month = array("January", "Febuary", "March", "April", 
                        "May", "June", "July", "August", 
                        "September", "October", "November", "December");
        
        return $_month[$month - 1];
    }

}

$controller = new Index();
$controller->perform_actions();
$controller->display();
?>