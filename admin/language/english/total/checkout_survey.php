<?php
//==============================================================================
// Checkout Survey v155.1
//
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
//==============================================================================

$version = 'v155.1';

// Heading
$_['heading_title']				= 'Checkout Survey';

// Buttons
$_['button_save_exit']			= 'Save & Exit';
$_['button_save_keep_editing']	= 'Save & Keep Editing';
$_['button_view_report']		= 'View Report';
$_['button_add_question']		= 'Add Question';
$_['button_add_response']		= 'Add Response';

// Entries
$_['entry_embed_code']			= 'Paste this code in the template file where you want to embed the survey:';
$_['entry_general_settings']	= 'General Settings';
$_['entry_status']				= 'Status:';
$_['entry_sort_order']			= 'Sort Order:<br /><span class="help">Enter the Sort Order for the line items where the customer\'s responses appear, relative to the other Order Total line items.</span>';
$_['entry_only_survey_first']	= 'Only Survey First-Time Customers:<br /><span class="help">If set to "Yes", the survey will not be displayed for registered customers unless it\'s their first order.</span>';
$_['entry_heading']				= 'Heading:<br /><span class="help">Enter a heading for the survey box.</span>';
$_['entry_prequestion_text']	= 'Pre-Question Text:<br /><span class="help">Optionally enter the text displayed before the survey questions. HTML is supported.</span>';
$_['entry_multiselect_size']	= 'Multi-Select Size:<br /><span class="help">Enter the number of entries displayed for multi-select boxes.</span>';
$_['entry_error_message']		= 'Error Message:<br /><span class="help">Fill in the error message to be displayed when the customer does not answer all the required questions.</span>';
$_['entry_button_selector']		= 'Button Selector:<br /><span class="help">Enter the CSS selector for buttons in your theme (in the default theme this is .button). This is used to prevent customers from proceeding when they haven\'t answered the required questions.</span>';

$_['entry_questions']			= 'Questions';
$_['entry_type']				= 'Type';
$_['entry_required']			= 'Required';
$_['entry_question']			= 'Question';
$_['entry_responses']			= 'Responses';
$_['entry_other_response']		= '"Other" Response';
$_['entry_line_item_text']		= 'Line Item Text';

$_['entry_historical_data']		= 'Historical Data';
$_['entry_response']			= 'Response';
$_['entry_customer_responses']	= 'Customer Responses';
$_['entry_customer_sales']		= 'Customer Sales';
$_['entry_guest_responses']		= 'Guest Responses';
$_['entry_guest_sales']			= 'Guest Sales';
$_['entry_notes']				= 'Notes';

// Text
$_['text_saving']				= 'Saving...';
$_['text_saved']				= 'Saved!';
$_['text_checkboxes']			= 'Checkboxes';
$_['text_date_field']			= 'Date Field';
$_['text_datetime_field']		= 'Date / Time Field';
$_['text_multiselect_box']		= 'Multi-Select Box';
$_['text_radio_buttons']		= 'Radio Buttons';
$_['text_select_dropdown']		= 'Select Dropdown';
$_['text_text_field']			= 'Text Field';
$_['text_textarea_field']		= 'Textarea Field';
$_['text_time_field']			= 'Time Field';
$_['text_initial_responses']	= 'Initial Responses';
$_['text_initial_total']		= 'Initial Total';

// Help
$_['help_type']					= 'Choose the question type. "Checkboxes" and "Multi-Select Box" allow multiple responses. "Radio Buttons" and "Select Dropdown" only allow a single response. "Text Field" and "Textarea Field" allow freeform responses.';
$_['help_required']				= 'Select whether a response for the question is required.';
$_['help_question']				= 'Enter the text for the question. HTML is supported.';
$_['help_responses']			= 'Enter responses separated by ; (semi-colons). Enter a non-selectable response (for grouping purposes) inside [ and ] (square brackets). For text, textarea, and date/time fields, enter the default value pre-filled for the customer, or leave blank.';
$_['help_other_response']		= 'If including a customer-editable "Other" response, fill in the text for it in the response list. Leave blank to not include an "Other" response.';
$_['help_line_item_text']		= 'Enter the text displayed for the Order Total line item. Do not enter : (a colon) at the end.';
$_['help_historical_data']		= 'To include historical data (or data recorded from other sources) in reports, create a response below and enter the exact question text for your default language. Then enter the initial number of responses from customers and guests, and the initial sales total associated with the responses. Use numbers only.';

// Copyright
$_['copyright']					= '<div style="text-align: center" class="help">' . $_['heading_title'] . ' ' . $version . ' &copy; <a target="_blank" href="http://www.getclearthinking.com">Clear Thinking, LLC</a></div>';

// Standard Text
$_['standard_module']			= 'Modules';
$_['standard_shipping']			= 'Shipping';
$_['standard_payment']			= 'Payments';
$_['standard_total']			= 'Order Totals';
$_['standard_feed']				= 'Product Feeds';
$_['standard_success']			= 'Success: You have modified ' . $_['heading_title'] . '!';
$_['standard_error']			= 'Error: You do not have permission to modify this extension!';
?>