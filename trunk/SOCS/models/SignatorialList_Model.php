<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SignatorialList_Model
 *
 * @author ronversa09
 */
class SignatorialList_Model extends Model {

    private $query;
    private $itemsPerPage = 10;
    private $filter_ID;
    private $filter_Name;
    private $sign_ID;
    private $sign_Name;

    public function __construct() {
        parent::__construct();

        $this->query = "";
    }

    public function getFilter_ID() {
        return $this->filter_ID;
    }

    public function getFilter_Name() {
        return $this->filter_Name;
    }

    public function getSign_ID() {
        return $this->sign_ID;
    }

    public function getSign_Name() {
        return $this->sign_Name;
    }

    /* ----------------------------------------------- */

    public function filter($Tdept_name, $Tsign_name, $Tpage) {
        $this->query = mysql_query("select signatories.signatory_id, signatories.signatory_name  from signatorialList
                                    inner join signatories 
                                    on signatorialList.signatory_id = signatories.signatory_id
                                    inner join departments
                                    on signatorialList.department_id = departments.department_id
                                    where departments.Department_Name like '%$Tdept_name%' and signatories.signatory_name like '%$Tsign_name%'
                                    LIMIT " . (($Tpage - 1) * $this->itemsPerPage) . ", " . $this->itemsPerPage);

        $this->filter_ID = array();
        $this->filter_Name = array();
        while ($row = mysql_fetch_array($this->query)) {
            array_push($this->filter_ID, $row['0']);
            array_push($this->filter_Name, $row['1']);
        }
    }

    /* ----------------------------------- */

//    public function filter_ID($Tdept_name, $Tsign_name, $Tpage){
//        $filter = array();
//        $this->query = mysql_query("select signatories.signatory_id from signatorialList
//                                    inner join signatories 
//                                    on signatorialList.signatory_id = signatories.signatory_id
//                                    inner join departments
//                                    on signatorialList.department_id = departments.department_id
//                                    where departments.Department_Name like '%$Tdept_name%' and signatories.signatory_name like '%$Tsign_name%'
//                                    LIMIT " . (($Tpage - 1) * $this->itemsPerPage) . ", " . $this->itemsPerPage);
//        
//        while($row = mysql_fetch_array($this->query)){
//            array_push($filter, $row['signatory_id']);
//        }
//        
//        return $filter;
//    }
//    
//    public function filter_SignName($Tdept_name, $Tsign_name, $Tpage){
//        $filter = array();
//        $this->query = mysql_query("select signatories.signatory_name from signatorialList
//                                    inner join signatories 
//                                    on signatorialList.signatory_id = signatories.signatory_id
//                                    inner join departments
//                                    on signatorialList.department_id = departments.department_id
//                                    where departments.Department_Name like '%$Tdept_name%' and signatories.signatory_name like '%$Tsign_name%'
//                                    LIMIT " . (($Tpage - 1) * $this->itemsPerPage) . ", " . $this->itemsPerPage);
//        
//        while($row = mysql_fetch_array($this->query)){
//            array_push($filter, $row['signatory_name']);
//        }
//        
//        return $filter;
//    }
    /* ----------------------------------- */

    public function getQueryPageSize($Tdept_name, $searchName) {
        $this->query = mysql_query("select signatories.signatory_name from signatorialList
                                    inner join signatories 
                                    on signatorialList.signatory_id = signatories.signatory_id
                                    inner join departments
                                    on signatorialList.department_id = departments.department_id
                                    where departments.Department_Name like '%$Tdept_name%' and signatories.signatory_name like '%$searchName%'");

        return mysql_num_rows($this->query) / $this->itemsPerPage;
    }

    public function update($dept_ID, $sign_ID, $newSign_ID) {
        mysql_query("UPDATE signatoriallist SET Signatory_ID=$newSign_ID
                    WHERE Department_ID=$dept_ID and Signatory_ID=$sign_ID");
    }

    public function deleteSignatorial($dept_ID, $sign_ID) {
        mysql_query("delete from signatoriallist where Department_ID = '$dept_ID' and Signatory_ID = '$sign_ID'");
    }

    public function insert($dept_ID, $sign_ID) {
        mysql_query("INSERT INTO `socs`.`signatoriallist` (`Department_ID`, `Signatory_ID`) 
                VALUES ('$dept_ID', '$sign_ID')");
    }

    /* --------- For Assigning Signatory ---------- */

    public function getListofSignatory() {
        $rowInfo = array();
        $this->query = mysql_query("select signatory_name from signatories");

        while ($row = mysql_fetch_array($this->query)) {
            array_push($rowInfo, $row['signatory_name']);
        }

        return $rowInfo;
    }
    
    public function getListofSignatoryID() {
        $rowInfo = array();
        $this->query = mysql_query("select signatory_ID from signatories");

        while ($row = mysql_fetch_array($this->query)) {
            array_push($rowInfo, $row[0]);
        }

        return $rowInfo;
    }

    public function getKeyListofSignatory() {
        $rowInfo = array();
        $this->query = mysql_query("select signatory_id from signatories");

        while ($row = mysql_fetch_array($this->query)) {
            array_push($rowInfo, $row['signatory_id']);
        }

        return $rowInfo;
    }

    public function getSignatorialList_underDeptName($Tdept_name) {
        $filter = array();
        $this->query = mysql_query("select signatories.signatory_name from signatorialList
                                    inner join signatories on signatorialList.signatory_id = signatories.signatory_id
                                    inner join departments on signatorialList.department_id = departments.department_id
                                    where departments.Department_Name = '$Tdept_name'");

        while ($row = mysql_fetch_array($this->query)) {
            array_push($filter, $row['0']);
        }

        return $filter;
    }

    public function getListOfDept_underSignName($signID) {
        $filter = array();
        $this->query = mysql_query("select departments.department_name from signatorialList
                                    inner join signatories on signatorialList.signatory_id = signatories.signatory_id
                                    inner join departments on signatorialList.department_id = departments.department_id
                                    where signatorialList.signatory_id = '$signID'");

        while ($row = mysql_fetch_array($this->query)) {
            array_push($filter, $row['0']);
        }

        return $filter;
    }
    
    public function getListOfDept_underSignNameID($signID) {
        $filter = array();
        $this->query = mysql_query("select departments.department_id from signatorialList
                                    inner join signatories on signatorialList.signatory_id = signatories.signatory_id
                                    inner join departments on signatorialList.department_id = departments.department_id
                                    where signatorialList.signatory_id = '$signID'");

        while ($row = mysql_fetch_array($this->query)) {
            array_push($filter, $row['0']);
        }

        return $filter;
    }

    public function getDeptId($dept_name) {
        $this->query = mysql_query("select Department_ID from departments where Department_Name like '%$dept_name%'");
        $row = mysql_fetch_array($this->query);

        return $row['Department_ID'];
    }

    public function getSignId($sign_name) {
        $this->query = mysql_query("select Signatory_ID from signatories where Signatory_Name like '%$sign_name%'");
        $row = mysql_fetch_array($this->query);

        return $row['Signatory_ID'];
    }

    /* --------- For Student Page ---------- */

    public function getListofSignatoryByDept($dept_id) {
        $this->query = mysql_query("select signatoriallist.Signatory_ID, signatory_name from signatoriallist
                                    inner join departments on departments.Department_ID = signatoriallist.Department_ID
                                    inner join signatories on signatories.Signatory_ID = signatoriallist.Signatory_ID
                                    where departments.Department_ID = '$dept_id'");

        $this->sign_ID = array();
        $this->sign_Name = array();
        while ($row = mysql_fetch_array($this->query)) {
            array_push($this->sign_ID, $row['0']);
            array_push($this->sign_Name, $row['1']);
        }
    }
    
    public function getListOfCourse_Sign($signID){
        $this->query = mysql_query("select courses.course_name from signatoriallist
                                    inner join departments on (signatoriallist.Department_ID = departments.Department_ID)  
                                    inner join courses on (departments.department_id = courses.department_id)
                                    where signatoriallist.signatory_id = '$signID'");
        
        $listCourses = array();
        while ($row = mysql_fetch_array($this->query)) {
            array_push($listCourses, $row['0']);
        }
        
        return $listCourses;
    }
    
    public function getListOfCourse_SignID($signID){
        $this->query = mysql_query("select courses.course_id from signatoriallist
                                    inner join departments on (signatoriallist.Department_ID = departments.Department_ID)  
                                    inner join courses on (departments.department_id = courses.department_id)
                                    where signatoriallist.signatory_id = '$signID'");
        
        $listCourses = array();
        while ($row = mysql_fetch_array($this->query)) {
            array_push($listCourses, $row['0']);
        }
        
        return $listCourses;
    }
    
    
    

    /* -------------------------------------------- */

//    public function getListofSignatoryByDept($dept_id){
//        $rowInfo = array();
//        $this->query = mysql_query("select signatory_name from signatoriallist
//                                    inner join departments on departments.Department_ID = signatoriallist.Department_ID
//                                    inner join signatories on signatories.Signatory_ID = signatoriallist.Signatory_ID
//                                    where departments.Department_ID = '$dept_id'");
//        
//        while($row = mysql_fetch_array($this->query)){
//            array_push($rowInfo, $row['signatory_name']);
//        }
//        
//        return $rowInfo;
//    }
//    
//    public function getListofSignatory_ID_ByDept($dept_id){
//        $rowInfo = array();
//        $this->query = mysql_query("select signatoriallist.Signatory_ID from signatoriallist
//                                    inner join departments on departments.Department_ID = signatoriallist.Department_ID
//                                    inner join signatories on signatories.Signatory_ID = signatoriallist.Signatory_ID
//                                    where departments.Department_ID = '$dept_id'");
//        
//        while($row = mysql_fetch_array($this->query)){
//            array_push($rowInfo, $row['0']);
//        }
//        
//        return $rowInfo;
//    }
}

?>
