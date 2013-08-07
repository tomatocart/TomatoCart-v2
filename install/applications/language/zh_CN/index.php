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

page_title_welcome = 欢迎进入TomatoCart v2.0 Alpha 4安装界面!
page_title_pre_installation_check = 安装前检查
page_title_database_server_setup = 数据库服务器配置
page_title_web_server = Web 服务器
page_title_online_store_settings = 网店设置
page_title_finished = 完成

nav_menu_title = 步骤
nav_menu_step_1_text = 1: 许可协议
nav_menu_step_2_text = 2: 安装前检测
nav_menu_step_3_text = 3: 数据库设置
nav_menu_step_4_text = 4: 网店设置
nav_menu_step_5_text = 5:完成

title_language = 语言:

box_title_license = 许可协议
label_agree_to_the_license = 我同意许可协议
warning_accept_license = 请单击同意选择框继续

text_welcome = <p style="background-color: #ff6633; padding: 5px; border: 1px #000 solid;">请注意该版本是不提供任何技术支持的alpha版本，只用于测试以及评估目的，因此不适用于实际网店。</p><p>TomatoCart v2.0是新一代开源在线电子商务解决方案。</p><p>TomatoCart基于强大、自由、开放的开源解决方案，其中包括<i>强大的</i>PHP脚本语言，<i>稳定的</i>Apache 网络服务器，以及<i>高效的</i>MySQL数据库服务器。</p><p>没有任何限制条件和特殊需求，TomatoCart可以安装在任何支持PHP5的网络服务器上，可以安装在任何支持PHP以及MySQL的环境中，包括Linux、Solaris、BSD、和Microsoft Windows。</p>
text_pre_installation_check = 在进行安装之前，请确保您的系统满足安装TomatoCart的最低要求。如果某些设置不被支持，请采取适当措施纠正这些错误。操作失误将可能导致TomatoCart运行不正常。
text_database_server_setup = 数据库服务器存储了网店的信息，如产品信息，客户信息，以及订单历史记录。如果您还未获知数据库服务器参数信息，请咨询您的服务器管理员。
text_web_server = <p>网络服务器负责为访客和客户提供网店页面。网络服务器参数确保页面链接指向正确的页面地址。<p>诸如会话数据、缓存文件这样的临时文件将被储存在工作目录中。重要的是该目录应位于网络服务器的根目录以外不能被外部访问。</p>
text_online_store_settings = <p> 在这里，您可以定义您的网店名称，并提供店主联系信息。</ p> <p>管理员用户名和密码的作用是用来登录到受保护的管理界面。</ p>
text_finished_title = 完成安装
text_remove_install_dir = 点击安装完成，系统将移除初始安装目录，完成电商系统安装，如须修改电商系统配置信息，请您进入电商管理平台进行操作。
text_finished = <p>恭喜您，您已经成功配置了TomatoCart的在线网店系统解决方案！</p><p>我们希望您在您的网店一切顺利，并欢迎您加入我们的中文社区。</p><p align="right">-  TomatoCart 团队</p>


param_database_server = 数据库服务器
param_database_server_description = 表单里的主机名称或IP地址必须是数据库服务器的地址。
param_database_username = 用户名
param_database_username_description = 连接数据库服务器的用户名。
param_database_password = 密码
param_database_password_description = 连接用户名和数据库服务器的密码
param_database_name = 数据库名称
param_database_name_description = 存储数据的数据库名称
param_database_type = 数据库类型
param_database_type_description = 使用的数据库服务器软件。
param_database_prefix = 数据库表前缀
param_database_prefix_description = 用于数据库表的前缀。

param_database_import_sample_data = 导入示例数据
param_database_import_sample_data_description = 建议在首次安装时导入示例数据到数据库中。

param_web_address = WWW服务地址：
param_web_address_description = 在线网店系统网址
param_web_root_directory = 网络服务器根目录
param_web_root_directory_description = 服务器上，在线网店安装的根目录
param_web_work_directory = 工作目录
param_web_work_directory_description = 工作目录用于存储临时文件。出于安全考虑，该目录不应位于公共服务器的根目录下。 (共享服务器不应该使用 /tmp/ 目录)

param_store_name = 店名
param_store_name_description = 公开的在线网店的名称。
param_store_owner_name = 店主名称
param_store_owner_name_description = 公开的店主姓名。
param_store_owner_email_address = 店主电子邮件地址
param_store_owner_email_address_description = 公开的店主Email地址。
param_administrator_username = 管理员用户名
param_administrator_username_description = 用于管理后台的管理员用户名
param_administrator_password = 管理员密码
param_confirm_password = 确认密码
param_administrator_password_description = 管理员账号的密码。


rpc_database_connection_test = 测试数据库连接...
rpc_database_connection_error = 连接到数据库服务器出错。发生以下错误：</p><p style="width: 150px;"><b>%s</b></p><p>请验证连接参数并再次尝试连接。
rpc_database_connected = 成功连接到数据库服务器。
rpc_database_importing = 正在导入数据库结构。请耐心等待。
rpc_database_imported = 数据库导入成功。
rpc_database_import_error = 导入数据库出错。发生以下错误：</p><p><b>%s</b></p><p>请验证连接参数并再次尝试。

rpc_store_setting_username_error = 网店设置出错。发生以下错误：</p><p><b>用户名不能为空！</b></p><p>请输入用户名。
rpc_store_setting_password_error = 网店设置出错。发生以下错误：</p><p><b>密码不能为空！</b></p><p>请输入用户名。
rpc_store_setting_confirm_error = 网店设置出错。发生以下错误：</p><p><b>密码不匹配！</b></p><p>请输入用户名。
rpc_store_setting_email_error = 在网店设置出错。发生以下错误：</p><p><b>无效的电子邮件地址！</b></p><p>请输入用户名。

rpc_work_directory_test = 测试工作目录......
rpc_work_directory_error_non_existent = 访问工作目录出现问题。发生以下错误：<br /><br /><b>目录不存在：<br /><br />%s</b><br /><br />请验证目录并且再尝试一次。
rpc_work_directory_error_not_writeable = 访问工作目录出错。发生以下错误：<br /><br /><b>网络服务器对目录没有书写权限：<br /><br />%s</b><br /><br />请验证目录权限后重试。
rpc_work_directory_configured = 成功配置工作目录。

rpc_database_sample_data_importing = 示例数据正导入数据库。 请耐心等待。
rpc_database_sample_data_imported = 示例数据导入成功。
rpc_database_sample_data_import_error = 导入示例数据至数据库出错。发生以下错误:</p><p><b>%s</b></p><p>请确认数据库服务器后，再尝试。


box_pre_install_title = 安装前检查
box_server_title = 服务器功能测试
box_server_php_version = PHP 版本
box_server_php_settings = PHP 设置
box_server_register_globals = register_globals
box_server_magic_quotes = magic _quotes
box_server_file_uploads = files _uploads
box_server_session_auto_start = session.auto _start
box_server_session_use_trans_sid = session.use_trans_sid
box_server_php_extensions = PHP 扩展
box_server_mysql = 我的SQL
box_server_gd = GD
box_server_curl = cURL
box_server_openssl = OpenSSL
box_server_on = 开
box_server_off = 关
box_file_permissions = 文件权限
box_directory_permissions = 文件夹权限

error_configuration_file_not_writeable = <p>在安装之前，请确认您有足够的读写权限在以下的文件和目录中：</p><p>%s</p>
error_configuration_file_alternate_method = <p>您可以在安装步骤的最后手动复制设置参数到设置文件configure.php里。</p>
error_agree_to_license = 在安装TomatoCart之前，请同意许可协议！

text_go_to_shop_after_cfg_file_is_saved = 请在配置文件保存之后访问您的网店：
