# $Id: backup.php $
# TomatoCart Open Source Shopping Cart Solutions
# http://www.tomatocart.com
#
# Copyright (c) 2009-2010 Wuxi Elootec Technology Co., Ltd
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License v2 (1991)
# as published by the Free Software Foundation.

heading_title = 数据备份

action_heading_new_backup = 备份数据库
action_heading_restore_local_file = 从本地文件恢复数据库
action_heading_batch_delete_backup_files = 批量删除备份文件

table_heading_backups = 数据备份文件
table_heading_date = 备份日期
table_heading_file_size = 文件大小
table_heading_action = 操作

field_compression_none = 未压缩
field_compression_gzip = GZIP 压缩
field_compression_zip = ZIP 压缩
field_download_only = 下载不保存

backup_location = 备份路径：
last_restoration_date = 最近还原日期：
forget_restoration_date = 忘记还原日期

introduction_new_backup = 为此数据库备份设置信息。

introduction_restore_file = 确认恢复此数据库备份文件？

introduction_restore_local_file = 请选择本地数据库备份文件恢复数据库。

introduction_delete_backup_file = 确认删除此数据库备份文件？

introduction_batch_delete_backup_files = 确认删除这些数据库备份文件？

ms_error_backup_directory_not_writable = 错误：此数据库备份目录不可写： %s
ms_error_backup_directory_non_existant = 错误：此数据库备份目录不存在： %s
ms_error_download_link_not_acceptable = 错误： 此下载链接不可用。

ms_success_database_restore = 成功：数据库恢复成功。请重新登录系统。
