<?php

/**
 * Department_List_Manager Controller
 *
 * @author Ozy
 */
require_once '../config/config.php';

class Department_List_Manager extends Controller {

    private $template;
    private $department_model;

    public function __construct() {
        parent::__construct();
        if (Session::user_exist() && Session::get_Account_type() == "Admin") {

            $this->template = new Template();
            $this->department_model = new Department_Model();
            $this->template->setPageName('Departments');

            $this->template->set_username(Session::get_user());
            $this->template->set_surname(Session::get_Surname());
            $this->template->set_firstname(Session::get_Firstname());
            $this->template->set_middlename(Session::get_Middlename());
            $this->template->set_account_type(Session::get_Account_type());

            $this->template->setContent('department_list_manager.tpl');
            $this->template->setCalendar('Calendar.tpl');
            $this->template->assign('assign_sign', '');

            $this->displayTable('', 1, "default");
            
            if(isset($_GET['successEdit'])){
                if($_GET['successEdit'] == 'true'){
                    $this->template->setAlert("Updating Department was Successful", Template::ALERT_SUCCESS, 'alert');
                }
            }
            
            if(isset($_GET['successAdd'])){
                if($_GET['successAdd'] == 'true'){
                    $this->template->setAlert("Adding Department was Successful", Template::ALERT_SUCCESS, 'alert');
                }
            }
        } else {
            header('Location: ' . HOST);
        }
    }
    
    public function addDepartment(){
        $this->template->setContent('addDepartment.tpl');
    }
    
    public function add_department(){
        $this->template->setContent('addDepartment.tpl');       
        if(trim($_POST['dept_name']) == "" || trim($_POST['dept_description']) == ""){       
            $this->template->setAlert("Adding Department was Failed", Template::ALERT_ERROR, 'alert');
        }else if($this->department_model->isExist(trim($_POST['dept_name']), trim($_POST['dept_description']))){
            $this->template->setAlert("Cannot Add a Department that is Existing", Template::ALERT_ERROR, 'alert');
        }else{
            $this->department_model->insert(trim($_POST['dept_name']), trim($_POST['dept_description']));
            //$this->template->setAlert("Adding Department was Successful", Template::ALERT_SUCCESS, 'alert');
            
            header('Location: department_list_manager.php?successAdd=true');
        }
    }

    public function displayCourse($deptName) {
        Session::set_deptpartName($deptName);
        header('Location: ' . HOST . "/administrator/course_list_byDepartment.php");
    }

    public function deleted() {
        $this->template->setAlert('Delete an Department Successfully!..', Template::ALERT_SUCCESS, 'alert');
    }

    public function delete($selected) {
        $explode = explode("-", $selected);
        foreach ($explode as $value) {
            $delete = $this->department_model->deleteSignatory(trim($value));
            if($delete == "false"){ $this->template->setAlert('You can delete an Unuse Department only!..', Template::ALERT_ERROR, 'alert'); return;}
        }
        $HOST = $explode[0] != null ? HOST . "/administrator/department_list_manager.php?action=deleted" : HOST . "/administrator/department_list_manager.php";
        header('Location: ' . $HOST);
    }
    
    public function editDepartment($seleted){
        $dept_name = $this->department_model->getDept_Name($seleted);
        $dept_desc = $this->department_model->getDept_Desc($seleted);

        $this->template->setContent("editDepartment.tpl");
        $this->template->assign("editDepartment_Name", $dept_name);
        $this->template->assign("editDepartment_Desc", $dept_desc);
        
        if(isset($_POST['editSave'])){
            if(trim($_POST['dept_name']) == "" || trim($_POST['dept_description']) == ""){       
                $this->template->setAlert("Updating Signatory was Failed", Template::ALERT_ERROR);
            }/*else if($this->department_model->isExist(trim($_POST['dept_name']), trim($_POST['dept_description']))){
                $this->template->setAlert("Cannot Update a Department that is Existing", Template::ALERT_ERROR, 'alert');
            }*/else{
                $this->department_model->update($seleted, trim($_POST['dept_name']), trim($_POST['dept_description']));
                //$this->template->setAlert("Updating Signatory was Successful", Template::ALERT_SUCCESS, 'alert');
                $this->template->assign("editDepartment_Name", trim($_POST['dept_name']));
                $this->template->assign("editDepartment_Desc", trim($_POST['dept_description']));
                
                header('Location: department_list_manager.php?successEdit=true');
            }
        }
        
    }
    

    public function filter($filterName) {
        $this->displayTable(trim($filterName), 1);
    }

    public function displayTable($searchName, $page, $finder) {
        $this->department_model->filter($searchName, $page);
        
        $numOfPages = $this->department_model->getQueryPageSize($searchName);
        $numOfResults = count($this->department_model->getFilter_Name());
        $getListofDeptName = $this->getListofName($this->department_model->getFilter_Name(), $searchName, $finder);
        $getListofDeptNameWithoutColor = $this->department_model->getFilter_Name();
        $filter_ID = $this->department_model->getFilter_ID();
        $filter_Desc = $this->parsingNewLine_Decs($this->department_model->getFilter_Desc());

        $this->template->assign('myName_dept', $getListofDeptName);
        $this->template->assign('myDept_Name', $getListofDeptNameWithoutColor);
        $this->template->assignByRef('myKey_dept', $filter_ID);
        $this->template->assign('filter', $searchName);
        $this->template->assign('dept_length', $numOfPages);
        $this->template->assign('rowCount_dept', $numOfResults);
        $this->template->assign('desc_dept', $filter_Desc);

        if ($numOfResults == 0) {
            $this->template->setAlert('No Results Found.', Template::ALERT_ERROR, 'alert');
        }
    }

    public function display() {
        $this->template->display('bootstrap.tpl');
        $this->department_model->db_close();
    }

}

$controller = new Department_List_Manager();
$controller->perform_actions();
$controller->display();
?>