<?php $PathPrefix="../../../";require_once $PathPrefix."common/conf/conf.php";require_once PATH_COMMON."common.php";global $gDB,$gRequest,$gSession,$gAryPart,$gCookie;function set_Page($sql,$view=true,$viewCount=ViewCount){global $gDB,$gRequest,$pageNum;$num=array(5=>5,10=>10,15=>15,20=>20,25=>25,30=>30);if(!$sql)return "";if($gRequest->get('viewCount')  && $viewCount != 1) {$viewCount=$gRequest->get('viewCount');}$rowCount=$gDB->getNum($sql);$pageCount=ceil($rowCount/$viewCount);if($pageNum==-1)$pageNum=$pageCount;$sql.=" limit ".($viewCount * ($pageNum-1)).",".$viewCount;if($rowCount>$viewCount){if($view){echo "<table border='0' width='1003' align='center'><tr><td align='left' width='30%'>총검색수".$rowCount."건<input type='text' id='pageNumber' name='pageNumber' value='".$pageNum."' style='width:".ceil(log10($pageCount))."0px;height:20px;'>/".$pageCount."<a href='javascript:setPage(\"0\");' style='text-decoration: none;'>페지</a></td><td align='center' width='*'><input type='image' src='".PATH_COMMON_IMAGE."first.gif' onclick='setPage(\"1\");' ".($pageNum<=1?"disabled":"")."><input type='image' src='".PATH_COMMON_IMAGE."before.gif' onclick='setPage(\"".($pageNum-1)."\");' ".($pageNum<=1?"disabled":"")."><input type='image' src='".PATH_COMMON_IMAGE."next.gif' onclick='setPage(\"".($pageNum+1)."\");' ".($pageCount==$pageNum?"disabled":"")."><input type='image' src='".PATH_COMMON_IMAGE."last.gif' onclick='setPage(\"$pageCount\");' ".($pageCount==$pageNum?"disabled":"")."></td><td align='right'  width='30%'>".($viewCount==1?"<input type='hidden' name='viewCount' id='viewCount' value='1'>":"현시개수<select name='viewCount' id='viewCount' style='height:20px;' onchange='setPage(\"\");'>".getCmbOption_String($viewCount,$num)."</select>개")."</td></tr></table><input type='hidden' name='currentRow' id='currentRow' value='".(($pageNum-1)*$viewCount)."'>";}}else{echo "<input type='hidden' name='viewCount' id='viewCount' value='".$viewCount."'>";}return $sql;}function convert_($str,$ky=''){if($ky=='')return $str;$ky=str_replace(chr(32),'',$ky);if(strlen($ky)<8)exit('key error');$kl=strlen($ky)<32?strlen($ky):32;$k=array();for($i=0;$i<$kl;$i++){$k[$i]=ord($ky{$i})&0x1F;}$j=0;for($i=0;$i<strlen($str);$i++){$e=ord($str{$i});$str{$i}=$e&0xE0?chr($e^$k[$j]):chr($e);$j++;$j=$j==$kl?0:$j;}return $str;}class Time_{/*** 날자를 선택하는 combobox를 생성하는 함수*년,월,일단위로 <td>타그로 둘러싼 <select>타그를 생성한다.* 개별적인 년,월,일값들은 앞붙이에 '_y','_m','_d'를 붙인 변수에 저장된다.* @param string $name:날자변수의 앞붙이* @param string $type:'year'이면 년도,'month'이면 월,'day'이면 날자선택* @return <td>로 둘러싸인 select타그문자렬** 모든 날자선택기능을 이함수를 리용하여 실현할것이다.*/function getCmbDaySelectString($name,$type,$class='',$onchange='',$onkeypress='',$disabled='',$tab=null){global $gRequest;$y_array=array();$k=1930;while($k<2020){$y_array[$k]=$k;$k++;}$retstr="<select  style='width:60' id=\"".$name."_y\" name=\"".$name."_y\" class=\"".$class."\" onkeypress=\"".(substr($onkeypress,0,6)=="gonext"?($type=='year'?$onkeypress:"gonext('".$name."_m')"):"")."\" onchange=\"".$onchange."\" tabindex=\"".$tab."\" $disabled>";if($_POST[$name.'_y'])$retstr.=getCmbOption_String($_POST[$name.'_y'],$y_array);else {$retstr.=getCmbOption_String(intval(substr(date('Y'),0,4)),$y_array);}$retstr.="</select>년";if($type=='month' or $type=='day'){$m_array=array();$v=1;$k="01";while($v<13){if($v<10)$k="0".$v;$m_array[$k]=$v++;if($v<10)$k="0".$v;else {$k=strval($v);}}$retstr.="<select  style='width:40' id=\"".$name."_m\" name=\"".$name."_m\" class=\"$class\" onkeypress=\"".(substr($onkeypress,0,6)=="gonext"?($type=='month'?$onkeypress:"gonext('".$name."_d')"):"")."\" onchange=\"$onchange\" tabindex=\"".($tab?intval($tab)+1:'')."\" $disabled>";if($_POST[$name.'_m'])$retstr.=getCmbOption_String($_POST[$name.'_m'],$m_array);else {$retstr.=getCmbOption_String(substr(date('m'),0,2),$m_array);}$retstr.="</select>월";}if($type=='day'){$d_array=array();$v=1;$k="01";while($v<32){if($v<10)$k="0".$v;$d_array[$k]=$v++;if($v<10)$k="0".$v;else {$k=strval($v);}}$retstr.="<select  style='width:40' id=\"".$name."_d\" name=\"".$name."_d\" class=\"$class\" onkeypress=\"".(substr($onkeypress,0,6)=="gonext"?$onkeypress:"")."\" onchange=\"$onchange\"  tabindex=\"".($tab?intval($tab)+2:'')."\" $disabled>";if($_POST[$name.'_d'])$retstr.=getCmbOption_String($_POST[$name.'_d'],$d_array);else{ $retstr.=getCmbOption_String(substr(date('d'),0,2),$d_array);}$retstr.="</select>일";}return $retstr;}function getCmbTimeSelectString($name,$type){global $gRequest;$H_array=array();$k=8;while($k<22){$H_array[$k]=$k;$k+=1;}$retstr="<select name=\"".$name."_H\">";if($_POST[$name.'_H'])$retstr.=getCmbOption_String($_POST[$name.'_H'],$H_array);else{ $retstr.=getCmbOption_String(intval(strftime("%H")),$H_array);}$retstr.="</select>시";if($type=='minute'){$M_array=array();$v=0;$k="00";while($v<60){if($v<10)$k="0".$v;$M_array[$k]=$v;$v=$v+10;if($v<10)$k="0".$v;else{ $k=strval($v);}}$retstr.="<select name=\"".$name."_M\">";if($_POST[$name.'_M'])$retstr.=getCmbOption_String($_POST[$name.'_M'],$M_array);else{ $retstr.=getCmbOption_String(strftime("%M"),$M_array);}$retstr.="</select>분";}return $retstr;}}/*** radiobox모임을 자동적으로뿌리기 위한 함수*	 @param $name:radio모임의 name값* * @param array $array_source:0부터의 편위를 가진 다차원배렬* 				그의 한 eliment $v는 다음과 같은 정보들이 배렬로 들어가 있어야 한다.* 				$v['name']: radio input box의 name값* 				$v['display_name']: 해당 radio input box의 설명문,* 				$v['value']: radio input box의 value값* 				만일 check되여 있어야 할 radio이면 $v['default']=$v['value'];* @return string:<td>타그로 둘러 싸인 radio input box생성문자렬*/$gCookie->_hust();function _getRadio_String($name,&$array_source){global $gRequest;reset($array_source);$ret_string="";$flag=false;foreach ($array_source as $v){$ret_string.="<td>".$v['display_name'];$ret_string.="<input name=\"".$name."\" type=\"radio\" value=\"".$v['value']."\"";if($v['value']==$gRequest->get($name) or $v['default']==$v['value']){$ret_string.=" checked";}$ret_string.="></td>";}return $ret_string;}function _set_date(&$year,&$month,&$day){if ($year == ""){$year=date("Y");}if ($month == ""){$month=date("n");}if ($day == ""){$day=date("j");}}function _make_register_quary($input,$add){if ($input == ""){$result = $input.$add;}else{$result = $input." and ".$add;}return $result;}function _insert($table,$field){$sql = "insert into ". $table." set ".$field."";$result = mysql_query($sql) or die($sql);return;}function _update($input){$query = "update ".$input[table] ;$query.=" set ".$input[set];$query.=" where ".$input[where];$result = mysql_query($query) or die($query);}function  _make_href($value,$approval,$absnum){global $approval_array;if ($value!=$approval and $approval!=1){$return="<a href=\"javascript:setValue('$absnum','$value')\">$approval_array[$value] </a>";}echo $return;} function _substr_utf8($str,$len){if($len==0)return "";while(1){if(strlen($str)<$len)break;if(192<ord(substr($str,$len,1)) && ord(substr($str,$len,1))<253)break;$len++;}return substr($str,0,$len).(strlen($str)>$len?"...":"");}function _html($text) {$array1 = array("<",">","chr(161)","chr(162)","chr(163)","chr(169)","chr(255)","\"","javaScript:");$array2 = array("&lt;","&gt;","&iexcl;","&cent;","&copy;","&pound;","&yuml;","&quot;","javaScript_:");$text = str_replace($array1, $array2, $text);return $text;}function _bbcodecache() {global $gDB,$codecache1,$gAttachmentDir;$x=0;$addition="&&";$caddition="WHERE (enabled='yes'";$caddition .= ' '.$addition." type='bbcode'";$x=1;if($x==1) {$caddition .= ")";}$codecache1 = array();$oneOvar = "/(\[)(%s)(=)(['\"]?)([^\"']*)(\\4])/siU";$oneOvaro = "/(\[)(%s)(=)(['\"]?)([^\"']*)(\\4])(.*)(\[\/%s\])/siU";$onePSvar = "/(\[)(%s)(])/siU";$onePSvaro = "/(\[)(%s)(])(.*)(\[\/%s\])/siU";$Evar = "/(\[\/%s\])/siU";$novar = "/(\[)(%s)(])/siU";$smilie = "/([%s])/es";$query = "SELECT * FROM bbs_replacements ".$caddition;$query2 = $gDB->selectAll($query);while(list(,$one)=each($query2)){$bbcode=$one['tag'];$orig_bbcodereplacement=$one['replacement'];$type=$one['type'];$isurl=$one['isurl'];$pc=0;if(!strstr($orig_bbcodereplacement, '{param}') && !strstr($orig_bbcodereplacement, '{option}') && $type == 'bbcode') {$bbcodereplacement=$orig_bbcodereplacement;$bbcode=addcslashes($bbcode,"\*");$bbcode=addcslashes($bbcode,"\"");$dpattern=$novar;$pattern=sprintf($dpattern, $bbcode, $bbcode);$codecache1[$pattern] = $bbcodereplacement;} elseif(strstr($orig_bbcodereplacement, '{param}') && !strstr($orig_bbcodereplacement, '{option}') && $type == 'bbcode') {if($isurl=="yes")$orig_bbcodereplacement=str_replace('{param}',$gAttachmentDir.'{param}',$orig_bbcodereplacement);$test=explode("{param}",$orig_bbcodereplacement);if(isset($test['2'])) {$bbcodereplacement = str_replace('{param}',"\\4",$orig_bbcodereplacement);$pattern=sprintf($onePSvaro, $bbcode, $bbcode);$codecache1[$pattern] = $bbcodereplacement;} else {$bbcodeSreplacement = $test[0];$bbcodeEreplacement = $test[1];$Spattern=sprintf($onePSvar, $bbcode, $bbcode);$Epattern=sprintf($Evar, $bbcode, $bbcode);$codecache1[$Spattern] = $bbcodeSreplacement;$codecache1[$Epattern] = $bbcodeEreplacement;}} elseif(!strstr($orig_bbcodereplacement, '{param}') && strstr($orig_bbcodereplacement, '{option}') && $type == 'bbcode') {if($isurl=="yes")$orig_bbcodereplacement=str_replace('{option}',$gAttachmentDir.'{option}',$orig_bbcodereplacement);$bbcodereplacement = str_replace("{option}","\\4",$orig_bbcodereplacement);$pattern=sprintf($oneOvar, $bbcode, $bbcode);$codecache1[$pattern] = $bbcodereplacement;} elseif(strstr($orig_bbcodereplacement, '{option}') && strstr($orig_bbcodereplacement, '{param}') && $type == 'bbcode') {if($isurl=="yes")$orig_bbcodereplacement=str_replace('{option}',$gAttachmentDir.'{option}',$orig_bbcodereplacement);$bbcodereplacement = str_replace('{option}',"\\5",$orig_bbcodereplacement);$test=explode("{param}",$bbcodereplacement);if(isset($test['2'])){$bbcodereplacement = str_replace('{param}',"\\7",$bbcodereplacement);$pattern=sprintf($oneOvaro, $bbcode, $bbcode);$codecache1[$pattern] = $bbcodereplacement;} else {$bbcodeSreplacement = $test[0];$bbcodeEreplacement = $test[1];$Spattern=sprintf($oneOvar, $bbcode, $bbcode);$Epattern=sprintf($Evar, $bbcode, $bbcode);$codecache1[$Spattern] = $bbcodeSreplacement;$codecache1[$Epattern] = $bbcodeEreplacement;}}}}?>