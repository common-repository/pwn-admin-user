<?php
/*
Plugin Name: pwn admin user
Plugin URI: http://callmeb.com
Description: A plugin to make your install of WordPress more secure by ensuring there is no account with the username 'admin'.  One of many things you should do to harden WordPress.
Version: 0.1
Author: Briston Davidge bristond@gmail.com
Author URI: http://callmeb.com
License: GPL2
*/

/*  Copyright 2011 

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

define( 'pwn_PUGIN_NAME', 'pwn admin user' );
define( 'pwn_PLUGIN_DIRECTORY', 'pwn-admin-user' );
define( 'pwn_CURRENT_VERSION', '0.1' );
define( 'pwn_CURRENT_BUILD', '3' );
//define( 'pwn_LOGPATH', str_replace( '\\', '/', WP_CONTENT_DIR ) . '/pwn-admin-user-logs/' );
define( 'pwn_DEBUG', false );

function pwn_check_for_admin_username() {
	
		require_once( ABSPATH . WPINC . '/registration.php' ); // get required bits
		
		if( username_exists( 'admin' )) : // 'admin' exists!
		
			if( $_POST['reassign'] <> '' || isset( $_POST['reassign'] )) : // check for auto delete request
			
				$admin_user_info = get_userdatabylogin( 'admin' );
				wp_delete_user( $admin_user_info->ID, $_POST['reassign'] );
				
				echo "<h2>Admin Deleted</h2>";
				
			endif;
			
			$current_user = wp_get_current_user();
			
			$auto_form = '<form action="" method="POST">';
				$auto_form .= '<input type="hidden" name="reassign" value="' . $current_user->ID . '" />';
				$auto_form .= '<button>Take care of this automatically</button>';
			$auto_form .= '</form>';
			
			echo '<div id="message" class="error">';
				echo '<h4>Your install of WordPress is using the default username <em>"ADMIN"</em> which is a potential security risk.</h4>';
					
					if( $current_user->user_login == 'admin' ) :
						
						echo "<p>Furthermore, you are logged in as admin.  Please follow the directions below to make this site more secure and less vulnerable to attacks.";
						echo '<ul>';
							echo '<li>1. <a href="' . get_admin_url() . '/user-new.php">Create a new user with administrator privileges</a></li>';
							echo '<li>2. Logout and log in as the new user</li>';
							echo '<li>3. Delete "admin" account from <a href="' . get_admin_url() . '/users.php">users menu</a></li>';
							echo '<li>4. Done and more secure!</li>';
						echo '</ul>';
						
					else : // not logged in as 'admin'
					
						echo '<p>' . $auto_form . '</p>';
					
					endif;
					
			echo '</div>';
			
		endif;
	
}

	add_action( 'admin_notices', 'pwn_check_for_admin_username' );

?>