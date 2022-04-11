<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Employee Database</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">
    <!-- Bootstrap library -->
    
    <!-- Stylesheet file -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

</head>
<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
		text-decoration: none;
		margin:10px;
	}
	
	#imp_btn{
		font-size:17px;
	}

	a:hover {
		color: #97310e;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 28px;
		font-weight: normal;
		margin: 0 0 14px 0;
		text-align:center;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
		min-height: 96px;
	}

	p {
		margin: 0 0 10px;
		padding:0;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	.btn{
		font-size: 12px;
		margin:20px;
		color: #e2e2e2;
		height:30px;
		background: #4ab3c1;
		border-radius: 5px;
	}
	#table_em{
		width:80%;
	}
	#table_e{
		width:50%;
	}
	table.table	{
	  margin-left: auto; 
	  margin-right: auto;
	}
	#importFrm{
		margin-left:20px;
	}
	#error{
		margin-left:30px;
		color:red;
	}
	#success{
		margin-left:30px;
		color:green;
	}
	#error_m{
		margin-left:30px;
		color:red;
	}
	</style>
<body>
<div id="container">
    <h1>Employees' List</h2>
	
    <!-- Display status message -->
    <?php if(!empty($success_msg)){ ?>
    <div class="col-xs-12" id="success">
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    </div>
	<?php } ?>
    <?php if(!empty($error_msg)){ ?>
    <div class="col-xs-12" id="error">
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    </div>
    <?php } ?>
	<div class="col-xs-12" id="error_m">
        <div class="alert alert-danger"></div>
    </div>
    <div class="row" style="text-align:center;">
        <!-- Import link -->
        <div class="col-md-12 head">
            <div>
                <a id="imp_btn" href="javascript:void(0);"  onclick="formToggle(this.id,'importFrm');">Click here to import new csv file >></a>
            </div>
        </div>
		
        <!-- File upload form -->
        <div class="col-md-12" id="importFrm" style="display: none;">
            <form action="<?php echo base_url('employee/import'); ?>" method="post" enctype="multipart/form-data">
                <input type="file" name="file" />
                <input type="submit" class="btn btn-primary" style="width:10%;" name="importSubmit" value="IMPORT" onclick="ContinueToggle(this.id);">
            </form>
        </div>
        
		
		<!--<form action="<?php echo base_url('employee/upload_csv_to_db'); ?>" method="post">-->

		<table id="table_e" class="table is-striped is-narrow is-hoverable">
           
            <tbody>
                <?php if(!empty($csvFields)){  ?>
				<tr>
					<td colspan="2" style="margin-bottom:20px;"><b>Specify Corresponding Columns:</b></td>

				</tr>
                <tr>
                    <td>Employee code </td>
                    <td><select name="emp_code" class="small-input" id ="emp_code">   
					<option value="0">Select --</option>
						<?php foreach($csvFields as $csvField) { ?>
								<option value=" <?php echo htmlspecialchars(json_encode($csvField)); ?> "> <?php echo $csvField; ?> </option>
						<?php }?>
				        </select>
					</td>
                </tr>
				<tr>
                    <td>Employee Name</td>
					<td><select name="emp_name" class="small-input" id ="emp_name">   
					<option value="0">Select --</option>
					
						<?php foreach($csvFields as $csvField) { ?>
								<option value=" <?php  echo htmlspecialchars(json_encode($csvField)); ?> "> <?php echo $csvField; ?> </option>
						<?php }?>
				        </select>
					</td>                </tr>
				<tr>
                    <td>Department</td>
                    <td><select name="dep" class="small-input" id ="dep">    
					<option value="0">Select --</option>
					
						<?php foreach($csvFields as $csvField) { ?>
								<option value=" <?php echo htmlspecialchars(json_encode($csvField));?> "> <?php echo $csvField; ?> </option>
						<?php }?>
				        </select>
					</td>
                </tr>
				<tr>
                    <td>Age</td>
                    <td><select name="dob" class="small-input" id ="dob">    
					<option value="0">Select Date of Birth --</option>					
						<?php foreach($csvFields as $csvField) { ?>
								<option value=" <?php echo htmlspecialchars(json_encode($csvField)); ?> "> <?php echo $csvField; ?> </option>
						<?php }?>
				        </select>
					</td>
                </tr>
				<tr>
                    <td>Experience</td>
                    <td><select name="joining_date" class="small-input" id ="joining_date">  
					<option value="0">Select joining date --</option>
					
						<?php foreach($csvFields as $csvField) { ?>
								<option value=" <?php echo htmlspecialchars(json_encode($csvField));?> "> <?php echo $csvField; ?> </option>
						<?php }?>
				        </select>
					</td>
                </tr>
				<tr>
					<td colspan="2" style="text-align:center;"><input type="submit" style="width:25%;align:center;" class="btn btn-primary" name="importUpload" onclick="validation()";value="Continue"></td>

				</tr>
                <?php  }?>
            </tbody>
        </table>    
		<input type="hidden" name="data"  id="data"value="<?php echo htmlspecialchars(json_encode($csvData));?>">                                     


		<!--</form>-->
		
		
        <!-- Data list table -->
        <table id="table_em" class="table is-bordered is-striped is-narrow is-hoverable">
            <thead class="thead-dark">
                <tr>
                    <th>Employee code</th>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Age</th>
                    <th>Experience in the organization</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($employees)){ foreach($employees as $row){ ?>
                <tr>
                    <td><?php echo $row['code']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['department']; ?></td>
                    <td><?php echo date_diff(date_create($row['dob']), date_create('today'))->y;?></td>
                    <td><?php $diff = date_diff(date_create($row['joining_date']), date_create('today')) ; echo $diff->y ." years ". $diff->m ." months"?></td>
                </tr>
                <?php } }else{ ?>
                <tr><td colspan="5">No member(s) found...</td></tr>
                <?php } ?>
            </tbody>
        </table>
		                <p id="p" style="text-indent:10px" class="is-fullwidth"><pre><?php echo $links; ?></pre></p>

    </div>
</div>

<script>
/*var table = document.getElementById("table_e");
var id_e = document.getElementById("imp_btn");
if(table.style.display === "block"){
        table.style.display = "block";
		id.style.display ="none";
		
    }else{
        table.style.display = "none";
    }*/
	
function validation(){
	
	var code = $('#emp_code').val();
	var name = $('#emp_name').val();
	var dep = $('#dep').val();
	var dob = $('#dob').val();
	var joining_date = $('#joining_date').val();
	var data = $('#data').val();

	if(code==0||name==0||dep==0||dob==0||joining_date ==0){
		$('#error_m').css('color','red');
        $('#error_m').text('Should fill up all fields');
		return false;
	}
	$.ajax({
	type :"POST",
	url: "upload_csv_to_db",
	data:{code:code,name:name,dep:dep,dob:dob,joining_date:joining_date,data:data},
	success:function(data)
		{
		data = data.trim();
		console.log(data);
		if(data =="Success")
		{
		window.location =window.location.origin +"/employee";
		window.scrollTo(0, 0);
		}else
		{
			$('#error_m').css('color','red');
			$('#error_m').text(data);
		}
		}
	});

}
jQuery(document).ready(function() { 
	var id_e = document.getElementById("imp_btn");

	var Status1= '<?php echo $count ?>';
	if( Status1 > 1)
	{
				id_e.style.display ="none";

	}

});

function formToggle(id,ID){
    var element = document.getElementById(ID);
	    var id = document.getElementById(id);

    if(element.style.display === "none"){
        element.style.display = "block";
		id.style.display ="none";
		
    }else{
        element.style.display = "none";
    }
}

</script>
</body>
</html>