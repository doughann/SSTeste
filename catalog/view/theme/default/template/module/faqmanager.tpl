<div class="section">
<?php if ($module_title){ ?>
<h3 class="section_title"><?php echo $module_title; ?></h3>
<?php } ?>
<?php foreach($sections as $section){ ?>
<div class="faq-single">
<div class="faq-heading <?php echo $module; ?>"><?php echo $section['title']; ?></div> 
<div class="faq-answer"><?php echo $section['description']; ?></div>
</div>
<?php } ?>
</div>

<script type="text/javascript">
$(document).ready(function(){
$('.hidden').next().remove();
$('.hidden').remove();
$(".faq-heading.<?php echo $module; ?>").click(function() {
$(this).toggleClass("active");
$(this).next(".faq-answer").slideToggle( 400, function() {
// Animation complete.
}); }); });
</script>

<style>
.section {margin-bottom:35px;}
.section_title {font-size:24px;margin-bottom:13px;font-weight:normal;}
.faq-single {border:1px solid #dddddd;border-bottom:none;margin-bottom:5px;position:relative;border-radius:3px;}
.faq-heading {font-size:12px;font-weight:bold;padding:11px 25px 11px 12px; line-height:18px; border-bottom:1px solid #dddddd;cursor:pointer;transition:all 500ms ease; background-image: url('catalog/view/theme/default/image/toggle-icon.png');
background-position:100% -90px; background-repeat:no-repeat;}
.faq-heading:hover, .faq-heading.active {background-color:#f9f9f9;}
.faq-heading.active {background-position:100% 0px; background-repeat:no-repeat;}
.faq-answer {display:none;line-height:18px;padding:15px 15px 0 15px;border-bottom:1px solid #dddddd;}
</style>