<?php
require_once("config/inc.config.php");
require_once(BASE_DIR . "classes/class.Template.php");
$oTemplate = Template::getInstance(unserialize(TEMPLATE_CONFIG), LANGUAGE);
$oTemplate->display("noScript.tpl.php");
?>