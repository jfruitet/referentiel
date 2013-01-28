<?php
// referentiel module
// Moodle 2
$string['pluginname'] = 'Skills repository';

// repartition des competences entre enseignants
$string['notification'] = 'Notification';
$string['repartition_notification'] = 'Répartition de la notification';
$string['identite_utilisateur'] = ' pour {$a}';
$string['repartition'] = 'Suivi';
$string['liste_repartition'] = 'Répartition des compétences entre référents';
$string['aide_repartition'] = 'Affectez à chaque référent la liste des compétences à évaluer';
$string['destinataires_notification'] = 'Destinataires de la notification ';
// Aide
$string['rnotificationh']= 'Liste des référents notifiés';
$string['rnotificationh_help']= 'La notification consiste à faire envoyer par Moodle
un mèl d\'information reprenant l\'essentiel du contenu de la déclaration d\'activité.
<br />Cet écran liste pour chaque compétence du référentiel qui sera destinataire des messages de notification pour l\'étudiant considéré';


$string['repartitionh']= 'Répartition du suivi des déclarations';
$string['repartitionh_help']= 'Les enseignants d\'un cours peuvent se répartir le suivi des déclarations en fonction des compétences du référentiel.
Selon les compétences déclarées dans les activités, la notification demandée par l\'étudiant sera adressée aux enseignants qui sont désignés dans la table de répartition par compétences.
<br />Si parmi les référents affectés à l\'étudiant dans la table d\'accompagnement
il y en a qui sont aussi désignés dans table de suivi pour les compétences pointées
dans l\'activité eux seuls recevront la notification.
<br />Sinon tous les référents affectés à l\'étudiant sont notifiés, et à défaut tous les enseignants du cours';

// Mahara artefact referentiel
// From assigment_mahara plugin
$string['exportmahara'] = 'Export to Mahara portfolio artefact';
$string['clicktopreview'] = 'click to preview in full-size popup';
$string['clicktoselect'] = 'click to select page';
$string['nomaharahostsfound'] = 'No mahara hosts found.';
$string['noviewscreated'] = 'You have not created any pages in {$a}.';
$string['noviewsfound'] = 'No matching pages found in {$a}.';
$string['preview'] = 'Preview';
$string['site'] = 'Site';
$string['site_help'] = 'This setting lets you select which Mahara site your students should submit their pages from. (The Mahara site must already be configured for mnet networking with this Moodle site.)';
$string['selectedview'] = 'Submitted Page';
$string['selectmaharaview'] = 'Select one of your {$a->name} portfolio pages from this complete list, or <a href="{$a->jumpurl}">click here</a> to visit {$a->name} and create a page right now.';
$string['title'] = 'Title';
$string['typemahara'] = 'Mahara portfolio';
$string['views'] = 'Pages';
$string['viewsby'] = 'Pages by {$a}';

// portfolio

$string['mustprovidereporttype'] = 'Reort Type unknown';
$string['proposedbynameondate'] = 'Validated by {$a->name}, {$a->date}';
$string['evaluatedbynameondate'] = 'Evaluated by {$a->name}, {$a->date}';
$string['publishporttfolio'] = 'Publish';
$string['mustprovideattachmentorcertificat'] = 'File or certificat unknown';
$string['mustprovideinstance'] = 'Skills repository unknown';
$string['invalidinstanceid'] = 'Skills repository instance not found';
$string['invalidcertificat'] = 'Certificate not found';
$string['invalidoccurrence'] = 'Occurrence not found';
$string['decisionnotfound'] = 'Not any decision about this certificate ({$a})';

// 2011/10/25
$string['groupe'] = 'Group {$a}';

// moodle/admin/report/referentiel stuff
$string['formatarchive'] = 'Select an archive format';
$string['moodleversion'] = 'Moodle version: {$a}';
$string['gerer_archives'] = 'Archive';
$string['deletefile'] = 'Delete';
$string['user'] = 'User';
$string['size'] = 'Size';
$string['totalsize'] = 'Total Size: {$a}';
$string['nbfile'] = 'Number of files: {$a}';
$string['archives'] = 'Archives';
$string['users_actifs'] = 'Actifs';
$string['activites_declarees'] = 'Declarations';
$string['instancedeleted'] = 'Instance supprimee';
$string['suppression_instance_impossible'] = 'La suppression de cette instance a echoue.';
$string['supprimer_instance'] = 'Supprimer cette instance';
$string['nonexist'] = 'Instance inexistante dans ce cours';
$string['instances'] = 'Instances';
$string['occurrences'] = 'Occurrences';
$string['occurrencedeleted'] = 'Occurrence deleted';
$string['instancenondefinie'] = 'Aucune instance n\'est definie pour ce referentiel';
$string['adminreport'] = 'Administration du module Referentiel';
$string['majmoodlesvp'] = 'Cette version de Moodle est trop ancienne. Faites la mise à jour SVP ({$a} requis)';

// moodle/referentiel stuff

$string['uploadproblemfilesize'] = 'Fichier trop volumineux';
$string['maxsize'] = 'Taille maximale des documents attaches : <b>{$a}</b>';
$string['export_file_size'] = ' totalisant <b>{$a}</b>';
$string['export_documents'] = 'Exporter les {$a} documents attaches ';
$string['export_url'] = ' sous forme de liens';
$string['export_data'] = ' sous forme de fichiers';

$string['selectarchive'] = 'Selectionner des dossiers numeriques à archiver';
$string['not_deleted'] = ' not deleted ';
$string['format_pourcentage'] = 'Exporter les pourcentages par domaine ';
$string['liste_poids_competence'] = 'Weights list';
$string['generatedby'] = 'Archive generated by Moodle\'s Skills repository module';
$string['archivextra'] = 'Le fichier est aussi depose dans les fichiers du cours, dans le dossier {$a}';
$string['archivefilename'] = 'archive';
$string['archivetemp'] = 'temp/archive';
$string['archive'] = 'Archive';
$string['archivereferentiel'] = 'Archiver le referentiel, les certificats et les activites associees';
$string['archivingreferentiels'] = 'Le referentiel, les certificats et les activites sont exportees vers un fichier';

// 6.0
$string['aide_incompatible_competencesh'] = 'Competences incompatibles';
$string['aide_incompatible_competencesh_help'] = 'La liste des competence du referentiel importe est incompatible avec la version du referentiel installee...';
$string['aide_incompatible_codeh'] = 'Code incompatible';
$string['aide_incompatible_codeh_help'] = 'Le code du referentiel importe est incompatible avec la version du referentiel installee...';
$string['aide_incompatible_cleh'] = 'Cle incompatible';
$string['aide_incompatible_cleh_help'] = 'La cle du referentiel importe est incompatible avec la version du referentiel installe...';


// help
$string['format_archiveh'] = 'Archivage des activites et certificats';
$string['format_archiveh_help'] = 'L\'archive contient l\'ensemble des declarations
d\'activite et les fichiers attaches sous forme de page Web ou de fichier XML
à importer dans un portfolio de type Mahara.
Pour exporter des certificats susceptibles d\'être recuperes dans un logiciel de gestion
de scolarite il convient plutôt de passer par l\'onglet \'Exporter\' ou \'Imprimer\'';

$string['format_certificath'] = 'Archivage de competences certifiees';
$string['format_certificath_help'] = 'Les competences peuvent être exportees :
<br />au <b>format normal</b> : liste des items valides.
<br />au <b>format pourcentage</b> : les pourcentages d\'items valides consolides par Competence et Domaine.';

$string['aide_incompatible_competences'] = 'A definir';
$string['aide_incompatible_code'] = 'A definir';
$string['aide_incompatible_cle'] = 'A definir';
$string['incompatible_competences'] = 'Competences incompatibles';
$string['incompatible_cle'] = 'Cle incompatible';
$string['incompatible_code'] = 'Code incompatible';

$string['archiveh'] = 'Archiver un referentiel';
$string['archiveh_help'] = 'En archivant un referentiel vous produisez une sauvegarde de l\'ensemble
des declarations d\'activite de tous les utilisateurs selectionnes (avec fichiers attaches aux declarations).
<br />Cette sauvegarde ne peut pas être restauree dans un cours Moodle
mais selon le format choisi elle peut être affichee comme un site Web ou integree dans un portfolio.';

$string['light'] = 'Affichage leger du referentiel, sans poids ni empreintes';
$string['light_display'] = 'Affichage leger du referentiel';
$string['config_light'] = 'En fonction de la valeur de <i>referentiel_light_display</i>
les enseignants avec droit d\'edition peuvent ou pas autoriser l\'affichage des poids et empreintes du referentiel.
<br />
<br />Si <i>referentiel_light_display</i> vaut 0 les enseignants avec droit d\'edition peuvent selectionner le mode leger;
<br />Si <i>referentiel_light_display</i> vaut 1 les enseignants ne peuvent selectionner le mode leger,
mais le createur du cours peut modifier ce paramètre pour son cours ;
<br />Si <i>referentiel_light_display</i> vaut 2, l\'affichage leger est interdit et de plus ce paramètre est verrouille au niveau du site et donc n\'est pas modifiable au niveau du cours.';

$string['importetudianth'] = 'Importer des donnees de scolarite';
$string['importetudianth_help'] = 'Les donnees de scolarite sont indispensables pour l\'impression des certificats.
<br />
Afin d\'importer des donnees de scolarite dans le module Referentiel de Moodle
il faut commencer par proceder à un export au format CSV qui sera ensuite complete / modifie sous editeur
(avec un tableur).
<br />
Les donnees de scolarite que l\'on importe dans Moodle (par l\'onglet Importer) doivent contenir l\'identifiant attribue par Moodle à chaque utilisateur lors de la creation des comptes dans Moodle.
<br />1) Obtenir une sauvegarde contenant les identifiants Moodle des etablissements et des etudiants.
<br />Commencez par mettre à jour la liste des etablissements du cours (onglet <strong>Etablissements</strong>)
Puis affichez la liste de tous les <strong>etudiants</strong> du cours (onglet <strong>Etudiants</strong>).
<br /><b>N.B.</b> : Inutile de completer les champs manquants, vous le ferez hors de Moodle sous editeur.
<br />Exportez ensuite (onglet <strong>Exporter</strong>) les donnees de scolarite de votre cours au format CSV.
<br />2) Modifier cette sauvegarde avec un editeur (un tableur). Tous les champs marques \'INCONNU\'
peuvent être modifies sous editeur
<br /><b>Attention</b> : Champs à ne modifier sous aucun pretexte :
<pre> #id_etablissement  #id_etudiant  user_id    login </pre>
N.B : Il suffit qu\'un des trois champs \'#id_etudiant\', \'user_id\' ou \'login\' soit correctement renseigne pour chaque etudiant.
<br />3) Importer ce fichier de scolarite desormais à jour (onglet <strong>Importer</strong>, format CSV avec separateur \';\' )
<br />4) Exporter les certificats (onglet <strong>Certificat</strong>), soit au format PDF pour impression, soit au format CSV pour injection dans un logiciel de scolarite type Apogee.
';

$string['importtaskh'] = 'Importer des tâches';
$string['importtaskh_help'] = 'Il est possible d\'importer un fichier de tâches, soit dans le même cours que le fichier d\'origine (chaque tâche importee prend un nouveau numero), soit dans un cours different.
<br />Ne peuvent être importees que des donnees enregistrees au format XML.
< br />Plusieurs contraintes doivent être respectees :
<br />Le referentiel associe à l\'instance courante doit être identique au referentiel de la sauvegarde.
Autrement dit, si les cle sont non vides, elles doivent être identiques ; si elles sont vides les noms et codes doivent être identiques. Faute de quoi l\'importation est refusee.
<br />Les tâches importees sont associees à celui qui effectue l\'importation
<br />Les dates de debut et de fin ne sont pas mises à jour
<br />Les liens URL associes aux consignes doivent être enregistres avec une adresse absolue, sinon ils ne sont pas correctement recuperes.
<br />Les fichiers associes aux consignes ne sont pas charges, car ils n\'appartiennent pas necessairement au même cours.
Il est donc necessaire de les redeposer après importation des tâches.
<br />Aucune verification quant au doublonnage de tâches n\'est effectuee, les tâches importees prenant un nouveau numero dans la liste des tâches.
<br />Les tâches importees sont masquees.
';

$string['importreferentielh'] = 'Importer un referentiel';
$string['importreferentielh_help'] = 'Si vous disposez d\'une sauvegarde d\'une occurrence de referentiel
au format XML oou CSV (produite par la fonction symetrique Exporter)
vous pouvez charger celle-ci dans le cours Moodle.
<br />
Deux situations peuvent alors se produire :
<br />Soit c\'est un nouveau referentiel pour cet espace Moodle ; il devient disponible pour être associe à de nouvelles instances.
<br />Soit il existe dejà une version identique (même nom, même code) sur le serveur :
<br />Si vous avez choisi d\'ecraser la version existante, elle est remplacee par la version importee ;
<br />Si vous avez choisi de conserver la version existante l\'importation est interrompue ;
';

$string['importpedagoh'] = 'Importer un fichier des formations';
$string['importpedagoh_help'] = 'L\'injection des Formations / Pedagogies / Composantes se fait par importation d\'un fichier CSV ou XML.
<br />Format CSV, Separateur \';\'
<br />Entête :
<br />
#Moodle Referentiel pedagos CSV Export;;latin1;Y:2011m:03d:17;;;;;;
<br />
#username;firstname;lastname;date_cloture;promotion;formation;pedagogie;composante;num_groupe;commentaire;referentiel;
<br />
Donnees :
<br />
E001326S;Severine;DUPON;2011-06-01;2011;6252;FCI2EMD;919;;;C2i2e-2011
<br />
dupuis-d;David;DUPUIS;2011-06-01;2011;6252;FCI2EME;919;a123;;
<br />
...
<br />
Des valeurs non vides pour les champs username, promotion, formation, pedagogie, composante, date_cloture sont requises.
Les autres champs peuvent être vides.
<br />Format XML
<br />
&lt;?xml version=\'1.0\' encoding=\'UTF-8\'?&gt;
<br />
&lt;pedagogies&gt;
<br />
   &lt;pedago&gt;
<br />    &lt;username&gt;epsilon&lt;/username&gt;
<br />    &lt;firstname&gt;&lt;text&gt;Epsilon&lt;/text&gt;&lt;/firtsname&gt;
<br />    &lt;lastname&gt;&lt;text&gt;ESPADON&lt;/text&gt;&lt;/lastname&gt;
<br />    &lt;date_cloture&gt;&lt;text&gt;2011-06-01&lt;/text&gt;&lt;/date_cloture&gt;
<br />    &lt;promotion&gt;&lt;text&gt;2011&lt;/text&gt;&lt;/promotion&gt;
<br />    &lt;formation&gt;&lt;text&gt;DIC2I2E1M&lt;/text&gt;&lt;/formation&gt;
<br />    &lt;pedagogie&gt;&lt;text&gt;FCI2EME&lt;/text&gt;&lt;/pedagogie&gt;
<br />    &lt;composante&gt;&lt;text&gt;999&lt;/text&gt;&lt;/composante&gt;
<br />    &lt;num_groupe&gt;&lt;text&gt;a123&lt;/text&gt;&lt;/num_groupe&gt;
<br />    &lt;commentaire&gt;&lt;text&gt;Formation initiale&lt;/text&gt;&lt;/commentaire&gt;
<br />   &lt;/pedago&gt;
<br />(...)
<br />&lt;pedagogies&gt;
<br />Une fois les pedagogies creees ou importees il est possible d\'utiliser cette information pour selectionner les certificats à exporter...';

$string['exportpedagoh'] = 'Exporter le fichier des formations';
$string['exportpedagoh_help'] = 'Aide à rediger';

$string['exportetudh'] = 'Exporter des donnees de scolarite';
$string['exportetudh_help'] =  'Les donnees de scolarite sont indispensables pour l\'impression des certificats.
<br />
Afin de pouvoir par la suite importer des donnees de scolarite dans le module Referentiel de Moodle
il faut commencer par proceder à un export au format CSV qui sera ensuite complete / modifie sous editeur
(avec un tableur).
<br />
En effet les donnees de scolarite que l\'on importe dans Moodle (par l\'onglet Importer) doivent contenir l\'identifiant attribue par Moodle à chaque utilisateur lors de la creation des comptes dans Moodle.';


$string['exporttaskh'] = 'Exporter les tâches';
$string['exporttaskh_help'] = 'Aide à rediger';

$string['etablissementh'] = 'Etablissements';
$string['etablissementh_help'] = 'Aide à rediger';

$string['etudianth'] = 'Etablissements';
$string['etudianth_help'] = 'Aide à rediger';

$string['pedagoh'] = 'Formations / Pedagogies';
$string['pedagoh_help'] = 'Les administrateurs du site peuvent selectionner les certificats
des etudiants regulièrement inscrits dans une filière de formation habilitee à delivrer
le certificat concerne.
<br />La liste des formations peut être importee ou saisie.
<br />L\'importation permet aussi d\'associer directement les etudiants aux formations qui les concernent.';

$string['verroucertificath'] = 'Verrouiller les certificats';
$string['verroucertificath_help'] = 'Le certificat reflète l\'etat instantane
des competences validees dans les activites. Quand de nouvelles declarations sont validees,
les competences associees sont immediatement prises en compte dans le certificat.
<br />C\'est ce processus qu\'il faut verrouiller lorsque qu\'une campagne de certification
touche à sa fin, afin de preparer le dossier de certification à presenter au jury.';

$string['printh']= 'Formats d\'impression';
$string['printh_help']= '* PDF (Adobe ®) fournit un document fac-simile.
* RTF (Rich Text File) est un format pivot pour les traitements de texte.
* OpenOffice (ODT) est le format natif du traitement de texte Writer d\'OpenOffice.
* MSWord DOC est le format proprietaire du traitement de texte Word de Microsoft (version 2000).
* CSV avec separateur \';\' est un format destine aux tableurs.
* XHTML s\'affiche dans une page Web.';

$string['printcertificath'] = 'Imprimer les certificats';
$string['printcertificath_help'] = '* Commencez par selectionner les donnees qui seront imprimees en tenant compte du parametrage impose par l\'administrateur du site ou par le createur du referentiel à imprimer.
* Puis selectionnez le format d\'impression';

$string['exportcertificath'] = 'Exporter les certificats';
$string['exportcertificath_help'] = ' Cette fonction est destinee à fournir les resulats
de la certification aux systèmes de gestion de la scolarite.
<br />Commencer par selectionner les certificats à exporter
<br />Puis choisir le format de fichier :
<br />Le format CSV avec separateur \';\' est destine aux tableurs tels MSExcel ou OpenOffice-Calc.
<br />Le format XML destine à la sauvegarde en vue d\'une restauration ulterieure.
<br />Les format HTML et format XHTML s\'affiche comme une page Web.

<br />Vous devez ensuite choisir la liste des donnees exportees
<br />Non condense : toutes les donnees disponibles sot exportees
<br />Condense type 1 : login, nom, prenom, pourcentages par domaine, competence, item
<br />Condense type 2 : login, nom, prenom, liste des competences acquises
<br />Formation / pedagogie :  si disponibles ces informations sont ajoutees à l\'export.
';

$string['selectcertificath'] = 'Selectionner les certificats';
$string['selectcertificath_help'] = '<br />Les enseignants avec droit d\'edition peuvent selectionner les certificats correspondant aux etudiants du cours,
soit individuellement, soit par groupe, soit accompagnes...
<br />Les administrateurs peuvent de plus exporter dans un seul fichier tous les certificats
du site pour un referentiel donne.

<b>Utilisation des \'Promotions / Formations / Composantes / Pedagogies\'</b>
<br />Les administrateurs peuvent aussi selectionner les certificats des etudiants regulièrement
inscrits dans une filière de formation habilitee à delivrer le certificat concerne
en selectionnant par \'Promotion, Formation, Pedagogie, Composante\'... si ces donnees sont disponibles (onglet \'Formation\')';

$string['exportreferentielh'] = 'Exporter un referentiel';
$string['exportreferentielh_help'] = 'En exportant un referentiel
vous produisez une sauvegarde que vous pouvez restaurer dans votre cours Moodle
par la fonction symetrique Importer).';

$string['exportoutcomesh'] = 'Barème d\'objectifs';
$string['exportoutcomesh_help'] = 'Si les objectifs sont actives sur votre serveur Moodle
vous pouvez sauvegarder le referentiel sous forme d\'un barème d\'objectifs
puis utiliser ce barème pour evaluer toute forme d\'activite Moodle
(forums, devoirs, bases de donnees, wiki, etc.)
<br />Le module Referentiel recupèrera ces evaluations et genèrera des declarations qui seront dès lors accessibles dans la liste des activites du module referentiel.';

$string['exportactiviteh'] = 'Exporter les activites';
$string['exportactiviteh_help'] = 'Pour exporter les activites d\'une instance
du referentiel vous avez le choix entre plusieurs formats :
<br />Le format XML destine à la sauvegarde en vue d\'une restauration ulterieure (cette fonction n\'est pas implantee actuellement)
<br />Le format CSV est un format textuel de donnees tableur avec separareur \';\'
<br />Les formats XHTML et HTML s\'affichent comme une page Web.';

$string['uploadh'] = 'Documents';
$string['uploadh_help'] = 'Les documents attaches à une activite sont destines à fournir
des traces observables de votre pratique.
<br />
A chaque activite vous pouvez associer un ou plusieurs documents, soit en recopiant son adresse Web (URL),
soit en deposant un fichier dans l\'espace Moodle du cours.';

$string['documenth'] = 'Document';
$string['documenth_help'] = '<br />Description du document : Une courte notice d\'information.
<br />Type de document : Texte, Exe, PDF, ZIP, Image, Audio, Video, etc.
<br />URL :
<br />Adresse Web du document (ou fichier déposé par vos soins dans l\'espace Moodle).
<br />Titre ou une etiquette
<br />Fenêtre cible où s\'ouvrira le document
<br />Fichier depose depuis votre poste de travail.';

$string['configreferentielh'] = 'Configuration';
$string['configreferentielh_help'] = 'La configuration du module Referentiel concerne :
<br />Les donnees de scolarite
<br />La creation, l\'importation et la modification d\'un referentiel
<br />La possibilite d\'associer un referentiel existant à une nouvelle instance de celui-ci
<br />L\'impression des certificats...

<br />
En fonction de votre rôle vous avez accès à differents niveaux de configuration.
<br />
<b>N.B.</b> Un parametrage incoherent peut vous interdire de completer la creation d\'un nouveau referentiel ou de telecharger un referentiel existant.';

$string['configsiteh'] = 'Configuration au niveau du site';
$string['configsiteh_help'] = 'Au niveau du site Moodle la configuration incombe à l\'administrateur du serveur.
Un parametrage au niveau du site s\'impose à toutes les occurences et toutes les instances de tous les referentiels du site.';
$string['configrefglobalh'] = 'Configuration au niveau du referentiel';
$string['configrefglobalh_help'] = 'Au niveau de chaque referentiel, tout enseignant avec droit d\'edition disposant
du mot de passe ad hoc peut completer la configuration au niveau du referentiel lui-même.
<br />
Les choix de configuration s\'imposent alors de façon globale au referentiel concerne,
mais sans incidence sur les autres referentiels.';
$string['configreflocalh'] = 'Configuration au niveau de l\'instance';
$string['configreflocalh_help'] = 'Au niveau de chaque instance de referentiel les enseignants avec droit d\'edition peuvent
configurer chaque instance de certification d\'un referentiel,
si les parametrages de niveau superieur lui en laissent la possibilite...
En resume, la configuration au niveau du site dicte celle de chaque referentiel
au niveau global qui dicte celle au niveau de l\'instance...';

$string['selectreferentielh'] = 'Selectionner un referentiel ';
$string['selectreferentielh_help'] = 'Si des occurrences de referentiels sont dejà installees
sur votre serveur moodle vous pouvez en selectionnner une pour l\'associer à l\'instance de certification
que vous venez de creer.
<br />
En cochant \'Filtrer les occurrences selectionnees\' vous pouvez n\'afficher que les occurrences locales
(propres au cours où l\'instance est cree) ou bien acceder aux occurrences globales
(definies pour tous les cours du serveur).';

$string['suppreferentielh'] = 'Supprimer le referentiel ';
$string['suppreferentielh_help'] = 'Avant de supprimer un referentiel commencez par supprimer toutes ses instances dans tous les cours où celles-ci sont declarees.
<br />
<b>Attention</b> :
<br />Ne pas confondre la suppression d\'une instance (une activite de type certification definie au niveau d\'un cours
et qui pointe vers une occurrence de referentiel)
avec la suppression d\'une occurrence de referentiel (au niveau du site) ;
<br />La suppression d\'une occurrence de referentiel supprime aussi toutes les declarations d\'activite, tâches et certificats associes à celle-ci ;
<br />Une fois un referentiel supprime celui-ci n\'est plus disponible au niveau du site, à moins d\'être importe à nouveau. ';


$string['modifreferentielh'] = 'Modifier le referentiel ';
$string['modifreferentielh_help'] = 'Vous ne pouvez modifer une occurence de referentiel
que si vous en êtes l\'auteur initial ou si vous avez un rôle d\'administrateur du serveur.
<br />Tout modification au niveau d\'une occurrence de referentiel
est propagee à toutes les instances de celui-ci.
<br />En augmentant le nombre de domaines / competences / items d\'une occurrence
vous pouvez lui ajouter autant de rubriques correspondantes.';

$string['certificath']= 'Certificats';
$string['certificath_help']= 'La certification est le processus par lequel les competences sont declarees, evaluees, validees.
<br />
Le certificat fournit une vue instantanee
de l’avancement de l\'etudiant dans le processus de certification.
<br /> Un certificat contient :

<br />Une liste de competences validees pour le referentiel considere ; 
<br />La reference de l\'enseignant ayant valide le certificat ; 
<br />La date et la decison du jury quant à la certification ; 
<br />Une synthèse eventuelle redigee par l\'etudiant certife et un commentaire du formateur referent ; 
';
$string['certificat2h']= 'Validation d\'un certificat';
$string['certificat2h_help']= '<p>Quand un certificat est verrouille (liste de competences sur fond rose),
plus aucune competence ne peut s’y ajouter, le dossier de l\'etudiant etant considere comme clos.</p>
<p>Les item d\'empreinte nulle ne sont pas retenus dans la certification</p>
<p>Un Item de competence n\'est pris en compte dans la validation du certificat
que si le nombre de declarations validees pour cet item est superieur à l\'empreinte de l\'item.</p>
<p>Pour permettre d\'apprecier le chemin qui reste à faire pour obtenir une competence ou un domaine de competences,
des notes sont affichees par competence et par domaine sous forme de pourcentages.</p>

<br />Au niveau de l\'Item :<br />100% * NOMBRE_ITEM_VALIDE / ENPREINTE_ITEM
<br />Au niveau de la competence :<br />100 * SOMME_SUR_ITEMS_COMPETENCE(NOMBRE_ITEM_VALIDE / POIDS_ITEM) / SOMME_SUR_ITEMS_COMPETENCE(POIDS_ITEM * ENPREINTE_ITEM)
<br />Au niveau du domaine :<br />100% * SOMME_SUR_ITEMS_DOMAINE(NOMBRE_ITEM_VALIDE / POIDS_ITEM) / SOMME_SUR_ITEMS_DOMAINE(POIDS_ITEM * ENPREINTE_ITEM)

<p>Autrement dit la contribution d\'un Item à la note de la competence et du domaine qui le contiennent
est proportionnelle au produit POIDS * EMPREINTE.</p>
';

$string['referentielh'] ='Referentiel';
$string['referentielh_help'] ='Un referentiel de competence est une hierarchie d’objets :
<pre>Referentiel
   Domaine 1 (Code, Description)
      Competence 1.1 (Code, Description)
         Item 1.1.1 (Code, Description, Poids)
         Item 1.1.2 (Code, Description, Poids)
      Competence 1.2 (Code, Description)
         Item 1.2.1 (Code, Description, Poids)
         Item 1.2.2 (Code, Description, Poids)
         Item 1.2.3 (Code, Description, Poids)
   Domaine 2 (Code, Description)
      Competence 2.1 (Code, Description)
         Item 2.1.1 (Code, Description, Poids)
         Item 2.1.2 (Code, Description, Poids)
...
</pre>
<br />Pour que le referentiel soit operationnel,
il doit comporter au moins un domaine, une comperence, un item
';

$string['referentiel2h'] ='Domaine / Competence / Item';
$string['referentiel2h_help'] ='

  <br /><b>Nom</b> [<em>obligatoir</em>e] : C\'est l\'identite du referentiel.
  <br /><b>Code</b> [<em>obligatoire</em>] : C\'est une etiquette commode pour identifier
    le referentiel.
  <br /><b>URL</b> [<em>facultatif</em>] : La reference sur le Web de l\'initiateur
    du referentiel (par exemple le N° d\'un Bulletin officiel de l\'Education nationale
    française...).
  <br /><b>Seuil de certification</b> [<em>valeur decimale ; facultatif</em>] :
    Si un barème et des notes sont associees à l\'obtention de chaque competence,
    le seuil est la valeur au delà de laquelle l\'impetrant est certifie.
  <br /><b>Referentiel global</b> [<em>oui / non ; par defaut oui</em>] : Un referentiel
    global est accessible depuis d\'autres cours que celui où il a ete cree.
  <br /><b>Nombre de domaines à enregistrer pour ce referentiel</b> [<em>valeur
    numerique ; obligatoire</em>] : Le nombre de domaines à creer. <br />
    <em>On peut demarrer à un et augmenter par la suite cette valeur pour definir
    de nouveaux domaines.</em>
  <br /><b>Liste des codes</b> [<em>genere ; obligatoire</em>] : les codes des items associes. <br />
    <em>Cette liste est calculee à partir des items de competence.</em>
  <br /><b>Liste des poids</b> [<em>genere ; obligatoire</em>] : les poids des items associes. <br />
    <em>Cette liste est calculee à partir des items de competence.</em>
  <br /><b>Liste des empreintes</b> [<em>genere ; obligatoire</em>] : les empreintes des items associes. <br />
    <em>Cette liste est calculee à partir des items de competence.</em>
';

$string['domaineh'] ='Domaine';
$string['domaineh_help'] ='

  <br /><b>Code</b> [<em>obligatoire</em>] : C\'est une etiquette commode pour identifier
    le domaine.
  <br /><b>Description</b> [<em>obligatoire</em>] : Le texte qui definit le domaine,
    en termes de savoir, savoir faire, competences, capacites, habiletes, rôle,
    attitude, etc..
  <br /><b>Numero</b> [<em>valeur numerique ; obligatoire</em>] : Le numero d\'ordre
    du domaine dans la hierarchie des objets du referentiel. <br />
    Les domaines sont affichees dans l\'ordre croissant de ce numero. 
  <br /><b>Nombre de competences à enregistrer pour ce domaine</b> [<em>valeur numerique
    ; obligatoire</em>] : Le nombre de competences à creer pour ce domaine. <br />
    <em>On peut demarrer à un et augmenter par la suite cette valeur pour definir
    de nouvelles competences</em>.';

$string['competenceh'] ='Competence';
$string['competenceh_help'] ='

  <br /><b>Code</b> [<em>obligatoire</em>] : C\'est une etiquette commode pour identifier
    la competence.
  <br /><b>Description</b> [<em>obligatoire</em>] : Le texte qui definit la competence,
    en termes de savoir, savoir faire, competences, capacites, habiletes, rôle,
    attitude, etc..
  <br /><b>Numero</b> [<em>valeur numerique ; obligatoire</em>] : Le numero d\'ordre
    de la competence dans la liste des competences de ce domaine. <br />
    Les competences sont affichees dans l\'ordre croissant de ce numero. 
  <br /><b>Nombre d\'items à enregistrer pour cette competence</b> [<em>valeur numerique
    ; obligatoire</em>] : Le nombre d\'items inclus dans cette competences. <br />
    <em>On peut demarrer à un et augmenter par la suite cette valeur pour definir
    de nouveaux items</em>.  ';
$string['itemh'] ='Item';
$string['itemh_help'] ='

  <br /><b>Code</b> [<em>obligatoire</em>] : C\'est une etiquette commode pour identifier
    l\'item. <em>Il convient de veiller à l\'unicite du code des items car tout
    le referentiel repose sur cette propriete, </em>autrement dit <em>il faut
    eviter de designer par le même code deux items appartenant à des competences
    differentes</em> du même referentiel :) ! 
  <br /><b>Description</b> [<em>obligatoire</em>] : Le texte qui definit l\'item,
    en termes de savoir, savoir faire, competences, capacites, habiletes, rôle,
    attitude, etc..
  <br /><b>Type d\'item [Obligatoire, Optionnel]</b> [<em>facultatif </em>] : Caracterisation
    de l\'importance de l\'item dans la certification.<br />
    Si l\'item n\'est pas obligatoire pour l\'obtention du certificat laisser vide
    ou utiliser &quot;Optionnel&quot;.
  <br /><b>Poids de l\'item (valeur numerique decimale) </b> : Coefficient affecte à l\'item dans le calcul de
    la note de certification. La formule de calcul prend en compte les
    poids et les empreintes des differents items.
  <br /><b>Empreinte de l\'item (valeur numerique entière; [0..999]) </b> : L\'empreinte determine le nombre d\'activites qui doivent pointer la dite
 competence pour que celle-ci soit validee. 
  <br /><b>Numero</b> [<em>valeur numerique ; obligatoire</em>] : Le numero d\'ordre
    de l\'item dans la liste des items de la competence contenant cet item. <br />
    Les items sont affichees dans l\'ordre croissant de ce numero. ';

$string['referentielinstanceh'] ='Instance de certification';
$string['referentielinstanceh_help'] ='Une instance de certification est une activite au sens Moodle (comme le sont \'Devoir\', \'Forum\' ou \'Leçon\') s\'appuyant sur un referentiel et qui permet aux elèves / etudiants :
<br />de declarer des activites mobilisant des savoir-faire ou habiletes qui seront evaluees par les enseignants
<br />de pointer les competences en regard d\'une \'occurrence de referentiel\'. ';


$string['taskh']= 'Tâches';
$string['taskh_help']= 'Les tâches sont des realisations ou des activites proposees par les enseignants aux etudiants de leur cours.

Seuls les enseignants peuvent creer des tâches
*   Titre de la tâche : pour faciliter son reperage par les etudiants
*   Description : Indiquez ici le contexte et les objectifs de la tâche
*   Competences mobilisees par cette tâche : Celles que l\'accomplissement de la tâche legitime
*   Consignes et critères de reussite afin de faciliter la realisation de la tâche
*   Documents attaches
*   Vous pouvez joindre des documents pour illustrer la tâche ou la cadrer ';

$string['accompagnementh']= 'Accompagnement et notification';
$string['accompagnementh_help']= 'L\'accompagnement consiste à designer un ou plusieurs enseignants en regard de chaque etudiant du cours. Ce faisant vous repartirez la charge de suivi des etudiants.
Cela influe en particulier la notification des nouvelles activites.
Il faut toutefois noter que l\'affectation d\'un referent à un etudiant n\'empêche pas les autres enseignants du cours de voir les declarations de cet etudiant et d\'eventuellement les evaluer.';

$string['notificationh']= 'Notification';
$string['notificationh_help']= 'La notification consiste à faire envoyer par Moodle un mèl d\'information reprenant l\'essentiel des declarations d\'activite ou des tâches, sur le modèle de ce qui se pratique pour les messages d\'un forum Moodle
Le createur d\'une activite doit demander explicitement la notification de celle-ci en cochant un bouton avant l\'enregistrement son enregistrement.
Si des referents sont affectes à un etudiant, ils seront les seuls à recevoir la notifications des nouvelles activites declarees par celui-ci ;
Si par contre aucun referent n\'est affecte à un etudiant, toute notification d\'une nouvelle activite sera adressee à tous les enseignants du cours ;';

$string['activiteh']= 'Activites';
$string['activiteh_help']= 'Plutôt que de declarer des competences item par item l’etudiant peut pointer ses competences en declarant des activites.
Une activite contient une partie declarative redigee par l\'etudiant, elle pointe une liste de competences de ce referentiel et un nombre illimite de documents peuvent être associes à une activite.';'
Pour que les competences declarees dans une activite soient prises en compte pour la certification cette activite doit être validee par un enseignant.';

$string['creer_activiteh']= 'Creer une nouvelle activite';
$string['creer_activiteh_help']= '*     Type d\'activite : En typant une activite vous facilitez son reperage par les enseignants.
*     Description : Indiquez ici le contexte et les objectifs de l\'activite
*     Competences mobilisees par cette activite : Celles que l\'activite legitime.
*     Documents : Pour fournir des traces observables de votre pratique, citez un ou plusieurs documents.';

$string['creer_documenth']= 'Document attache';
$string['creer_documenth_help']= '*        Description du document : Une courte fiche de presentation
*         Type de document [Texte, Exe, PDF, ZIP, Image, Audio, Video, etc.] : Afin d\'en faciliter l\'affichage.
*         URL : Copiez l\'adresse Web du document
*         Vous pouvez aussi deposer le document dans l\'espace Moodle';

$string['name_instanceh']= 'Titre de l\'instance ';
$string['name_instanceh_help']= 'Le titre est celui affiche pour decrire l\'activite, par exemple \'Declarez vos competences C2i\'';

$string['formath']= 'Formats d\'importation / exportation';
$string['formath_help']= '* XML est un format textuel de donnees representant l\'information comme une hierarchies de balises et de donnees imbriquees. Il permet la sauvegarde et la restauration  de referentiels, d\'activites et de certificats.
* Le format CSV est un format textuel de donnees tabulaires (comme dans un tableur).
Le format CSV utilise un separateur, en general \';\' entre les cellules de la table.
* Les formats HTML et XHTML s\'affichent comme des pages Web.';

$string['overrider']= 'Remplacer un referentiel';
$string['overrider_help']= 'Si l\'occurrence du referentiel importe existe dejà sur le serveur
(même nom, même code) elle est alors remplacee par la version importee
si vous avez choisi d\'ecraser la version existante, sinon l\'importation est interrompue.';

$string['overrideo']= 'Remplacer l\'occurrence associee';
$string['overrideo_help']= '**Attention** : l\'instance courante de ce cours est dejà
associee à une occurrence de referentiel.
Faut-il remplacer celle-ci par la version importee ?
Sinon l\'importation sera interrompue.';

// End Help strings

// Version 5.6.02
$string['date_proposition']= 'Date de la proposition';
$string['confirmdeletepedagos']= 'Confirmer la suppression des formations enregistrees';
$string['vider_pedagos']= 'Supprimer toutes les formations et pedagogies associees';

$string['precedent']= 'Page precedente';
$string['suivant']= 'Page suivante';

$string['exportedoutcomes']= 'Exported outcomes\' files';
$string['exportedtasks']= 'Exported tasks\' files';
$string['exportedpedago']= 'Exported formations\' files';
$string['exportedetud']= 'Exported scolarship\' files';
$string['exportedreferentiel']= 'Exported Skills repository\' files';
$string['exportedactivities']= 'Exported activities\' files';
$string['exportedcertificates']= 'Fichiers de certificats exportes';
$string['printedcertificates']= 'Fichiers d\'impression de certificats';

$string['filename']= 'File';
$string['timecreated']= 'Created the';
$string['timemodified']= 'Modified the';
$string['mimetype']= 'MIME';
$string['filesize']= 'Size (bytes)';

$string['forcer_regeneration']= 'Forcer la regeneration des certificats';
$string['firstname'] = 'Firstname';
$string['lastname'] = 'Lastname';
$string['certiftraite'] = ' certificates ...';

$string['verroucertificat'] = 'Lock / Unlock certificates';
$string['verrou_all'] = 'To lock everything';
$string['deverrou_all'] = 'To unlock everything';
$string['verroucertif'] = 'Bolts';
$string['selectverroucertificat'] = 'Select certificates to lock / unlock';


$string['nondefini']= 'Not defined';
$string['onlyteachersimport']= 'Teachers only can import ';
$string['creer_nouveau_referentiel'] = 'Create a new Skills repository';
$string['submissions'] = 'Submissions';
$string['availabledate'] = 'Available Date';
$string['maximumsize'] = 'Size Max';
$string['invalidid'] = 'Invalid ID';
$string['invalidreferentiel'] = 'Invalid Skills repository';

$string['pluginname'] = 'Skills repository';
$string['pluginadministration'] = 'Skills repository administration';
$string['name_instance_obligatoire'] = 'Skills repository instance is mandatory';

// Version 5.5.5
// A TRADUIRE
$string['selectcertificatprint'] = 'Select certificates to print';
$string['pedagos'] = 'Formations';
$string['pedago'] = 'Formation';
$string['selectcertificat'] = 'Select certificates to export';
$string['cocher'] = 'Check';
$string['exportcertifpedagos'] = 'Select formations / pedagogies to export';
$string['nbcertifs'] = 'Nb. of certificates';
$string['aide_selection_pedago'] = 'Filter the formations then mark those whose certificates
will be exported.<br />Attention: certain combinations can be incompatible!';
$string['aide_association'] = 'match a formation to each student';
$string['pedagogie'] = 'Pedagogy';
$string['listpedago'] = 'Formations';
$string['creer_pedago'] = 'Record a new formation / pedagogy';
$string['addpedago'] = 'Create';
$string['listasso'] = 'Associations';
$string['formation'] = 'Formation';
$string['exportpedago'] = 'Export';
$string['exportingpedagos'] = 'Formations / pedagogies exported in a file';
$string['exportpedagos'] = 'Select any formations';
$string['exportpedagogies'] = 'Export formation / pedagogy data';
$string['importpedagogies'] = 'Import formation / pedagogy data';
$string['importpedagos'] = 'Import formation / pedagogy data';
$string['importpedago'] = 'Import';
$string['composante'] = 'Constituent';
$string['promotion'] = 'Promotion';
$string['editpedago'] = 'Modify';
$string['editasso'] = 'Associate';
$string['num_groupe'] = 'Group / taught discipline';
$string['date_cloture'] = 'Close of certification process';
$string['modifier_pedago'] = 'Modify the index card of formation {$a}';
$string['nopedago'] = 'Not yet any formation / pedagogy';

// Version 5.4.12
$string['graph'] = 'Forbid to show the certification graphs';
$string['statcertif'] = 'Graph';
$string['certification_etat'] = 'State of skills validation';
$string['code_items'] = 'Items';
$string['poids'] = 'Weights';
$string['moyenne'] = 'Average';
$string['seuil'] = 'Threshold';
$string['select_aff_graph'] = 'Forbid to show the graph of certificates validated at instance level';
$string['config_aff_graph'] = 'According to the value of <i>referentiel_affichage_graphique</i>
teachers with editing role can or not authorize the display of the graphs of certification.

<br />If <i>referentiel_affichage_graphique</i> the teachers with  editing role are worth 0 can authorize this display;
<br />if <i>referentiel_affichage_graphique</i> is worth 1 the teachers cannot authorize this display,
but the creator of the course can modify this parameter for his course;
<br />If <i>referentiel_affichage_graphique</i> is worth 2, the display is forbidden and this parameter
is locked at the level of the site and thus is not modifiable at the level of the course.
';

// version 5.4.10
$string['format_reduit1'] = ' Type 1 ';
$string['format_reduit2'] = ' Type 2 ';

// version 5.4.8
$string['not_activite_tache_1'] = '<i>All course\'s students linked to this task will get this mail !</i>';
$string['not_activite_tache_2'] = '<i>All course\'s teachers linked to this task will get this mail !</i>';
$string['not_activite_tache_3'] = '<i>All course\'s students and teachers linked to this task will get this mail !</i>';

$string['not_tache_1'] = '<i>All course\'s students will get this mail !</i>';
$string['not_tache_2'] = '<i>All course\'s teachers will get this mail !</i>';
$string['not_tache_3'] = '<i>All course\'s students and teachers will get this mail !</i>';


$string['referentiels'] = 'Skills repositories';


$string['login'] = 'Login';
$string['pdf'] = 'PDF Format';
$string['rtf'] = 'RTF Format';
$string['msword'] = 'MSWord Format';
$string['ooffice'] = 'OpenOffice Format';
// version 5.4.3
$string['ressources_associees'] = 'Yet {$a} documents linked to this activity';
$string['ressource_associee'] = 'Yet {$a} documents linked to this activity';
$string['notification_commentaire'] = 'Mail this commentary';
$string['savedoc'] = 'Modify';
$string['num'] = 'N° ';

// version 5.4.3
$string['release'] = 'N° ';
$string['modifier_instance_referentiel'] = 'Modify the skills repository instance <i>{$a}</i> ';
$string['referentiel_config_local_interdite'] = '<b>Warning</b>
<br />Settings for configuration at instance level have been desabled for that Skills Repository.';
$string['aide_referentiel_config_local_impression'] = '<b>Warning</b>
<br /><i>These settings give the list of data to be printed on certificates.</i>';
$string['referentiel_config_impression_local_interdite'] =  '<b>Warning</b>
<br /><i>Settings for printing at instance level have been desabled for that Skills Repository.</i>';
$string['pass_referentiel_admin'] = 'Key in a password to protect this skills repository';
$string['configref']= 'Settings';
$string['referentiel_config_global_impression'] = 'Certificates print settings ';
$string['configurer_referentiel'] = 'Settings for the skills repository {$a} at global level ';
$string['referentiel_config_global'] = 'Skills repository Settings ';
$string['aide_referentiel_config_global'] = 'The settings for configuration and printing depend
on settings defined :
<br /> first at the site level by Moodle administrator
<br /> then at the global level by the skills repository authos
<br /> then at the instance level.
An inconsistent parameter setting can forbid you to complete
the creation of a new skills repository or to download an existing one.';

// version 5.3.7
$string['consignes']= 'Instructions';
// version 5.3.5
$string['ajouter_domaines'] = 'Increase this value to add new domains to this skills repository...';
$string['ajouter_competences'] = 'Increase this value to add new skills to this domain...';
$string['ajouter_items'] = 'Increase this value to add new items to this skill...';
$string['importfileupload_xml_simple'] = 'Upload a simple XML file (\'_sxml.xml\' extension )...';
$string['importfilearea_xml_simple']= 'Import from a simple XML course file (\'_sxml.xml\' extension )...';
$string['assigner'] = 'Assign a task';
// version 5.3.4
$string['uploadproblemfile'] = 'File download error';
$string['formatincompatible'] = 'Downloaded file does not match selected format';

$string['editer_referentiel'] = 'Edit a skills repository';
$string['editeur_referentiel'] = 'XML Editor';
$string['editer_referentiel_xml'] = 'Edit a simplified XML skills repository';
$string['charger_referentiel_xml'] = 'Load a simplified XML skills repository';
$string['import_referentiel_xml'] = 'Import a simplified XML skills repository';

// version 5.3.3
$string['aide_referentiel_config_local'] = 'The parameters of configuration and printing at the level of the skills repository instance depend
on parameters defined at the level of the site by the administrator of Moodle. An inconsistent parameter setting can forbid you to complete
the creation of a new skills repository or to download an existing one.';
$string['not_verrou'] = 'Not locked';
$string['filtre_auteur'] = 'Order';
$string['filtre_verrou'] = 'Locked';
$string['filtre_date_decision'] = 'Decision date';
$string['decision_favorable'] = 'Receipt';
$string['decision_defavorable'] = 'Adjourned';
$string['decision_differee'] = 'In wait';
$string['rediger_decision'] = 'Draft the decision of the jury';

$string['saisie_synthese_certificat'] = 'Draft your synthesis';
$string['synthese_certificat'] = 'Synthesis';
$string['synthese'] = 'Synthesis';
$string['aide_synthese_certificat'] = 'The synthesis is a short paper presenting the context in which was made the certification.';
// version 5.3.2
$string['format_condense'] = 'Print to condensed format ';

// version 5.3.1
$string['activites_tache_delete'] = 'Select / unselect activities to delete...';
$string['delete_all_activity_task_closed'] ='That task is closed. Delete all activities linked with that task ? ';
$string['delete_all_activities_task'] = 'Delete all activities linked with that task ?';
$string['delete_all_task_associations'] = 'Delete task and mapped activities';
$string['confirmdeleterecord_all_activities'] = 'Confirm task {$a} deletion<b>with all mapped activities...</b>';

// version 5.2.5
$string['ref_course'] = 'Course ID';
$string['teacherid'] = 'Teacher ID';
$string['description_document'] = 'Description';
$string['ref_activite'] = 'Activity ID';

// version 5.2.4
$string['exportallcertificates'] = 'Export all the certificats';
// version 5.2.3
$string['date_modif_by'] = 'by teacher the ';
$string['date_modif_student_by'] = 'by author the  ';
$string['select_acc'] = 'Accompanied Students';
$string['select_all'] = 'Check all';
$string['select_not_any'] = 'Uncheck all';
$string['REF'] = 'Referee';
$string['ACC'] = 'Tutor';
$string['aide_accompagnement'] = 'Students matched to referees';
$string['liste_accompagnement'] = 'Students / Referees';
$string['eleves'] = 'Students';
$string['addreferent'] = 'Add a referee';
$string['notmatched'] = 'Not any referee for this student';
$string['noaccompagnement'] = 'Not any accompaniment registered for this referee';
$string['referents'] = 'Referees';
$string['type_accompagnement'] = 'Accompaniment Type';

$string['accompagnement'] = 'Accompaniment';
$string['listaccompagnement'] = 'List';
$string['manageaccompagnement'] = 'Modify';
$string['addaccompagnement'] = 'Match';
$string['tache_masquee_num'] = 'Hidden task number a ';
$string['tache_masquee'] = 'Hidden Task';
$string['taskiscurrentlyhidden'] = 'Hidden task';
$string['un_enseignant'] = 'a teacher of this course';

$string['referentiel:addactivity']='Add an activity';
$string['referentiel:exportcertif']='Export all the certificates';
$string['referentiel:addtask']='Add a task';  	  	  	  	  					
$string['referentiel:approve'] = 'Approve';
$string['referentiel:comment'] = 'Comment';
$string['referentiel:export'] = 'Export a skills repository';
$string['referentiel:import'] = 'Import a skills repository';
$string['referentiel:managecertif'] = 'Manage certificates';
$string['referentiel:managecomments'] = 'Manage comments';
$string['referentiel:managescolarite'] = 'Manage scolarship';
$string['referentiel:rate'] = 'Approve an activity';
$string['referentiel:select'] = 'Select an activity';
$string['referentiel:selecttask'] = 'Select a task';
$string['referentiel:view'] = 'View skills repository';
$string['referentiel:viewrate'] = 'View rates';
$string['referentiel:viewscolarite'] = 'View scolarship data'; 									
$string['referentiel:viewtask'] = 'View tasks'; 									
$string['referentiel:write'] = 'Modify activities';
$string['referentiel:writereferentiel'] = 'Write skills repository';
$string['referentiel:archive'] = 'Export archive (certificates and activities)';

// version 5.1.1
$string['subscribed_task'] = 'Subscribed task';

// version 5.1.0
$string['activer_outcomes'] = 'First ask to admin to enable outcomes(also known as Competencies, Goals, Standards or Criteria) means that we can grade things using one or more scales that are tied to outcome statements. Enabling outcomes makes such special grading possible throughout the site.';
$string['help_outcomes'] = '0utcomes(also known as Competencies, Goals, Standards or Criteria) makes such special grading possible throughout the site.';

$string['scale'] = 'Scale';
$string['outcome'] = 'Outcome';
$string['outcomes'] = 'Outcomes';
$string['export_bareme'] = 'Scale and Outcomes';
$string['export_outcomes'] = 'Export skills repository as scale and outcomes';
$string['outcome_type'] = 'Activity';
$string['outcome_date'] = ' Date: ';
$string['outcome_description'] = '{$a->name} : {$a->description} ';
$string['description_inconnue'] = 'No description';
$string['nom_bareme'] = 'Item skills repository';
$string['bareme'] = 'Not applicable,Not validated,Validated';
$string['description_bareme'] = 'This scale is for outcomes evaluation of skills repository module.';
// version 4.4.1
$string['choix_web_link'] = 'You may paste a Web link';
$string['choiximportfilearea'] = 'Or import from a course file......';
$string['choiximportfileupload'] = 'Or upload a new file...';

$string['processingdigest'] = 'Processing email digest for user {$a}';
$string['activites'] = 'Activities';
$string['notification_certificat'] = 'Mail this certificate';
$string['notification_tache'] = 'Mail this task';
$string['notification_activite'] = 'Mail this activity';

$string['digestmailheader'] = 'This is your daily digest of the {$a->sitename} skills repositories. To change your email preferences, go to {$a->userprefs}.';
$string['digestmailprefs'] = 'your user profile';
$string['digestmailsubject'] = '{$a}: skills repository digest';
$string['digestsentusers'] = 'Email skills repositories digests successfully sent to {$a} users.';
$string['postincontext'] = 'See this post in context';
$string['postmailinfo'] = '{$a->type} from Skills repository module (referentiel) on server {$a->site}. To read, click this link:';
$string['bynameondate'] = 'by {$a->name} - {$a->date}';

$string['valide_empreinte'] = 'validated [/footprint]';
$string['competence_certifiee'] = 'Certificated competencies (0:No 1:Yes)';
// version 4.2.3
$string['cocher_enregistrer'] = 'Only selected activities will be registered...';
$string['activites_tache'] = 'Select / unselect activities to validate...';
// version 4.2.2
// To translate
$string['aide_souscription'] = 'Select students to link with this task.';
$string['cle_souscription'] = 'Subscription key';
$string['mode_souscription'] = 'Subscription modalities';
$string['souscription_forcee'] = 'Impose subscription';
$string['aide_souscription_forcee'] = '<i>Link this task to a choosen audience.</i>';
$string['aide_souscription_cle'] = '<i>To restrict the subscription, enter a key to diffuse to the concerned audience.</i>';
$string['obtenir_cle_souscription'] = ' <i>Ask </i>{$a}<i> to get the key...</i>';
$string['souscription'] = 'Subscription';
$string['souscription_libre'] = 'Free subscription ';
$string['souscription_restreinte'] = 'Subscription with key';
$string['libre'] = 'Free';
$string['avec_cle'] = 'With key';
$string['cle'] = 'Key';

// version 4.2.1 
$string['selection_champs_etudiant'] = 'Select student\'s items to print';
$string['items'] = 'Items';
$string['certificat_sauver_parametre'] = 'Save parameters';
$string['config_sauver_parametre'] = 'Save these parameters for next print';
$string['detail_referentiel'] = 'Detailled Repositiory';

$string['pourcentage'] = 'Percentages';
$string['referentiel_config_local_impression'] = 'Print Configuration of certificates';
$string['refcert'] = 'Print the skills repository as certificate header.';
$string['instcert'] = 'Print skills repository instance as certificate header.';
$string['numetu'] = 'Print student number.';
$string['nometu'] = ' &nbsp; &nbsp; &nbsp; student firstname and lastname.';
$string['etabetu'] = ' &nbsp; &nbsp; &nbsp; school name.';
$string['ddnetu'] = ' &nbsp; &nbsp; &nbsp; student birthdate.';
$string['lieuetu'] = ' &nbsp; &nbsp; &nbsp; student birth place.';
$string['adretu'] = ' &nbsp; &nbsp; &nbsp; student adress.';
$string['detail'] = 'Print detailled competencies data.';
$string['pourcent'] = ' &nbsp; &nbsp; &nbsp; competencies and domain results by percentages.';
$string['compdec'] = ' &nbsp; &nbsp; &nbsp; competencies list from activities.';
$string['compval'] = ' &nbsp; &nbsp; &nbsp;  competencies list from certificated.';
$string['nomreferent'] = ' &nbsp; &nbsp; &nbsp; teacher name.';
$string['jurycert'] = ' &nbsp; &nbsp; &nbsp; jury decision.';
$string['comcert'] = ' &nbsp; &nbsp; &nbsp; teacher\'s comments.';

$string['selection_champs_certificat'] = 'Select certificate dat to print.';
$string['certificat_sel_print_format'] = 'Select print format';
$string['certificat_sel_referentiel'] = 'Pository description';
$string['certificat_sel_referentiel_instance'] = 'Pository Intance';
$string['certificat_sel_etudiant'] = 'Students data';	
$string['certificat_sel_etudiant_numero'] = 'Student number';
$string['certificat_sel_etudiant_nom_prenom'] = 'Student firtname and lastname';
$string['certificat_sel_etudiant_etablissement'] = 'Student school';
$string['certificat_sel_etudiant_ddn'] = 'Student birthdate';
$string['certificat_sel_etudiant_lieu_naissance'] = 'Lieu de naissance de l\'etudiant';
$string['certificat_sel_certificat'] = 'Certificate data';
$string['certificat_sel_certificat_detail'] = 'Detailled Certificate data';
$string['certificat_sel_certificat_pourcent'] = 'Results in percentages';
$string['certificat_sel_activite_competences'] = 'Declared Competencies';
$string['certificat_sel_certificat_competences'] = 'Certificated Competencies';
$string['certificat_sel_certificat_referents'] = 'Teacher name';
$string['certificat_sel_decision_jury'] = 'Juru decision';
$string['certificat_sel_commentaire'] = 'Commentaries and Synthesis';
$string['certificat_sel_etudiant_adresse'] = 'Student Adress';	

$string['config_certificat_sel_referentiel'] = 'Append skills repository to certificate header.';
$string['config_certificat_sel_referentiel_instance'] = 'Append instance of skills repository to certificate header.';
	
$string['config_certificat_sel_etudiant_nom_prenom'] = 'Append student name to certificate.';
$string['config_certificat_sel_etudiant_numero'] = 'Append student number.';
$string['config_certificat_sel_etudiant_etablissement'] = 'Append scholl name.';
$string['config_certificat_sel_etudiant_ddn'] = 'Append student birthday.';
$string['config_certificat_sel_etudiant_lieu_naissance'] = 'Append student birtplace.';

$string['config_certificat_sel_certificat_detail'] = 'Append to certificate Inclure dans le certificat  l\'enonce detaille des competences.';
$string['config_certificat_sel_certificat_pourcent'] = 'Append domains and competencies percentages.';
$string['config_certificat_sel_activite_competences'] = 'Append list of competencies declared in activities.';
$string['config_certificat_sel_certificat_competences'] = 'Append list of competencies validated.';
$string['config_certificat_sel_certificat_referents'] = 'Append teacher name.';
$string['config_certificat_sel_decision_jury'] = 'Append jury decision.';
$string['config_certificat_sel_commentaire'] = 'Append teachers\' comments.';
$string['config_certificat_sel_etudiant_adresse'] = 'Append student adress.';

// version 4.2.0
$string['cible_link'] = 'Open that link in a new window';
$string['etiquette_document'] = 'Document title';
$string['etiquette_consigne'] = 'Hint title';
$string['creer_activite_teacher'] = '<i>You are going to declare an activity for yourself as teacher...</i>.';

// version 4.1.2
$string['competences_declarees'] = 'Declared Competencies in Activities by {$a} ';
$string['tous'] = 'All';
$string['nocommentaire'] = 'No comment';
$string['suivi'] = 'Followed by';
$string['non_examine'] = 'No';
$string['examine'] = 'Yes';
$string['croissant'] = 'Increasing order';
$string['decroissant'] = 'Decreasing order';
$string['competences_declarees'] = 'Competencies declared in activities by {$a} ';

// A traduire ou retraduire
// version 4.0.1
$string['zero_activite'] = 'Not any activity declared at this time {$a} by ';
$string['menu'] = 'Menu';
$string['activite_exterieure'] = 'Other course... ';
$string['id_activite'] = 'Activity <i>{$a}</i>';
$string['date_modif'] = 'Modified by teacher the ';
$string['date_modif_student'] = 'Modified by author the ';
$string['listactivityall'] = 'Details';
$string['listactivitysingle'] = 'Details';
$string['filtre_validation'] = 'Validation';
$string['filtre_date_modif'] = 'Folow-up Date';
$string['filtre_date_modif_student'] = 'Author Date';

// version 3.3.4
$string['evaluation_par'] = 'Evaluation by';
$string['web_link'] = 'Paste a Web Link';

// version 3.2.5
$string['import_task'] = 'Import a task';
$string['export_task'] = 'Export a task';
$string['importtasks'] = 'Import tasks';
$string['exporttasks'] = 'Export tasks';
$string['incompatible_task'] = 'This skills repository does not match with loaded tasks...';

$string['erreur_creation'] = 'Creation error: missing data (Name and Code mandatory...). Retry.';
$string['exporttask'] = 'Export tasks ';
$string['exportingtasks'] = 'Tasks are exported to a file';
$string['notask'] = 'Not any task registered ';
$string['instance'] = 'Instance';

// version 3.2.4
$string['filtrer'] = 'Filter';

// version 3.2.3
$string['supprimer_referentiel'] = 'Delete this skills repository';
$string['suppression_non_autorisee'] = 'You can\'t delete this skills repository';
$string['cours_externe'] = 'In other course';
$string['cours_courant'] = 'In that course';
$string['deletereferentiel'] = 'Delete';
$string['instance_deleted'] = 'Instance deleted';
$string['selection_instance_referentiel'] = 'Check first instances to be to deleted';

$string['ressaisir_pass_referentiel'] = 'Input new password ';
$string['suppression_pass_referentiel'] = 'Delete password ';
$string['suppression_referentiel_impossible'] = 'Delete first instances of this skills repository ';

// version 3.1.4
$string['modifier_depot_document'] = 'Modify<br />linked document ';
$string['competences_bloquees'] = 'Bocked list of skills';
$string['modifier_depot_consigne'] = 'Modify<br />linked instruction document ';

// Version 3.1

$string['modifier_consigne'] = 'Modify instructions document';
// $string['consigne_associee'] = 'Instructions linked to the task ';

$string['version'] ='Skills repository module version: ';
$string['modulename-intance'] ='Slkills repository Instance';

// Version 3.0

$string['referentiel_impression_autorisee'] = 'Forbid Certificate Print';
$string['addtask'] = 'Declare';
$string['aide_cle_referentiel'] = 'Key is an identifier computed with skills repository code and uthor mail adress';
$string['aide_creer_referentiel'] = 'You have now to link that <b>new instance</b> to an <b>occurrence of a Skills repository</b>.
Choose an <i>existing</i> one of your Moodle server or type in (or import)
<i>a new one</i>.';
$string['aide_pass_referentiel'] = 'A password protects that pository againts modification.';

$string['annee'] = 'Year';
$string['approve_all_activity_task'] = 'Validate all activities linked with that task ';
$string['approve_all_activity_task_closed'] = 'That task is closed. Validate all activities linked with that task ? ';
$string['approved_task_by'] = 'Tache approved by ';
$string['check_pass_referentiel'] = 'Type in a password to protect this skills repository.';
$string['cle_referentiel'] = 'Key value';
$string['closed_task'] = 'Closed Task';
$string['config_creer_referentiel'] = 'The value of <i>config_select_referentiel</i> determines if editingteachers may or may not select an existing skills repository.
<br />If <i>referentiel_creation_limitee</i> is 0, editing teachers <i>may</i> create or import a skills repository at course level ;
<br />If <i>referentiel_creation_limitee</i> is 1, editing teachers <i>may not</i> create or import a skills repository at course level but but course creator may modify this parameter for his course ;
<br />If <i>referentiel_creation_limitee</i> is 2, editing teachers <i>may not</i> create or import a skills repository at course level and this parameter is locked at site level.';

$string['config_impression_referentiel'] = 'This parameter governs certificates print policy.
<br />If <i>impression_referentiel_autorisee</i> is 0, editing teachers may print certificates from the course ; 
<br />If <i>impression_referentiel_autorisee</i> is 1, editing teachers may not print certificates from the course, but course creator may modify this parameter for his course ;
<br />If <i>impression_referentiel_autorisee</i> is 2 edit editing teachers may not print certificates and this parameter is locked at site level.';
$string['config_scolarite'] = 'Scolarship data are needed to edit and print certificates. Sometimes thez are typed in by editing teachers of the le course. 
In that case they have to be readable. Sometimes thezy are downloaded by Moodle administrator. In that case they have to be hidden to users.
<br />If <i>referentiel_scolarite_masquee</i> is 0 editing teachers <i>may</i> display or import scolarship data at course level ; 
<br />If <i>referentiel_creation_limitee</i> is 1 editing teachers <i>may not</i> display or import scolarship data at course level, but course creator may modify this parameter for his course ;
<br />If <i>referentiel_creation_limitee</i> is 2 editing teachers <i>may not</i> display or import scolarship data at course level and this parameter is locked at site level.';
$string['config_select_referentiel'] = 'Parameter <i>referentiel_selection_autorisee</i> governs skills repository selection at course level.
<br />If <i>referentiel_selection_autorisee</i> is 0, after a new instance of skills repository has been created editing teachers <i>may</i> link an existing skills repository to that instance ; 
<br />If <i>referentiel_selection_autorisee</i> is 1 editing teachers <i>may not</i> select an existing skills repository to link it with the new instance, but course creator may modify this parameter for his course ;
<br />If <i>referentiel_selection_autorisee</i> is 2, selection of exiting skills repository is <i>forbidden</i> and this parameter is locked at site level.';
$string['config_verrouillee'] = 'Configuration locked by site administrator';
$string['configuration'] = 'Configuration';
$string['confirm_association_task'] = 'Confirm selection of that task';
$string['confirm_validation_task'] = 'Confirm task validation.<br /><b>Attention</b> : Confirmation will automatically validate all souscriptions to that task !';
$string['consigne']= 'Instruction Num.';
$string['consigne']= 'Document';
$string['consigne_ajout'] = 'Add an instruction document';
$string['consigne_associe']= 'Attached Document';
$string['consigne_associee'] = 'Instruction Document attached';

$string['consigne_task'] = 'Mission';
$string['create_referentiel'] = 'Forbid creation and importation of skills repository';
$string['creer_task'] = 'Add a Task';
$string['creref']='Forbid creation and importation of skills repository';
$string['criteres_evaluation'] = 'Evaluation Criteria';
$string['date_debut'] = 'Beginning date';
$string['date_fin'] = 'End date';
$string['date_modification'] = 'Modified on ';
$string['depot_consigne']= 'Join an instruction document';
$string['existe_pass_referentiel'] = 'Leave empty to keep existing password.';
$string['heure'] = 'Hour';
$string['impcert']='Forbid certificates printing';
$string['impression_referentiel_autorisee'] = 'Forbid certificates printing';
$string['jour'] = 'Day';
$string['listtask'] = 'Display';
$string['listtasksingle'] = 'Details';
$string['modif_task'] = 'Modify';
$string['modifier_task'] = 'Modify a task';
$string['mois'] = 'Month';
$string['pass_referentiel'] = 'Password';
$string['referentiel_config'] = 'Pository instance Configuration';
$string['scol']='Scolarship data hiddden to users';
$string['scolarite_user'] = 'Scolarship data hiddden to users';
$string['select_referentiel'] = 'Pository Selection Forbidden';
$string['selecttask'] = 'Choose';
$string['selref']='Pository Selection Forbidden';
$string['souscrire'] = 'Subscribe';
$string['task'] = 'Task';
$string['tasks'] = 'Tasks';
$string['type_consigne']= 'Document Type';
$string['type_task']= 'Thema';
$string['updatetask'] = 'Modify';


// Version  1.3 2009/03/25
// A traduire ou retraduire
$string['referentiel_config_local'] = 'Scholarship';
$string['masquee'] = 'Hidden';
// $string['visible'] = 'Visible';

$string['scolarite_user'] = 'Scholarship Data hidden to users';
$string['config_scolarite'] = 'Scholarship Data are needed for certificates print. 
Teachers may edit them for ther course in skills repository module, 
in this case these data have no to be hidden; if data are loaded by Administrator, they have to be hidden.';
$string['exportetudiant'] = 'Export';
$string['importetudiant'] = 'Import';
$string['import_etudiant'] = 'Import Students adresses';
$string['export_etudiant'] = 'Export Students adresses';
$string['importetudiants'] = 'Importer scholarship data';
$string['exportetudiants'] = 'Exporter scholarship data';
$string['importstudentsdone'] = 'Scholarship data import finished';
$string['exportingetudiants'] = 'Institutions an Students adresses are expoted to a file';
$string['noetudiant'] = 'Not any student registered. <br />You have to register students to this course... ';

// Version  2008/06/21
// A traduire ou retraduire
$string['html'] = 'HTML Format';
$string['code_referentiel'] = 'skills repository Code'; 
$string['description_referentiel'] = 'Description'; 
$string['url_referentiel'] = 'skills repository URL'; 
$string['nb_domaines'] = 'Number of Domains'; 

$string['code_domaine'] = 'Domain Code'; 
$string['description_domaine'] = 'Description of domain'; 
$string['num_domaine'] = 'Domain Number'; 
$string['nb_competences'] = 'Number of skills'; 
$string['code_competence'] = 'Skill Code'; 
$string['description_competence'] = 'Skill Description'; 
$string['num_competence'] = 'Skill Number'; 
$string['nb_item_competences'] = 'Number of items'; 
$string['description_item'] = 'Item Description';

// Version  2008/06/17

// en_utf8 traduits par Philippe V.
$string['depot_document'] = 'Upload a linked document ';
$string['modifier_document'] = 'Modify a document';
$string['nouvelle_fenetre'] = ' in a new window.';
$string['ouvrir'] = 'Open ';

$string['nocertificat'] = 'No certificate has been saved. <br />An activity must be declared and validated by a teacher... ';
$string['enseignant'] = 'Teacher ';
$string['date_naissance'] = 'Born on ';
$string['date_signature'] = 'On {$a} - Signature';
$string['date_instance'] = 'Date'; 
$string['ref_referentiel'] = 'skills repository'; 
$string['visible'] = 'Visible'; 
// $string['ID'] = 'ID';
$string['competences'] = 'Skills'; 
$string['decision'] = 'Decision'; 
$string['datedecision'] = 'Date of decision : {$a}';
$string['userid'] = 'User ID'; 
$string['valide_par'] = 'Validated by'; 
$string['verrou'] = 'Locked'; 
 
$string['num_item'] = 'Item Number';	
$string['t_item'] = 'Type';
$string['p_item'] = 'Weight';
$string['e_item'] = 'Fingerprint';
$string['empreinte_item']  = 'Fingerprint [0..999]:';
$string['empreinte'] = 'Fingerprint [0..999]: Number of times this skill must be validated to be certified.';
$string['etiquette_inconnue'] = 'Unknow label. Click to show the page';
$string['etiquette_url'] = 'Label of link ';
$string['liste_empreintes_competence'] = 'Fingerprints list';
$string['liste_codes_empreintes_competence'] = 'Codes<br />Weights<br />and <i>Fingerprints</i> lists';
$string['listactivitysingle'] = 'Details';
$string['upload_succes'] = 'File uploaded successfully.';



$string['abandonner'] = 'Cancel';
$string['activite'] = 'Activity ';
$string['addactivity'] = 'Declare';
$string['addcertif'] = 'Generate';
$string['aide_saisie_competences'] = 'Select the skills required by this activity ';
$string['approved'] = 'Validated';
$string['approve'] = 'Validate';
$string['associer_referentiel'] = 'Link to a skills repository ';
$string['auteur'] = 'Author';

$string['cannotcreatepath'] = 'The file cannot be created ({$a})';
$string['cannoteditafterattempts'] = 'Cannot add or delete skills, there are already submissions.';
$string['cannotinsert'] = 'Cannot insert the skill';
$string['cannotopen'] = 'Cannot open the export file ({$a})';
$string['cannotread'] = 'Cannot open the import file (or the file is empty)';
$string['cannotwrite'] = 'Cannot edit the export file ({$a})';
$string['certificat'] = 'Certificate';
$string['certificats'] = 'Certificates';
$string['certificat_etat'] = 'Status of certificate ';
$string['certificat_initialiser'] = 'Reinitialise the certificate ';
$string['certificat_verrou'] = 'Lock the certificate? ';
$string['certification'] = 'Certification';
$string['certifier'] = 'Certify';
$string['choisir'] = '_Choose_';
$string['choix_newinstance'] = 'Yes';
$string['choix_notnewinstance'] = 'No';
$string['choosefile'] = 'Choose a file';
$string['choix_instance'] = 'Choice of instances ';
$string['choix_filtrerinstance'] = 'Do you want to filter the selected instances? ';
$string['choix_oui_filtrerinstance'] = 'Yes ';
$string['choix_non_filtrerinstance'] = 'No ';
$string['choix_localinstance'] = 'local';
$string['choix_globalinstance'] = 'global';
$string['choix_override'] = 'Overwrite';
$string['choix_notoverride'] = 'Keep (do not overwrite)';
$string['cocher_competences_valides'] = 'Select the skills already validated ';
$string['code'] = 'Code ';
$string['comment'] = 'Comment';
$string['comment_certificat'] = 'Comment a certificate';
$string['commentaire'] = 'Comments';
$string['commentaire_certificat'] = 'Comments';
$string['competence'] = 'Skill ';
$string['competences_activite'] = 'Skills declared for this activity ';
$string['competences_certificat'] = 'Certified Skills ';
$string['competence_inconnu'] = 'Unknown Skill ';
$string['competences_validees'] = 'Skills already validated ';
$string['completer_referentiel'] = 'Complete the skills repository ';
$string['confirmdeleterecord'] = 'Do you really want to delete the record? ';
$string['confirminitialiserecord'] = 'Do you really want to reinitialise the record <br />The content will be deleted and the skills will be updated...';
$string['confirmvalidateactivity'] = 'Please confirm the activity <b>validation</b> ';
$string['confirmdeverrouiller'] = 'Please confirm the <b>unlocking</b> of the certificate ';
$string['confirmverrouiller'] = 'Please confirmez the <b>locking</b> of the certificate ';
$string['confirmdevalidateactivity'] = 'Plase confirm the <b>devalidation</b> of the activity ';
$string['corriger'] = 'Correct';
$string['creation_domaine'] = 'Create domains ';
$string['creation_competence'] = 'Create skills ';
$string['creation_item'] = 'Create item ';
$string['creer'] = 'Create';
$string['creer_certificat'] = 'Create a certificate';
$string['creer_referentiel'] = 'Create a Skills repository ';
$string['creer_activite'] = 'Declare an activity';
$string['creer_instance_referentiel'] = 'Create / modify a Skills repository Instance ';
$string['csv'] = 'CSV Format';

$string['date_creation'] = 'Created on ';
$string['date_modif'] = 'Modified on ';
$string['date_decision'] = 'Decision date'; 	
$string['decision_jury'] = 'Committee Decision'; 	
$string['declarer'] = 'Declare';
$string['delete_activity'] = 'Delete';
$string['deletecertif'] = 'Delete';
$string['desapprove'] = 'Do not validate';
$string['description'] = 'Description ';
$string['description_instance'] = 'Instance description ';
$string['deverrouille'] = 'Unlocked';
$string['deverrouiller'] = 'Unlock';
$string['document'] = 'Document';
$string['document_ajout'] = 'Add a document';
$string['document_associe'] = 'Documents linked to the activity ';
$string['domaine_inconnu'] = 'Unknown domain ';
$string['domaine'] = 'Domain ';
$string['download'] = 'Click to download the export file';
$string['downloadextra'] = '(the file is also available in the course files, in the directory /referentiel)';

$string['edit_activity'] = 'Activities';
$string['editcertif'] = 'Edit';
$string['editetudiant'] = 'Edit';
$string['edit_an_activity'] = 'Edit an activity';
$string['editreferentiel'] = 'Modify';
$string['erreur_referentiel'] = 'There are no defined Skills Depository ';
$string['erreurscript'] = 'PHP script error:<i> {$a} </i>. Inform module\'s author.';
$string['etablissement']= 'Institution';
$string['etablissements']= 'Institutions';
$string['etudiant']= 'Student ';
$string['etudiants']= 'Students';
$string['evaluation']= 'Evaluation';
$string['exportreferentiel'] = 'Export the skills repository';
$string['export'] = 'Export';
$string['exportactivite'] = 'Export activities ';
$string['exportcertificat'] = 'Export certificates ';
$string['exporterror'] = 'An error has occured during the exportation process';
$string['exportfilename'] = 'referentiel'; // quiz : Ne pas traduire cette chaîne ! Voir http://tracker.moodle.org/browse/MDL-4544
$string['exportingreferentiels'] = 'Skills repositories is exported to a file';
$string['exportingactivites'] = 'Activities are exported to a file';
$string['exportname'] = 'File name';
$string['exportingcertificats'] = 'Certificates are exported to a file';
$string['exportnameformat'] = '%Y%m%d-%H%M';

$string['filtrerlocalinstance'] = 'If the above option is selected, choose the instances ';
$string['fileformat'] = 'File format';
$string['fileprint'] = 'Printing format';
$string['formatnotfound'] = 'Import/export {$a} format not found';
$string['formatnotimplemented'] = 'This format has not been correctly implemented';
$string['fromfile'] = 'From file&nbsp;:';

$string['globalinstance'] = 'Global skills repository Intances ';
$string['global'] = '[Global instance]';

$string['id'] = 'ID ';
$string['import'] = 'Import';
$string['importerror_referentiel_id'] = 'An error has occured during the import process: Unknown skills repository id';
$string['importerror'] = 'An error has occured during the import process';
$string['importfilearea'] = 'Import from a course file...';
$string['importfileupload'] = 'Import from a file to be uploaded...';
$string['importfromthisfile'] = 'Import from this file';
$string['importing'] = 'Import skills repository {$a} from file';
$string['importmax10error'] = 'This skill has an error. Impossible to have more than 10 answers';
$string['importmaxerror'] = 'This skill has an error. there are too many answers.';
$string['importminerror'] = 'This skill has an error. There is not enough answers for this skill type';
$string['importreferentiel'] = 'Import a skills repository';
$string['importdone'] = 'skills repository importation done successfufly...';
$string['importtodatabase'] = 'Import to current database...';
$string['incompletedata'] = 'Missing data... Importation process interrupted. ';
$string['inconnu'] = 'UNKNOWN';
$string['item'] = 'Item ';
$string['item_supplementaire'] = 'Additional item ';

$string['label_domaine_question'] = 'Label of the <i>domain</i>? ';
$string['label_competence_question'] = 'Label of the <i>skill</i>? ';
$string['label_item_question'] = 'Label of the <i>item</i>? ';
$string['label_domaine'] = 'Label of domains ';
$string['label_competence'] = 'Label of skills';
$string['label_item'] = 'Label of items ';
$string['liste_codes_competence'] = 'Skills ';
$string['listactivity'] = 'List';
$string['listcertif'] = 'List';
$string['listetudiant'] = 'Students';
$string['lister'] = 'List';
$string['listreferentiel'] = 'List';
$string['localinstance'] = 'Local skills repository instances ';
$string['local'] = '[Local instance]';
$string['logo'] = 'Logo';

$string['managecertif'] = 'Export';
$string['manageetab'] = 'New Institution';
$string['modification'] = 'Modified';
$string['modifier'] = 'Modify';

$string['modifier_referentiel'] = 'Modify the skills repository ';
$string['modifier_domaine_referentiel'] = 'Modify a skill domain ';
$string['modifier_competence_referentiel'] = 'Modify a skill ';
$string['modifier_item'] = 'Modify an item ';
$string['modifier_item_competence_referentiel'] = 'Modify an item ';
$string['modifier_activite'] = 'Modify an activity';
$string['modifier_certificat'] = 'Modify a certificate';
$string['modifier_etudiant'] = 'Modify';
$string['modulenameplural'] = 'Skills repositories ';
$string['modulename'] = 'Skills repository ';
$string['moins'] = 'Less';

$string['name'] = 'Name ';
$string['name_instance'] = 'Instance title ';
$string['newinstance'] = 'Create a new instance in this course from the loaded skills repository? ';
$string['noaccess'] = 'the access is not allowed ';
$string['noaccess_certificat'] = 'Access to certificates not allowed ';
$string['noactivite'] = 'No activity has been saved ';
$string['noactivitefiltre'] = 'No filtered activities ';
$string['nocertificat'] = 'No certificate has been saved ';
$string['nombre_domaines_supplementaires'] = 'Number of domains<br />to be saved for this skills repository ';
$string['nombre_competences_supplementaires'] = 'Number of skills<br />to be saved for this domain';
$string['nombre_item_competences_supplementaires'] = 'Number of items<br />to be saved for this skill ';
$string['numero'] = 'Number ';
$string['noinfile'] = 'Importation process canceled<br />(incorrect data or overwriting risk of exisiting data) ';
$string['invalide'] = 'invalid';
$string['nologo'] = 'Logo ?';
$string['noresponse'] = 'No answer';
$string['not_approved'] = 'Not validated';

$string['override'] = 'If an identical occurrence of Skills repository exists (same name, same code) it must be  ';
$string['overriderisk'] = ' : overwriting risk of local version ';

$string['parsing'] = 'Analysis of file to be imported.';
$string['plus'] = 'Plus';
$string['poids_item'] = 'Item weight (decimal value) ';
$string['profil'] = 'Profile';
$string['print'] = 'Printing';
$string['printfilename'] = 'printing';
$string['printcertif'] = 'Print';
$string['printcertificat'] = 'Printing certificates';

$string['quit'] = 'Quit';

$string['recordapproved'] = 'Record saved successfully ';
$string['referent'] = 'Referee';
$string['referentiel'] = 'skills repository';
$string['referentiel_global'] = 'Global skills repository ';
$string['referentiel_inconnu'] = 'Unknown skills repository ';
$string['referentiel_instance'] = 'skills repository instance ';

$string['saisie_competence'] = 'You must enter at least one skill ';
$string['saisie_competence_supplementaire'] = 'Enter a new skill ';
$string['saisie_domaine_competence'] = 'You must enter at least one skill domain ';
$string['saisie_domaine_supplementaire'] = 'Enter a new skill domain ';
$string['saisie_item'] = 'You must enter at least one item of skill ';
$string['saisie_item_supplementaire'] = 'Enter a new skill item ';
$string['scolarite'] = 'Scholarship';
$string['select'] = 'Select';
$string['selectreferentiel'] = 'Select an existing skills repository ';
$string['selecterror_referentiel_id'] = 'An error has occured during the selection process: Unknown skills repository id number';
$string['stoponerror'] = 'Stop on error';
$string['selectnoreferentiel'] = 'No skills repository has been saved ';
$string['seuil_certificat'] = 'Certification level (decimal value) ';
$string['single'] = 'Show activity';
$string['supprimer_activite'] = 'Delete activity';

$string['type'] = 'Type ';
$string['type_activite'] = 'Type of activity ';
$string['type_document'] = 'Type of document ';
$string['extensions_document'] = '[Text, Exe, PDF, ZIP, Image, Audio, Video...] ';
$string['type_item'] = 'Type of item [Mandatory, Optional...] ';

$string['updateactivity'] = 'Modify';
$string['updatecertif'] = 'Modify';
$string['url'] = 'URL ';

$string['valide'] = 'valid';
$string['validation'] = 'Validation';
$string['verrouille'] = 'Locked';
$string['verrouiller'] = 'Lock';
$string['visible_referentiel'] = 'Is the skills repository visible? ';

$string['xhtml'] = 'XHTML format';
$string['xml'] = 'XML Moodle format';
$string['xmlimportnoname'] = 'Name of skills repository missing in the xml file';
$string['xmlimportnocode'] = 'Code of missing skill in the xml file';

$string['nom_prenom'] = 'NAME Firstname'; 
$string['num_etudiant'] = 'Student ID Number'; 
$string['ddn_etudiant'] = 'Date of birth'; 
$string['lieu_naissance'] = 'Place of birth'; 
$string['departement_naissance'] = 'Postcode (Place of birth)'; 
$string['adresse_etudiant'] = 'Address'; 
$string['ref_etablissement'] = 'Institution Reference ';

$string['modifier_etablissement'] = 'Modify Institution';
$string['num_etablissement'] = 'Institution ID Number'; 	
$string['nom_etablissement'] = 'Name of Institution' 	;
$string['adresse_etablissement'] = 'Address of Institution';
?>
