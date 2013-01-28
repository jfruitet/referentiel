<?php 

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    require_once($CFG->dirroot.'/mod/referentiel/lib.php');


// Valeurs par defaut
// scol:0;creref:0;selref:0;impcert:0;graph:0;refcert:1;instcert:0;numetu:1;nometu:1;etabetu:0;ddnetu:0;lieuetu:0;adretu:0;pourcent:0;compdec:0;compval:1;refcert:0;jurycert:1;comcert:0;

/*
// exemples d'usage
    $options = array(ASSIGNMENT_COUNT_WORDS   => trim(get_string('numwords', '', '?')),
                     ASSIGNMENT_COUNT_LETTERS => trim(get_string('numletters', '', '?')));
    $settings->add(new admin_setting_configselect('assignment_itemstocount', get_string('itemstocount', 'assignment'),
                       get_string('configitemstocount', 'assignment'), ASSIGNMENT_COUNT_WORDS, $options));

    $settings->add(new admin_setting_configcheckbox('assignment_showrecentsubmissions', get_string('showrecentsubmissions', 'assignment'),
                       get_string('configshowrecentsubmissions', 'assignment'), 1));

    $settings->add(new admin_setting_configselect('forum_displaymode', get_string('displaymode', 'forum'),
                       get_string('configdisplaymode', 'forum'), FORUM_MODE_NESTED, forum_get_layout_modes()));

    $settings->add(new admin_setting_configcheckbox('forum_replytouser', get_string('replytouser', 'forum'),
                       get_string('configreplytouser', 'forum'), 1));

    // Less non-HTML characters than this is short
    $settings->add(new admin_setting_configtext('forum_shortpost', get_string('shortpost', 'forum'),
                       get_string('configshortpost', 'forum'), 300, PARAM_INT));

*/

$options = array();
$options[0] = 0;
$options[1] = 1;
$options[2] = 2;
// scolarite
if (isset($CFG->referentiel_scolarite_masquee)){
    $settings->add(new admin_setting_configselect('referentiel_scolarite_masquee', get_string('scolarite_user', 'referentiel'),
                   get_string('config_scolarite', 'referentiel'), $CFG->referentiel_scolarite_masquee, $options));
}
else{
    $settings->add(new admin_setting_configselect('referentiel_scolarite_masquee', get_string('scolarite_user', 'referentiel'),
                   get_string('config_scolarite', 'referentiel'), 0, $options));
}
// creation de referentiels
unset($options);
$options[0] = 0;
$options[1] = 1;
$options[2] = 2;
if (isset($CFG->referentiel_creation_limitee)){
    $settings->add(new admin_setting_configselect('referentiel_creation_limitee', get_string('create_referentiel', 'referentiel'),
                   get_string('config_creer_referentiel', 'referentiel'), $CFG->referentiel_creation_limitee, $options));
}
else{
    $settings->add(new admin_setting_configselect('referentiel_creation_limitee', get_string('create_referentiel', 'referentiel'),
                   get_string('config_creer_referentiel', 'referentiel'), 0, $options));
}
// selection de referentiels
unset($options);
$options[0] = 0;
$options[1] = 1;
$options[2] = 2;
if (isset($CFG->referentiel_selection_autorisee)){
    $settings->add(new admin_setting_configselect('referentiel_selection_autorisee', get_string('select_referentiel', 'referentiel'),
                   get_string('config_select_referentiel', 'referentiel'), $CFG->referentiel_selection_autorisee, $options));
}
else{
    $settings->add(new admin_setting_configselect('referentiel_selection_autorisee', get_string('select_referentiel', 'referentiel'),
                   get_string('config_select_referentiel', 'referentiel'), 0, $options));
}

// affichage graphique des competences validées
unset($options);
$options[0] = 0;
$options[1] = 1;
$options[2] = 2;
if (isset($CFG->referentiel_affichage_graphique)){
    $settings->add(new admin_setting_configselect('referentiel_affichage_graphique', get_string('select_aff_graph', 'referentiel'),
                   get_string('config_aff_graph', 'referentiel'), $CFG->referentiel_affichage_graphique, $options));
}
else{
    $settings->add(new admin_setting_configselect('referentiel_affichage_graphique', get_string('select_aff_graph', 'referentiel'),
                   get_string('config_aff_graph', 'referentiel'), 0, $options));
}

// impression des certificats
unset($options);
$options[0] = 0;
$options[1] = 1;
$options[2] = 2;
if (isset($CFG->referentiel_impression_autorisee)){
    $settings->add(new admin_setting_configselect('referentiel_impression_autorisee', get_string('referentiel_impression_autorisee', 'referentiel'),
                   get_string('config_impression_referentiel', 'referentiel'), $CFG->referentiel_impression_autorisee, $options));
}
else{
    $settings->add(new admin_setting_configselect('referentiel_impression_autorisee', get_string('referentiel_impression_autorisee', 'referentiel'),
                   get_string('config_impression_referentiel', 'referentiel'), 0, $options));
}

unset($options);
$options[0] = 1;
$options[1] = 0;
if (isset($CFG->certificat_sel_referentiel)){
    $settings->add(new admin_setting_configselect('certificat_sel_referentiel', get_string('certificat_sel_referentiel', 'referentiel'),
                   get_string('config_certificat_sel_referentiel', 'referentiel'), $CFG->certificat_sel_referentiel, $options));
}
else{
    $settings->add(new admin_setting_configselect('certificat_sel_referentiel', get_string('certificat_sel_referentiel', 'referentiel'),
                   get_string('config_certificat_sel_referentiel', 'referentiel'), 0, $options));
}

unset($options);
$options[0] = 0;
$options[1] = 1;
if (isset($CFG->certificat_sel_referentiel_instance) ){
    $settings->add(new admin_setting_configselect('certificat_sel_referentiel_instance', get_string('certificat_sel_referentiel_instance', 'referentiel'),
                   get_string('config_certificat_sel_referentiel_instance', 'referentiel'), $CFG->certificat_sel_referentiel_instance, $options));
}
else{
    $settings->add(new admin_setting_configselect('certificat_sel_referentiel_instance', get_string('certificat_sel_referentiel_instance', 'referentiel'),
                   get_string('config_certificat_sel_referentiel_instance', 'referentiel'), 0, $options));
}

unset($options);
$options[0] = 1;
$options[1] = 0;
if (isset($CFG->certificat_sel_etudiant_numero)){
    $settings->add(new admin_setting_configselect('certificat_sel_etudiant_numero', get_string('certificat_sel_etudiant_numero', 'referentiel'),
                   get_string('config_certificat_sel_etudiant_numero', 'referentiel'), $CFG->certificat_sel_etudiant_numero, $options));
}
else{
    $settings->add(new admin_setting_configselect('certificat_sel_etudiant_numero', get_string('certificat_sel_etudiant_numero', 'referentiel'),
                   get_string('config_certificat_sel_etudiant_numero', 'referentiel'), 0, $options));
}
				 
unset($options);
$options[0] = 1;
$options[1] = 0;
if (isset($CFG->certificat_sel_etudiant_nom_prenom) ){
    $settings->add(new admin_setting_configselect('certificat_sel_etudiant_nom_prenom', get_string('certificat_sel_etudiant_nom_prenom', 'referentiel'),
                   get_string('config_certificat_sel_etudiant_nom_prenom', 'referentiel'), $CFG->certificat_sel_etudiant_nom_prenom, $options));
}
else{
    $settings->add(new admin_setting_configselect('certificat_sel_etudiant_nom_prenom', get_string('certificat_sel_etudiant_nom_prenom', 'referentiel'),
                   get_string('config_certificat_sel_etudiant_nom_prenom', 'referentiel'), 0, $options));
}

unset($options);
$options[0] = 0;
$options[1] = 1;
if (isset($CFG->certificat_sel_etudiant_etablissement) ){
    $settings->add(new admin_setting_configselect('certificat_sel_etudiant_etablissement', get_string('certificat_sel_etudiant_etablissement', 'referentiel'),
                   get_string('config_certificat_sel_etudiant_etablissement', 'referentiel'), $CFG->certificat_sel_etudiant_etablissement, $options));
}
else{
    $settings->add(new admin_setting_configselect('certificat_sel_etudiant_etablissement', get_string('certificat_sel_etudiant_etablissement', 'referentiel'),
                   get_string('config_certificat_sel_etudiant_etablissement', 'referentiel'), 0, $options));
}

unset($options);
$options[0] = 0;
$options[1] = 1;
if (isset($CFG->certificat_sel_etudiant_ddn) ){
    $settings->add(new admin_setting_configselect('certificat_sel_etudiant_ddn', get_string('certificat_sel_etudiant_ddn', 'referentiel'),
                   get_string('config_certificat_sel_etudiant_ddn', 'referentiel'), $CFG->certificat_sel_etudiant_ddn, $options));
}
else{
    $settings->add(new admin_setting_configselect('certificat_sel_etudiant_ddn', get_string('certificat_sel_etudiant_ddn', 'referentiel'),
                   get_string('config_certificat_sel_etudiant_ddn', 'referentiel'), 0, $options));
}

unset($options);
$options[0] = 0;
$options[1] = 1;
if (isset($CFG->certificat_sel_etudiant_lieu_naissance)){
    $settings->add(new admin_setting_configselect('certificat_sel_etudiant_lieu_naissance', get_string('certificat_sel_etudiant_lieu_naissance', 'referentiel'),
                   get_string('config_certificat_sel_etudiant_lieu_naissance', 'referentiel'), $CFG->certificat_sel_etudiant_lieu_naissance, $options));
}
else{
    $settings->add(new admin_setting_configselect('certificat_sel_etudiant_lieu_naissance', get_string('certificat_sel_etudiant_lieu_naissance', 'referentiel'),
                   get_string('config_certificat_sel_etudiant_lieu_naissance', 'referentiel'), 0, $options));
}

unset($options);
$options[0] = 0;
$options[1] = 1;
if (isset($CFG->certificat_sel_etudiant_adresse)){
    $settings->add(new admin_setting_configselect('certificat_sel_etudiant_adresse', get_string('certificat_sel_etudiant_adresse', 'referentiel'),
                   get_string('config_certificat_sel_etudiant_adresse', 'referentiel'), $CFG->certificat_sel_etudiant_adresse, $options));
}
else{
    $settings->add(new admin_setting_configselect('certificat_sel_etudiant_adresse', get_string('certificat_sel_etudiant_adresse', 'referentiel'),
                   get_string('config_certificat_sel_etudiant_adresse', 'referentiel'), 0, $options));
}

unset($options);
$options[0] = 1;
$options[1] = 0;
if (isset($CFG->certificat_sel_certificat_detail)){
    $settings->add(new admin_setting_configselect('certificat_sel_certificat_detail', get_string('certificat_sel_certificat_detail', 'referentiel'),
                   get_string('config_certificat_sel_certificat_detail', 'referentiel'), $CFG->certificat_sel_certificat_detail, $options));
}
else{
    $settings->add(new admin_setting_configselect('certificat_sel_certificat_detail', get_string('certificat_sel_certificat_detail', 'referentiel'),
                   get_string('config_certificat_sel_certificat_detail', 'referentiel'), 0, $options));
}

unset($options);
$options[0] = 0;
$options[1] = 1;
if (isset($CFG->certificat_sel_certificat_pourcent)){
    $settings->add(new admin_setting_configselect('certificat_sel_certificat_pourcent', get_string('certificat_sel_certificat_pourcent', 'referentiel'),
                   get_string('config_certificat_sel_certificat_pourcent', 'referentiel'), $CFG->certificat_sel_certificat_pourcent, $options));
}
else{
    $settings->add(new admin_setting_configselect('certificat_sel_certificat_pourcent', get_string('certificat_sel_certificat_pourcent', 'referentiel'),
                   get_string('config_certificat_sel_certificat_pourcent', 'referentiel'), 0, $options));
}

unset($options);
$options[0] = 0;
$options[1] = 1;
if (isset($CFG->certificat_sel_activite_competences) ){
    $settings->add(new admin_setting_configselect('certificat_sel_activite_competences', get_string('certificat_sel_activite_competences', 'referentiel'),
                   get_string('config_certificat_sel_activite_competences', 'referentiel'), $CFG->certificat_sel_activite_competences, $options));
}
else{
    $settings->add(new admin_setting_configselect('certificat_sel_activite_competences', get_string('certificat_sel_activite_competences', 'referentiel'),
                   get_string('config_certificat_sel_activite_competences', 'referentiel'), 0, $options));
}

unset($options);
$options[0] = 1;
$options[1] = 0;
if (isset($CFG->certificat_sel_certificat_competences)){
    $settings->add(new admin_setting_configselect('certificat_sel_certificat_competences', get_string('certificat_sel_certificat_competences', 'referentiel'),
                   get_string('config_certificat_sel_certificat_competences', 'referentiel'), $CFG->certificat_sel_certificat_competences, $options));
}
else{
    $settings->add(new admin_setting_configselect('certificat_sel_certificat_competences', get_string('certificat_sel_certificat_competences', 'referentiel'),
                   get_string('config_certificat_sel_certificat_competences', 'referentiel'), 0, $options));
}

unset($options);
$options[0] = 0;
$options[1] = 1;
if (isset($CFG->certificat_sel_certificat_referents)){
    $settings->add(new admin_setting_configselect('certificat_sel_certificat_referents', get_string('certificat_sel_certificat_referents', 'referentiel'),
                   get_string('config_certificat_sel_certificat_referents', 'referentiel'), $CFG->certificat_sel_certificat_referents, $options));
}
else{
    $settings->add(new admin_setting_configselect('certificat_sel_certificat_referents', get_string('certificat_sel_certificat_referents', 'referentiel'),
                   get_string('config_certificat_sel_certificat_referents', 'referentiel'), 0, $options));
}

unset($options);
$options[0] = 1;
$options[1] = 0;
if (isset($CFG->certificat_sel_decision_jury)){
    $settings->add(new admin_setting_configselect('certificat_sel_decision_jury', get_string('certificat_sel_decision_jury', 'referentiel'),
                   get_string('config_certificat_sel_decision_jury', 'referentiel'), $CFG->certificat_sel_decision_jury, $options));
}
else{
    $settings->add(new admin_setting_configselect('certificat_sel_decision_jury', get_string('certificat_sel_decision_jury', 'referentiel'),
                   get_string('config_certificat_sel_decision_jury', 'referentiel'), 0, $options));
}

unset($options);
$options[0] = 0;
$options[1] = 1;
if (isset($CFG->certificat_sel_commentaire)){
    $settings->add(new admin_setting_configselect('certificat_sel_commentaire', get_string('certificat_sel_commentaire', 'referentiel'),
                   get_string('config_certificat_sel_commentaire', 'referentiel'), $CFG->certificat_sel_commentaire, $options));
}
else{
    $settings->add(new admin_setting_configselect('certificat_sel_commentaire', get_string('certificat_sel_commentaire', 'referentiel'),
                   get_string('config_certificat_sel_commentaire', 'referentiel'), 0, $options));
}
}
?>