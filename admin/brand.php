<?php
//  ------------------------------------------------------------------------ //
// ���Ҳե� tad �s�@
// �s�@����G2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------�ޤJ�ɮװ�--------------*/
include "../../../include/cp_header.php";
include "../function.php";
include "admin_function.php";


/*-----------function��--------------*/
//ck2_stores_brand�s����
function ck2_stores_brand_form($brand_sn=""){
	global $xoopsDB;

	//����w�]��
	if(!empty($brand_sn)){
		$DBV=get_ck2_stores_brand($brand_sn);
	}else{
		$DBV=array();
	}

	//�w�]�ȳ]�w

	$brand_sn=(!isset($DBV['brand_sn']))?"":$DBV['brand_sn'];
	$brand_name=(!isset($DBV['brand_name']))?"":$DBV['brand_name'];
	$brand_desc=(!isset($DBV['brand_desc']))?"":$DBV['brand_desc'];
	$brand_url=(!isset($DBV['brand_url']))?"":$DBV['brand_url'];
	$enable=(!isset($DBV['enable']))?"1":$DBV['enable'];

	$op=(empty($brand_sn))?"insert_ck2_stores_brand":"update_ck2_stores_brand";
	//$op="replace_ck2_stores_brand";

	$main="
	<link type='text/css' rel='stylesheet' href='".XOOPS_URL."/modules/ck2_cart/class/formValidator/style/validator.css'>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/jquery_last.js' type='text/javascript'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidator.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidatorRegex.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/DateTimeMask.js' language='javascript' type='text/javascript'></script>
	<script type='text/javascript'>
	$(document).ready(function(){
	$.formValidator.initConfig({formid:'myForm',onerror:function(msg){alert(msg)}});



	//�ubrand_name�v����ˬd
	$('#brand_name').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_BRAND_NAME)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','255')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:255,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_BRAND_NAME)."'
	});
	});
	</script>
	<script defer='defer' src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/datepicker/WdatePicker.js' type='text/javascript'></script>

	<form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
	<table class='form_tbl'>

	<input type='hidden' name='brand_sn' value='{$brand_sn}'>
	<tr><td class='title'>"._MA_CK2CART_BRAND_NAME."</td>
	<td class='col'><input type='text' name='brand_name' size='40' value='{$brand_name}' id='brand_name'></td><td class='col'><div id='brand_nameTip'></div></td></tr>
	<tr><td class='title'>"._MA_CK2CART_BRAND_IMAGE."</td>
	<td class='col' colspan=3><input type='file' name='image' size='50'  id='image'></td><td class='col'><div id='imageTip'></div></td></tr>";

	include(XOOPS_ROOT_PATH."/modules/ck2_cart/class/fckeditor/fckeditor.php") ;
	$oFCKeditor = new FCKeditor('brand_desc') ;
	$oFCKeditor->BasePath	= XOOPS_URL."/modules/ck2_cart/class/fckeditor/" ;
	$oFCKeditor->Config['AutoDetectLanguage']=false;
	$oFCKeditor->Config['DefaultLanguage']		= 'zh' ;
	$oFCKeditor->ToolbarSet ='my';
	$oFCKeditor->Width = '544' ;
	$oFCKeditor->Height = '150' ;
	$oFCKeditor->Value =$brand_desc;
	$brand_desc_editor=$oFCKeditor->CreateHtml() ;

	$main.="<tr><td class='title'>"._MA_CK2CART_BRAND_DESC."</td>
	<td class='col' colspan=2>$brand_desc_editor<div id='brand_descTip'></div></td></tr>
	
	<tr><td class='title'>"._MA_CK2CART_BRAND_URL."</td>
	<td class='col'><input type='text' name='brand_url' size='40' value='{$brand_url}' id='brand_url'></td>
	<td class='col'>"._MA_CK2CART_BRAND_IS_UNABLE."
	<input type='radio' name='enable' id='enable' value='1' ".chk($enable,'1','1').">"._MA_CK2CART_BRAND_IS_ENABLE."
	<input type='radio' name='enable' id='enable' value='0' ".chk($enable,'0').">"._MA_CK2CART_BRAND_IS_UNABLE."

	<input type='hidden' name='op' value='{$op}'>
	<input type='submit' value='"._MA_SAVE."'></td></tr>
	

	</table>
	</form>";

	$main.=list_ck2_stores_brand(1,true);

	//raised,corners,inset
	$main=div_3d(_MA_CK2CART_BRAND_INPUT_FORM,$main,"raised");

	return $main;
}

//�s�W��ƨ�ck2_stores_brand��
function insert_ck2_stores_brand(){
	global $xoopsDB;
	$stores_sn=get_stores_sn();
	$sql = "insert into ".$xoopsDB->prefix("ck2_stores_brand")." (`stores_sn`,`brand_name`,`brand_desc`,`brand_url`,`enable`) values('{$stores_sn}','{$_POST['brand_name']}','{$_POST['brand_desc']}','{$_POST['brand_url']}','{$_POST['enable']}')";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	//���o�̫�s�W��ƪ��y���s��
	$brand_sn=$xoopsDB->getInsertId();

	//�W�ǹ���
  if(!empty($_FILES['image']['name'])){
  	upload_pic("brand_sn",$brand_sn,"1","insert");
 	}

	return $brand_sn;
}

//�C�X�Ҧ�ck2_stores_brand���
function list_ck2_stores_brand($show_function=1,$nodiv=false){
	global $xoopsDB,$xoopsModule;
	$MDIR=$xoopsModule->getVar('dirname');
	$stores_sn=get_stores_sn();

	$sql = "select a.*,b.filename from ".$xoopsDB->prefix("ck2_stores_brand")." as a left join ".$xoopsDB->prefix("ck2_stores_image_center")." as b on b.col_name='brand_sn' and b.col_sn=a.brand_sn where stores_sn='$stores_sn'";

	//PageBar(��Ƽ�, �C����ܴX�����, �̦h��ܴX�ӭ��ƿﶵ);
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$total=$xoopsDB->getRowsNum($result);

	$navbar = new PageBar($total, 20, 10);
	$mybar = $navbar->makeBar();
	$bar= sprintf(_BP_TOOLBAR,$mybar['total'],$mybar['current'])."{$mybar['left']}{$mybar['center']}{$mybar['right']}";
	$sql.=$mybar['sql'];

	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$function_title=($show_function)?"<th>"._BP_FUNCTION."</th>":"";

	//�R���T�{��JS
	$data="
	<script>
	function delete_ck2_stores_brand_func(brand_sn){
		var sure = window.confirm('"._BP_DEL_CHK."');
		if (!sure)	return;
		location.href=\"{$_SERVER['PHP_SELF']}?op=delete_ck2_stores_brand&brand_sn=\" + brand_sn;
	}
	</script>
	<table id='tbl' style='width:100%;'>
	<tr>
	<th>"._MA_CK2CART_BRAND_NAME."</th>
	<th>"._MA_CK2CART_BRAND_URL."</th>
	<th>"._MA_CK2CART_ENABLE."</th>
	$function_title</tr>
	<tbody>";

	while($all=$xoopsDB->fetchArray($result)){
	  //�H�U�|���ͳo���ܼơG $brand_sn,$stores_sn,$brand_name,$brand_desc,$brand_url,$enable
    foreach($all as $k=>$v){
      $$k=$v;
    }
		$fun=($show_function)?"<td>
		<a href='{$_SERVER['PHP_SELF']}?op=ck2_stores_brand_form&brand_sn=$brand_sn'><img src='".XOOPS_URL."/modules/{$MDIR}/images/edit.gif' alt='"._BP_EDIT."'></a>
		<a href=\"javascript:delete_ck2_stores_brand_func($brand_sn);\"><img src='".XOOPS_URL."/modules/{$MDIR}/images/del.gif' alt='"._BP_DEL."'></a></td>":"";


		$pic=empty($filename)?"":"<img src='"._CK2CART_UPLOAD_URL."/{$stores_sn}/image/thumb/{$brand_sn}_{$filename}'>";
		
		$status=($enable=='1')?_MA_CK2CART_BRAND_IS_ENABLE:_MA_CK2CART_BRAND_IS_UNABLE;
		
		$data.="<tr>
		<td>{$pic}</td>
		<td><b>{$brand_name}</b><br>{$brand_url}</td>
		<td>{$status}</td>
		$fun</tr>";
	}

	$data.="
	<tr>
	<td colspan=5 class='bar'>{$bar}</td></tr>
	</tbody>
	</table>";
	
	if($nodiv)return $data;

	//raised,corners,inset
	$main=div_3d("",$data,"corners");

	return $main;
}


//�H�y�������o�Y��ck2_stores_brand���
function get_ck2_stores_brand($brand_sn=""){
	global $xoopsDB;
	if(empty($brand_sn))return;
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_brand")." where brand_sn='$brand_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}

//��sck2_stores_brand�Y�@�����
function update_ck2_stores_brand($brand_sn=""){
	global $xoopsDB;
	$stores_sn=get_stores_sn();
	$sql = "update ".$xoopsDB->prefix("ck2_stores_brand")." set  `brand_name` = '{$_POST['brand_name']}', `brand_desc` = '{$_POST['brand_desc']}', `brand_url` = '{$_POST['brand_url']}', `enable` = '{$_POST['enable']}' where brand_sn='$brand_sn' and stores_sn='$stores_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	//�W�ǹ���
  if(!empty($_FILES['image']['name'])){
  	upload_pic("brand_sn",$brand_sn,"1","update");
 	}
	return $brand_sn;
}

//�R��ck2_stores_brand�Y����Ƹ��
function delete_ck2_stores_brand($brand_sn=""){
	global $xoopsDB;
	$stores_sn=get_stores_sn();
	$sql = "delete from ".$xoopsDB->prefix("ck2_stores_brand")." where brand_sn='$brand_sn' and stores_sn='$stores_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}

/*-----------����ʧ@�P�_��----------*/
$op = (!isset($_REQUEST['op']))? "main":$_REQUEST['op'];

switch($op){

	//��s���
	case "update_ck2_stores_brand":
	update_ck2_stores_brand($_POST['brand_sn']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//�s�W���
	case "insert_ck2_stores_brand":
	insert_ck2_stores_brand();
	header("location: {$_SERVER['PHP_SELF']}");
	break;
	//��J���
	case "ck2_stores_brand_form":
	$main=ck2_stores_brand_form($_GET['brand_sn']);
	break;

	//�R�����
	case "delete_ck2_stores_brand":
	delete_ck2_stores_brand($_GET['brand_sn']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//�w�]�ʧ@
	default:
	$main=ck2_stores_brand_form($_GET['brand_sn']);
	break;


}

/*-----------�q�X���G��--------------*/
xoops_cp_header();
echo "<link rel='stylesheet' type='text/css' media='screen' href='../module.css' />";
echo menu_interface();
echo $main;
xoops_cp_footer();

?>
