<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
        </div>
        <div class="content">
            <form method="POST" action="#">
                <input type="hidden" name="mc_api" id="mc_api" value="<?=$mailchimp_api?>" />
            </form>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="list">
                    <thead>
                        <tr>
                            <td class="left"><?php echo $entry_settings; ?></td>
                            <td class="left"><?php echo $entry_layout; ?></td>
                            <td class="left"><?php echo $entry_position; ?></td>
                            <td class="left"><?php echo $entry_status; ?></td>
                            <td class="right"><?php echo $entry_sort_order; ?></td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody id="module">
                        <?php $module_row = 0; ?>
                        <?php foreach ($modules as $module) { ?>
                        <tr id="module-row<?php echo $module_row; ?>" data="<?php echo $module_row; ?>" class="row">
                            <td class="left" valign="top">
                                <a class="button" id="setting-link-<?php echo $module_row; ?>" href="#" onclick="$(this).next().show(); $(this).hide(); return false;"><?php echo $entry_settings; ?></a>

                                <div class="setting-table" data="#setting-link-<?php echo $module_row; ?>">
                                    <table class="form">
                                        <tr>
                                            <td><?php echo $entry_list; ?></td>
                                            <td>
                                                <select class="mailchimp_list" name="mail_chimp_module[<?php echo $module_row; ?>][list]">
                                                    <option value="0"><?php echo $text_select_list; ?></option>
                                                    <?php 
                                                    $selected_list = array();
                                                    foreach($mailchimp_list as $m_list_id => $m_list){ 
                                                    if($module['list'] == $m_list_id){
                                                        $selected_list = $m_list['merge-vars'];
                                                    }
                                                    ?>
                                                    <option value="<?=$m_list_id?>" <?php if ($module['list'] == $m_list_id) { ?>selected="selected"<?php } ?>><?php echo $m_list['name']; ?></option>
                                                    <?php } ?>
                                                </select>    

                                                <?php if (isset($error_list[$module_row])) { ?>
                                                <span class="error"><?php echo $error_list[$module_row]; ?></span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo $entry_popup_frm; ?></td>
                                            <td>
                                                <select name="mail_chimp_module[<?php echo $module_row; ?>][popup_frm]">
                                                    <?php if ($module['popup_frm']) { ?>
                                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                                    <option value="0"><?php echo $text_disabled; ?></option>
                                                    <?php } else { ?>
                                                    <option value="1"><?php echo $text_enabled; ?></option>
                                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>

                                    <div id="languages-<?=$module_row?>" class="htabs">
                                        <?php foreach ($languages as $language) { ?>
                                        <a href="#language-<?=$module_row?>-<?php echo $language['language_id']; ?>" style="display: inline;"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                                        <?php } ?>
                                    </div>
                                    <?php 
                                    foreach ($languages as $language) { 
                                        $txt_heading_title = '';
                                        $txt_description = '';
                                        $txt_success_message = '';
                                        $txt_button_text = '';
                                        if(
                                            isset($module['texts']) &&
                                            isset($module['texts'][$language['language_id']])    
                                        ){
                                            $txt_heading_title = $module['texts'][$language['language_id']]['heading_title'];
                                            $txt_description = $module['texts'][$language['language_id']]['description'];
                                            $txt_success_message = $module['texts'][$language['language_id']]['success_message'];
                                            $txt_button_text = $module['texts'][$language['language_id']]['button_text'];
                                        }
                                    ?>
                                    <div id="language-<?=$module_row?>-<?php echo $language['language_id']; ?>" class="htabs-content">
                                        <table class="form spfrom"><tbody>
                                            <tr><th><?=$entry_heading_title?></th><td><input type="text" name="mail_chimp_module[<?php echo $module_row; ?>][texts][<?php echo $language['language_id']; ?>][heading_title]" value="<?php echo $txt_heading_title; ?>" /></td></tr>
                                            <tr><th><?=$entry_description?></th><td><textarea name="mail_chimp_module[<?php echo $module_row; ?>][texts][<?php echo $language['language_id']; ?>][description]"><?php echo $txt_description; ?></textarea></td></tr>
                                            <tr><th><?=$entry_success_msg?></th><td><textarea name="mail_chimp_module[<?php echo $module_row; ?>][texts][<?php echo $language['language_id']; ?>][success_message]"><?php echo $txt_success_message; ?></textarea></td></tr>
                                            <tr><th><?=$entry_submit_button_text?></th><td><input type="text" name="mail_chimp_module[<?php echo $module_row; ?>][texts][<?php echo $language['language_id']; ?>][button_text]" value="<?php echo $txt_button_text; ?>" /></td></tr>
                                        </tbody></table>

                                        <div class="list-fields" id="list-fields-<?=$module_row?>-<?php echo $language['language_id']; ?>">
                                            <table class="form">
                                                <tr><td colspan="3"><?php echo $entry_list_fields; ?></td></tr>
                                                <tr>
                                                    <td class="heading"><?php echo $text_show; ?></td>
                                                    <td class="heading"><?php echo $entry_sort_order; ?></td>
                                                    <td class="heading"><?php echo $entry_name; ?></td>
                                                </tr>
                                                
                                                        <?php 
                                                        foreach ($selected_list as $key => $merge_vars) { 
                                                            $checked = '';
                                                            $sorted = '0';
                                                            $label = $merge_vars['name'];

                                                            if(
                                                                isset($module['list_fields']) && 
                                                                isset($module['list_fields'][$merge_vars['tag']]) && 
                                                                isset($module['list_fields'][$merge_vars['tag']][$language['language_id']]) && 
                                                                isset($module['list_fields'][$merge_vars['tag']][$language['language_id']]['tag'])
                                                            ){
                                                                $checked = ' checked="checked" ';

                                                                if( isset($module['list_fields'][$merge_vars['tag']][$language['language_id']]['tag_order']) ){
                                                                    $sorted = $module['list_fields'][$merge_vars['tag']][$language['language_id']]['tag_order'];
                                                                }

                                                                if( isset($module['list_fields'][$merge_vars['tag']][$language['language_id']]['name']) ){
                                                                    $label = $module['list_fields'][$merge_vars['tag']][$language['language_id']]['name'];
                                                                }

                                                                
                                                            }

                                                            
                                                        ?>
                                                            <tr>
                                                                <td width="30px;">
                                                                   <input class="show_<?php echo $module_row; ?>_<?=$merge_vars['tag']?>" onclick="$('.show_<?php echo $module_row; ?>_<?=$merge_vars['tag']?>').prop('checked',$(this).is(':checked'));" <?=$checked?> type="checkbox" name="mail_chimp_module[<?php echo $module_row; ?>][list_fields][<?=$merge_vars['tag']?>][<?php echo $language['language_id'];?>][tag]" value="<?=$merge_vars['tag']?>" />
                                                                </td>
                                                                <td>
                                                                    <input onblur="$('.so_<?php echo $module_row; ?>_<?=$merge_vars['tag']?>').val($(this).val())" class="txt_center so_<?php echo $module_row; ?>_<?=$merge_vars['tag']?>" size="4" type="text" name="mail_chimp_module[<?php echo $module_row; ?>][list_fields][<?=$merge_vars['tag']?>][<?php echo $language['language_id'];?>][tag_order]" value="<?=$sorted?>" />
                                                                </td>
                                                                <td>
                                                                    <input size="50" name="mail_chimp_module[<?php echo $module_row; ?>][list_fields][<?=$merge_vars['tag']?>][<?php echo $language['language_id'];?>][name]" type="text" value="<?=$label?>" />
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    
                                    <a class="hide-setting button" href="#" oclick="return false;"><?=$text_hide?></a>
                                </div>
                            </td>
                            <td class="left"><select name="mail_chimp_module[<?php echo $module_row; ?>][layout_id]">
                                <?php foreach ($layouts as $layout) { ?>
                                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                            <td class="left"><select name="mail_chimp_module[<?php echo $module_row; ?>][position]">
                                <?php if ($module['position'] == 'content_top') { ?>
                                <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                                <?php } else { ?>
                                <option value="content_top"><?php echo $text_content_top; ?></option>
                                <?php } ?>
                                <?php if ($module['position'] == 'content_bottom') { ?>
                                <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                                <?php } else { ?>
                                <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                                <?php } ?>
                                <?php if ($module['position'] == 'column_left') { ?>
                                <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                                <?php } else { ?>
                                <option value="column_left"><?php echo $text_column_left; ?></option>
                                <?php } ?>
                                <?php if ($module['position'] == 'column_right') { ?>
                                <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                                <?php } else { ?>
                                <option value="column_right"><?php echo $text_column_right; ?></option>
                                <?php } ?>
                            </select></td>
                            <td class="left"><select name="mail_chimp_module[<?php echo $module_row; ?>][status]">
                                <?php if ($module['status']) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select></td>
                            <td class="right"><input type="text" name="mail_chimp_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
                            <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
                        </tr>
                        <?php $module_row++; ?>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5"></td>
                        <td class="left"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
                    </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
<?php if(count($mailchimp_list) > 0){ ?>
var mailchimp_list = <?php echo json_encode($mailchimp_list);?>
<?php } else { ?>
var mailchimp_list = [];
<?php }  ?>
//--></script>

<script type="text/javascript"><!--
$(function(){
    $('.setting-table').hide();
    processWebbyTabs();
});

function processWebbyTabs(){
    $('.htabs').find('a').unbind().bind('click', function(ele){
        $(this).closest('div').find('a').each(function(){
            $(this).removeClass('selected');
            $($(this).attr('href')).hide();
        });
        $(this).addClass('selected');
        $($(this).attr('href')).show();

        return false;
    });

    $('.htabs').find('a:first').trigger('click');

    $('.hide-setting').unbind().bind('click', function (ele){
        var link_id = $(this).closest('.setting-table').hide().attr('data');
        $(link_id).show();
        return false;
    });

    $('.mailchimp_list').unbind().bind('change', function (){

        var selectedList = [];
        var selected_list_id =  $(this).val();

        $.each(mailchimp_list, function(listid, list) {
            if(listid == selected_list_id){
                selectedList = list;
            }
        });

        var row_id = $(this).closest('.row').attr('data');

        if(typeof(selectedList['merge-vars'])!='undefined'){
            <?php foreach ($languages as $language) { ?>
                html = '';
                html += '<table class="form"><tbody>';
                    html += '<tr><td colspan="3"><?php echo $entry_list_fields; ?></td></tr>';
                    html += '<tr>';
                        html += '<td class="heading"><?php echo $text_show; ?></td>';
                        html += '<td class="heading"><?php echo $entry_sort_order; ?></td>';
                        html += '<td class="heading"><?php echo $entry_name; ?></td>';
                    html += '</tr>';

                    $.each(selectedList['merge-vars'], function(index, fieldinfo) {

                        html += '<tr>';
                            html += '<td width="30px;">';
                               html += '<input class="show_'+row_id+'_'+fieldinfo.tag+'" onclick="$(\'.show_'+row_id+'_'+fieldinfo.tag+'\').prop(\'checked\',$(this).is(\':checked\'));" type="checkbox" name="mail_chimp_module['+row_id+'][list_fields]['+fieldinfo.tag+'][<?php echo $language['language_id'];?>][tag]" value="'+fieldinfo.tag+'" />';
                            html += '</td>';
                            html += '<td>';
                                html += '<input onblur="$(\'.so_'+row_id+'_'+fieldinfo.tag+'\').val($(this).val())" class="txt_center so_'+row_id+'_'+fieldinfo.tag+'" size="4" type="text" name="mail_chimp_module['+row_id+'][list_fields]['+fieldinfo.tag+'][<?php echo $language['language_id'];?>][tag_order]" value="0" />';
                            html += '</td>';
                            html += '<td>';
                                html += '<input size="50" name="mail_chimp_module['+row_id+'][list_fields]['+fieldinfo.tag+'][<?php echo $language['language_id'];?>][name]" type="text" value="'+fieldinfo.name+'" />';
                            html += '</td>';
                        html += '</tr>';
                    });
                html += '</tbody></table>';

                $('#list-fields-'+row_id+'-<?php echo $language['language_id']; ?>').html(html);
 
            <?php } ?>         
        }

    });
}
//--></script> 

<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;
function addModule() {

html = '  <tr id="module-row' + module_row + '" class="row" data="' + module_row + '">';
/* Settings */
 html += '    <td class="left" valign="top">';
     html += '    <a class="button" id="setting-link-' + module_row + '" href="#" onclick="$(this).next().show(); $(this).hide(); return false;"><?php echo $entry_settings; ?></a>';

     html += '<div class="setting-table" data="#setting-link-' + module_row + '">';
         html += '<table class="form">';
             html += '<tr>';
                 html += '<td><?php echo $entry_list; ?></td>';
                 html += '<td>';
                     html += '<select class="mailchimp_list" name="mail_chimp_module[' + module_row + '][list]">';
                         html += '<option value="0"><?php echo $text_select_list; ?></option>';
                        <?php 
                        foreach($mailchimp_list as $m_list_id => $m_list){ 
                        ?>
                         html += '<option value="<?=$m_list_id?>"><?php echo $m_list['name']; ?></option>';
                        <?php } ?>
                    html += '</select>';    
                html += '</td>';
            html += '</tr>';
            html += '<tr>';
                html += '<td><?php echo $entry_popup_frm; ?></td>';
                html += '<td>';
                    html += '<select name="mail_chimp_module[' + module_row + '][popup_frm]">';
                        html += '<option value="0"><?php echo $text_disabled; ?></option>';
                        html += '<option value="1"><?php echo $text_enabled; ?></option>';
                    html += '</select>';
                html += '</td>';
            html += '</tr>';
        html += '</table>';

        html += '<div id="languages-' + module_row + '" class="htabs">';
            <?php foreach ($languages as $language) { ?>
            html += '<a href="#language-' + module_row + '-<?php echo $language['language_id']; ?>" style="display: inline;"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>';
            <?php } ?>
        html += '</div>';
        <?php foreach ($languages as $language) { ?>
        html += '<div id="language-' + module_row + '-<?php echo $language['language_id']; ?>" class="htabs-content">';
            html += '<table class="form spfrom"><tbody>';
                html += '<tr><th><?=$entry_heading_title?></th><td><input type="text" name="mail_chimp_module[' + module_row + '][texts][<?php echo $language['language_id']; ?>][heading_title]" value="" /></td></tr>';
                html += '<tr><th><?=$entry_description?></th><td><textarea name="mail_chimp_module[' + module_row + '][texts][<?php echo $language['language_id']; ?>][description]"></textarea></td></tr>';
                html += '<tr><th><?=$entry_success_msg?></th><td><textarea name="mail_chimp_module[' + module_row + '][texts][<?php echo $language['language_id']; ?>][success_message]"></textarea></td></tr>';
                html += '<tr><th><?=$entry_submit_button_text?></th><td><input type="text" name="mail_chimp_module[' + module_row + '][texts][<?php echo $language['language_id']; ?>][button_text]" value="" /></td></tr>';
            html += '</tbody></table>';

            html += '<div class="list-fields" id="list-fields-' + module_row + '-<?php echo $language['language_id']; ?>"">';
                html += '<table class="form"><tbody>';
                    html += '<tr><td colspan="3"><?php echo $entry_list_fields; ?></td></tr>';
                    html += '<tr>';
                        html += '<td class="heading"><?php echo $text_show; ?></td>';
                        html += '<td class="heading"><?php echo $entry_sort_order; ?></td>';
                        html += '<td class="heading"><?php echo $entry_name; ?></td>';
                    html += '</tr>';
                html += '</tbody></table>';
            html += '</div>';
        html += '</div>';
        <?php } ?>                          
        html += '<a class="hide-setting button" href="#" oclick="return false;"><?=$text_hide?></a>';
    html += '</div>';
html += '</td>';
/* Settings End */
    html += '    <td class="left"><select name="mail_chimp_module[' + module_row + '][layout_id]">';
        <?php foreach ($layouts as $layout) { ?>
        html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
        <?php } ?>
    html += '    </select></td>';
    html += '    <td class="left"><select name="mail_chimp_module[' + module_row + '][position]">';
        html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
        html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
        html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
        html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
    html += '    </select></td>';
    html += '    <td class="left"><select name="mail_chimp_module[' + module_row + '][status]">';
        html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
        html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
    html += '    <td class="right"><input type="text" name="mail_chimp_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
    html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
html += '  </tr>';


$('#module').append(html);
processWebbyTabs();

$('#setting-link-' + module_row).trigger('click');

module_row++;
}
//--></script>
<style type="text/css">
table.form > tbody > tr > td:first-child {
    width: auto !important;
}
.txt_center{
    text-align: center;
}
.list tbody td {
    background: #ffffff none repeat scroll 0 0;
    padding: 0 5px;
    vertical-align: sub;
}
.heading{
    font-weight: bold;
}
.htabs-content{
    display: none;
  background-color: #fff;
  border-bottom: 1px solid #ddd;
  border-left: 1px solid #ddd;
  border-right: 1px solid #ddd;
  margin-bottom: 15px;
  padding: 15px;
}
.spfrom textarea {
    height: 100px;
    width: 100%;
}
.spfrom input{
    width: 100%;
}
.spfrom th{
    width: 130px;
}
.list tbody td a {
    text-decoration: none;
}
.htabs {
    margin-bottom: 0px;
}
.htabs-content{
    padding: 15px;
}
.list tbody tr:hover td {
  background-color: #FFC;
}

.list tbody tr:hover tr:hover td{
    background-color: #FFF;  
}
</style>
<?php echo $footer; ?>