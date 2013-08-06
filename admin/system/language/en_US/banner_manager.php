# $Id: banner_manager.php $
# TomatoCart Open Source Shopping Cart Solutions
# http://www.tomatocart.com
#
# Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd; Copyright (c) 2007 osCommerce
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License v2 (1991)
# as published by the Free Software Foundation.

heading_title = Banner Manager
panel_graphs_title = Graphs
panel_table_title = Table
banner_manager_new_dialog = New
banner_manager_edit_dialog = Edit
banner_manager_preview_dialog = Preview
banner_manager_statistics_dialog = Statistics

action_heading_new_banner = New Banner
action_heading_batch_delete_banners = Batch Delete Banners

button_insert = Insert

operation_heading_type = Type
operation_heading_month = Month
operation_heading_year = Year

operation_heading_type_value = Daily

table_heading_banners = Banners
table_heading_group = Group
table_heading_status = Status
table_heading_statistics = Statistics
table_heading_action = Action
table_heading_source = Source
table_heading_views = Views
table_heading_clicks = Clicks

section_daily = Daily
section_monthly = Monthly
section_yearly = Yearly

subsection_heading_statistics_daily = %s Daily Statistics For %s %s
subsection_heading_statistics_monthly = %s Monthly Statistics For %s
subsection_heading_statistics_yearly = %s Yearly Statistics

field_title = Title:
field_url = URL:
field_group = Group:
field_banner_type = Banner Type:
field_image = Image
field_html = HTML Text
field_group_new = New Group:
field_image_local = , or enter a local file below:
field_image_target = Image Target (Save To):
field_html_text = HTML Text:
field_scheduled_date = Scheduled Date:
field_expiry_date = Expiry Date:
field_maximum_impressions = Maximum Impressions
field_status = Status
field_delete_image = Delete Banner Image?

filter_null = none

introduction_new_banner = Please fill in the following information for the new banner.

introduction_edit_banner = Please make the necessary changes for this banner.

introduction_delete_banner = Please verify the removal of this banner.

introduction_batch_delete_banners = Please verify the removal of the following banners.

info_banner_fields = <b>Banner Notes:</b><ul><li>Use an image or HTML text for the banner - not both.</li><li>HTML Text has priority over an image</li></ul>
<b>Image Notes:</b><ul><li>Uploading directories must have proper user (write) permissions setup!</li><li>Do not fill out the \'Save To\' field if you are not uploading an image to the webserver (ie, you are using a local (serverside) image).</li><li>The 'Save To' field must be an existing directory with an ending slash (eg, banners/).</li></ul>
<b>Expiry Notes:</b><ul><li>Only one of the two fields should be submitted</li><li>If the banner is not to expire automatically, then leave these fields blank</li></ul>
<b>Schedule Notes:</b><ul><li>If a schedule is set, the banner will be activated on that date.</li><li>All scheduled banners are marked as deactive until their date has arrived, to which they will then be marked active.</li></ul>

ms_error_graphs_directory_non_existant = Error: Graphs directory does not exist: %s
ms_error_graphs_directory_not_writable = Error: Graphs directory is not writable: %s
ms_error_date_scheduled_empty = Error: Please specify the scheduled date. <br>
ms_error_date_expires_empty = Error: Please specify the expires date. <br>
ms_error_banners_title_empty = Error: Please fill in a banners title. <br>
ms_error_banners_title_double = Error: The title can not be repeated. <br>
ms_error_banners_image_double = Error: The file already exists. <br>
ms_error_banners_url_empty = Error: Please fill in a banners url. <br>
ms_error_group_empty = Error: Please fill in a group. <br>
ms_error_group_style = Error: The group style should be like x*y. eg: 200*300 <br>