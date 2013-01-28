<?php // $Id$
// d'après ./report/question/
    require_once(dirname(__FILE__).'/../../config.php');
    require_once($CFG->libdir.'/adminlib.php');
    require_once($CFG->dirroot.'/mod/referentiel/lib.php');
    require_once($CFG->dirroot.'/mod/referentiel/locallib.php');
	require_once($CFG->dirroot.'/mod/referentiel/version.php');
    require_once($CFG->dirroot.'/mod/referentiel/lib_archive.php');  // archivage
    
/// Get all required strings

    $strreferentiels = get_string("modulenameplural", "referentiel");
    $strreferentiel  = get_string("modulename", "referentiel");

/// Get all the appropriate data
    $referentiel_referentiels = referentiel_get_referentiel_referentiels( NULL);

    $bgc0="#ffffee";
    $bgc1="#eeeedd";
    // Initialise the table.
    $table = new html_table();
    
    $table->head  = array (get_string('occurrences', 'referentiel'), get_string('instances', 'referentiel'));
    $table->align = array ("center", "left", "center");

    $instance_head  = '<table cellspacing="1" cellpadding="2" bgcolor="#333300" width="100%">'.
'<tr valign="top" bgcolor="#cccccc"><th width="30%">'.get_string('instance', 'referentiel').'</th><th width="40%">'.get_string('description', 'referentiel').'</th><th width="10%">'.get_string('users_actifs','referentiel').'</th><th width="10%">'.get_string('activites_declarees','referentiel').'</th><th width="10%">'.get_string('course').'</th><th width="10%">'.get_string('archives', 'referentiel').'</th></tr>'."\n";

// Print the header & check permissions.
    admin_externalpage_setup('reportreferentiel');
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('adminreport', 'referentiel'));

    $msg = '';
    $contextversionneeded = 2007101500;  // Moodle 1.9 branch
    // print_object($CFG);

    // exit;
    if ($CFG->version < $contextversionneeded){
        ///version issus
        $msg .= get_string('majmoodlesvp', 'referentiel', $contextversionneeded);
        $msg .= "<br />".get_string('moodleversion', 'referentiel',$CFG->version)."<br />\n";
    }
    elseif (empty($referentiel_referentiels)){
        ///version issus
        $msg .= "<br />".get_string('erreur_referentiel', 'referentiel');
    }
    else{
        // Liste des occurrences de referentiels
        foreach ($referentiel_referentiels as $referentiel_referentiel) {
            if ($referentiel_referentiel){
                if ($referentiel_referentiel->name){
        			$name_referentiel = stripslashes($referentiel_referentiel->name);
                }
                else{
                    $name_referentiel = get_string('inconnu','referentiel');
                }
                if ($referentiel_referentiel->code_referentiel){
                    $code_referentiel = stripslashes($referentiel_referentiel->code_referentiel);
                }
                else{
                    $code_referentiel =get_string('inconnu','referentiel');
                }
                $local = $referentiel_referentiel->local;
                // Liste d'instances de cette occurence
                $referentiel_instances = $DB->get_records("referentiel", array("ref_referentiel" => "$referentiel_referentiel->id"));
                if ($referentiel_instances){
                    $instance_data=$instance_head;
                    $ligne=0;
                    foreach ($referentiel_instances as $referentiel_instance) {
                        $users_data = '';
                        $activites_data = '';
                        $archives_data = '';
                        $context_instance=NULL;
                        
                        $course_instance= $DB->get_record('course', array('id' => $referentiel_instance->course));
                        if ($course_instance){
                            if (!$course_instance->visible) {
                                $link_course = "<a class=\"dimmed\" href=\"$CFG->wwwroot/course/view.php?id=$course_instance->id\">$course_instance->shortname</a>";
                            }
                            else{
                                $link_course = "<a href=\"$CFG->wwwroot/course/view.php?id=$course_instance->id\">$course_instance->shortname</a>";
                            }
                    		$course_module = get_coursemodule_from_instance('referentiel', $referentiel_instance->id, $course_instance->id);
                            if ($course_module){
                                if (!$referentiel_instance->visible) {
                                    //Show dimmed if the mod is hidden
                                    $link_instance = "<a class=\"dimmed\" href=\"$CFG->wwwroot/mod/referentiel/view.php?d=$referentiel_instance->id\">$referentiel_instance->name</a>";
                                }
                                else {
                                    //Show normal if the mod is visible
                                    $link_instance = "<a href=\"$CFG->wwwroot/mod/referentiel/view.php?d=$referentiel_instance->id\">$referentiel_instance->name</a>";
                                }
                                $context_instance = get_context_instance(CONTEXT_MODULE, $course_module->id);
                            }
                            else{
                                $link_course = get_string('nondefini','referentiel');
                            }
                        }
                        else{
                            $link_instance = $referentiel_instance->name.'<br /><i>'.get_string('nonexist','referentiel').'</i>'."\n";
                           // Proposer suppression
                            $link_instance.="<br /><a href=\"./delete.php?i=$referentiel_instance->id\">".get_string('supprimer_instance', 'referentiel')."</a>";
                        }



                        // Proposer des infos concernant le nombre de déclarations d'activités et le voluem des données
                        $activites_users_instance=  referentiel_get_users_activites_instance($referentiel_instance->id);
                        if ($activites_users_instance){
                            $users_data = count($activites_users_instance);
                        }

                        $activites_instance= referentiel_get_activites_instance($referentiel_instance->id);
                        if ($activites_instance){
                            $activites_data = count($activites_instance);
                            // proposer archivage
                            $archives_data .="<a href=\"./archive.php?i=$referentiel_instance->id\">".get_string('gerer_archives', 'referentiel')."</a>";
                            if (!empty($context_instance)){
                                if ($CFG->referentiel_purge_archives){
                                    // Archives older than REFERENTIEL_ARCHIVE_OBSOLETE days will be deleted.
                                    $delai_destruction = REFERENTIEL_ARCHIVE_OBSOLETE * 24 * 3600;
                                    referentiel_purge_archives($context_instance->id, $delai_destruction, false );
                                }
                                $archive_info=referentiel_get_how_many_files($context_instance->id);
                                if (!empty($archive_info->nfile)){
                                    $archives_data .= " &nbsp; ".display_size($archive_info->total_size)."\n";
                                }
                            }
                        }
                        
                        if (($ligne % 2)==0){
                            $bgcolor=$bgc0;
                        }
                        else{
                            $bgcolor=$bgc1;
                        }
                        $instance_data.='<tr valign="top" bgcolor="'.$bgcolor.'"><td>'.$link_instance. '<br />(#'.$referentiel_instance->id.') </td><td>'. stripslashes($referentiel_instance->description_instance).'</td><td>'.$users_data.'</td><td>'.$activites_data.'</td><td>'.$link_course.'</td><td>'.$archives_data.'</td></tr>'."\n";
                        $ligne++;
                    }
                    $instance_data.='</table>'."\n";
                }
                else{
                    $instance_data=get_string('instancenondefinie','referentiel');
                   // Proposer suppression
                    $instance_data.="<br /><a href=\"./delete.php?r=$referentiel_referentiel->id\">".get_string('supprimer_referentiel', 'referentiel')."</a>";
                }
                if ($local){
                    $table->data[] = array ('<b>'.$code_referentiel. '</b><br />(#'.$referentiel_referentiel->id.')<br /><i>'.get_string('local','referentiel').'</i><br /><i>'.$name_referentiel.'</i>', $instance_data);
                }
                else{
                    $table->data[] = array ('<b>'.$code_referentiel. '</b><br />(#'.$referentiel_referentiel->id.')<br /><i>'.$name_referentiel.'</i>', $instance_data);
                }
            }
        }
    }
    
    // Version du module
    $s_version='';
	if (!empty($module->release)) {
        $s_version.= $module->release;
   	}

	if (!empty($module->version)){
		// 2009042600;  // The current module version (Date: YYYYMMDDXX)
		$s_version.= ' ('.get_string('release','referentiel').' '.$module->version.')'."\n";
	}

	if ($s_version!=''){
	   $msg.= get_string("version", "referentiel").'<br /><a href="'.$CFG->wwwroot.'/mod/referentiel/info_module_referentiel.html" target="_blank"><i>'.$s_version.'</i></a>'."\n";
	}

    if ($msg) {
        echo $OUTPUT->box_start('generalbox boxwidthwide boxaligncenter centerpara');
        echo $msg;
        echo $OUTPUT->box_end();
    }

    // Print it.
    echo html_writer::table($table);

    // Footer.
    echo $OUTPUT->footer();

?>
