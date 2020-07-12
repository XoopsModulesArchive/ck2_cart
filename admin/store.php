<?php
//  ------------------------------------------------------------------------ //
// ���Ҳե� tad �s�@
// �s�@����G2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------�ޤJ�ɮװ�--------------*/
include "../../../include/cp_header.php";
include "../function.php";

/*-----------function��--------------*/
//ck2_stores�s����
function ck2_stores_form($stores_sn=""){
	global $xoopsDB,$xoopsUser;
	include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
	//include_once(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php");

  $uid=$xoopsUser->getVar('uid');

	//����w�]��
	if(!empty($uid)){
		$DBV=get_ck2_stores($stores_sn,$uid);
	}else{
		$DBV=array();
	}

	//�w�]�ȳ]�w

	$stores_sn=(!isset($DBV['stores_sn']))?"":$DBV['stores_sn'];
	$store_title=(!isset($DBV['store_title']))?"":$DBV['store_title'];
	$store_desc=(!isset($DBV['store_desc']))?"":$DBV['store_desc'];
	$store_counter=(!isset($DBV['store_counter']))?"":$DBV['store_counter'];
	$store_master=(!isset($DBV['store_master']))?"":$DBV['store_master'];
	$store_email=(!isset($DBV['store_email']))?"":$DBV['store_email'];
	$enable=(!isset($DBV['enable']))?"":$DBV['enable'];
	$uid=(!isset($DBV['uid']))?"":$DBV['uid'];
	$open_date=(!isset($DBV['open_date']))?"":$DBV['open_date'];



	include(XOOPS_ROOT_PATH."/modules/ck2_cart/class/fckeditor/fckeditor.php") ;
	$oFCKeditor = new FCKeditor('store_desc') ;
	$oFCKeditor->BasePath	= XOOPS_URL."/modules/ck2_cart/class/fckeditor/" ;
	$oFCKeditor->Config['AutoDetectLanguage']=false;
	$oFCKeditor->Config['DefaultLanguage']		= 'zh' ;
	$oFCKeditor->ToolbarSet ='my';
	$oFCKeditor->Width = '544' ;
	$oFCKeditor->Height = '150' ;
	$oFCKeditor->Value =$store_desc;
	$store_desc_editor=$oFCKeditor->CreateHtml() ;
	
	
	$op=(empty($stores_sn))?"insert_ck2_stores":"update_ck2_stores";
	//$op="replace_ck2_stores";

	$main="
	<link type='text/css' rel='stylesheet' href='".XOOPS_URL."/modules/ck2_cart/class/formValidator/style/validator.css'>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/jquery_last.js' type='text/javascript'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidator.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidatorRegex.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/DateTimeMask.js' language='javascript' type='text/javascript'></script>
	<script type='text/javascript'>
	$(document).ready(function(){
	$.formValidator.initConfig({formid:'myForm',onerror:function(msg){alert(msg)}});



	//�u�ө��W�١v����ˬd
	$('#store_title').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_STORE_TITLE)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','255')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:255,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_STORE_TITLE)."'
	});

	//�u�ө�²���v����ˬd
	$('#store_desc').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_STORE_DESC)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_MIN,'1')."',
		oncorrect:'OK!'
	});

	//�u���D�m�W�v����ˬd
	$('#store_master').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_STORE_MASTER)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','255')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:255,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_STORE_MASTER)."'
	});

	//�u�pôEmail�v����ˬd
	$('#store_email').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_STORE_EMAIL)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','255')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:255,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_STORE_EMAIL)."'
	});
	});
	</script>
	<script defer='defer' src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/datepicker/WdatePicker.js' type='text/javascript'></script>

	<form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
	<table class='form_tbl'>

	<input type='hidden' name='stores_sn' value='{$stores_sn}'>
	<tr><td class='title'>"._MA_CK2CART_STORE_TITLE."</td>
	<td class='col'><input type='text' name='store_title' size='20' value='{$store_title}' id='store_title'></td><td class='col'><div id='store_titleTip'></div></td></tr>


	<tr><td class='title'>"._MA_CK2CART_STORE_IMAGE."</td>
	<td class='col' colspan=3><input type='file' name='image' size='50'  id='image'></td><td class='col'><div id='imageTip'></div></td></tr>

	<tr><td class='title'>"._MA_CK2CART_STORE_DESC."</td>
	<td class='col'>$store_desc_editor</td><td class='col'><div id='store_descTip'></div></td></tr>
	
	<tr><td class='title'>"._MA_CK2CART_STORE_MASTER."</td>
	<td class='col'><input type='text' name='store_master' size='20' value='{$store_master}' id='store_master'></td><td class='col'><div id='store_masterTip'></div></td></tr>
	
	<tr><td class='title'>"._MA_CK2CART_STORE_EMAIL."</td>
	<td class='col'><input type='text' name='store_email' size='20' value='{$store_email}' id='store_email'></td><td class='col'><div id='store_emailTip'></div></td></tr>
	
	<tr><td class='title'>"._MA_CK2CART_ENABLE."</td>
	<td class='col'>
	<input type='radio' name='enable' id='enable' value='0' ".chk($enable,'0').">"._MA_CK2CART_STORES_UNABLE."
	<input type='radio' name='enable' id='enable' value='1' ".chk($enable,'1').">"._MA_CK2CART_STORES_ENABLE."</td><td class='col'><div id='enableTip'></div></td></tr>
	
	<tr><td class='bar' colspan='3'>
	<input type='hidden' name='op' value='{$op}'>
	<input type='submit' value='"._MA_SAVE."'></td></tr>
	</table>
	</form>";

	//raised,corners,inset
	$main=div_3d(_MA_CK2CART_STORES_INPUT_FORM,$main,"raised");

	return $main;
}

//�s�W��ƨ�ck2_stores��
function insert_ck2_stores(){
	global $xoopsDB,$xoopsUser;
	$uid=$xoopsUser->getVar('uid');
	$sql = "insert into ".$xoopsDB->prefix("ck2_stores")." (`store_title`,`store_desc`,`store_counter`,`store_master`,`store_email`,`enable`,`uid`,`open_date`) values('{$_POST['store_title']}','{$_POST['store_desc']}','{$_POST['store_counter']}','{$_POST['store_master']}','{$_POST['store_email']}','{$_POST['enable']}','{$uid}',now())";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],30, mysql_error());

	//���o�̫�s�W��ƪ��y���s��
	$stores_sn=$xoopsDB->getInsertId();
	

	//�W�ǹ���
  if(!empty($_FILES['image']['name'])){
  	upload_pic("stores_sn",$stores_sn,"1","insert");
 	}
 	
	return $stores_sn;
}



//��sck2_stores�Y�@�����
function update_ck2_stores($stores_sn=""){
	global $xoopsDB,$xoopsUser;
	$uid=$xoopsUser->getVar('uid');
	$sql = "update ".$xoopsDB->prefix("ck2_stores")." set  `store_title` = '{$_POST['store_title']}', `store_desc` = '{$_POST['store_desc']}', `store_counter` = '{$_POST['store_counter']}', `store_master` = '{$_POST['store_master']}', `store_email` = '{$_POST['store_email']}', `enable` = '{$_POST['enable']}'  where `stores_sn`='$stores_sn' and `uid` = '{$uid}'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	

	//�W�ǹ���
  if(!empty($_FILES['image']['name'])){
  	upload_pic("stores_sn",$stores_sn,"1","update");
 	}
 	
	return $stores_sn;
}

//�R��ck2_stores�Y����Ƹ��
function delete_ck2_stores($stores_sn=""){
	global $xoopsDB;
	$sql = "delete from ".$xoopsDB->prefix("ck2_stores")." where stores_sn='$stores_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}


/*-----------����ʧ@�P�_��----------*/
$op = (!isset($_REQUEST['op']))? "main":$_REQUEST['op'];

switch($op){
	//��s���
	case "update_ck2_stores":
	update_ck2_stores($_POST['stores_sn']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//�s�W���
	case "insert_ck2_stores":
	insert_ck2_stores();
	header("location: commodity.php");
	break;
	
	//��J���
	case "ck2_stores_form":
	$main=ck2_stores_form($_GET['stores_sn']);
	break;

	//�R�����
	case "delete_ck2_stores":
	delete_ck2_stores($_GET['stores_sn']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//�w�]�ʧ@
	default:
	$main=ck2_stores_form($_GET['stores_sn']);
	break;



}

/*-----------�q�X���G��--------------*/
xoops_cp_header();
echo "<link rel='stylesheet' type='text/css' media='screen' href='../module.css' />
<link rel='stylesheet' type='text/css' media='screen' href='../admin_store.css' />";
echo menu_interface();
echo $main;
xoops_cp_footer();

?>
