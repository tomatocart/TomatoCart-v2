# $Id: customers_groups.php $
# TomatoCart Open Source Shopping Cart Solutions
# http://www.tomatocart.com
#
# Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd; Copyright (c) 2007 osCommerce
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License v2 (1991)
# as published by the Free Software Foundation.

heading_customers_groups_title = Customer Groups

action_heading_new_customer_group = New Customer Group
action_heading_batch_delete_customer_groups = Batch Delete Customer Groups

table_heading_group_name = Group Name
table_heading_group_discount = Group Discount
table_heading_action = Action

field_group_name = Group Name:
field_group_discount = Group Discount:
field_set_as_default = Set as Default?

ms_warning_customer_group_name_empty = Warning: The customer group name for %s is empty. Please fill in a name for this language.
ms_warning_customers_groups_discount_error = Warning: The customer group discount should be a 0 ~ 99 integer number.

introduction_new_customer_group = Please fill in the following information for the new customer group.

introduction_edit_customer_group = Please make the necessary changes for this customer group.

introduction_delete_customer_group = Please verify the removal of this customer group.
delete_error_customer_group_prohibited = Error: The default customer group cannot be removed. Please set another customer group as the default customer group and try again.
delete_error_customer_group_in_use = Error: This customer group is currently assigned to %s customers and cannot be removed.

introduction_batch_delete_customer_groups = Please verify the removal of the following customer groups.
batch_delete_error_customer_group_prohibited = Error: The default customer group cannot be removed. Please set another customer group as the default customer group and try again.
batch_delete_error_customer_group_in_use = Error: One or more customer groups are currently assigned to customers and cannot be removed.
