<?php
//  ------------------------------------------------------------------------ //
// ���Ҳե� tad �s�@
// �s�@����G2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //


define("_CK2CART_UPLOAD_DIR",XOOPS_ROOT_PATH."/uploads/ck2_cart");
define("_CK2CART_UPLOAD_URL",XOOPS_URL."/uploads/ck2_cart");

//�ߧY�H�X
function send_now($email="",$title="",$content=""){
	global $xoopsConfig,$xoopsDB,$xoopsModuleConfig,$xoopsModule;

	$xoopsMailer =& getMailer();
	$xoopsMailer->multimailer->ContentType="text/html";
	$xoopsMailer->addHeaders("MIME-Version: 1.0");

	$msg.=($xoopsMailer->sendMail($email,$title, $content,$headers))?"<div>mail to {$email} OK~</div>":"<div>mail to {$email} error!</div>";
	return $msg;
}

//�H�y�������o�Y��ck2_stores_customer���
function get_ck2_stores_customer($uid=""){
	global $xoopsDB;
	if(empty($uid))return;
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_customer")." where uid='$uid'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}



//�C�X�Ҧ�ck2_stores_order_commodity���
function list_ck2_stores_order_commodity($order_sn=""){
	global $xoopsDB,$xoopsModule;

	$money=0;


	$MDIR=$xoopsModule->getVar('dirname');
	$sql = "select a.*,b.specification_title,b.commodity_sn from ".$xoopsDB->prefix("ck2_stores_order_commodity")." as a left join ".$xoopsDB->prefix("ck2_stores_commodity_specification")." as b on a.specification_sn=b.specification_sn where a.order_sn='$order_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$data="
	<table  style='width:100%;border:0px;'>";
	while($all=$xoopsDB->fetchArray($result)){
	  //�H�U�|���ͳo���ܼơG $order_sn , $specification_sn , $amount , $sum
    foreach($all as $k=>$v){
      $$k=$v;
    }

		$data.="<tr style='border:0px;'>
		<td style='width:150px;border:0px;'><a href='".XOOPS_URL."/modules/ck2_cart/commodity.php?commodity_sn=$commodity_sn' style='font-weight:normal;font-size:11px;'>{$specification_title}</a></td>
		<td style='width:20px;border:0px;'>{$amount}</td>
		<td style='width:50px;text-align:right;border:0px;'>{$sum}</td>
		$fun
		</tr>";

    $money+=$sum;
	}

	$data.="
	</table>";


	$main['all']=$data;
	$main['money']=$money;

	return $main;
}

//�q��s��
function mk_order_sn($order_sn="",$order_date=""){
  $order_date=substr($order_date,2,8);
  $order_date=str_replace("-","",$order_date);
	$sn=$order_date.sprintf("%04s",$order_sn);
	return $sn;
}

//���o�̷s���
function get_price($commodity_sn="",$mode=""){
	global $xoopsDB,$xoopsUser;

	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_commodity_specification")." where commodity_sn='$commodity_sn' order by specification_price limit 0,1";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	while($all=$xoopsDB->fetchArray($result)){
	  //�H�U�|���ͳo���ܼơG $specification_sn , $commodity_sn , $specification_title , $specification_amount , $specification_price , $specification_sprice , $specification_sprice_end_date
    foreach($all as $k=>$v){
      $$k=$v;
    }
    
    $now=time();
		$end_date=strtotime($specification_sprice_end_date);
    
    if($mode=="short"){
      $price=(empty($specification_sprice) or $now > $end_date)?_MD_CK2CART_COM_PRICE._MD_CK2CART_FOR."<span class='price'>{$specification_price}</span> "._MD_CK2CART_MONEY."":_MD_CK2CART_COM_SPRICE._MD_CK2CART_FOR."<span class='sprice'>{$specification_sprice}</span> "._MD_CK2CART_MONEY."";
		}else{
			$price=(empty($specification_sprice) or $now > $end_date)?_MD_CK2CART_COM_PRICE._MD_CK2CART_FOR."<span class='price'>{$specification_price}</span> "._MD_CK2CART_MONEY."":_MD_CK2CART_COM_PRICE._MD_CK2CART_FOR."<span style='text-decoration:line-through;' >{$specification_price}</span> "._MD_CK2CART_MONEY." "._MD_CK2CART_COM_SPRICE._MD_CK2CART_FOR."<span class='sprice'>{$specification_sprice}</span> "._MD_CK2CART_MONEY."";
		}
	}

	return $price;

}



//�H�y�������o�Y��ck2_stores_commodity���
function get_ck2_stores_commodity($commodity_sn=""){
	global $xoopsDB;
	if(empty($commodity_sn))return;
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_commodity")." where commodity_sn='$commodity_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}

//�q�W����o�ӫ~���
function get_ck2_com_from_spec($specification_sn=""){
	global $xoopsDB;
	if(empty($specification_sn))return;
	$sql = "select a.*,b.commodity_sn,b.com_title,b.com_unit,b.com_summary,b.payment,b.shipping,c.filename,d.stores_sn
	from ".$xoopsDB->prefix("ck2_stores_commodity_specification")." as a
	left join ".$xoopsDB->prefix("ck2_stores_commodity")." as b
		on a.commodity_sn=b.commodity_sn
	left join ".$xoopsDB->prefix("ck2_stores_image_center")." as c
		on c.col_name='commodity_sn' and c.col_sn=b.commodity_sn
	left join ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." as d
		on d.kinds_sn=b.kinds_sn
	where a.specification_sn='$specification_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}



//���ock2_stores_commodity_kinds�Ҧ���ư}�C
function get_ck2_stores_commodity_kinds_all($stores_sn=""){
	global $xoopsDB;
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." where stores_sn='{$stores_sn}'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while($data=$xoopsDB->fetchArray($result)){
		$kinds_sn=$data['kinds_sn'];
		$data_arr[$kinds_sn]=$data;
	}
	return $data_arr;
}



//��ƶq�ܦ��ﶵ
function amount2option($amount="",$default_amount=""){
  $main="<option></option>";
	for($i=1;$i<=$amount;$i++){
	  $selected=($default_amount==$i)?"selected":"";
		$main.="<option value='$i' $selected>$i</option>";
	}
	return $main;
}


//���ock2_stores_brand�Ҧ���ư}�C
function get_ck2_stores_brand_all(){
	global $xoopsDB;
	$stores_sn=get_stores_sn();
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_brand")." where stores_sn='$stores_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while($data=$xoopsDB->fetchArray($result)){
		$brand_sn=$data['brand_sn'];
		$data_arr[$brand_sn]=$data;
	}
	return $data_arr;
}


//�W�ǹ��ɡA$col_name=store,commodities
function upload_pic($col_name="",$col_sn="",$sort="",$update_sql="insert",$images_sn=""){
	global $xoopsDB,$xoopsUser;
  include_once XOOPS_ROOT_PATH."/modules/ck2_cart/class/upload/class.upload.php";

	
	$stores_sn=get_stores_sn();


  set_time_limit(0);
  ini_set('memory_limit', '50M');

  $img_handle = new upload($_FILES['image'],"zh_TW");

  if($col_name=="stores_sn"){
    $image_dir=_CK2CART_UPLOAD_DIR."/{$stores_sn}/stores";
    $thumb_image_dir=_CK2CART_UPLOAD_DIR."/{$stores_sn}/stores/thumb";
  }elseif($col_name=="commodity_sn"){
    $image_dir=_CK2CART_UPLOAD_DIR."/{$stores_sn}/commodity";
    $thumb_image_dir=_CK2CART_UPLOAD_DIR."/{$stores_sn}/commodity/thumb";
  }else{
    $image_dir=_CK2CART_UPLOAD_DIR."/{$stores_sn}/image";
    $thumb_image_dir=_CK2CART_UPLOAD_DIR."/{$stores_sn}/image/thumb";
  }
  
  if ($img_handle->uploaded) {
	    $name=strtolower(substr($_FILES['image']['name'],0,-4));
      $img_handle->file_safe_name = false;
      $img_handle->file_new_name_body   = "{$col_sn}_{$name}";
      $img_handle->image_resize         = true;
      $img_handle->image_x              = 250;
      $img_handle->image_ratio_y        = true;
      $img_handle->process($image_dir);
      $img_handle->auto_create_dir = true;

      //�s�@�Y��
      $img_handle->file_safe_name = false;
      $img_handle->file_new_name_body   = "{$col_sn}_{$name}";
      $img_handle->image_resize         = true;
      $img_handle->image_x              = 100;
      $img_handle->image_ratio_y        = true;
      $img_handle->process($thumb_image_dir);
      $img_handle->auto_create_dir = true;
      
      if ($img_handle->processed) {
          $img_handle->clean();
          $image_name=strtolower($_FILES['image']['name']);
          
          if($update_sql=="insert"){
  	        $sql = "insert into ".$xoopsDB->prefix("ck2_stores_image_center")." (`col_name`, `col_sn`, `filename`, `sort`) values('$col_name','$col_sn','{$image_name}','$sort')";
  					$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
          }elseif($update_sql=="update"){
  	         $sql = "replace into ".$xoopsDB->prefix("ck2_stores_image_center")." (`col_name`, `col_sn`, `filename`, `sort`) values('$col_name','$col_sn','{$image_name}','$sort')";
  					$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
          }

					return true;
      } else {
					redirect_header($_SERVER['PHP_SELF'],3, "Error:".$img_handle->error);
      }
  }
}

//���o�Ҧ��ӫ~�������(�ө��s��,�������j�M�s��,�ثe���������ݽs��,�ثe�����s��)
function commodity_kinds_select($stores_sn="",$start_search_sn="0",$default_of_kinds_sn="0",$default_kinds_sn="0",$level=0){
	global $xoopsDB,$xoopsModule;
	$sql = "select kinds_sn,kind_title from ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." where stores_sn='{$stores_sn}' and of_kinds_sn='{$start_search_sn}' order by kind_sort";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	
	//die($sql);
	$prefix=str_repeat("__",$level);
	$level++;

	$main="";
	while(list($kinds_sn,$kind_title)=$xoopsDB->fetchRow($result)){
	  $selected=($kinds_sn==$default_of_kinds_sn)?"selected":"";
	  if($kinds_sn==$default_kinds_sn){
	  	continue;
	  }else{
	  	$main.="<option value=$kinds_sn $selected>{$prefix}{$kind_title}</option>";
      $main.=commodity_kinds_select($stores_sn,$kinds_sn,$default_of_kinds_sn,$default_kinds_sn,$level);
		}
	}
	return $main;
}

//�Huid���o�ө��s��
function get_stores_sn(){
	global $xoopsDB,$xoopsUser;
	$uid=$xoopsUser->getVar('uid');
	$sql = "select stores_sn from ".$xoopsDB->prefix("ck2_stores")." where uid='$uid'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	list($stores_sn)=$xoopsDB->fetchRow($result);
	return $stores_sn;
}

//�H�y�������o�Y��ck2_stores_shipping���
function get_ck2_stores_shipping($shipping_sn=""){
	global $xoopsDB;
	if(empty($shipping_sn))return;
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_shipping")." where shipping_sn='$shipping_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}


//�p�ƾ�
function add_counter($tbl="",$counter_col="",$col="",$col_sn=""){
	global $xoopsDB;
	$sql = "update ".$xoopsDB->prefix($tbl)." set  `{$counter_col}` = `{$counter_col}`+1 where `{$col}`='{$col_sn}'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}

//��Ҳճ]�w�����ର�ﶵ
function mc2arr($name="",$def="",$kind="option"){
	global $xoopsModuleConfig;
	$arr=explode(";",$xoopsModuleConfig[$name]);
	if($kind=="checkbox"){
		$opt=arr2checkbox($name,$arr,$def,true);
	}else{
		$opt=arr2opt($arr,$def,true);
	}
	return $opt;
}


//��}�C�ରoption�ﶵ
function arr2opt($arr,$def="",$v_as_k=false){
	foreach($arr as $k=>$v){
	  if($v_as_k)$k=$v;
	  $selected=($k==$def)?"selected":"";
		$main.="<option value='$k' $selected>$v</option>";
	}
	return $main;
}


//�H�y�������o�Y��ck2_stores���
function get_ck2_stores($stores_sn="",$uid=""){
	global $xoopsDB;
	if(!empty($stores_sn)){
	  $w="stores_sn='$stores_sn'";
	}elseif(!empty($uid)){
		$w="uid='$uid'";
	}else{
		return;
	}
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores")." where $w";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}


/********************* �w�]��� *********************/
//�ꨤ��r��
function div_3d($title="",$main="",$kind="raised",$style=""){
	$main="<table style='width:auto;{$style}'><tr><td>
	<div class='{$kind}'>
	<h1>$title</h1>
	<b class='b1'></b><b class='b2'></b><b class='b3'></b><b class='b4'></b>
	<div class='boxcontent'>
 	$main
	</div>
	<b class='b4b'></b><b class='b3b'></b><b class='b2b'></b><b class='b1b'></b>
	</div>
	</td></tr></table>";
	return $main;
}

//�ǩ��~��
function gray_border($title="",$data=""){
	$main="
	<div style='background-color: #F2F2F2;	padding:10px;'>
		  <div style='color:#D2D2D2;font-size:24px;font-weight:bold;margin-bottom:8px;'>$title</div>
		  $data
	</div>";
	return $main;
}


//�޲z���������
function menu_interface($show=1){
global $xoopsModule,$xoopsModuleConfig;
	if(empty($show))return;
	$dirname=$xoopsModule->getVar('dirname');
	include_once("".XOOPS_ROOT_PATH."/modules/{$dirname}/language/tchinese/modinfo.php");
	include("menu.php");
	$page=explode("/",$_SERVER['PHP_SELF']);
	$n=sizeof($page)-1;
	if(is_array($adminmenu)){
		foreach($adminmenu as $m){
			$td.="<a href='".XOOPS_URL."/modules/{$dirname}/{$m['link']}'>{$m['title']}</a>";
		}
	}else{
		$td="<td></td>";
	}
	$main="
	<style type='text/css'>
	#admtool{
		margin-bottom:10px;
	}
	#admtool a:link, #admtool a:visited {
		font-size: 12px;
		background-image: url(".XOOPS_URL."/modules/{$dirname}/images/bbg.jpg);
		margin-right: 0px;
		padding: 3px 10px 2px 10px;
		color: rgb(80,80,80);
		background-color: #FCE6EA;
		text-decoration: none;
		border-top: 1px solid #FFFFFF;
		border-left: 1px solid #FFFFFF;
		border-bottom: 1px solid #717171;
		border-right: 1px solid #717171;
	}
	#admtool a:hover {
		background-image: url(".XOOPS_URL."/modules/{$dirname}/images/bbg2.jpg);
		color: rgb(255,0,128);
		border-top: 1px solid #717171;
		border-left: 1px solid #717171;
		border-bottom: 1px solid #FFFFFF;
		border-right: 1px solid #FFFFFF;
	}
	</style>
	<div id='admtool'>{$td}<a href='".XOOPS_URL."/modules/{$dirname}/'>"._BACK_MODULES_PAGE."</a>
	</div>";
	return $main;
}

//�������s���u��
function toolbar($interface_menu=array()){
	global $xoopsModule,$xoopsModuleConfig,$xoopsUser;
	if(empty($interface_menu))return;
	$dirname=$xoopsModule->getVar('dirname');
	$moduleperm_handler = & xoops_gethandler( 'groupperm' );
	//�P�_�O�_���޲z�v��
	if ( $xoopsUser) {
		if ($moduleperm_handler->checkRight( 'module_admin', $xoopsModule->getVar( 'mid' ), $xoopsUser->getGroups() ) ) {
			$admin_tools="<a href='".XOOPS_URL."/modules/{$dirname}/admin/index.php'>"._TO_ADMIN_PAGE."</a>";
		}
	}
	if(is_array($interface_menu)){
		foreach($interface_menu as $title => $url){
			$td.="<a href='".XOOPS_URL."/modules/{$dirname}/{$url}'>{$title}</a>";
		}
	}else{
		return;
	}
	$main="
	<style type='text/css'>
	#toolbar{
		margin-bottom:10px;
	}
	#toolbar a:link, #toolbar a:visited {
		font-size: 11px;
		background-image: url(".XOOPS_URL."/modules/{$dirname}/images/bbg.jpg);
		margin-right: 0px;
		padding: 3px 10px 2px 10px;
		color: rgb(80,80,80);
		background-color: #FCE6EA;
		text-decoration: none;
		border-top: 1px solid #FFFFFF;
		border-left: 1px solid #FFFFFF;
		border-bottom: 1px solid #717171;
		border-right: 1px solid #717171;
	}
	#toolbar a:hover {
		background-image: url(".XOOPS_URL."/modules/{$dirname}/images/bbg2.jpg);
		color: rgb(255,0,128);
		border-top: 1px solid #717171;
		border-left: 1px solid #717171;
		border-bottom: 1px solid #FFFFFF;
		border-right: 1px solid #FFFFFF;
	}
	</style>
	<div id='toolbar'>{$td}{$admin_tools}</div>";
	return $main;
}

//���^�_��l��ƨ��
function chk($DBV="",$NEED_V="",$defaul="",$return="checked"){
	if($DBV==$NEED_V){
		return $return;
	}elseif(empty($DBV) && $defaul=='1'){
		return $return;
	}
	return "";
}

//�ƿ�^�_��l��ƨ��
function chk2($default_array="",$NEED_V="",$default=1){
	if(in_array($NEED_V,$default_array)){
		return "checked";
	}elseif($default=='1'){
		return "checked";
	}

	return "";
}


//�ӳ��v���P�_
function power_chk($perm_name="",$psn=""){
	global $xoopsUser,$xoopsModule;

	//���o�ثe�ϥΪ̪��s�սs��
	if($xoopsUser) {
		$groups = $xoopsUser->getGroups();
	}else{
		$groups = XOOPS_GROUP_ANONYMOUS;
	}

	//���o�Ҳսs��
	$module_id = $xoopsModule->getVar('mid');
	//���o�s���v���\��
	$gperm_handler =& xoops_gethandler('groupperm');

	//�v�����ؽs��
	$perm_itemid = intval($psn);
	//�̾ڸӸs�լO�_����v�����ئ��ϥ��v���P�_ �A�����P���B�z
	if($gperm_handler->checkRight($perm_name, $perm_itemid, $groups, $module_id)) {
		return true;
	}
	return false;
}

//���P�_
function is_checked($v1="",$v2="",$default=""){
	if(isset($v1) and $v1==$v2){
		return "checked";
	}elseif($default=="default"){
		return "checked";
	}
}



//��������
class PageBar{
	// �ثe�Ҧb���X
	var $current;
	// �Ҧ�����Ƽƶq (rows)
	var $total;
	// �C����ܴX�����
	var $limit;
	// �ثe�b�ĴX�h�����ƿﶵ�H
	var $pCurrent;
	// �`�@�����X���H
	var $pTotal;
	// �C�@�h�̦h���X�ӭ��ƿﶵ�i�ѿ�ܡA�p�G3 = {[1][2][3]}
	var $pLimit;
	var $prev;
	var $next;
	var $prev_layer = ' ';
	var $next_layer = ' ';
	var $first;
	var $last;
	var $bottons = array();
	// �n�ϥΪ� URL ���ưѼƦW�H
	var $url_page = "g2p";
	// �n�ϥΪ� URL Ū���ɶ��ѼƦW�H
	var $url_loadtime = "loadtime";
	// �|�ϥΨ쪺 URL �ܼƦW�A�� process_query() �L�o�Ϊ��C
	var $used_query = array();
	// �ثe�����C��
	var $act_color = "#990000";
	var $query_str; // �s�� URL �ѼƦC

	function PageBar($total, $limit, $page_limit){
		$mydirname = basename( dirname( __FILE__ ) ) ;
		$this->prev = "<img src='".XOOPS_URL."/modules/{$mydirname}/images/1leftarrow.gif' alt='"._BP_BACK_PAGE."' align='absmiddle' hspace=3>"._BP_BACK_PAGE;
		$this->next = "<img src='".XOOPS_URL."/modules/{$mydirname}/images/1rightarrow.gif' alt='"._BP_NEXT_PAGE."' align='absmiddle' hspace=3>"._BP_NEXT_PAGE;
		$this->first = "<img src='".XOOPS_URL."/modules/{$mydirname}/images/2leftarrow.gif' alt='"._BP_FIRST_PAGE."' align='absmiddle' hspace=3>"._BP_FIRST_PAGE;
		$this->last = "<img src='".XOOPS_URL."/modules/{$mydirname}/images/2rightarrow.gif' alt='"._BP_LAST_PAGE."' align='absmiddle' hspace=3>"._BP_LAST_PAGE;

		$this->limit = $limit;
		$this->total = $total;
		$this->pLimit = $page_limit;
	}

	function init(){
		$this->used_query = array($this->url_page, $this->url_loadtime);
		$this->query_str = $this->processQuery($this->used_query);
		$this->glue = ($this->query_str == "")?'?':
		'&';
		$this->current = (isset($_GET["$this->url_page"]))? $_GET["$this->url_page"]:
		1;
		$this->pTotal = ceil($this->total / $this->limit);
		$this->pCurrent = ceil($this->current / $this->pLimit);
	}

	//��l�]�w
	function set($active_color = "none", $buttons = "none"){
		if ($active_color != "none"){
			$this->act_color = $active_color;
		}

		if ($buttons != "none"){
			$this->buttons = $buttons;
			$this->prev = $this->buttons['prev'];
			$this->next = $this->buttons['next'];
			$this->prev_layer = $this->buttons['prev_layer'];
			$this->next_layer = $this->buttons['next_layer'];
			$this->first = $this->buttons['first'];
			$this->last = $this->buttons['last'];
		}
	}

	// �B�z URL ���ѼơA�L�o�|�ϥΨ쪺�ܼƦW��
	function processQuery($used_query){
		// �N URL �r��������G���}�C
		$vars = explode("&", $_SERVER['QUERY_STRING']);
		for($i = 0; $i < count($vars); $i++){
			$var[$i] = explode("=", $vars[$i]);
		}

		// �L�o�n�ϥΪ� URL �ܼƦW��
		for($i = 0; $i < count($var); $i++){
			for($j = 0; $j < count($used_query); $j++){
				if (isset($var[$i][0]) && $var[$i][0] == $used_query[$j]) $var[$i] = array();
			}
		}

		// �X���ܼƦW�P�ܼƭ�
		for($i = 0; $i < count($var); $i++){
			$vars[$i] = implode("=", $var[$i]);
		}

		// �X�֬��@���㪺 URL �r��
		$processed_query = "";
		for($i = 0; $i < count($vars); $i++){
			$glue = ($processed_query == "")?'?':
			'&';
			// �}�Y�Ĥ@�ӬO '?' ��l���~�O '&'
			if ($vars[$i] != "") $processed_query .= $glue.$vars[$i];
		}
		return $processed_query;
	}

	// �s�@ sql �� query �r�� (LIMIT)
	function sqlQuery(){
		$row_start = ($this->current * $this->limit) - $this->limit;
		$sql_query = " LIMIT {$row_start}, {$this->limit}";
		return $sql_query;
	}


	// �s�@ bar
	function makeBar($url_page = "none"){
		if ($url_page != "none"){
			$this->url_page = $url_page;
		}
		$this->init();

		// ���o�ثe�ɶ�
		$loadtime = '&loadtime='.time();

		// ���o�ثe����(�h)���Ĥ@�ӭ��Ʊҩl�ȡA�p 6 7 8 9 10 = 6
		$i = ($this->pCurrent * $this->pLimit) - ($this->pLimit - 1);

		$bar_center = "";
		while ($i <= $this->pTotal && $i <= ($this->pCurrent * $this->pLimit)){
			if ($i == $this->current){
				$bar_center = "{$bar_center}<font color='{$this->act_color}'>[{$i}]</font>";
			}else{
				$bar_center .= " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}={$i}{$loadtime}'' title='{$i}'>{$i}</a> ";
			}
			$i++;
		}
		$bar_center = $bar_center . "";

		// ���e���@��
		if ($this->current <= 1){
			$bar_left = " {$this->prev} ";
			$bar_first = " {$this->first} ";
		}	else{
			$i = $this->current-1;
			$bar_left = " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}={$i}{$loadtime}' title='"._BP_BACK_PAGE."'>{$this->prev}</a> ";
			$bar_first = " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}=1{$loadtime}' title='"._BP_FIRST_PAGE."'>{$this->first}</a> ";
		}

		// ������@��
		if ($this->current >= $this->pTotal){
			$bar_right = " {$this->next} ";
			$bar_last = " {$this->last} ";
		}	else{
			$i = $this->current + 1;
			$bar_right = " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}={$i}{$loadtime}' title='"._BP_NEXT_PAGE."'>{$this->next}</a> ";
			$bar_last = " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}={$this->pTotal}{$loadtime}' title='"._BP_LAST_PAGE."'>{$this->last}</a> ";
		}

		// ���e���@��ӭ���(�h)
		if (($this->current - $this->pLimit) < 1){
			$bar_l = " {$this->prev_layer} ";
		}	else{
			$i = $this->current - $this->pLimit;
			$bar_l = " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}={$i}{$loadtime}' title='".sprintf($this->pLimit,_BP_GO_BACK_PAGE)."'>{$this->prev_layer}</a> ";
		}

		//������@��ӭ���(�h)
		if (($this->current + $this->pLimit) > $this->pTotal){
			$bar_r = " {$this->next_layer} ";
		}	else{
			$i = $this->current + $this->pLimit;
			$bar_r = " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}={$i}{$loadtime}' title='".sprintf($this->pLimit,_BP_GO_NEXT_PAGE)."'>{$this->next_layer}</a> ";
		}

		$page_bar['center'] = $bar_center;
		$page_bar['left'] = $bar_first . $bar_l . $bar_left;
		$page_bar['right'] = $bar_right . $bar_r . $bar_last;
		$page_bar['current'] = $this->current;
		$page_bar['total'] = $this->pTotal;
		$page_bar['sql'] = $this->sqlQuery();
		return $page_bar;
	}

}

?>
