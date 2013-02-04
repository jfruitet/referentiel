<?php
    // recupere les notes et objectifs en rapport avec les referentiels de comp�tence
    
    
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

      // Exports selected outcomes in CSV format. 

// JF 
// utilise les tables grades_xx et scale_xx

// Selectionner tous les outcomes g�n�r�s depuis le module r�f�rentiel grace a export_grade_outcomes.php

// -------------------------------------------------
function referentiel_traitement_notations(){
// genere des declarations d'activites "fictives" � partir des notation par objectif 
// sur les activites du cours
global $CFG;
global $DB; 
global $scales;
  // all users that are subscribed to any post that needs sending
  $notations = array(); 
  $scales = array();
  $n_activites=0;
  // Enregistrements anterieurs � 2 jours non traites.  This is to avoid the problem where
  // cron has not been running for a long time
  $timenow   = time();
  if (NOTIFICATION_DELAI){
        $endtime   = $timenow - $CFG->maxeditingtime;
  }
  else{
        $endtime   = $timenow;
  }

  $starttime = $endtime - NOTIFICATION_INTERVALLE_JOUR * 24 *  3600;   // Two days earlier

  // DEBUG
  if (REFERENTIEL_DEBUG){
        $endtime   = $timenow;
        $starttime = $endtime - NOTIFICATION_INTERVALLE_JOUR * 7 * 24 * 3600;   // Two weeks earlier
  }

 
  $scales_list = '';     // for items with a scaleid
  
  // users
  $users = array();
  $users_list = '';
  
  // JF
  // DEBUG
  mtrace("\nDEBUT CRON OBJECTIFS.");


  $notations=referentiel_get_outcomes($starttime, $endtime);
  if ($notations){
    foreach($notations as $notation){
      if ($notation){ 
        
        if (REFERENTIEL_DEBUG){
          mtrace("\nDEBUG :: grade/cron_outcomes.php Line 74 :: USERID ".$notation->userid." ; COURSEID ".$notation->courseid."\nNOTATION :\n");
          print_r($notation);
        }
        
        if (!empty($notation->scaleid) && !preg_match("/ ".$notation->scaleid."\,/", $scales_list)){
          $scales_list .= " $notation->scaleid,";
        }

        if (!empty($notation->userid) && !empty($notation->courseid)){
        
                    if (!preg_match("/ ".$notation->userid."\,/", $users_list)){
                        $users_list .= " $notation->userid,";
                        $user=new Object();
                        $user->userid = $notation->userid;
                        $user->courses = array();
                        $user->course_list = '';
                        $users[$notation->userid]=$user;
                    }

                    if (!preg_match("/ ".$notation->courseid."\,/", $users[$notation->userid]->course_list)){
                        $users[$notation->userid]->course_list .= " $notation->courseid,";
                        $course=new Object();
                        $course->courseid = $notation->courseid;
                        $course->referentiel_list = '';
                        $course->referentiels = array();
                        $users[$notation->userid]->courses[$notation->courseid] = $course;
                    }

                    if (!preg_match("/ ".$notation->referentiel_instanceid."\,/", $users[$notation->userid]->courses[$notation->courseid]->referentiel_list)){
                            $users[$notation->userid]->courses[$notation->courseid]->referentiel_list = " $notation->referentiel_instanceid,";
                            $referentiel = new object();
                            $referentiel->referentiel_instanceid = $notation->referentiel_instanceid;
                            $referentiel->ref_referentiel = $notation->ref_referentiel;
                            $referentiel->module_list ='';
                            $referentiel->modules = array();
                            // $referentiel->referentiels[$notation->referentiel_instanceid]->outcome_list = '';
                            $users[$notation->userid]->courses[$notation->courseid]->referentiels[$notation->referentiel_instanceid]=$referentiel;
                    }

                    if ((empty($users[$notation->userid]->courses[$notation->courseid]->referentiels[$notation->referentiel_instanceid]->module_list))
                            || (!preg_match("/ ".$notation->module.":".$notation->moduleinstance."\,/", $users[$notation->userid]->courses[$notation->courseid]->referentiels[$notation->referentiel_instanceid]->module_list))){
                            $users[$notation->userid]->courses[$notation->courseid]->referentiels[$notation->referentiel_instanceid]->module_list .= " $notation->module:$notation->moduleinstance,";
                            $module = new object();
                            $module->modulename = $notation->module;
                            $module->moduleinstance = $notation->moduleinstance;
                            $module->teacherid = $notation->teacherid;    // MODIF JF 2012/01/31
                            $module->outcome_list='';
                            $module->scaleid_list='';
                            $module->timemodified_list='';
                            $users[$notation->userid]->courses[$notation->courseid]->referentiels[$notation->referentiel_instanceid]->modules[$notation->moduleinstance]=$module;
                    }

                    if (!empty($users[$notation->userid]->courses[$notation->courseid]->referentiels[$notation->referentiel_instanceid]->modules[$notation->moduleinstance])){
                            // les notes
                            if ($notation->outcomeshortname!=''){
                                $users[$notation->userid]->courses[$notation->courseid]->referentiels[$notation->referentiel_instanceid]->modules[$notation->moduleinstance]->outcome_list.=" $notation->outcomeshortname:$notation->finalgrade,";
                                $users[$notation->userid]->courses[$notation->courseid]->referentiels[$notation->referentiel_instanceid]->modules[$notation->moduleinstance]->scaleid_list.=" $notation->scaleid,";
                                $users[$notation->userid]->courses[$notation->courseid]->referentiels[$notation->referentiel_instanceid]->modules[$notation->moduleinstance]->timemodified_list.=" $notation->timemodified,";
                            }

                    }


        }
      }      
    }
  }
  
  
  if (!empty($users)){
    // DEBUG
    if (REFERENTIEL_DEBUG){
      mtrace("\nDEBUG :: grade/cron_outcomes.php Line 146 :: USERS \n");
      print_r($users);
    }
    
  
    foreach($users as $user) {
        if (REFERENTIEL_DEBUG){
            mtrace("\nDEBUG :: grade/cron_outcomes.php Line 153 :: USER \n");
            print_r($user);
        }
        
        foreach($user->courses as $course){
            // echo "<br />COURSE_ID $course->courseid; \n";
            foreach($course->referentiels  as $referentiel){
                // echo "<br />REFERENTIEL_INSTANCE $referentiel->referentiel_instanceid; REFERENTIEL_ID $referentiel->ref_referentiel\n";
                foreach($referentiel->modules as $module){
                    // echo "<br />MODULE $module->modulename ; Instance $module->moduleinstance ; \n";
                    // preparer l'enregistrement
                    // DEBUG
                    // echo "<br />DEBUG :: 180 ; MODULE : $module->modulename, INSTANCE : $module->moduleinstance, COURS : $course->courseid\n";
                    if ($module && !empty($module->modulename) && !empty($module->moduleinstance) && !empty($course->courseid)){
                        $m = referentiel_get_module_info($module->modulename, $module->moduleinstance, $course->courseid);
/*
              // module
  $m->id;
  $m->type=$modulename;
  $m->instance=$moduleinstance;
  $m->course=$courseid;
  $m->date=$cm->added;
  $m->userdate=userdate($cm->added);
  $m->ref_activite=$mid;
  $m->name=$mname;
  $m->description=$mdescription;
  $m->link=$mlink;
*/
                        // DEBUG
                        if (REFERENTIEL_DEBUG){
                            mtrace("\nDEBUG :: grade/cron_outcomes.php Line 184 :: MODULE \n");
                            print_r($m);
                        }
                        $activite= new Object();
                        $activite->type_activite='['.get_string('outcome_type', 'referentiel').' '.get_string('modulename', $m->type).' '.$m->ref_activite.'] '.get_string('outcome_date','referentiel').' '.$m->userdate;
                        $activite->description_activite=get_string('outcome_description','referentiel', $m);
                        $activite->competences_activite='';
                        $activite->commentaire_activite='';
                        $activite->ref_instance=$referentiel->referentiel_instanceid;
                        $activite->ref_referentiel=$referentiel->ref_referentiel;
                        $activite->ref_course=$course->courseid;
                        $activite->userid=$user->userid;
                        $activite->teacherid=$module->teacherid;  // MODIF JF 2013/02/04
                        $activite->date_creation=$m->date;
                        $activite->date_modif_student=0;
                        $activite->date_modif=$m->date;
                        $activite->approved=1;   // approuve par defaut
                        $activite->ref_task=0;

                    // DEBUG
                    /*
                    if (REFERENTIEL_DEBUG){
                        mtrace("DEBUG :: grade/cron_outcomes.php Line 181 :: TIMEMODIFIED_LIST $module->timemodified_list\n");
                    }
                    */
                        $t_datemodif=explode(',',$module->timemodified_list);
                        sort($t_datemodif);
                        $imax=count($t_datemodif)-1;
                        $timemodified=$t_datemodif[$imax];
                        if ($timemodified>$activite->date_creation){
                            //$activite->date_modif_student=$timemodified;
                            $activite->date_modif=$timemodified;
                        }
              
                        // echo "<br />SCALE_LIST $module->scaleid_list\n";
                        $t_scales=explode(',',$module->scaleid_list);
              
                        // echo "<br />OUTCOME_LIST $module->outcome_list\n";
                        $t_outcomes=explode(',',$module->outcome_list);
                        $n=count($t_outcomes);
                        if ($n>0){
                            $i=0;
			                while ($i<$n){
                                if ($t_outcomes[$i]!=''){
                                    list($cle, $val)=explode(':',$t_outcomes[$i]);
                                    $cle=trim($cle);
                                    $val=trim($val);
                                    $scaleid=$t_scales[$i];
                                    // echo "<br />CODE : $cle ; VALEUR : $val ;\n";
                    
                                    $scale  = referentiel_get_scale($scaleid);
                                    // DEBUG
                                    // print_object($scale);
                    
                                    // ------------------
                                    if ($scale){
                                        // echo "<br /> $scale->scale\n";
                                        // print_r($scale->scaleopt);
                                        // echo $scale->scaleopt[(int)$val]."\n";
                      
                                        if ($val>=$scale->grademax){
                                        $activite->competences_activite.=$cle.'/';
                                        // echo " ---&gt; VALIDE \n";
                                    }
                                    else{
                                        // echo " ---&gt; INVALIDE \n";
                                    }
                                }
                            }
                            $i++;
                        }
                    }
                
                    // enregistrer l'activite
                    // DEBUG
                    if (REFERENTIEL_DEBUG){
                        mtrace("\nDEBUG :: grade/cron_outcomes.php Line 243 ; ACTIVITE\n");
                        print_r($activite);
                    }
                    if (referentiel_activite_outcomes($activite, $m)){
                        if (REFERENTIEL_DEBUG){
                            mtrace("\nDEBUG :: grade/cron_outcomes.php Line 248\n-----------------\nACTIVITE ENREGISTREE\n");
                        }
                        $n_activites++;
                    }

                }
            }
        }
    }
}
}
  // echo "<br />\n";
  mtrace($n_activites.' ACTIVITES CREES OU MODIFIEES.');
  mtrace('FIN CRON REFERENTIEL OBJECTIFS.');
}


// -------------------------------------------------
function referentiel_get_scale($scaleid){
  // Preload scale objects for items with a scaleid
  global $scales;
  global $DB;
  if ($scaleid){
    if (!empty($scales[$scaleid])){
      // echo "<br />DEBUG :: 211 SCALE\n";
      return $scales[$scaleid];
    }    
    else {  
      $scale_r = $DB->get_record("scale", array("id" => "$scaleid"));
      if ($scale_r){
        $scale = new Object();
        $scale->scaleid = $scaleid;
        $scale->scale = $scale_r->scale;
        $tscales=explode(',',$scale_r->scale);
        // reindex because scale is off 1
        // MDL-12104 some previous scales might have taken up part of the array
        // so this needs to be reset  
        $scale->scaleopt = array();
        $i = 0;
        foreach ($tscales as $scaleoption) {
          $i++;
          $scale->scaleopt[$i] = trim($scaleoption);
        }
        $scale->grademin=1;
        $scale->grademax=$i;
        $scales[$scaleid]=$scale;
        return $scale;      
      }
    }
  }
  return NULL;
}

// -------------------------------------------------
function referentiel_get_module_info($modulename, $moduleinstance, $courseid){
// retourne les infos concernant ce module
global $CFG;
global $DB;
  if (! $course = $DB->get_record("course", array("id" => "$courseid"))) {;
    // error("DEBUG :: referentiel_get_module_info :: This course doesn't exist");
    return false;
  }
  if (! $module = $DB->get_record("modules", array("name" => "$modulename"))) {
    // error("DEBUG :: referentiel_get_module_info :: This module type doesn't exist");
    return false;
  }
  if (! $cm = $DB->get_record("course_modules", array("course" => "$course->id", "module" => "$module->id", "instance" => "$moduleinstance"))) {
    // error("DEBUG :: referentiel_get_module_info :: This course module doesn't exist");
    return false;
  }

  $mid=0;
  $mname='';
  $mdescription='';
  $mlink='';

  if ($modulename=='forum'){
    if (! $forum = $DB->get_record("forum", array("id" => "$cm->instance"))) {
      // error("DEBUG :: referentiel_get_module_info :: This forum module doesn't exist");
      return false;
    }
    $mid=$forum->id;
    $mname=$forum->name;
    $mdescription=$forum->intro;
    $mlink = $CFG->wwwroot.'/mod/forum/view.php?f='.$forum->id;
  }
  elseif ($modulename=='assignment'){
    if (! $assignment = $DB->get_record("assignment", array("id" => "$cm->instance"))) {
      // error("DEBUG :: referentiel_get_module_info :: This assignment doesn't exist");
      return false;
    }
    $mid=$assignment->id;
    $mname=$assignment->name;
    $mdescription=$assignment->intro;
    $mlink = $CFG->wwwroot.'/mod/assignment/view.php?a='.$assignment->id;
  }
  elseif ($modulename=='chat'){
    if (! $chat = $DB->get_record("chat", array("id" => "$cm->instance"))) {
      //error("DEBUG :: referentiel_get_module_info :: This chat doesn't exist");
      return false;
    }
    $mid=$chat->id;
    $mname=$chat->name;
    $mdescription=$chat->intro;
    $mlink = $CFG->wwwroot.'/mod/chat/view.php?id='.$cm->id;
  }
  elseif ($modulename=='choice'){
    if (! $choice = $DB->get_record("choice", array("id" => "$cm->instance"))) {
      // error("DEBUG :: referentiel_get_module_info :: This choice module doesn't exist");
      return false;
    }
    $mid=$choice->id;
    $mname=$choice->name;
    $mdescription=$choice->intro;
    $mlink = $CFG->wwwroot.'/mod/choice/view.php?id='.$cm->id;
  }
  elseif ($modulename=='data'){
    if (! $data = $DB->get_record("data", array("id" => "$cm->instance"))) {
      // error("DEBUG :: referentiel_get_module_info :: This data module doesn't exist");
      return false;
    }
    $mid=$data->id;
    $mname=$data->name;
    $mdescription=$data->intro;
    $mlink = $CFG->wwwroot.'/mod/data/view.php?id='.$cm->id;

// http://tracker.moodle.org/browse/MDL-15566
// Notice: Undefined property: stdClass::$cmidnumber in C:\xampp\htdocs\moodle_dev\mod\data\lib.php on line 831
  }
  elseif ($modulename=='glossary'){
    if (! $glossary = $DB->get_record("glossary",array("id" => "$cm->instance"))) {
      print_error("DEBUG :: referentiel_get_module_info :: This glossary module doesn't exist");
    }
    $mid=$glossary->id;
    $mname=$glossary->name;
    $mdescription=$glossary->intro;
    $mlink = $CFG->wwwroot.'/mod/glossary/view.php?id='.$cm->id;
  }
  else{
    // tentative pour un module generique
    if (! $record_module = $DB->get_record($module->name,array("id" => "$cm->instance"))) {
      // error("DEBUG :: referentiel_get_module_info :: This ".$module->name." module doesn't exist");
      return false;
    }
    $mid=$record_module->id;
    $mname=$record_module->name;
    if (isset($record_module->intro)){
      $mdescription=$record_module->intro;
    }
    else if (isset($record_module->info)){
      $mdescription=$record_module->info;
    }
    else if (isset($record_module->description)){
      $mdescription=$record_module->description;
    }
    else if (isset($record_module->text)){
      $mdescription=$record_module->text;
    }
    else{
      $mdescription=get_string('description_inconnue','referentiel');
    }
    $mlink = $CFG->wwwroot.'/mod/'.$modulename.'/view.php?id='.$cm->id;
  }

  $m=new Object();
  $m->id=$module->id;
  $m->type=$modulename;
  $m->instance=$moduleinstance;
  $m->course=$courseid;
  $m->date=$cm->added;
  $m->userdate=userdate($cm->added);
  $m->ref_activite=$mid;
  $m->name=$mname;
  $m->description=$mdescription;
  $m->link=$mlink;

  return $m;
}

// -------------------------------------------------
function referentiel_get_outcomes($starttime, $endtime){
// genere le liste des notations
global $CFG;
global $DB;
  $notations=array();
  // selectionner tous les codes de r�f�rentiel
    $params=array();
	$sql = "SELECT {referentiel}.id AS instanceid,
        {referentiel}.course AS courseid,
        {referentiel_referentiel}.id AS ref_referentiel,
        {referentiel_referentiel}.code_referentiel AS code_referentiel
  FROM {referentiel}, {referentiel_referentiel}
  WHERE {referentiel}.ref_referentiel={referentiel_referentiel}.id
  ORDER BY {referentiel}.course ASC, {referentiel}.id ASC,
    {referentiel_referentiel}.code_referentiel ASC ";
  
  // DEBUG
  /*
  if (REFERENTIEL_DEBUG){
    echo "<br />DEBUG :: ./mod/referentiel/grade/cron_outcomes.php<br />Line 420 :: SQL:$sql<br/>\n";
  }
  */
  
	$r_referentiels=$DB->get_records_sql($sql, $params);
  if ($r_referentiels){
    foreach($r_referentiels as $r_referentiel){
      
      // DEBUG
      /*
      if (REFERENTIEL_DEBUG){
        mtrace("DEBUG :: ./mod/referentiel/grade/cron_outcomes.php\nLine 410 :: REFERENTIELS\n");
        print_r($r_referentiel);
      }
      */
      // selectionner les outcomes 
    	/*

--
-- Structure de la table 'mdl_grade_outcomes'
--

CREATE TABLE mdl_grade_outcomes (
  id bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  courseid bigint(10) unsigned DEFAULT NULL,
  shortname varchar(255) NOT NULL DEFAULT '',
  fullname text NOT NULL,
  scaleid bigint(10) unsigned DEFAULT NULL,
  description text,
  timecreated bigint(10) unsigned DEFAULT NULL,
  timemodified bigint(10) unsigned DEFAULT NULL,
  usermodified bigint(10) unsigned DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY mdl_gradoutc_cousho_uix (courseid,shortname),
  KEY mdl_gradoutc_cou_ix (courseid),
  KEY mdl_gradoutc_sca_ix (scaleid),
  KEY mdl_gradoutc_use_ix (usermodified)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='This table describes the outcomes used in the system. An out';

      */   
      $params=array("fullname" => "$r_referentiel->code_referentiel%") ;
      $sql = "SELECT id, courseid, shortname, fullname, scaleid 
      FROM {grade_outcomes}
      WHERE fullname LIKE :fullname
      ORDER BY fullname ASC ";	
      $r_outcomes=$DB->get_records_sql($sql, $params);
      if ($r_outcomes){ 
        foreach($r_outcomes as $r_outcome){
          // selectionner les items (activites utilisant ces outcomes) 
          // DEBUG
          /*
          if (REFERENTIEL_DEBUG){
            mtrace("DEBUG :: ./mod/referentiel/grade/cron_outcomes.php Line 442 :: OBJECTIFS<br/>\n");
            print_r($r_outcome);
            echo "<br />\n";
          }
          */
/*

CREATE TABLE mdl_grade_items (
  id bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  courseid bigint(10) unsigned DEFAULT NULL,
  categoryid bigint(10) unsigned DEFAULT NULL,
  itemname varchar(255) DEFAULT NULL,
  itemtype varchar(30) NOT NULL DEFAULT '',
  itemmodule varchar(30) DEFAULT NULL,
  iteminstance bigint(10) unsigned DEFAULT NULL,
  itemnumber bigint(10) unsigned DEFAULT NULL,
  iteminfo mediumtext,
  idnumber varchar(255) DEFAULT NULL,
  calculation mediumtext,
  gradetype smallint(4) NOT NULL DEFAULT '1',
  grademax decimal(10,5) NOT NULL DEFAULT '100.00000',
  grademin decimal(10,5) NOT NULL DEFAULT '0.00000',
  scaleid bigint(10) unsigned DEFAULT NULL,
  outcomeid bigint(10) unsigned DEFAULT NULL,
  gradepass decimal(10,5) NOT NULL DEFAULT '0.00000',
  multfactor decimal(10,5) NOT NULL DEFAULT '1.00000',
  plusfactor decimal(10,5) NOT NULL DEFAULT '0.00000',
  aggregationcoef decimal(10,5) NOT NULL DEFAULT '0.00000',
  sortorder bigint(10) NOT NULL DEFAULT '0',
  display bigint(10) NOT NULL DEFAULT '0',
  decimals tinyint(1) unsigned DEFAULT NULL,
  hidden bigint(10) NOT NULL DEFAULT '0',
  locked bigint(10) NOT NULL DEFAULT '0',
  locktime bigint(10) unsigned NOT NULL DEFAULT '0',
  needsupdate bigint(10) NOT NULL DEFAULT '0',
  timecreated bigint(10) unsigned DEFAULT NULL,
  timemodified bigint(10) unsigned DEFAULT NULL,
  PRIMARY KEY (id),
  KEY mdl_graditem_locloc_ix (locked,locktime),
  KEY mdl_graditem_itenee_ix (itemtype,needsupdate),
  KEY mdl_graditem_gra_ix (gradetype),
  KEY mdl_graditem_idncou_ix (idnumber,courseid),
  KEY mdl_graditem_cou_ix (courseid),
  KEY mdl_graditem_cat_ix (categoryid),
  KEY mdl_graditem_sca_ix (scaleid),
  KEY mdl_graditem_out_ix (outcomeid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='This table keeps information about gradeable items (ie colum';          

INSERT INTO `mdl_grade_items` (`id`, `courseid`, `categoryid`, `itemname`, `itemtype`, `itemmodule`, `iteminstance`, `itemnumber`, `iteminfo`, `idnumber`, `calculation`, `gradetype`, `grademax`, `grademin`, `scaleid`, `outcomeid`, `gradepass`, `multfactor`, `plusfactor`, `aggregationcoef`, `sortorder`, `display`, `decimals`, `hidden`, `locked`, `locktime`, `needsupdate`, `timecreated`, `timemodified`) 
VALUES(1, 2, NULL, NULL, 'course', NULL, 1, NULL, NULL, NULL, NULL, 1, '100.00000', '0.00000', NULL, NULL, '0.00000', '1.00000', '0.00000', '0.00000', 1, 0, NULL, 0, 0, 0, 0, 1260780703, 1260780703);
...
INSERT INTO `mdl_grade_items` (`id`, `courseid`, `categoryid`, `itemname`,     `itemtype`, `itemmodule`, `iteminstance`, `itemnumber`, `iteminfo`, `idnumber`, `calculation`, `gradetype`, `grademax`, `grademin`, `scaleid`, `outcomeid`, `gradepass`, `multfactor`, `plusfactor`, `aggregationcoef`, `sortorder`, `display`, `decimals`, `hidden`, `locked`, `locktime`, `needsupdate`, `timecreated`, `timemodified`) 
VALUES                        (9,     2,          1,            'C2i2e B.4.1', 'mod',      'assignment',  1,             1003,         NULL,        NULL,       NULL,         2,           '3.00000',  '1.00000',   2,        27,           '0.00000',   '1.00000',   '0.00000',    '0.00000',          5, 0, NULL, 0, 0, 0, 0, 1266785659, 1266785659);
*/
          $params=array("outcomeid" => "$r_outcome->id", "courseid" => "$r_referentiel->courseid");
          $sql = "SELECT `id`, `courseid`, `categoryid`, `itemname`, `itemtype`, `itemmodule`, `iteminstance`, `itemnumber`, `iteminfo`, `idnumber`, `calculation`, `gradetype`, `grademax`, `grademin`, `scaleid`, `outcomeid`, `timemodified` 
 FROM {grade_items}  WHERE outcomeid= :outcomeid  AND courseid=:courseid
 ORDER BY courseid, outcomeid ASC ";
 
          $r_items=$DB->get_records_sql($sql, $params);
          if ($r_items){ 
            foreach($r_items as $r_item){
              // selectionner les items (activites) utilisant ces outcomes 
              // DEBUG
              /*
              if (REFERENTIEL_DEBUG){
                mtrace("DEBUG :: ./mod/referentiel/grade/cron_outcomes.php\nLine 546 :: ITEMS<br/>\n");
                print_r($r_item);                
              }
              */
              // selectionner les grades (notes attribu�es aux utilisateur de ces activit�s) 
/*
              

--
-- Structure de la table 'mdl_grade_grades'
--

CREATE TABLE mdl_grade_grades (
  id bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  itemid bigint(10) unsigned NOT NULL,
  userid bigint(10) unsigned NOT NULL,
  rawgrade decimal(10,5) DEFAULT NULL,
  rawgrademax decimal(10,5) NOT NULL DEFAULT '100.00000',
  rawgrademin decimal(10,5) NOT NULL DEFAULT '0.00000',
  rawscaleid bigint(10) unsigned DEFAULT NULL,
  usermodified bigint(10) unsigned DEFAULT NULL,
  finalgrade decimal(10,5) DEFAULT NULL,
  hidden bigint(10) unsigned NOT NULL DEFAULT '0',
  locked bigint(10) unsigned NOT NULL DEFAULT '0',
  locktime bigint(10) unsigned NOT NULL DEFAULT '0',
  exported bigint(10) unsigned NOT NULL DEFAULT '0',
  overridden bigint(10) unsigned NOT NULL DEFAULT '0',
  excluded bigint(10) unsigned NOT NULL DEFAULT '0',
  feedback mediumtext,
  feedbackformat bigint(10) unsigned NOT NULL DEFAULT '0',
  information mediumtext,
  informationformat bigint(10) unsigned NOT NULL DEFAULT '0',
  timecreated bigint(10) unsigned DEFAULT NULL,
  timemodified bigint(10) unsigned DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY mdl_gradgrad_useite_uix (userid,itemid),
  KEY mdl_gradgrad_locloc_ix (locked,locktime),
  KEY mdl_gradgrad_ite_ix (itemid),
  KEY mdl_gradgrad_use_ix (userid),
  KEY mdl_gradgrad_raw_ix (rawscaleid),
  KEY mdl_gradgrad_use2_ix (usermodified)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='grade_grades  This table keeps individual grades for each us';

--
-- Contenu de la table 'mdl_grade_grades'
--

INSERT INTO mdl_grade_grades (id, itemid, userid, rawgrade, rawgrademax, rawgrademin, rawscaleid, usermodified, finalgrade, hidden, locked, locktime, exported, overridden, excluded, feedback, feedbackformat, information, informationformat, timecreated, timemodified) VALUES
(1, 3, 2, '2.00000', '3.00000', '1.00000', 1, 4, '2.00000', 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 1266662583),
(2, 1, 2, NULL, '100.00000', '0.00000', NULL, NULL, '50.00000', 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL),
(3, 3, 3, '3.00000', '3.00000', '1.00000', 1, 2, '3.00000', 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 1266664474),
(4, 1, 3, NULL, '100.00000', '0.00000', NULL, NULL, '100.00000', 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL),
(5, 4, 3, NULL, '100.00000', '0.00000', NULL, 2, '3.00000', 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 1266663872),
(6, 5, 3, '3.00000', '3.00000', '1.00000', 2, 4, '3.00000', 0, 0, 0, 0, 0, 0, 'OK ', 1, NULL, 0, 1266785814, 1266785949),
(7, 6, 3, NULL, '100.00000', '0.00000', NULL, 4, '2.00000', 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 1266785948),
(8, 7, 3, NULL, '100.00000', '0.00000', NULL, 4, '3.00000', 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 1266785949),
(9, 8, 3, NULL, '100.00000', '0.00000', NULL, 4, '3.00000', 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 1266785949),
(10, 9, 3, NULL, '100.00000', '0.00000', NULL, 4, '3.00000', 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 1266785949);

*/

                    
  
                  // DEBUG
                  /*
                  echo ("<br />REFERENTIEL INSTANCE : ".$r_referentiel->instanceid.", Course_id: ".$r_referentiel->courseid."\n");                  
                  echo ("<br />REFERENTIEL : ".$r_referentiel->code_referentiel."\n");
                  echo ("<br />OBJECTIF : Id:".$r_outcome->id." Nom:".$r_outcome->fullname."\n");
                  echo ("<br />ITEM : Num_Cours:".$r_item->courseid.", Nom_Item:".$r_item->itemname.", module:".$r_item->itemmodule.", instance:".$r_item->iteminstance.", Num_Objectif:".$r_item->outcomeid);
                  */
              $params=array("itemid"=>"$r_item->id", "starttime" =>"$starttime", "endtime" => "$endtime");
              // MODIF JF 2012/01/31 : usermodified
              $sql = "SELECT id, itemid, userid, usermodified, rawscaleid, finalgrade, timemodified
 FROM {grade_grades} WHERE itemid=:itemid AND ((timemodified>=:starttime)
 AND (timemodified < :endtime)) ORDER BY itemid ASC, userid ASC ";
 
              // DEBUG
              /*
              if (REFERENTIEL_DEBUG){
                mtrace("DEBUG :: ./mod/referentiel/grade/cron_outcomes.php Line 626 ::\nSQL = $sql\n");
              }
              */
              $r_grades=$DB->get_records_sql($sql, $params);

              if ($r_grades){ 
                foreach($r_grades as $r_grade){                  
                  if ($r_grade){

                    // stocker l'activite pour traitement  
                      $notation=new Object();
                      $notation->referentiel_instanceid=$r_referentiel->instanceid;  
                      $notation->courseid=$r_referentiel->courseid; 
                      $notation->ref_referentiel=$r_referentiel->ref_referentiel; 
                      $notation->code_referentiel=$r_referentiel->code_referentiel;
                      $notation->outcomeid= $r_outcome->id;
                      $notation->outcomeshortname= $r_outcome->shortname;
                      $notation->scaleid= $r_outcome->scaleid;
                      $notation->itemname= $r_item->itemname;
                      $notation->module=  $r_item->itemmodule;
                      $notation->moduleinstance= $r_item->iteminstance;              
                      $notation->userid=$r_grade->userid;  
                      $notation->teacherid=$r_grade->usermodified;     // MODIF JF 2012/01/31
                      $notation->finalgrade=$r_grade->finalgrade; 
                      $notation->timemodified=$r_grade->timemodified;
                      $notations[]= $notation;              
                  }
                }        
              }
            }
          }
        }
      }
    }
  }
  return $notations;
}


/**
 * Given an object containing all the necessary data,
 * this function will create a new activity and return the id number
 *
 * @param object $activite an special referentiel_activite object
 * @param object $m a secial module object
 * @return int The id of the newly inserted record
 **/
function referentiel_activite_outcomes($activite, $m) {
// creation / mise � jour activite par objectif
global $CFG;
global $DB;

$activite_id=0;
	if (REFERENTIEL_DEBUG){
        // DEBUG
        mtrace("\nDEBUG :: referentiel_activite_outcomes :: 8755\nDEMANDE MISE A JOUR\n");
        print_r($activite);
    }
    $activite->type_activite=addslashes($activite->type_activite);
    $activite->description_activite=addslashes($activite->description_activite);
    $activite->mailnow=0;
    $activite->mailed=1;         // ne pas notifier.
/*
  $m->id;
  $m->type=$modulename;
  $m->instance=$moduleinstance;
  $m->course=$courseid;
  $m->date=$cm->added;
  $m->userdate=userdate($cm->added);
  $m->ref_activite=$mid;
  $m->name=$mname;
  $m->description=$mdescription;
  $m->link=$mlink;
*/

  // cette activite est-elle enregistree ?
    $params=array("ref_instance"=>"$activite->ref_instance",
        "ref_referentiel"=>"$activite->ref_referentiel",
        "ref_course"=>"$activite->ref_course",
        "userid"=>"$activite->userid",
        "type"=>"$m->type",
        "moduleid"=>"$m->id",
        "ref_activite"=>"$m->ref_activite");
        
	$sql = "SELECT * FROM {referentiel_activite_modules}
  WHERE ref_instance=:ref_instance
  AND ref_referentiel=:ref_referentiel AND ref_course=:ref_course
  AND userid=:userid
  AND type=:type
  AND moduleid=:moduleid
  AND ref_activite=:ref_activite ";
    if (REFERENTIEL_DEBUG){
        mtrace("\nSQL:\n$sql\n");
	}

	$r_activite_outcomes=$DB->get_record_sql($sql, $params);

    if ($r_activite_outcomes) {
        if (REFERENTIEL_DEBUG){
		  // DEBUG
		  mtrace("\nDEBUG :: referentiel_activite_outcomes :: 8782\n");
		  print_r($r_activite_outcomes);
        }

        // cet utilisateur est deja ref�renc� pour cette activite
        $activite_old=$DB->get_record("referentiel_activite", array("id" => "$r_activite_outcomes->activiteid"));
        if ($activite_old){
            if (REFERENTIEL_DEBUG){
                // DEBUG
                mtrace("\nDEBUG :: grade/cron_outcomes/referentiel_activite_outcomes :: 754\nOLD\n");
                print_r($activite_old);
            }

            $activite_old->id;
            $activite->id=$activite_old->id;
            $activite->date_modif_student=$activite_old->date_modif_student;
            if (!$activite->date_modif){
                $activite->date_modif=time();
            }
            $activite->approved=$activite_old->approved;

            if (!$activite_old->date_modif || ($activite_old->date_modif<$activite->date_modif)){
                // DEBUG
                // print_object($activite);
                // echo "<br />";
                if ($DB->update_record("referentiel_activite", $activite)){
                    referentiel_mise_a_jour_competences_certificat_user($activite_old->competences_activite, $activite->competences_activite, $activite->userid, $activite->ref_referentiel, $activite->approved, true, $activite->approved);
                    $activite_id=$activite->id;
                }
            }
        }
        else{
            // Cette activite a �t� supprimee
            // Supprimer la ligne qui n'a plus de correspondance dans les activites
            $DB->delete_records("referentiel_activite_modules", array("id" => "$r_activite_outcomes->id"));
            return 0;
        }
    }
    else {
        if (REFERENTIEL_DEBUG){
                // DEBUG
                mtrace("\nDEBUG :: grade/cron_outcomes/referentiel_activite_outcomes :: 786 :: NEW ACTIVITY\n");
                print_r($activite);
        }

        // $activite_id = $DB->insert_record("referentiel_activite", $activite);
        $activite_id = referentiel_insert_activite_controlee($activite);

        if 	(($activite_id>0) && ($activite->competences_activite!='')){
            // mise a jour du certificat
            referentiel_mise_a_jour_competences_certificat_user('', $activite->competences_activite, $activite->userid, $activite->ref_referentiel, $activite->approved, true, false);
        }
        else{
            if (REFERENTIEL_DEBUG){
                // DEBUG
                mtrace("\nDEBUG :: grade/cron_outcomes/referentiel_activite_outcomes :: 799 :: ERROR INSERT ACTIVITY \n");
                print_r($activite);
            }

        }

        // Rajouter le lien
        if (isset($activite_id) && ($activite_id>0)
			&&
			(	(isset($m->link) && !empty($m->link))
				||
				(isset($m->name) && !empty($m->description))
			)
	     ){
            $document = new object();
            $document->url_document=$m->link;
            $document->type_document=addslashes(get_string('modulename', $m->type));
            $document->description_document=''; // addslashes($m->description);
            $document->ref_activite=$activite_id;
			$document->cible_document=1;
			$document->etiquette_document=addslashes($m->name);

            // DEBUG
            // print_object($document);
            // echo "<br />";

            $document_id = $DB->insert_record("referentiel_document", $document);
            // echo "DOCUMENT ID / $document_id<br />";
        }

        //
        if (isset($activite_id) && ($activite_id>0)){
            //
            $r_a_outcomes=new object();
            $r_a_outcomes->activiteid=$activite_id;
            $r_a_outcomes->ref_course=$activite->ref_course;
            $r_a_outcomes->ref_instance=$activite->ref_instance;
            $r_a_outcomes->ref_referentiel=$activite->ref_referentiel;
            $r_a_outcomes->userid=$activite->userid;
            $r_a_outcomes->type=$m->type;
            $r_a_outcomes->moduleid=$m->id;
            $r_a_outcomes->ref_activite=$m->ref_activite;
            $DB->insert_record("referentiel_activite_modules", $r_a_outcomes);
        }
    }
    return $activite_id;
}

/**
 * Given an object containing activity data,
 * this function will check the completude and insert a new row and return the id number
 *
 * @param object $activite an special referentiel_activite object
 * @return int The id of the newly inserted record
 **/
function referentiel_insert_activite_controlee($activite){
// on verifie si la liste de comp�tences appartient
// bien au r�f�rentiel design�
// histoire d'eviter des affectations intempestives pour des referentiel ayan le m�me code
/*

object Object
(
    [type_activite] => [Activity Assignment 6]  Date:  Friday,  22 April 2011, 08:03 AM
    [description_activite] => D�pot de fichier avec Objectifs : D�posez un ficheir PDF
    [competences_activite] => B.2.1/B.2.2/
    [commentaire_activite] =>
    [ref_instance] => 1
    [ref_referentiel] => 1
    [ref_course] => 2
    [userid] => 3
    [teacherid] => 2  // MODIF JF 2012/01/31
    [date_creation] => 1303452226
    [date_modif_student] =>  1303453169
    [date_modif] => 1303453170
    [approved] => 1
    [ref_task] => 0
    [mailnow] => 0
    [mailed] => 1
)


*/
global $DB;

    if (!empty($activite) && !empty($activite->competences_activite)) {
        $rref= $DB->get_record("referentiel_referentiel", array("id" => $activite->ref_referentiel));
        if (REFERENTIEL_DEBUG){
                // DEBUG
                mtrace("\nDEBUG :: grade/cron_outcomes/referentiel_activite_outcomes :: 894 :: CONTROLE INSERTION ACTIVITE POUR UN REFERENTIEL\n");
                mtrace("COMPETENCES : $activite->competences_activite \n est-elle compatible avec \n LISTE : $rref->liste_codes_competence\n");
        }
        if (!empty($rref->liste_codes_competence)){
            $t_codes_item=explode('/', $activite->competences_activite);
            if ($t_codes_item && (count($t_codes_item)>0)){
                foreach($t_codes_item as $code_item){
                    if (!empty($code_item)){
                        $pos=strpos($rref->liste_codes_competence, $code_item);
                        if ($pos===false){
                            return 0;
                        }
                    }
                }

                return $DB->insert_record("referentiel_activite", $activite);
            }
        }
    }
    return 0;
}

?>
