<?php
/**The following list of functions generates HTML select elements which lists
* given options in month, day, year, time, and lists of officers and departments
*/
function retrieveMonthListHTML($monthName){
	?>
	<select class='form-control' name='<?php echo $monthName; ?>' id='<?php echo $monthName; ?>'>
	<option <?php if(date('m')=='1'){ echo "selected"; } ?> value='1'>January</option>
	<option <?php if(date('m')=='2'){ echo "selected"; } ?> value='2'>February</option>
	<option <?php if(date('m')=='3'){ echo "selected"; } ?> value='3'>March</option>
	<option <?php if(date('m')=='4'){ echo "selected"; } ?> value='4'>April</option>
	<option <?php if(date('m')=='5'){ echo "selected"; } ?> value='5'>May</option>
	<option <?php if(date('m')=='6'){ echo "selected"; } ?> value='6'>June</option>
	<option <?php if(date('m')=='7'){ echo "selected"; } ?> value='7'>July</option>
	<option <?php if(date('m')=='8'){ echo "selected"; } ?> value='8'>August</option>
	<option <?php if(date('m')=='9'){ echo "selected"; } ?> value='9'>September</option>
	<option <?php if(date('m')=='10'){ echo "selected"; } ?> value='10'>October</option>
	<option <?php if(date('m')=='11'){ echo "selected"; } ?> value='11'>November</option>
	<option <?php if(date('m')=='12'){ echo "selected"; } ?> value='12'>December</option>
	</select>
	<?php
}
function retrieveDayListHTML($dayName){
	?>
	<select  class='form-control'  name='<?php echo $dayName; ?>' id='<?php echo $dayName; ?>'>
	<?php

	for($i=1;$i<32;$i++){
			
	?>
		<option <?php if(date("d")==$i){ echo "selected"; }	?> value='<?php echo $i; ?>'><?php echo $i; ?></option>
	<?php
	}
	?>
	</select>
	<?php
}

function retrieveYearListHTML($yearName){
	?>
	<select  class='form-control' name='<?php echo $yearName; ?>' id='<?php echo $yearName; ?>'>
	<?php
	$yearNow=date("Y");
	$yearSet=$yearNow*1-10;
	$yearEnd=$yearNow*1+10;	
	
	for($i=$yearSet;$i<$yearEnd;$i++){
	?>
		<option <?php if((date("Y"))==$i){ echo "selected"; } ?>><?php echo $i; ?></option>
	<?php
	}
	?>
		</select>
	<?php	
}

function retrieveHourListHTML($hourName){
	?>
	<select  class='form-control' name='<?php echo $hourName; ?>'>
	<?php
		for($i=1;$i<=12;$i++){
	?>
	<option <?php if(date("g")==$i){ echo "selected"; } ?>><?php echo $i; ?></option>
	<?php
	}
?>
	</select>
	<?php
}

function retrieveMinuteListHTML($minuteName){
	?>
	<select  class='form-control' name='<?php echo $minuteName; ?>'>
	<?php
	for($i=0;$i<60;$i++){
	?>
		<option <?php if(date("i")*1==$i){ echo "selected"; } ?>><?php echo $i; ?></option>
	<?php
	}
	?>
	</select>
	<?php
}

function retrieveShiftListHTML($shiftName){
	?>
	<select  class='form-control' name='<?php echo $shiftName; ?>'>
	<option <?php if(date("A")=='AM'){ echo "selected"; } ?>>AM</option>
	<option <?php if(date("A")=='PM'){ echo "selected"; } ?>>PM</option>
	</select>	
	<?php
}

function retrieveClassListHTML2($db,$className,$alternClass,$division){
	?>
	<select  class='form-control' id='<?php echo $className; ?>' name='<?php echo $className; ?>' onchange='checkAlternate(this,90,"<?php echo $alternClass; ?>")'>
	<?php
	$sql="select * from classification inner join division_classification on classification.id=classification_id where division_id in ('ALL','$division') order by classification_desc";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
	?>
		<option value='<?php echo $row['id']; ?>'><?php echo $row['classification_desc']; ?></option>
	<?php
	}
	?>
	<option value='90'>OTHER</option>

	</select>
	<input class='form-control' type=text id='<?php echo $alternClass; ?>' name='<?php echo $alternClass; ?>' disabled=true size=33 />
	<?php
}
function retrieveClassListHTML($db,$className,$alternClass){
	?>
	<div class='form-group'>
	<select class='form-control' id='<?php echo $className; ?>' name='<?php echo $className; ?>' onchange='checkAlternate(this,5,"<?php echo $alternClass; ?>")'>
	<?php
	$sql="select * from classification inner join division_classification on classification.id=classification_id where division_id='ALL'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
	?>
		<option value='<?php echo $row['id']; ?>'><?php echo $row['classification_desc']; ?></option>
	<?php
	}
	?>
	</select>
	</div>

	<div class='form-group'>
	<input class='form-control' type=text id='<?php echo $alternClass; ?>' name='<?php echo $alternClass; ?>' disabled=true size=33 />
	</div>
	<?php
}

function retrieveOfficeListHTML($db,$selected,$officeName,$alternate){
	?>
	<select  class='form-control' id='<?php echo $officeName; ?>' name='<?php echo $officeName; ?>' onchange='checkAlternate(this,13,"<?php echo $alternate; ?>")'>
	<?php
	$sql="select * from originating_office where department_code not in ('13')";
	$result=$db->query($sql);
	$number_of_results=$result->num_rows;

	for($i=0;$i<$number_of_results;$i++){
	$row=$result->fetch_assoc();
		if($row['department_name']==""){
		}
		else {
	?>
	<option data-division='<?php echo $row['department_tag']; ?>'
		<?php if($selected==$row['department_code']){ ?> 
		selected 
		<?php } ?>
		value='<?php echo $row['department_code']; ?>'>
		<?php echo $row['department_name']; ?>
	</option>
	<?php
		}
	}
	?>
	<option value='13'>OTHER</option>
	</select>
	<input class='form-control' type=text id='<?php echo $alternate; ?>'  name='<?php echo $alternate; ?>' disabled=true />
	<?php
}	
	

function retrieveOfficerListHTML($db,$officer_name){
	$sql="select * from originating_officer";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	?>
	<select  class='form-control' id='<?php echo $officer_name; ?>' name='<?php echo $officer_name; ?>'>
	<?php
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		?>
		<option data-division='<?php echo $row['division']; ?>' value='<?php echo $row['id']; ?>'><?php echo $row['name']; ?></option>
	<?php
	}
	?>	
	<option value='OTHER'>OTHER</option>
	</select>

	<?php
}	

function retrieveOfficerListHTML2($db,$officer_name,$division){
	$sql="select * from originating_officer";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	?>
	<select  class='form-control' id='<?php echo $officer_name; ?>' name='<?php echo $officer_name; ?>' onchange='processChange(this)'>
	<?php
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		?>
		<option <?php if($row['division']==$division){ echo "selected"; } ?> data-row-division='<?php echo $division; ?>' data-division='<?php echo $row['division']; ?>' data-name='<?php echo $row['name']; ?>' value='<?php echo $row['id']; ?>'><?php echo $row['name']; ?></option>
	<?php
	}
	$db2=new mysqli("localhost","records","","user_management");	
	$sql2="select * from users where deptCode='".$division."' order by lastName";
	$rs2=$db2->query($sql2);
	
	$nm2=$rs2->num_rows;
	for($i=0;$i<$nm2;$i++){
		$row2=$rs2->fetch_assoc();
		?>	
		<option data-division='<?php echo $division; ?>'  data-row-division='<?php echo $division; ?>' data-name='<?php echo $row2['firstName']." ".$row2['lastName']; ?>' data-position='<?php echo $row2['position']; ?>' value='OTHER' ><?php echo $row2['firstName']." ".$row2['lastName']; ?></option>
	<?php
	}
	?>	
	<!--
	<option value='OTHER'>OTHER</option>
	-->
	</select>

	<?php
}	

	
function retrieveDepartmentListHTML($db,$selected,$officeName){
	?>
	<select class='form-control' id='<?php echo $officeName; ?>' name='<?php echo $officeName; ?>'>
	<?php
	$sql="select * from department";
	$result=$db->query($sql);
	$number_of_results=$result->num_rows;

	for($i=0;$i<$number_of_results;$i++){
	$row=$result->fetch_assoc();
		if($row['department_name']==""){
		}
		else {
	
	?>
	<option 
		<?php if($selected==$row['department_code']){ ?> 
		selected 
		<?php } ?>
		value='<?php echo $row['department_code']; ?>'>
		<?php echo $row['department_name']; ?>
	</option>
	<?php
		}
	}
	?>
	</select>
	<?php
}		

	
function retrieveOfficerList($db){
	$sql="select * from originating_officer";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	$select="<select  class='form-control' name='officer'>";
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$select.="<option value='".$row['id']."'>".$row['name']."</option>";
	}
	$select.="</select>";
	
	return $select;
}

/** This function calculates the number of days within a given month and year */
function dateLimit($month,$year){

	if(($month=="4")||($month=="6")||($month=="9")||($month=="11"))
	{
		$datelimit="30";
	}
	else if($month=="2")
	{
	   if((($year/4) == round($year/4))&&(($year/100) == round($year/100))){
		$datelimit="29";
	   }
	   else {
		$datelimit="28";	
	   }		
	}
	else {
	   $datelimit="31";
	}
	return $datelimit;
}

?>