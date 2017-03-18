<?php echo $header;?>
<style type="text/css">

  /*CHECKBOX CUSTOM*/
    label.checkbox_container{
      position: relative;
      float: left;
      width: 45px;
    }
    label.checkbox_container:hover
    {
      cursor: pointer;
    }
    input[type="checkbox"] { 
      position: absolute;
      opacity: 0;
      left: -28px;
    }

    input[type="checkbox"].ios-switch + div {
      vertical-align: middle;
      width: 40px;  
      height: 20px;
      border: 1px solid rgba(0,0,0,.4);
      border-radius: 999px;
      background-color: rgba(0, 0, 0, 0.1);
      -webkit-transition-duration: .4s;
      -webkit-transition-property: background-color, box-shadow;
      box-shadow: inset 0 0 0 0px rgba(0,0,0,0.4);
    }

    input[type="checkbox"].ios-switch:checked + div {
      width: 40px;
      background-position: 0 0;
      background-color: #153964;
      border: 1px solid #154277;
      box-shadow: inset 0 0 0 10px #194e8d;
    }

    input[type="checkbox"].ios-switch + div > div {
      float: left;
      width: 18px; 
      height: 18px;
      border-radius: inherit;
      background: #ffffff;
      -webkit-transition-timing-function: cubic-bezier(.54,1.85,.5,1);
      -webkit-transition-duration: 0.4s;
      -webkit-transition-property: transform, background-color, box-shadow;
      -moz-transition-timing-function: cubic-bezier(.54,1.85,.5,1);
      -moz-transition-duration: 0.4s;
      -moz-transition-property: transform, background-color;
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3), 0px 0px 0 1px rgba(0, 0, 0, 0.4);
      pointer-events: none;
      margin-top: 1px;
      margin-left: 1px;
    }

    input[type="checkbox"].ios-switch:checked + div > div {
      -webkit-transform: translate3d(20px, 0, 0);
      -moz-transform: translate3d(20px, 0, 0);
      background-color: #ffffff;
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3), 0px 0px 0 1px rgba(8, 80, 172,1);
    }
  /*END CHECKBOX CUSTOM*/

  /*IMPUTS CUSTOM*/
    input[type="text"], input[type="password"], textarea, select{
      
      padding: 5px;   
      border: 1px solid #DDDDDD;
      
      /*Applying CSS3 gradient*/
      background: -moz-linear-gradient(center top , #FFFFFF,  #EEEEEE 1px, #FFFFFF 20px);    
      background: -webkit-gradient(linear, left top, left 20, from(#FFFFFF), color-stop(5%, #EEEEEE) to(#FFFFFF));
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FBFBFB', endColorstr='#FFFFFF');
      
      /*Applying CSS 3radius*/   
      -moz-border-radius: 3px;
      -webkit-border-radius: 3px;
      border-radius: 3px;
      
      /*Applying CSS3 box shadow*/
      -moz-box-shadow: 0 0 2px #DDDDDD;
      -webkit-box-shadow: 0 0 2px #DDDDDD;
      box-shadow: 0 0 2px #DDDDDD;

      width: 200px;
      color: #797979;
    }

    input[type="text"]:hover,
    input[type="password"]:hover,
    textarea:hover
    {
        border:1px solid #cccccc;
    }
    input[type="text"]:focus,
    input[type="password"]:focus,
    textarea:focus
    {
        box-shadow:0 0 2px #3B89EC;
    }
    textarea{
      height: 315px;
      width: 500px;
    }
  /*END IMPUTS CUSTOM*/

  /*BUTTONS CUSTOM*/
    .button2 {
        text-decoration: none;
        font: 14px;
        font-weight: bold;
        display: inline-block;
        text-align: center;
        color: #fff;    
        border: 1px solid #9c9c9c; /* Fallback style */
        border: 1px solid rgba(0, 0, 0, 0.3);
        text-shadow: 0 1px 0 rgba(0,0,0,0.4);    
        box-shadow: 0 0 .05em rgba(0,0,0,0.4);   
    }

    .button2, 
    .button2 span {
        -moz-border-radius: .3em;
        border-radius: .3em;
    }

    .button2 span {
        border-top: 1px solid #fff; /* Fallback style */
        border-top: 1px solid rgba(255, 255, 255, 0.5);
        display: block;
        padding: 5px 19px;    
        /* The background pattern */
        background-image: linear-gradient(45deg, rgba(0, 0, 0, 0.05) 25%, transparent 25%, transparent),
                          linear-gradient(-45deg, rgba(0, 0, 0, 0.05) 25%, transparent 25%, transparent),
                          linear-gradient(45deg, transparent 75%, rgba(0, 0, 0, 0.05) 75%),
                          linear-gradient(-45deg, transparent 75%, rgba(0, 0, 0, 0.05) 75%);

        /* Pattern settings */
        background-size: 3px 3px;  
        color: #fff;          
    }

    .button2:hover {
        box-shadow: 0 0 .1em rgba(0,0,0,0.4);
    }

    .button2:active {
        /* When pressed, move it down 1px */
        position: relative;
        top: 1px;
    }

    .button-blue {
        background: #4477a1;
        background: -webkit-gradient(linear, left top, left bottom, from(#003A88), to(#4477a1) );
        background: -moz-linear-gradient(-90deg, #003A88, #4477a1);
        filter:  progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#003A88', endColorstr='#4477a1');
    }

    .button-blue:hover {
        background: #003A88;
        background: -webkit-gradient(linear, left top, left bottom, from(#4477a1), to(#003A88) );
        background: -moz-linear-gradient(-90deg, #4477a1, #003A88);
        filter:  progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#4477a1', endColorstr='#003A88');            
    }

    .button-blue:active {
        background: #4477a1;
    }

  /*END BUTTONS CUSTOM*/

  /*Footer Opencart quality extensions*/
    a.logo{
      position: relative;
      width: 200px;
      height: 151px;
      float: left;
      margin-right: 30px;
    }
    table.form > tbody > tr.footer{
      border-top: 3px solid #eee;
      border-bottom: 3px solid #eee;
    }
    table.form > tbody > tr.footer td{
      font-size: 15px;
      padding: 40px 20px;
      line-height: 27px;
    }
  /*END Footer Opencart quality extensions*/
</style>

<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if (isset($success)) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if (isset($error)) { ?>
  <div class="warning"><?php echo $error; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $status; ?></td>
            <td>
               <label class="checkbox_container"><input name="google_remarketing_status" type="checkbox" class="ios-switch green" value="1" <?php echo ($google_remarketing_status==1 ? 'checked="selected"':''); ?> /><div><div></div></div></label>
            </td>
          </tr>

          <tr>
            <td><?php echo $type; ?></td>
            <td>
              <select name="google_remarketing_type">
                <option value="0" <?php if ($google_remarketing_type==0) { ?> selected="selected" <?php } ?>><?php echo $type_dynamic;?></option>
                <option value="1" <?php if ($google_remarketing_type==1) { ?> selected="selected" <?php } ?>><?php echo $type_standard;?></option>
              </select>
            </td>
          </tr>

          <tr class="id_preffix" <?php if ($google_remarketing_type==1) echo 'style="display:none;"'; ?>>
            <td><?php echo $insert_id_preffix; ?></td>
            <td>
              <input type="text" value="<?php echo $google_remarketing_id_preffix; ?>" name="google_remarketing_id_preffix">
            </td>
          </tr>

          <tr class="id_suffix" <?php if ($google_remarketing_type==1) echo 'style="display:none;"'; ?>>
            <td><?php echo $insert_id_suffix; ?></td>
            <td>
              <input type="text" value="<?php echo $google_remarketing_id_suffix; ?>" name="google_remarketing_id_suffix">
            </td>
          </tr>


          <tr>
            <td class="label_code_dynamic" <?php if ($google_remarketing_type==1) echo 'style="display:none;"'; ?>><?php echo $insert_code_dynamic; ?></td>
            <td class="label_code_standard" <?php if ($google_remarketing_type==0) echo 'style="display:none;"'; ?>><?php echo $insert_code_standard; ?></td>
            <td>
              <textarea rows="13" cols="150" name="google_remarketing_code"><?php echo $google_remarketing_code; ?></textarea>
            </td>
          </tr>

          <tr class="footer">
            <td colspan="2">
              <a target="_new" href="http://www.opencartqualityextensions.com/" class="logo"><img src="http://www.opencartqualityextensions.com/images/extensions/OQE_positive_footer.png"></a>
              <?php echo $footer1; ?>
              <br>
              <?php echo $footer2; ?>
              <br>
              <?php echo $footer3; ?>
              <br>
              <?php echo $footer4; ?>
              <br>
              <?php echo $footer5; ?>
            </td>
          </tr>

        </table>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
  $( document ).ready(function() {
    jQuery('select[name="google_remarketing_type"]').on('change', function(){
      if (jQuery(this).val()==0)
      {
        jQuery('td.label_code_dynamic').show();
        jQuery('td.label_code_standard').hide();
        jQuery('tr.id_suffix').show();
        jQuery('tr.id_preffix').show();
      }
      else
      {
        jQuery('td.label_code_standard').show();
        jQuery('td.label_code_dynamic').hide();
        jQuery('tr.id_suffix').hide();
        jQuery('tr.id_preffix').hide();
      }
    });
  });
</script>
<?php echo $footer; ?> 