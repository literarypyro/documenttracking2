<style type='text/css'>
.container {
	margin-left:30%;
	float:left;
	width:60%;
}
</style>
<div class="container" >

<div class="col-lg-6" >
<div class="well">

<form class="bs-example form-horizontal" action='submit.php' method='post'>
	<legend>Search Archive</legend>
	<div class='form-group'>
	<label class="col-lg-10">
	<?php
		echo "<input type=hidden name='document_action' value='FIND' />";
	?>
	</label>
	</div>
	<div class='form-group'>
	<label class='col-lg-4 control-label'>
	Type of Document
	</label>
	<div class="col-lg-6">
	<select name='document_type' class='form-control'>
	<option value='IN'>Incoming (from outside MRT3)</option>
	<option value='MEMO'>Internal Document</option>
<?php 
if($_SESSION['department']=='REC'){
?>
	<option value='ORD'>Office Order</option>
<?php
}
?>
	<option value='OUT'>Outgoing (to outside MRT3)</option>
</select>
	</div>
	</div>
	<div class='form-group'>
	<input type=submit class="btn btn-primary"  value='Process' />
	</div>

</td>
</tr>

</table>
</form>
</div>
</div>
</div>
