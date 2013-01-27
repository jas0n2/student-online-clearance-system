<!-- Header-->
<h1>
    <center>{$Dept_name}</center>
</h1>

<!-- Navigation Tabs-->
<ul class="nav nav-tabs">
    <li class="active"><a href='../administrator/course_list_byDepartment.php'>Courses</a></li>
    <li><a href='../administrator/signatorialList.php'>Signatorial List</a></li>
</ul>

<!-- Buttons-->
<div class="pull-right">   
    <input class="btn" type="button" value="Add Courses" onclick="window.location.href='course_list_byDepartment.php?action=addCourse'">
    <input class="btn" type="button" value="Back" onclick="window.location.href='department_list_manager.php'">    
</div>

<!--Search Bar and other buttons-->
{call name=search}

<!-- Course List Table-->
<table class="table table-hover">     
    <tr>
        <th>
            <input type="checkbox" onclick="isCheck({$rowCount_course})" id="check"> Courses
        </th>
        <th>
    <div>Controls</div>
</th>
</tr>
{foreach from = $myName_course key = k item = i}
    <tr>
        <td>
            <label class="checkbox">
                <input class="Checkbox" type="checkbox" id = '{$k}' value = {$myKey_course[$k]} > {$i}
            </label>
        </td>
        <td>
            <div>
                <a style="cursor:pointer;" onclick="window.location.href='course_list_byDepartment.php?action=editCourse&seleted={$myKey_course[$k]}'">
                    <i class="icon-pencil"></i> Edit
                </a>
            </div>
        </td>
    </tr>
{/foreach}
</table>

<!-- Delete Button-->
<a style="cursor:pointer;" onclick="findCheck('{$rowCount_course}','course')" >
    <i class="icon-remove"></i> Delete Selected
</a>

<!-- Pagination-->
<div class="pull-right">
    Jump to: <select id="jump" class="input-mini" onchange="jumpToPage()">
        <option>--</option>
        {for $start = 1 to $course_length}
        <option>{$start}</option>
        {/for}
    </select>
</div>