<?php
/**
 * @author Nabeel Shahzad <www.phpvms.net>
 * @desc Handles AJAX calls
 */

include '../core/config.inc.php';


if(!Auth::LoggedIn() && !Auth::UserInGroup('Administrators'))
        die('Unauthorized access!');

define('ADMIN_PANEL', true);

Template::SetTemplatePath(ADMIN_PATH . '/templates');
MainController::loadModules($ADMIN_MODULES);

MainController::RunAllActions();

echo '<script type="text/javascript>
        EvokeListeners();
</script>';
?>