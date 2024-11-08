<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void

   {
       // Définir le guard (exemple : 'api' ou 'web')
       $guard = 'api';

       // Permissions
       $permissions = [
           // Gestion des Utilisateurs et Rôles
           'Créer un utilisateur',
           'Modifier un utilisateur',
           'Supprimer un utilisateur',
           'Voir la liste des utilisateurs',
           'Voir les détails d\'un utilisateur',
           'Attribuer un rôle à un utilisateur',
           'Voir la liste des utilisateurs par rôle',

           // Gestion des Zones
           'Créer une zone',
           'Modifier une zone',
           'Supprimer une zone',
           'Voir la liste des zones',
           'Voir les détails d\'une zone',
           'Attribuer un administrateur à une zone',
           'Voir le nombre d\'équipes par zone',

           // Gestion des Équipes
           'Créer une équipe',
           'Modifier une équipe',
           'Supprimer une équipe',
           'Voir la liste des équipes',
           'Voir les détails d\'une équipe',
           'Attribuer une équipe à une zone',
           'Attribuer un gestionnaire à une équipe',
           'Voir le nombre de joueurs par équipe',

           // Gestion des Joueurs
           'Créer un joueur',
           'Modifier un joueur',
           'Supprimer un joueur',
           'Voir la liste des joueurs',
           'Voir les détails d\'un joueur',
           'Attribuer un joueur à une équipe',
           'Voir l\'historique des transferts d\'un joueur',
           'Voir la liste des joueurs par catégorie',

           // Gestion des Catégories
           'Créer une catégorie',
           'Modifier une catégorie',
           'Supprimer une catégorie',
           'Voir la liste des catégories',
           'Voir les détails d\'une catégorie',
           'Voir le nombre de joueurs par catégorie',

           // Gestion des Compétitions
           'Créer une compétition',
           'Modifier une compétition',
           'Supprimer une compétition',
           'Voir la liste des compétitions',
           'Voir les détails d\'une compétition',
           'Inscrire une équipe à une compétition',
           'Voir la liste des équipes inscrites à une compétition',
           'Effectuer un tirage au sort pour les phases/poules',

           // Gestion des Matchs
           'Créer un match',
           'Modifier un match',
           'Supprimer un match',
           'Voir la liste des matchs',
           'Voir les détails d\'un match',
           'Enregistrer le résultat d\'un match',
           'Voir la liste des matchs par compétition',
           'Voir la liste des matchs par équipe',

           // Gestion des Résultats
           'Enregistrer le score d\'un match',
           'Enregistrer les cartons d\'un match',
           'Enregistrer les buteurs d\'un match',
           'Modifier le résultat d\'un match',
           'Voir les statistiques d\'un match',

           // Gestion des Statistiques
           'Voir les statistiques d\'un joueur',
           'Voir les statistiques d\'une équipe',
           'Voir les statistiques par zone',
           'Voir le classement des buteurs',
           'Voir le classement des passeurs',
           'Voir le classement des cartons',

           // Gestion des Classements
           'Voir le classement d\'une compétition',
           'Voir le classement par zone',
           'Mettre à jour automatiquement les classements après chaque match',
           'Voir l\'évolution du classement d\'une équipe',

           // Gestion des Notifications
           'Créer une notification',
           'Envoyer une notification à un utilisateur',
           'Envoyer une notification à une équipe',
           'Voir la liste des notifications',
           'Marquer une notification comme lue',

           // Gestion des Réclamations
           'Créer une réclamation',
           'Modifier une réclamation',
           'Supprimer une réclamation',
           'Voir la liste des réclamations',
           'Voir les détails d\'une réclamation',
           'Traiter une réclamation',

           // Rapports et Analyses
           'Générer un rapport de performance d\'une équipe',
           'Générer un rapport de performance d\'un joueur',
           'Analyser les tendances des compétitions par zone',
           'Voir les statistiques globales de la saison',

           // Gestion du Calendrier
           'Créer le calendrier d\'une compétition',
           'Modifier le calendrier d\'une compétition',
           'Voir le calendrier des matchs par équipe',
           'Voir le calendrier des matchs par compétition',

           // Gestion des Transferts
           'Enregistrer un transfert de joueur',
           'Voir l\'historique des transferts',
           'Analyser les tendances des transferts par saison',

           // Interface Publique
           'Voir les résultats en direct',
           'Consulter les classements publics',
           'Voir les statistiques des joueurs et des équipes',
           'Consulter le calendrier des matchs à venir',

           // Gestion des Articles de Blog
            'Créer un article de blog',
            'Modifier un article de blog',
            'Supprimer un article de blog',
            'Voir la liste des articles de blog',
            'Voir les détails d\'un article de blog',
        ];



       // Créer les permissions avec le guard spécifié
       foreach ($permissions as $permission) {
           Permission::firstOrCreate(['name' => $permission, 'guard_name' => $guard]);
       }

       // Rôles
        $roles = ['admin', 'zone', 'equipe'];
       foreach ($roles as $roleName) {
           Role::firstOrCreate(['name' => $roleName, 'guard_name' => $guard]);
       }

       // Attribution des permissions aux rôles

       // Récupérer les rôles
       $roleAdmin = Role::findByName('admin', $guard);
       $roleZone = Role::findByName('zone', $guard);
       $roleEquipe = Role::findByName('equipe', $guard);

       // Permissions pour admin (toutes les permissions)
       $roleAdmin->givePermissionTo($permissions);

       // Permissions pour administrateur de zone
       $permissionsZone = [
           // Gestion des Zones
           'Voir la liste des zones',
           'Voir les détails d\'une zone',
           'Attribuer un administrateur à une zone',
           'Voir le nombre d\'équipes par zone',

           // Gestion des Équipes
           'Créer une équipe',
           'Modifier une équipe',
           'Supprimer une équipe',
           'Voir la liste des équipes',
           'Voir les détails d\'une équipe',
           'Attribuer une équipe à une zone',
           'Voir le nombre de joueurs par équipe',

           // Gestion des Joueurs
           'Voir la liste des joueurs',
           'Voir les détails d\'un joueur',
           'Voir la liste des joueurs par catégorie',

           // Gestion des Compétitions
           'Créer une compétition',
           'Modifier une compétition',
           'Supprimer une compétition',
           'Voir la liste des compétitions',
           'Voir les détails d\'une compétition',
           'Inscrire une équipe à une compétition',
           'Voir la liste des équipes inscrites à une compétition',
           'Effectuer un tirage au sort pour les phases/poules',

           // Gestion des Matchs
           'Voir la liste des matchs',
           'Voir les détails d\'un match',
           'Voir la liste des matchs par compétition',
           'Voir la liste des matchs par équipe',

           // Gestion des Statistiques
           'Voir les statistiques d\'un joueur',
           'Voir les statistiques d\'une équipe',
           'Voir les statistiques par zone',
           'Voir le classement des buteurs',
           'Voir le classement des passeurs',
           'Voir le classement des cartons',

           // Gestion des Classements
           'Voir le classement d\'une compétition',
           'Voir le classement par zone',
           'Voir l\'évolution du classement d\'une équipe',

           // Gestion des Notifications
           'Créer une notification',
           'Envoyer une notification à un utilisateur',
           'Voir la liste des notifications',
           'Marquer une notification comme lue',

           // Gestion des Réclamations
           'Voir la liste des réclamations',
           'Voir les détails d\'une réclamation',
           'Traiter une réclamation',

           // Rapports et Analyses
           'Générer un rapport de performance d\'une équipe',
           'Analyser les tendances des compétitions par zone',
           'Voir les statistiques globales de la saison',

           // Gestion du Calendrier
           'Créer le calendrier d\'une compétition',
           'Modifier le calendrier d\'une compétition',
           'Voir le calendrier des matchs par équipe',
           'Voir le calendrier des matchs par compétition',
       ];
       $roleZone->givePermissionTo($permissionsZone);

       // Permissions pour gestionnaire d'équipe
       $permissionsEquipe = [
           // Gestion des Équipes
           'Voir les détails d\'une équipe',
           'Modifier une équipe',
           'Voir le nombre de joueurs par équipe',

           // Gestion des Joueurs
           'Créer un joueur',
           'Modifier un joueur',
           'Supprimer un joueur',
           'Voir la liste des joueurs',
           'Voir les détails d\'un joueur',
           'Attribuer un joueur à une équipe',
           'Voir l\'historique des transferts d\'un joueur',

           // Gestion des Matchs
           'Voir la liste des matchs par équipe',
           'Voir les détails d\'un match',

           // Gestion des Résultats
           'Voir les statistiques d\'un match',

           // Gestion des Statistiques
           'Voir les statistiques d\'un joueur',
           'Voir les statistiques d\'une équipe',
           'Voir le classement des buteurs',
           'Voir le classement des passeurs',
           'Voir le classement des cartons',

           // Gestion des Classements
           'Voir le classement d\'une compétition',
           'Voir l\'évolution du classement d\'une équipe',

           // Gestion des Notifications
           'Voir la liste des notifications',
           'Marquer une notification comme lue',

           // Gestion des Réclamations
           'Créer une réclamation',
           'Voir les détails d\'une réclamation',

           // Rapports et Analyses
           'Générer un rapport de performance d\'une équipe',

           // Gestion du Calendrier
           'Voir le calendrier des matchs par équipe',
       ];
       $roleEquipe->givePermissionTo($permissionsEquipe);

}
 }
