# $Id: backup.php $
# TomatoCart Open Source Shopping Cart Solutions
# http://www.tomatocart.com
#
# Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd; Copyright (c) 2007 osCommerce
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License v2 (1991)
# as published by the Free Software Foundation.

heading_title = Database Backup Manager

action_heading_new_backup = New Database Backup
action_heading_restore_local_file = Restore From Local Backup
action_heading_batch_delete_backup_files = Batch Delete Backup Files

table_heading_backups = Backups
table_heading_date = Date
table_heading_file_size = File Size
table_heading_action = Action

field_compression_none = No Compression
field_compression_gzip = GZIP Compression
field_compression_zip = ZIP Compression
field_download_only = Download Without Saving

backup_location = Backup Directory:
last_restoration_date = Last Restoration Date:
forget_restoration_date = Forget Restoration Date

introduction_new_backup = Please fill in the following information for the new database backup.

introduction_restore_file = Please verify the restoration of the following database backup file.

introduction_restore_local_file = Please select the database backup file to restore from.

introduction_delete_backup_file = Please verify the removal of this database backup file.

introduction_batch_delete_backup_files = Please verify the removal of the following database backup files.

ms_error_backup_directory_not_writable = Error: The database backup directory is not writable: %s
ms_error_backup_directory_non_existant = Error: The database backup directory does not exist: %s
ms_error_download_link_not_acceptable = Error: The download link is not acceptable.

ms_success_database_restore = Success: The database is successfully restored. Please Login again to access the system.