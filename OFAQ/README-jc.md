# Commentaire du correcteur

Globalement l'application répond à la demande dans les grandes lignes et est codée selon les bonnes pratiques de Symfony vues en cours. Pas complet donc mais code fourni quasi-exempt de bugs, ce qui est une bonne chose et là encore une bonne pratique des méthodes agiles (que tu en ais utilisé une ou non ;)) : chaque version livrée de l'application fonctionne.

Deux bugs mineurs dûs à un manque de temps et de tests : sélection de la réponse valide + édition du mot de passe User. Les migrations sont plantées mais le .sql passe. Les fixtures fonctionnent bien.

Côté front c'est correct malgré quelques mises en pages parfois chaotiques :) (formulaires).

## Points forts

- Appli fonctionnelle dans sa version livrée une bonne partie de l'énoncé).
- Bon usage du Framework et de ses modules (Routing, Controller, View, Entity, Repository).
- Gestion des tags.
- Fixtures cohérentes.
- Bien vu, la récupération du rôle par défaut depuis RoleRepository.

## Recommandations

- Penser à produire ou à fournir les documents de conception ! CDC, User stories, MCD, Wireframes, etc.
- Attention à l'organisation de certains fichiers/routes etc. par ex. la gestion de la réponse est dans le dossier `backend`, ce n'est pê pas le meilleur endroit. Idem pour d'autres contrôleurs/méthodes de ce sous-dossier.
- Quitte à utiliser Bootstrap, essayer de le faire en mode le plus _standard_ possible avant de personnaliser => ici le `inline-flex` sur `form` et le `text-align: center` sur `.wrapper` sont peut-être de trop. Mais c'est bien d'essayer :thumbs_up:
- Continue sur cette voie en t'appuyant sur les bonnes pratiques Symfony, PHP, Opquast, de conception.

## Remarques

- Gestion des autorisations : je ne sais pas si c'est voulu mais j'ai noté une gestion dans `security.yaml` et une autre dans le contrôleur `Admin`. Pas de souci avec ça, c'est tout à fait possible de mixer.