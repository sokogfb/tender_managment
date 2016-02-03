<?php
session_start();
require_once("../main_includes/main_class.php");
$obj = new main_front_class();
if(!isset($_SESSION['rb_uname']) || !isset($_SESSION['rb_pass']) || !isset($_SESSION['rb_pin']) || !isset($_SESSION['rb_power']))
{
	$obj->redirect("index.php");
}
else
{
	$result_login = $obj->LoginData($_SESSION['rb_power'],$_SESSION['rb_uname']);
	
}
if(isset($_GET['val']) && $_GET['val']!="")
{
	$result = $obj->common_fetchdata('tender',$_GET['val']);
	
	$corect_date_format = explode("-",$result['tender_due_date']);
	$final_date =$corect_date_format[2].'-'.$corect_date_format[1].'-'.$corect_date_format[0];
	$tender_office= $obj->Common_name_id('office','office_name',$result['tender_office']);
	
	$tender_purchaser = $obj->Common_name_id('create_purchaser','purchaser_short_name',$result['tender_purchaser']);
	
	
$result_product = $obj->common_fetch_attachement_withItem_nolimit('tender_firm_product','tender_id',$_GET['val']);
	
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../main_css/main_css.css"/>
<script type="text/javascript" src="../js/jquery-1.9.1.min.js"></script>
<style type="text/css">
*
{
	margin:0;
	padding:0;
	
}
#wrapper
{
	width:1200px;
	height:auto;
	min-height:600px;

	margin:5px auto 0px auto;
}
#office
{
	width:100%;
	height:25px;
    font-family: 'BebasNeueRegular';
	letter-spacing:1px;
	color:#363636;
	border-bottom:1px solid #000;
		border-top:1px solid #000;
		line-height:25px;
		padding-left:5px;

	
	
}
#sec_strip
{
	width:100%;
	height:25px;
    font-family: 'BebasNeueRegular';
		letter-spacing:1px;
	color:#363636;
	border-bottom:1px solid #000;
		border-top:1px solid #000;
		line-height:25px;
		padding-left:5px;
		margin-top:10px;
		

	
	
}
#sec_strip span
{
	font-family: 'BebasNeueRegular';
	letter-spacing:1px;
	color:#363636;
	margin-left:130px;		

}
p
{
	text-align:center;
	font-family: 'BebasNeueRegular';
	letter-spacing:1px;
	color:#363636;
	margin-top:5px;
}
table
{
	border-collapse:collapse;
	text-align:center;
	margin:0 auto;
	margin-top:10px;
}
table th
{
	text-align:center;
	font-family: 'BebasNeueRegular';
	letter-spacing:1px;
	color:#363636;
	font-weight:normal;
	height:30px;
}
table tr td
{
	min-height:35px;
	height:auto;
	word-wrap:break-word;
	font-size:12px;
	height:20px;
}
</style>
<script type="text/javascript">
    $(document).ready(function() {
    $('.print').click(function() {
    window.print();
    return false;
    });
    });
</script>
<title>Information of Tender No. <?php print_r($result['tender_number']); ?></title>
</head>

<body>
<div id="wrapper">
<p><input type="button" class="print" value="Print" /></p>
<p style="font-size:19px;">Tender Number : <?php print_r($result['tender_number']); ?> <?php print_r($result['officer_last_name']) ?></p>
<table width="800" border="1">
  <tr>
    <th width="157" scope="col">Tender Office</th>
    <th width="589" scope="col"><?php print_r($tender_office['office_name']); ?></th>
  </tr>
  <tr>
    <th width="157" scope="col">Purchaser</th>
    <th width="589" scope="col"><?php print_r($tender_purchaser['purchaser_short_name']); ?></th>
  </tr>
  <tr>
    <th width="157" scope="col">Tender Type</th>
    <th width="589" scope="col"><?php echo $tenderType = $obj->Tender_type($result['tender_type']); ?></th>
  </tr>
  <tr>
    <th width="157" scope="col">Due Date/Time</th>
    <th width="589" scope="col"><?php echo $final_date; ?>/<?=$result['tender_time'];?></th>
  </tr>
  <tr>
    <th width="157" scope="col">Sample Required</th>
    <th width="589" scope="col"><?php if($result['tender_sample']==0){echo "Yes";} else { echo "No"; } ?></th>
  </tr>
  <tr>
    <th width="157" scope="col">TDC</th>
    <th width="589" scope="col"><?php print_r($result['tender_tdc']); ?></th>
  </tr>
  <tr>
    <th width="157" scope="col">EMD</th>
    <th width="589" scope="col"><?php print_r($result['tender_emd']); ?></th>
  </tr>
  <tr>
    <th width="157" scope="col">Criteria</th>
    <th width="589" scope="col"><?php print_r($result['tender_criteria']); ?></th>
  </tr>
  
 
 </table>

<!------------------------------------------------------>
 <?php if($result_product[0]!='')
 {
	 ?>
     <table style="width:800px;margin-left:0px; margin:10px auto; table-layout:fixed;">
     <tr>
 <th style="width:200px; border:1px solid #333;">Item Category</th> 
 <th style="width:100px; border:1px solid #333;">Item Inspection</th>
 <th style="width:200px; border:1px solid #333;">Item Discription</th>
 <th style="width:100px; border:1px solid #333;">Consignee</th>
 <th style="width:80px; border:1px solid #333;">Quantity</th>
 <th style="width:80px; border:1px solid #333;">Unit</th>
 
</tr>
<?php
foreach($result_product as $mj) 
	{
		$my_unit=$obj->unit($mj['unit']);
	$my_inspection=$obj->inspection($mj['inspection']);
		?>
    	<tr id="update_row<?=$mj['id'];?>">
      <td style="border:1px solid #333;"><?=$mj['item_name'].":-".$mj['item_discription'].':('.$mj['item_id'].')';?></td>
    <td style="border:1px solid #333;"><?php echo $my_inspection;?></td>
    <td style="border:1px solid #333;"><?=$mj['discription'];?></td>
     <td style="border:1px solid #333;"><?=$mj['main_csign_short_name'];?></td>
     <td style="border:1px solid #333;"><?=$mj['quantity'];?></td>
      <td style="border:1px solid #333;"><?php echo $my_unit;?></td>
      
        
   
    </tr>
	<?php
	}    
 
 ?>
 
     </table>
     
     <?php
	
 }
 
 ?>
<!------------------------------------------------------>

</div>

</body>
</html>
