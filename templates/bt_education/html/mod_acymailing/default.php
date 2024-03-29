<?php
/**
 * @copyright	Copyright (C) 2009-2012 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
<div class="acymailing_module<?php echo $params->get('moduleclass_sfx')?>" id="acymailing_module_<?php echo $formName; ?>">
<?php
	$style = array();
	if($params->get('effect','normal') == 'mootools-slide'){
		if(!empty($mootoolsIntro)) echo '<p class="acymailing_mootoolsintro">'.$mootoolsIntro.'</p>'; ?>
		<div class="acymailing_mootoolsbutton" id="acymailing_toggle_<?php echo $formName; ?>" >
			<p><a class="acymailing_togglemodule" id="acymailing_togglemodule_<?php echo $formName; ?>" href="#subscribe"><?php echo $mootoolsButton ?></a></p>
	<?php
	}
	if($params->get('textalign','none') != 'none') $style[] .= 'text-align:'.$params->get('textalign');
	$styleString = empty($style) ? '' : 'style="'.implode(';',$style).'"';
	?>
	<div class="acymailing_fulldiv" id="acymailing_fulldiv_<?php echo $formName; ?>" <?php echo $styleString; ?> >
		<form id="<?php echo $formName; ?>" action="<?php echo JRoute::_('index.php'); ?>" onsubmit="return submitacymailingform('optin','<?php echo $formName;?>')" method="post" name="<?php echo $formName ?>" <?php if(!empty($fieldsClass->formoption)) echo $fieldsClass->formoption; ?> >
		<div class="acymailing_module_form" >
			<?php $introText = $params->get('introtext'); if(!empty($introText)) echo '<span class="acymailing_introtext">'.$introText.'</span>';?>
			<?php if(!empty($visibleListsArray)){
				if($params->get('dropdown',0)){?>
					<select name="subscription[1]">
						<?php foreach($visibleListsArray as $myListId){?>
						<option value="<?php echo $myListId ?>"><?php echo $allLists[$myListId]->name; ?></option>
						<?php } ?>
					</select>
				<?php }else{?>
			<div class="acymailing_lists">
				<?php foreach($visibleListsArray as $myListId){
					$check = in_array($myListId,$checkedListsArray) ? 'checked="checked"' : '';
					if($params->get('checkmode',0) == '0' AND !empty($identifiedUser->email)){
						if(empty($allLists[$myListId]->status)){$check = '';}
						else{
							$check = $allLists[$myListId]->status == '-1' ? '' : 'checked="checked"';
						}
					}
					?>
					
						<div>
							<input type="checkbox" class="acymailing_checkbox" name="subscription[]" id="acylist_<?php echo $myListId; ?>" <?php echo $check; ?> value="<?php echo $myListId; ?>"/>
						</div>
						<div>
						<label for="acylist_<?php echo $myListId; ?>">
						<?php
						$joomItem = $params->get('itemid',0);
						if(empty($joomItem)) $joomItem = $config->get('itemid',0);
						$addItem = empty($joomItem) ? '' : '&Itemid='.$joomItem;
						$archivelink = acymailing_completeLink('archive&listid='.$allLists[$myListId]->listid.'-'.$allLists[$myListId]->alias.$addItem);
						if($params->get('overlay',0)){
							if(!$params->get('link',1) OR !$allLists[$myListId]->visible) $archivelink = '';
							echo acymailing_tooltip($allLists[$myListId]->description,$allLists[$myListId]->name,'',$allLists[$myListId]->name,$archivelink);
						}else{
							if($params->get('link',1) AND $allLists[$myListId]->visible){
								echo '<a href="'.$archivelink.'" alt="'.$allLists[$myListId]->alias.'"'.((JRequest::getCmd('tmpl') == 'component') ? 'target="_blank"' : '').' >';
							}
							echo $allLists[$myListId]->name;
							if($params->get('link',1) AND $allLists[$myListId]->visible){
								echo '</a>';
							}
						}
						?>
						</label>
						</div>
					
				<?php }?>
			</div>
			<?php }//endif dropdown
				}//endif visiblelists ?>
			<div class="acymailing_form">
				<div>
					<?php foreach($fieldsToDisplay as $oneField){
						if($oneField == 'name' AND empty($extraFields[$oneField])){
							if($displayOutside) echo '<div><label for="user_name_'.$formName.'">'.$nameCaption.'</label></div>'; ?>
							<div class="acyfield_<?php echo $oneField; ?>">
								<input id="user_name_<?php echo $formName; ?>" <?php if(!empty($identifiedUser->userid)) echo 'disabled="disabled" ';  if(!$displayOutside){ ?> onfocus="if(this.value == '<?php echo $nameCaption;?>') this.value = '';" onblur="if(this.value=='') this.value='<?php echo $nameCaption?>';"<?php } ?> class="inputbox" type="text" name="user[name]" size="<?php echo $params->get('fieldsize',15)?>" value="<?php if(!empty($identifiedUser->userid)) echo $identifiedUser->name; elseif(!$displayOutside) echo $nameCaption; ?>" />
							</div> <?php
						}elseif($oneField == 'email' AND empty($extraFields[$oneField])){
							if($displayOutside) echo '<div><label for="user_email_'.$formName.'">'.$emailCaption.'</label></div>'; ?>
							<div class="acyfield_<?php echo $oneField; ?>">
								<input id="user_email_<?php echo $formName; ?>" <?php if(!empty($identifiedUser->userid)) echo 'disabled="disabled" ';  if(!$displayOutside){ ?> onfocus="if(this.value == '<?php echo $emailCaption;?>') this.value = '';" onblur="if(this.value=='') this.value='<?php echo $emailCaption?>';"<?php } ?> class="inputbox" type="text" name="user[email]" size="<?php echo $params->get('fieldsize',15)?>" value="<?php if(!empty($identifiedUser->userid)) echo $identifiedUser->email; elseif(!$displayOutside) echo $emailCaption; ?>" />
							</div> <?php
						}elseif($oneField == 'html' AND empty($extraFields[$oneField])){
							echo '<div class="acyfield_'.$oneField.'" ';
							if($displayOutside AND !$displayInline) echo 'colspan="2"';
							echo '>'.JText::_('RECEIVE').JHTML::_('select.booleanlist', "user[html]" ,'',isset($identifiedUser->html) ? $identifiedUser->html : 1,JText::_('HTML'),JText::_('JOOMEXT_TEXT'),'user_html_'.$formName).'</div>';
						}elseif(!empty($extraFields[$oneField])){
							if($displayOutside){
								echo '<div><label '.((strpos($extraFields[$oneField]->type,'text') !== false) ? 'for="user_'.$oneField.'_'.$formName.'"' : '' ).'>'.$fieldsClass->trans($extraFields[$oneField]->fieldname).'</label></div>';
							} ?>
							<div class="acyfield_<?php echo $oneField; ?>">
							<?php if(!empty($identifiedUser->userid) AND in_array($oneField,array('name','email'))){ ?>
									<input id="user_<?php echo $oneField; ?>_<?php echo $formName; ?>" disabled="disabled" class="inputbox" type="text" name="user[<?php echo $oneField;?>]" <?php if(!empty($extraFields[$oneField]->options['size'])) echo 'size="'.$extraFields[$oneField]->options['size'].'"'; ?> value="<?php echo @$identifiedUser->$oneField; ?>"/>
							<?php }else{
									echo $fieldsClass->display($extraFields[$oneField],@$identifiedUser->$oneField,'user['.$oneField.']',!$displayOutside);
							}?>
							</div><?php
						}else{
							continue;
						}
						if(!$displayInline) echo '</div><div>';
					}
				if(!empty($fieldsClass->excludeValue)){
					$js = "\n"."<!--"."\n"."acymailing['excludeValues".$formName."'] = Array();";
					foreach($fieldsClass->excludeValue as $namekey => $value){
						$js .= "\n"."acymailing['excludeValues".$formName."']['".$namekey."'] = '".$value."';";
					}
					$js .= "\n"."//-->";
					$doc =& JFactory::getDocument();
						if($params->get('includejs','header') == 'header'){
							$doc->addScriptDeclaration( $js );
						}else{
							echo "<script type=\"text/javascript\">
									$js
									</script>";
						}
				}
				if(empty($identifiedUser->userid) AND $config->get('captcha_enabled') AND acymailing_level(1)){ ?>
					<div class="captchakeymodule">
					<?php
					if(version_compare(JVERSION,'1.6.0','>=')){
						$image = '<img title="'.JText::_('ERROR_CAPTCHA').'" width="'.$config->get('captcha_width_module').'" height="'.$config->get('captcha_height_module').'" class="captchaimagemodule" src="'.JRoute::_('index.php?option=com_acymailing&ctrl=captcha&acyformname='.$formName.'&val='.rand(0,10000).'&no_html=1').'" alt="captcha" />';
					}else{
						$image = '<img title="'.JText::_('ERROR_CAPTCHA').'" width="'.$config->get('captcha_width_module').'" height="'.$config->get('captcha_height_module').'" class="captchaimagemodule" src="'.rtrim(JURI::root(),'/').'/index.php?option=com_acymailing&amp;ctrl=captcha&amp;acyformname='.$formName.'&amp;val='.rand(0,10000).'&amp;no_html=1" alt="captcha" />';
					}
					if($displayOutside){ echo $image.'</div><div class="captchafieldmodule">'; }else{echo $image;} ?>
					<input id="user_captcha_<?php echo $formName; ?>" class="inputbox captchafield" type="text" name="acycaptcha" size="5" /></div>
					<?php if(!$displayInline) echo '</div><div>';
				}
				 if($params->get('showterms',false)){
					?>
					<div class="acyterms" <?php if($displayOutside AND !$displayInline) echo 'colspan="2"'; ?> >
					<input id="mailingdata_terms_<?php echo $formName; ?>" class="checkbox" type="checkbox" name="terms"/> <?php echo $termslink;?>
					</div>
					<?php if(!$displayInline) echo '</div><div>';
					} ?>
					<div <?php if($displayOutside AND !$displayInline) echo 'colspan="2"'; ?> class="acysubbuttons">
						<?php if($params->get('showsubscribe',true)){?>
						<input class="button subbutton" type="submit" value="<?php $subtext = $params->get('subscribetextreg'); if(empty($identifiedUser->userid) OR empty($subtext)){ $subtext = $params->get('subscribetext',JText::_('SUBSCRIBECAPTION')); } echo $subtext;  ?>" name="Submit" onclick="try{ return submitacymailingform('optin','<?php echo $formName;?>'); }catch(err){alert('The form could not be submitted '+err);return false;}"/>
						<?php }if($params->get('showunsubscribe',false) AND (!$params->get('showsubscribe',true) OR empty($identifiedUser->userid) OR !empty($countUnsub)) ){?>
						<input class="button unsubbutton" type="button" value="<?php echo $params->get('unsubscribetext',JText::_('UNSUBSCRIBECAPTION')); ?>" name="Submit" onclick="return submitacymailingform('optout','<?php echo $formName;?>')"/>
						<?php } ?>
					</div>
				</div>
			<div style="clear:both;height:0;border:none;"></div>
			</div>
			<?php $postText = $params->get('finaltext'); if(!empty($postText)) echo '<span class="acymailing_finaltext">'.$postText.'</span>';
			$ajax = ($params->get('redirectmode') == '3') ? 1 : 0;?>
			<input type="hidden" name="ajax" value="<?php echo $ajax; ?>"/>
			<input type="hidden" name="ctrl" value="sub"/>
			<input type="hidden" name="task" value="notask"/>
			<input type="hidden" name="redirect" value="<?php echo urlencode($redirectUrl); ?>"/>
			<input type="hidden" name="redirectunsub" value="<?php echo urlencode($redirectUrlUnsub); ?>"/>
			<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT ?>"/>
			<?php if(!empty($identifiedUser->userid)){ ?><input type="hidden" name="visiblelists" value="<?php echo $visibleLists;?>"/><?php } ?>
			<input type="hidden" name="hiddenlists" value="<?php echo $hiddenLists;?>"/>
			<input type="hidden" name="acyformname" value="<?php echo $formName; ?>" />
			<?php if(JRequest::getCmd('tmpl') == 'component'){ ?>
				<input type="hidden" name="tmpl" value="component" />
				<?php if($params->get('effect','normal') == 'mootools-box' AND !empty($redirectUrl)){ ?>
					<input type="hidden" name="closepop" value="1" />
				<?php } } ?>
			<?php $myItemId = $config->get('itemid',0); if(empty($myItemId)){ global $Itemid; $myItemId = $Itemid;} if(!empty($myItemId)){ ?><input type="hidden" name="Itemid" value="<?php echo $myItemId;?>"/><?php } ?>
			</div>
		</form>
	</div>
	<?php if($params->get('effect','normal') == 'mootools-slide'){ ?> </div> <?php } ?>
</div>