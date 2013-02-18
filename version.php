<?php // $Id: version.php,v 1.3 2006/08/28 16:41:20 mark-nielsen Exp $
/**
 * Code fragment to define the version of referentiel
 * This fragment is called by moodle_needs_upgrading() and /admin/index.php
 *
 * @author 
 * @version $Id: version.php,v 1.3 2011/06/01 16:41:20 jf Exp $
 * @package referentiel Moodle 2
 **/


defined('MOODLE_INTERNAL') || die();
$module=new StdClass();

$module->requires = 2011120500;  // Requires this Moodle version  2.0
$module->version  = 2013021700;  // The current module version (Date: YYYYMMDDXX)
$module->release  = 'Referentiel v 8.04 for Moodle 2.4 - 2013-02-17';    // User-friendly date of release
$module->cron     = 60; //  Period for cron to check this module (secs)

?>
