<?php
//  ------------------------------------------------------------------------ //
// ���Ҳե� tad �s�@
// �s�@����G2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------�ޤJ�ɮװ�--------------*/
include "header.php";
$xoopsOption['template_main'] = "shop_content_tpl.html";
/*-----------function��--------------*/


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
	<div style='background-color:#FFFFFF;padding:10px;'>
	{$page_content}
	</div>
	<div align='right'>{$post_date}</div>";

	//raised,corners,inset
	//$main=div_3d("",$data,"corners");
	
	$data=gray_border($page_title,$data);

	return $data;
}


/*-----------����ʧ@�P�_��----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$page_sn=(empty($_REQUEST['page_sn']))?"":intval($_REQUEST['page_sn']);
switch($op){
	case "f2":
	f2();
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	default:
	$main=show_one_ck2_stores_contents($page_sn);
	break;
}

/*-----------�q�X���G��--------------*/
include XOOPS_ROOT_PATH."/header.php";
$xoopsTpl->assign( "css" , "<link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/ck2_cart/module.css' />") ;
$xoopsTpl->assign( "toolbar" , toolbar($interface_menu)) ;
$xoopsTpl->assign( "content" , $main) ;
include_once XOOPS_ROOT_PATH.'/footer.php';

?>
