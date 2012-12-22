<ul class="nav nav-tabs">
    <li><a href='../administrator/index.php'>User Accounts</a></li>
    <li  class="active"><a href='../administrator/signatory_list_manager.php'>Signatories</a></li>
    <li><a href='../administrator/department_list_manager.php'>Departments</a></li>
</ul>

<button class="pull-right btn" onclick="window.location.href='signatory_list_manager.php'">Back</button>
        
<form action="" method='post' class="form-horizontal">
    <legend>Edit Signatory:</legend>
    <div class="control-group">
        <label class="control-label">Signatory Name: </label>
        <div class="controls">
            <input class="input-xxlarge" type ='text' name='sign_name' value='{$editSignatory_Name}'>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Description: </label>
        <div class="controls">
            <textarea class="input-xxlarge" name='sign_description' rows="5" cols="50">{$editSignatory_Desc}</textarea>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <input class="btn btn-primary" type='Submit' value='Save' name ='editSave'>
        </div>
    </div>
</form>       