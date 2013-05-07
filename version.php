<?php // $Id: version.php,v 1.0 2013/04/05 16:41:20 jf Exp $
/**
 * Code fragment to define the version of referentiel
 * This fragment is called by moodle_needs_upgrading() and /admin/index.php
 *
 * @author 
 * @version $Id: version.php,v 1.0 2013/04/05 16:41:20 jf Exp $
 * @package referentiel for Moodle 2.5
 **/


defined('MOODLE_INTERNAL') || die();

$module=new StdClass();
// $module->requires = 2012112900;  // Requires this Moodle version  2.5
$module->requires = 2012120300;  // Requires this Moodle version  2.4
$module->version  = 2013050600;  // The current module version (Date: YYYYMMDDXX)
$module->release  = 'Referentiel v 8.07 for Moodle 2.4 - 2013-05-06';    // User-friendly date of release
$module->cron     = 60; //  Period for cron to check this module (secs)
$module->component  = 'mod_referentiel'; // Full name of the plugin (used for diagnostics)
$module->maturity  = MATURITY_STABLE;
$module->dependencies = NULL;

