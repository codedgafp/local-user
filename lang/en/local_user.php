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
 * Plugin strings
 *
 * @package    local_user
 * @copyright  2020 Edunao SAS (contact@edunao.com)
 * @author     remi <remi.colet@edunao.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname'] = 'Local User';

$string['cohortnotexisterror'] = 'Le cohort (id : {$a}) n\'existe pas';
$string['addusercohortmembererror']
    = 'L\'utilisateur (id : {$a->userid}) n\'a pas été ajouté à la cohorte (id : {$a->cohortid})';
$string['removeusercohortmembererror']
    = 'L\'utilisateur (id : {$a->userid}) n\'a pas été enlevé à la cohorte (id : {$a->cohortid})';
$string['unknownusererror'] = 'L\'utilisateur (id : {$a}) n\'est actuellement plus enregistré';
$string['unknownuseryemailerror'] = 'L\'utilisateur (email : {$a}) n\'est actuellement plus enregistré';
$string['suspendedusererror'] = 'L\'utilisateur (id : {$a}) n\'a pas été suspendu';
$string['activeusererror'] = 'L\'utilisateur (id : {$a}) n\'a pas été activé';
$string['rolenotexisterror'] = 'Le role {$a} n\'exite pas';

/*****************Edadmin**************************/

$string['edadminusercoursetype'] = 'Utilisateurs';
$string['edadminusercoursetitle'] = 'Gestion des utilisateurs';

/*****************User admin**********************/

// Renderer.
$string['manageroles'] = "Gérer les rôles";
$string['lastname'] = "Nom";
$string['firstname'] = "Prénom";
$string['email'] = "Mail";
$string['externalusers'] = 'Comptes externes';
$string['connectingentity'] = 'Entité de rattachement';
$string['region'] = 'Région';
$string['lastconnection'] = "Date de dernière connexion";
$string['sortby'] = "Filtrer par";
$string['status'] = "Statut";
$string['applyfilters'] = "Appliquer les filtres";
$string['externaluser'] = "Utilisateur externe";
$string['externalusers'] = "Utilisateurs externes";
$string['resetfilters'] = "Réinitialiser les filtres";
$string['suspendusers'] = 'Suspendre des utilisateurs en lot';
$string['suspendedusers'] = 'Comptes suspendus';
$string['userssuspension'] = 'Suspension d\'utilisateurs';

// CSV Import.
$string['importusers'] = "Importation d'utilisateurs";
$string['validateimport'] = "Valider l'import";
$string['invalid_headers']
    = 'Les en-têtes du fichier sont incorrects. Les en-têtes attendus sont : "lastname", "firstname" et "email". Si vous avez les bons en-têtes, pensez à vérifier que vous avez sélectionné le bon séparateur.';
$string['missing_headers']
    = 'Les en-têtes du fichier sont incorrects. Les en-têtes attendus sont : "lastname", "firstname" et "email". Si vous avez les bons en-têtes, pensez à vérifier que vous avez sélectionné le bon séparateur.';

// JS.
$string['deletemultipleusers'] = 'Etes-vous sur de vouloir supprimer ces {$a} utilisateurs ?';
$string['deletemultipleuserswhithusername'] = 'Etes-vous sur de vouloir supprimer les utilisateurs :';
$string['deleteoneuser'] = 'Etes-vous sur de vouloir supprimer l\'utilisateur {$a} ?';
$string['removeuser'] = 'Supprimer utilisateur';
$string['adduser'] = 'Ajouter un utilisateur';
$string['validemailrequired'] = 'Une adresse électronique valide est requise';
$string['neverconnected'] = 'Jamais';
$string['elevatedroles'] = 'Consultation des rôles';
$string['exportlistusers'] = 'Exporter la liste des utilisateurs';

/*****************DataTable***********************/

$string['langfile'] = 'French';

/***************Capabilities**********************/

$string['user:manageusers'] = 'Gérer les utilisateurs d\'une entité';

/***************Task*****************************/

$string['task_never_logged_user_first_recall'] = 'Suppression de comptes utilisateurs jamais connectés - premier rappel';
$string['subject_mail_never_logged_user_first_recall'] = 'Mentor: Suppression de votre compte utilisateur - premier rappel';
$string['content_mail_never_logged_user_first_recall'] = 'Bonjour {$a->firstname} {$a->lastname},

Vous avez certainement constaté qu\'un compte utilisateur a été créé pour vous sur la plateforme interministérielle Mentor il y a plusieurs mois. Puisque vous ne l\'utilisez pas, nous vous proposons de le supprimer automatiquement d\'ici deux mois.

Pour conserver votre compte, connectez-vous ici : <a href="{$a->siteurl}">{$a->siteurl}</a><br>
Pour récupérer ou créer votre mot de passe, vous pouvez utiliser le lien : <a href="{$a->forgotpasswordurl}">Mot de passe oublié</a><br>
Pour plus de détails, veuillez consulter la <a href="{$a->faqurl}">FAQ</a>.';

$string['task_never_logged_user_last_recall'] = 'Suppression de comptes utilisateurs jamais connectés - dernier rappel';
$string['subject_mail_never_logged_user_last_recall'] = 'Mentor: Suppression de votre compte utilisateur - dernier rappel';
$string['content_mail_never_logged_user_last_recall'] = 'Bonjour {$a->firstname} {$a->lastname},

Conformément à notre dernier message, nous vous proposons de supprimer votre compte utilisateur de la plateforme interministérielle Mentor d\'ici un mois.

Pour conserver votre compte, connectez-vous ici : <a href="{$a->siteurl}">{$a->siteurl}</a><br>
Pour récupérer ou créer votre mot de passe, vous pouvez utiliser le lien : <a href="{$a->forgotpasswordurl}">Mot de passe oublié</a><br>
Pour plus de détails, veuillez consulter la <a href="{$a->faqurl}">FAQ</a>.';

$string['task_never_logged_user_deleted'] = 'Suppression de comptes utilisateurs jamais connectés - Supression';

$string['task_suspended_user_deleted'] = 'Suppression de comptes utilisateurs suspendus';

$string['task_not_logged_user_first_recall'] = 'Suppression de comptes utilisateurs jamais connectés sans connexion depuis 2 ans - premier rappel';
$string['subject_mail_not_logged_user_first_recall'] = 'Mentor: Suppression de votre compte utilisateur - premier rappel';
$string['content_mail_not_logged_user_first_recall'] = 'Bonjour {$a->firstname} {$a->lastname},

Voilà deux ans déjà que votre accès à la plateforme interministérielle Mentor est inactif. Si aucune nouvelle formation ne vous attire et/ou que vous n\'avez plus besoin de votre compte, nous vous proposons de le supprimer automatiquement d\'ici deux mois.

Pour consulter l\'offre de formation actuelle, cliquez ici : <a href="{$a->catalogurl}">{$a->catalogurl}</a>
Pour conserver votre compte, connectez-vous ici : <a href="{$a->siteurl}">{$a->siteurl}</a><br>
Pour récupérer ou créer votre mot de passe, vous pouvez utiliser le lien : <a href="{$a->forgotpasswordurl}">Mot de passe oublié</a><br>
Pour plus de détails, veuillez consulter la <a href="{$a->faqurl}">FAQ</a>.';


$string['task_not_logged_user_last_recall'] = 'Suppression de comptes utilisateurs jamais connectés sans connexion depuis 2 ans - dernier rappel';
$string['subject_mail_not_logged_user_last_recall'] = 'Mentor: Suppression de votre compte utilisateur - dernier rappel';
$string['content_mail_not_logged_user_last_recall'] = 'Bonjour {$a->firstname} {$a->lastname},

Conformément à notre dernier message, nous vous proposons de supprimer votre compte utilisateur de la plateforme interministérielle Mentor d\'ici un mois.

Pour consulter l\'offre de formation actuelle, cliquez ici : <a href="{$a->catalogurl}">{$a->catalogurl}</a>
Pour conserver votre compte, connectez-vous ici : <a href="{$a->siteurl}">{$a->siteurl}</a><br>
Pour récupérer ou créer votre mot de passe, vous pouvez utiliser le lien : <a href="{$a->forgotpasswordurl}">Mot de passe oublié</a><br>
Pour plus de détails, veuillez consulter la <a href="{$a->faqurl}">FAQ</a>.';

$string['task_not_logged_user_deleted'] = 'Suppression de comptes utilisateurs jamais connectés sans connexion depuis 2 ans - Supression';

$string['task_user_deleted_log'] = 'Suppression de l’utilisateur : {$a->userid} ; {$a->email} ; Date de la purge : {$a->date}';

$string['task_error_header'] = 'Les comptes utisateurs en erreurs :';
$string['task_error_line'] = ' * {$a->userid} ; {$a->email} ; erreur durant l\'appel : {$a->message}';


$string['import_queued'] = 'L\'importation de l\'utilisateur a été mise en file d\'attente et sera traitée sous peu.';
$string['importusers'] = 'Import utilisateurs';
