# Gestion des modèles de forfait

---

Il est possible de créer des modèles de forfait pour chaque flotte ou un ensemble de flottes.
Pour chaque modèle forfait il faut préciser un nom et une durée de validité. 


- [Créer un modèle de forfait](#create-rental-package)
- [Liste des modèles de forfait](#list-rental-package)
- [Editer un modèle de forfait](#edit-rental-package)
- [Lister et ajouter un forfait client](#sailor-rental-package)


<a name="create-rental-package"></a>
## Créer un modèle de forfait

Utiliser le formulaire à droite de la liste des forfaits existant pour en créer un.
Saisissez un nom pour le forfait ainsi que la ou les flottes le composant et la durée de validité du forfait.

- Le nom du forfait doit être unique
- Au moins une flotte doit être sélectionnée 
- Une fois la durée de validité du forfait d'un client dépassée il n'apparait plus dans la liste (voir la section [Forfait client](/{{route}}/{{version}}/sailor-rental-package))

> {danger.fa-close} Attention il est impossible de créer un forfait avec une flotte désactivée

<a name="list-rental-package"></a>
## Liste des modèles de forfait

Retrouvez les différentes informations renseignées lors de la création d'un modèle de forfait dans la liste ainsi que le 
nombre de forfait client valide. 
Un forfait client valide dispose d'au moins une heure de navigation et n'a pas dépassé la durée de validité. 

---
![image](/docs/rental-packages.png)

<a name="list-rental-package"></a>
## Editer un modèle de forfait

- Cliquez sur l'icône <i class="text-primary fa fa-edit fa-lg"></i> pour éditer le modèle de forfait


<a name="sailor-rental-package"></a>
## Lister et ajouter un forfait client

Voir la section [Forfait client](/{{route}}/{{version}}/sailor-rental-package) pour obtenir d'avantage d'informations sur les forfaits clients 

- Cliquez sur l'icône <i class="text-info fa fa-list fa-lg"></i> pour consulter la liste des forfaits client associés à ce modèle de forfait
- Cliquez sur l'icône <i class="text-success fa fa-user-plus fa-lg"></i> pour ajouter un forfait client associé à ce modèle de forfait via la fenêtre de création visible ci dessous

---

![image](/docs/modal-creation-forfait.png)

