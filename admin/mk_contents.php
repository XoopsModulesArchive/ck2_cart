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
//ck2_stores_contents�s����
function ck2_stores_contents_form($page_sn=""){
	global $xoopsDB,$xoopsUser;
	include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
	//include_once(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php");

	//����w�]��
	if(!empty($page_sn)){
		$DBV=get_ck2_stores_contents($page_sn);
	}else{
		$DBV=array();
	}

	//�w�]�ȳ]�w


	//�]�w�upage_sn�v���w�]��
	$page_sn=(!isset($DBV['page_sn']))?"":$DBV['page_sn'];

	//�]�w�upage_title�v���w�]��
	$page_title=(!isset($DBV['page_title']))?"":$DBV['page_title'];

	//�]�w�upage_content�v���w�]��
	$page_content=(!isset($DBV['page_content']))?"":$DBV['page_content'];

	//�]�w�uin_menu�v���w�]��
	$in_menu=(!isset($DBV['in_menu']))?"1":$DBV['in_menu'];

	//�]�w�upost_date�v���w�]��
	$post_date=(!isset($DBV['post_date']))?date("Y-m-d H:i:s"):$DBV['post_date'];

	$op=(empty($page_sn))?"insert_ck2_stores_contents":"update_ck2_stores_contents";
	//$op="replace_ck2_stores_contents";

	$main="
	<link type='text/css' rel='stylesheet' href='".XOOPS_URL."/modules/ck2_cart/class/formValidator/style/validator.css'>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/jquery_last.js' type='text/javascript'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidator.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidatorRegex.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/DateTimeMask.js' language='javascript' type='text/javascript'></script>
	<script type='text/javascript'>
	$(document).ready(function(){
	$.formValidator.initConfig({formid:'myForm',onerror:function(msg){alert(msg)}});



	//�u���D�v����ˬd
	$('#page_title').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_PAGE_TITLE)."',
		onfocus:'"._MD_INPUT_VALIDATOR_NEED."',
		oncorrect:'OK!'
	}).inputValidator({
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_PAGE_TITLE)."'
	});
	});
	</script>
	<script defer='defer' src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/datepicker/WdatePicker.js' type='text/javascript'></script>

	<form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
	<table class='form_tbl'>


	<!--����-->
	<input type='hidden' name='page_sn' value='{$page_sn}'>

	<!--���D-->
	<tr><td class='title' nowrap>"._MA_CK2CART_PAGE_TITLE."</td>
	<td class='col'><input type='text' name='page_title' size='20' value='{$page_title}' id='page_title'></td><td class='col'><div id='page_titleTip'></div></td></tr>";

	include(XOOPS_ROOT_PATH."/modules/ck2_cart/class/fckeditor/fckeditor.php") ;
	$oFCKeditor = new FCKeditor('page_content') ;
	$oFCKeditor->BasePath	= XOOPS_URL."/modules/ck2_cart/class/fckeditor/" ;
	$oFCKeditor->Config['AutoDetectLanguage']=false;
	$oFCKeditor->Config['DefaultLanguage']		= 'zh' ;
	$oFCKeditor->ToolbarSet ='my';  //Basic , Default
	$oFCKeditor->Width = '544' ;
	$oFCKeditor->Height = '250' ;
	$oFCKeditor->Value =$page_content;
	$page_content_editor=$oFCKeditor->CreateHtml() ;

	$main.="
	<!--���e-->
<tr><td class='title' nowrap>"._MA_CK2CART_PAGE_CONTENT."</td>
	<td class='col' colspan='2'>$page_content_editor<div id='page_contentTip'></div></td></tr>

	<!--�X�{�b�u��C�W�H-->
	<tr><td class='title' nowrap>"._MA_CK2CART_IN_MENU."</td>
	<td class='col'>
	<input type='radio' name='in_menu' id='in_menu' value='1' ".chk($in_menu,'1','1').">1
	<input type='radio' name='in_menu' id='in_menu' value='0' ".chk($in_menu,'0').">0</td><td class='col'><div id='in_menuTip'></div></td></tr>
	<tr><td class='bar' colspan='3'>
	<input type='hidden' name='op' value='{$op}'>
	<input type='submit' value='"._MA_SAVE."'></td></tr>
	</table>
	</form>";

	//raised,corners,inset
	$main=div_3d(_MA_INPUT_FORM,$main,"raised");

	return $main;
}

//�s�W��ƨ�ck2_stores_contents��
function insert_ck2_stores_contents(){
	global $xoopsDB,$xoopsUser;


	$sql = "insert into ".$xoopsDB->prefix("ck2_stores_contents")."
	(`page_title` , `page_content` , `in_menu` , `post_date`)
	values('{$_POST['page_title']}' , '{$_POST['page_content']}' , '{$_POST['in_menu']}' , now())";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	//���o�̫�s�W��ƪ��y���s��
	$page_sn=$xoopsDB->getInsertId();
	return $page_sn;
}

//�C�X�Ҧ�ck2_stores_contents���
function list_ck2_stores_contents($show_function=1){
	global $xoopsDB,$xoopsModule;
	$MDIR=$xoopsModule->getVar('dirname');
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_contents")."";

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
	function delete_ck2_stores_contents_func(page_sn){
		var sure = window.confirm('"._BP_DEL_CHK."');
		if (!sure)	return;
		location.href=\"{$_SERVER['PHP_SELF']}?op=delete_ck2_stores_contents&page_sn=\" + page_sn;
	}
	</script>

	<table summary='list_table' id='tbl' style='width:100%;'>
	<tr>
	<th>"._MA_CK2CART_PAGE_SN."</th>
	<th>"._MA_CK2CART_PAGE_TITLE."</th>
	<th>"._MA_CK2CART_IN_MENU."</th>
	<th>"._MA_CK2CART_POST_DATE."</th>
	$function_title</tr>
	<tbody>";

	while($all=$xoopsDB->fetchArray($result)){
	  //�H�U�|���ͳo���ܼơG $page_sn , $page_title , $page_content , $in_menu , $post_date
    foreach($all as $k=>$v){
      $$k=$v;
    }

		$fun=($show_function)?"
		<td>
		<a href='{$_SERVER['PHP_SELF']}?op=ck2_stores_contents_form&page_sn=$page_sn'><img src='".XOOPS_URL."/modules/{$MDIR}/images/edit.gif' alt='"._BP_EDIT."'></a>
		<a href=\"javascript:delete_ck2_stores_contents_func($page_sn);\"><img src='".XOOPS_URL."/modules/{$MDIR}/images/del.gif' alt='"._BP_DEL."'></a>
		</td>":"";

		$data.="<tr>
		<td>{$page_sn}</td>
		<td>{$page_title}</td>
		<td>{$in_menu}</td>
		<td>{$post_date}</td>
		$fun
		</tr>";
	}

	$data.="
	<tr>
	<td colspan=6 class='bar'>
	<a href='{$_SERVER['PHP_SELF']}?op=ck2_stores_contents_form'><img src='".XOOPS_URL."/modules/{$MDIR}/images/add.gif' alt='"._BP_ADD."' align='right'></a>
	{$bar}</td></tr>
	</tbody>
	</table>";

	//raised,corners,inset
	$main=div_3d("",$data,"corners");

	return $main;
}


//�H�y�������o�Y��ck2_stores_contents���
function get_ck2_stores_contents($page_sn=""){
	global $xoopsDB;
	if(empty($page_sn))return;
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_contents")." where page_sn='$page_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}

//��sck2_stores_contents�Y�@�����
function update_ck2_stores_contents($page_sn=""){
	global $xoopsDB,$xoopsUser;


	$sql = "update ".$xoopsDB->prefix("ck2_stores_contents")." set
	 `page_title` = '{$_POST['page_title']}' ,
	 `page_content` = '{$_POST['page_content']}' ,
	 `in_menu` = '{$_POST['in_menu']}' ,
	 `post_date` = now()
	where page_sn='$page_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	return $page_sn;
}

//�R��ck2_stores_contents�Y����Ƹ��
function delete_ck2_stores_contents($page_sn=""){
	global $xoopsDB;
	$sql = "delete from ".$xoopsDB->prefix("ck2_stores_contents")." where page_sn='$page_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}

//�H�y�����q�X�Y��ck2_stores_contents��Ƥ��e
function show_one_ck2_stores_contents($page_sn=""){
	global $xoopsDB,$xoopsModule;
	if(empty($page_sn)){
		return;
	}else{
		$page_sn=intval($page_sn);
	}
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_contents")." where page_sn='{$page_sn}'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$all=$xoopsDB->fetchArray($result);

	//�H�U�|���ͳo���ܼơG $page_sn , $page_title , $page_content , $in_menu , $post_date
	foreach($all as $k=>$v){
		$$k=$v;
	}

	$data="
	<table summary='list_table' id='tbl'>
	<tr><th>"._MA_CK2CART_PAGE_SN."</th><td>{$page_sn}</td></tr>
	<tr><th>"._MA_CK2CART_PAGE_TITLE."</th><td>{$page_title}</td></tr>
	<tr><th>"._MA_CK2CART_PAGE_CONTENT."</th><td>{$page_content}</td></tr>
	<tr><th>"._MA_CK2CART_IN_MENU."</th><td>{$in_menu}</td></tr>
	<tr><th>"._MA_CK2CART_POST_DATE."</th><td>{$post_date}</td></tr>
	</table>";

	//raised,corners,inset
	$main=div_3d("",$data,"corners");

	return $main;
}




/*-----------����ʧ@�P�_��----------*/
$op = (!isset($_REQUEST['op']))? "main":$_REQUEST['op'];

switch($op){
	//��s���
	case "update_ck2_stores_contents":
	update_ck2_stores_contents($_POST['page_sn']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//�s�W���
	case "insert_ck2_stores_contents":
	insert_ck2_stores_contents();
	header("location: {$_SERVER['PHP_SELF']}");
	break;
	
	//��J���
	case "ck2_stores_contents_form":
	$main=ck2_stores_contents_form($_GET['page_sn']);
	break;

	//�R�����
	case "delete_ck2_stores_contents":
	delete_ck2_stores_contents($_GET['page_sn']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//�w�]�ʧ@
	default:
	if(empty($_GET['page_sn'])){
		$main=list_ck2_stores_contents();
		//$main.=ck2_stores_contents_form($_GET['page_sn']);
	}else{
		$main=show_one_ck2_stores_contents($_GET['page_sn']);
	}
	break;
}

/*-----------�q�X���G��--------------*/
xoops_cp_header();
echo "<link rel='stylesheet' type='text/css' media='screen' href='../module.css' />";
echo menu_interface();
echo $main;
xoops_cp_footer();

?>
