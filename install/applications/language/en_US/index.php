# TomatoCart Open Source Shopping Cart Solution
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License v3 (2007)
# as published by the Free Software Foundation.
# 
# @package		TomatoCart
# @author		TomatoCart Dev Team
# @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
# @license		http://www.gnu.org/licenses/gpl.html
# @link		http://tomatocart.com
# @since		Version 2.0
# @filesource

page_title_welcome = Welcome to TomatoCart v2.0 alpha4!
page_title_pre_installation_check = Pre-installation Check
page_title_database_server_setup = Database Server Setup
page_title_web_server = Web Server
page_title_online_store_settings = Online Store Settings
page_title_finished = Finished!

nav_menu_title = Steps
nav_menu_step_1_text = 1: Licence agreement
nav_menu_step_2_text = 2: Pre-installation Check
nav_menu_step_3_text = 3: Database Setup
nav_menu_step_4_text = 4: Online Store Settings
nav_menu_step_5_text = 5: Finished

title_language = Language:

box_title_license = License
label_agree_to_the_license = I agree to the license
warning_accept_license = Please check the agreement checkbox to go on!

text_welcome = <p align="justify">TomatoCart v2.0 is an innovative ecommerce solution that helps individuals, small & medium-sized businesses create & build flexible and solid ecommerce framework. Its feature packed out-of-the-box installation allows store owners to setup, run, and maintain their online stores with minimum effort and with no costs involved.</p><p>TomatoCart combines open source solutions to provide a free and open development platform, which includes the <i>powerful</i> PHP web scripting language, the <i>stable</i> Apache web server, and the <i>fast</i> MySQL database server.</p><p>With no restrictions or special requirements, TomatoCart can be installed on any PHP5 enabled web server, on any environment that PHP and MySQL supports, which includes Linux, Solaris, BSD, and Microsoft Windows environments.</p><p class="alert alert-error"><b>Alpha version is recommended for community developers and users who are participating in test and feedback.</b></p>
text_pre_installation_check = Before proceeding to installation please make sure your system does meet the minimum requirements for installation. If any of these settings are not supported, please take appropriate actions to correct the errors. Failure to do so could lead to TomatoCart not functioning properly.
text_database_server_setup = The database server stores the content of the online store such as product information, customer information, and the orders that have been made. Please consult your server administrator if your database server parameters are not yet known.
text_web_server = <p>The web server takes care of serving the pages of the online store to the visitors and customers. The web server parameters make sure the links to the pages point to the correct location.</p><p>Temporary files such as session data and cache files are stored in the work directory. It is important that this directory is located outside the web server root directory and is protected from public access.</p>
text_online_store_settings = <p>Here you can define the name of your online store, and the contact information for the store owner.</p><p>The administrator username and password are used to log into the protected administration tool section.</p>
text_remove_install_dir = Please remove this installation directory for security reasons.
text_finished = <p>Congratulations on configuring TomatoCart as your online store solution!</p><p>We hope you all the best with your online store and welcome you to join and participate in our community.</p><p align="right">- The TomatoCart Team</p>


param_database_server = Database Server
param_database_server_description = The address of the database server in the form of a hostname or IP address.
param_database_username = Username
param_database_username_description = The username used to connect to the database server.
param_database_password = Password
param_database_password_description = The password that is used together with the username to connect to the database server.
param_database_name = Database Name
param_database_name_description = The name of the database to hold the data in.
param_database_type = Database Type
param_database_type_description = The database server software that is used.
param_database_prefix = Database Table Prefix
param_database_prefix_description = The prefix to use for the database tables.

param_database_import_sample_data = Import Sample Data
param_database_import_sample_data_description = Inserting sample data into the database is recommended for first time installations.

param_web_address = WWW Address:
param_web_address_description = The web address to the online store.
param_web_root_directory = Webserver Root Directory
param_web_root_directory_description = The directory where the online store is installed on the server.
param_web_work_directory = Work Directory
param_web_work_directory_description = The working directory for temporarily created files. This directory should be located outside the public webserver root directory for security reasons. (Shared hosting servers should not use /tmp/)

param_store_name = Store Name
param_store_name_description = The name of the online store that is presented to the public.
param_store_owner_name = Store Owner Name
param_store_owner_name_description = The name of the store owner that is presented to the public.
param_store_owner_email_address = Store Owner E-Mail Address
param_store_owner_email_address_description = The e-mail address of the store owner that is presented to the public.
param_administrator_username = Administrator Username
param_administrator_username_description = The administrator username to use for the administration tool.
param_administrator_password = Administrator Password
param_confirm_password = Confirm Password
param_administrator_password_description = The password to use for the administrator account.


rpc_database_connection_test = Testing database connection..
rpc_database_connection_error = There was a problem connecting to the database server. The following error had occured:</p><p style="width: 150px;"><b>%s</b></p><p>Please verify the connection parameters and try again.
rpc_database_connected = Successfully connected to the database.
rpc_database_importing = The database structure is now being imported. Please be patient during this procedure.
rpc_database_imported = Database imported successfully.
rpc_database_import_error = There was a problem importing the database. The following error had occured:</p><p><b>%s</b></p><p>Please verify the connection parameters and try again.

rpc_store_setting_store_name_error = There was a problem on the store setting. The following error had occured:</p><p><b>The store name can not be null</b></p><p>Please input the store name.
rpc_store_setting_store_owner_error = There was a problem on the store setting. The following error had occured:</p><p><b>The store owner can not be null</b></p><p>Please input the store owner.

rpc_store_setting_username_error = There was a problem on the store setting. The following error had occured:</p><p><b>The username can not be null</b></p><p>Please input the username.
rpc_store_setting_password_error = There was a problem on the store setting. The following error had occured:</p><p><b>The password can not be null!</b></p><p>Please input the password.
rpc_store_setting_confirm_error = There was a problem on the store setting. The following error had occured:</p><p><b>The passwords do not match!</b></p><p>Please check it again.
rpc_store_setting_email_error = There was a problem on the store setting. The following error had occured:</p><p><b>invalid email address!</b></p><p>Please input the email address.

rpc_work_directory_test = Testing work directory..
rpc_work_directory_error_non_existent = There was a problem accessing the working directory. The following error had occured:<br /><br /><b>The directory does not exist:<br /><br />%s</b><br /><br />Please verify the directory and try again.
rpc_work_directory_error_not_writeable = There was a problem accessing the working directory. The following error had occured:<br /><br /><b>The webserver does not have write permissions to the directory:<br /><br />%s</b><br /><br />Please verify the permissions of the directory and try again.
rpc_work_directory_configured = Working directory successfully configured.

rpc_database_sample_data_importing = The sample data is now being imported into the database. Please be patient during this procedure.
rpc_database_sample_data_imported = Database sample data imported successfully.
rpc_database_sample_data_import_error = There was a problem importing the database sample data. The following error had occured:</p><p><b>%s</b></p><p>Please verify the database server and try again.


box_pre_install_title = Pre-install Check
box_server_title = Server Capabilities
box_server_php_version = PHP Version
box_server_php_settings = PHP Settings
box_server_safe_mode = safe_mode
box_server_register_globals = register_globals
box_server_magic_quotes = magic_quotes
box_server_file_uploads = file_uploads
box_server_session_auto_start = session.auto_start
box_server_session_use_trans_sid = session.use_trans_sid
box_server_php_extensions = PHP Extensions
box_server_mysql = MySQL
box_server_gd = GD
box_server_curl = cURL
box_server_openssl = OpenSSL
box_server_on = On
box_server_off = Off
box_file_permissions = File Permissions
box_directory_permissions = Directory Permissions

error_configuration_file_not_writeable = <p>Before proceeding to installation please make sure you have the appropriate permissions on the following files and directories:</p><p>%s</p>
error_configuration_file_alternate_method = <p>Alternatively the possibility to copy the configuration parameters to the configuration file by hand is also provided at the end of the installation procedure.</p>
error_agree_to_license = Please agree to the license before you install TomatoCart!

text_go_to_shop_after_cfg_file_is_saved = Please visit your store after the configuration file has been saved: