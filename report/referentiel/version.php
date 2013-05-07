<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version details.
 *
 * @package    report
 * @subpackage backups
 * @copyright  2007 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$plugin->version  = 2013040600;  // Plugin version  for 2.0
$plugin->requires = 2013040500;  // Requires this Moodle version  2.5
$plugin->release  = 'Referentiel Plugin for Moodle 2.5 - 2013-04-06';    // User-friendly date of release

$plugin->component = 'report_referentiel';  // Full name of the plugin (used for diagnostics)
/*
$plugin->cron     = 0; //  Period for cron to check this plugin (secs)

$plugin->maturity  = MATURITY_STABLE;
*/
$plugin->dependencies = array(
    'mod_referentiel' => 2013040500
);

