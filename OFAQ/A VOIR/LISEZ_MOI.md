# A l'attention du correcteur  #

*****************
Un export de la BDD se situe dans ce dossier.
Il y a des fausses données (fixtures) qui remplissent tous les champs et respectent les relations entre entités.
Les utilisateurs sont les suivants :

|nom|mot de passe|role|
|-|-|-|
|admin|admin|administrateur|
|modo|modo|modérateur|
|(prénom généré par les fixtures)|user|membre (utilisateur connecté)|

J'ai réalisé la plupart des tâches demandées, à savoir : 

+ la création d'un compte utilisateur
+ création des 3 rôles (changement de rôle par l'admin qui est le seul à voir le champ "rôle " lors de l'édition d'un utilisateur)
+ s'enregistrer sur le site
+ création et édition (par leur auteur ou l'admin) des questions/réponses
+ bloquage des Questions/Réponses par le modérateur, disparition de celle-ci de l'affichage aux membres et visiteurs
+ affichage des questions selon leur tags
+ validation d'une réponse par l'auteur de la question et mise en avant de celle-ci
