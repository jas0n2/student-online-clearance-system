/*
 *Main JS File
 */

/*
 * Administrator Script Functions
 */

function getSignatory(){
    var cmdSignatory = document.getElementById("cmdSignatory").value;
    window.location.assign("?action=addSignatory&cmdSignatory=" +cmdSignatory);
}

function jumpToPage(){
    var jump = document.getElementById("jump").value;
    var search = document.getElementById("search").value;
    window.location.assign("?action=displayTable&filter=" + search +"&page=" + jump);
}       
        
function isCheckAll(isChecked, rowCount){
    for(var i = 0; i <= rowCount; i++){
        document.getElementById("" +i).checked = isChecked;
    }
}
        
function findCheck(rowCount){
    
    var bool = confirm("Are you sure you want to delete?");
    
    if(bool == true){
        var valueDeleted = "";
        for(var i = 0; i < rowCount; i++){
            if(document.getElementById("" +i).checked == true){
                valueDeleted += document.getElementById("" +i).value + "-";
            }
        }
        window.location.assign("?action=delete&selected=" + valueDeleted);
    }
}

function confirmDelete(selected){
    var bool = confirm("Are you sure you want delete?");
    
    if(bool == true){
        window.location = "?action=delete&selected=" + selected;
    }
}