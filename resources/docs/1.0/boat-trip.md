# Les sorties en mer

---

- [Créer une sortie en mer](#create-boat-trip)
  - [Identité du client](#id-boat-trip)
  - [Ajouter un autre type d'embarcations](#fleets-boat-trip)
  - [Ajouter une sortie en mer](#add-boat-trip-start-date)
  - [Ajouter une sortie en mer qui démarre immédiatement](#boat-trip-start-now)
  - [Ajouter une sortie en mer qui démarre automatiquement à l'heure indiquée](#add-boat-trip-start-auto)
  - [Supprimer une sortie en mer](#delete-boat-trip)
  - [Forcer la création d'une sortie en mer](#force-add-boat-trip)
  - [Sortie en mer en retard](#late-boattrip)
- [Disponibilité des flottes](#avai-fleets)

<a name="create-boat-trip"></a>
## Créer une sortie en mer

Afin de créer une sortie en mer cliquez sur le bouton en haut à droite de l'écran "Ajouter une sortie". 
Vous pouvez aussi utiliser les icônes <i class="text-primary fa fa-plus-circle fa-lg"></i> situées dans le tableau 
des embarcations disponibles sur la droite de votre fenêtre.  

> {danger.fa-warning} Il n'est pas possible d'ajouter une sortie en mer si aucune flotte n'est active

<div style="text-align: center;">
<img src="/docs/add-boat-trip.png" style="width:50%;text-align: center;" />
</div>


<a name="id-boat-trip"></a>
## Identité du client
Depuis la fenêtre de dialogue précisez l'identité du client, vous avez la possibilité de préciser si le client
est un membre ou un moniteur de votre club nautique.  

<a name="fleets-boat-trip"></a>
## Ajouter un second type d'embarcation

Il est possible de créer des sorties multi support, par exemple une sortie avec deux kayaks et 1 paddle. Pour celà 
cliquez sur le bouton  <larecipe-button type="primary" radius="">Ajouter un type d'embarcation</larecipe-button>
et indiquer le nombre d'embarcation(s) désiré.

<a name="add-boat-trip-start-date"></a>
## Ajouter une sortie en mer

Lorsque vous créer une sortie en mer vous pouvez spécifier l'heure de début approximative de la sortie.  
La sortie sera marquée avec le statut : <span class="badge bg-warning">A terre</span>
- Cliquez sur l'icône <i class="text-success fa fa-play fa-lg"></i> pour démarrer la sortie en mer.
  La sortie passera du statut <span class="badge bg-warning">A terre</span> au statut <span class="badge bg-info">En navigation</span>

<a name="boat-trip-start-now"></a>
## Ajouter une sortie en mer qui démarre immédiatement

Créer une sortie en mer en sélectionnant l'option "Faire partir la location immédiatement". 

La sortie sera marqué avec le statut : <span class="badge bg-info">En navigation</span>
- Cliquez sur l'icône <i class="text-primary fa fa-pause fa-lg"></i> pour clôturer la sortie en mer. <small>Les sorties clôturées sont consultables dans l'onglet "Terminées"</small> 

<a name="add-boat-trip-start-auto"></a>
## Ajouter une sortie en mer qui démarre automatiquement à l'heure indiquée

Créer une sortie en mer en sélectionnant l'option "Faire partir automatiquement à l'heure indiquée".

La sortie sera marquée avec le statut : <span class="badge bg-warning">A terre</span> et passera automatiquement au statut <span class="badge bg-info">En navigation</span> 
lorsque l'heure de départ sera atteinte.

<a name="force-add-boat-trip"></a>
## Forcer la création d'une sortie en mer

Il est possible d'ajouter une sortie en mer même si vous ne disposez pas assez de matériel à l'instant de l'ajout. Pour celà cliquer sur le bouton 
"Forcer la création de la sortie". 

<div style="text-align: center;">
<img src="/docs/force-add-boattrip.png" style="width:50%;text-align: center;" />
</div>


<a name="delete-boat-trip"></a>
## Supprimer une sortie en mer

- Cliquez sur l'icône <i class="text-danger fa fa-trash fa-lg"></i> pour supprimer la sortie en mer
> {info} Les sorties supprimées ne sont pas comptabilisées dans les différentes statistiques (nombre de sorties au mois, par support etc...)


<a name="late-boattrip"></a>
## Sortie en mer en retard

Lorsque sortie en mer dépasse l'heure de fin approximative de 30 minutes elle passe au statut <span class="badge bg-danger">En retard</span>
qui vous laissera prendre une décision en conséquence.




<a name="avai-fleets"></a>
## Disponibilité des flottes

Trouver à droite du tableau de bord un aperçu du nombres d'embarcations non utilisés (voir capture d'écran ci dessous).
Vous pouvez aussi utiliser les icônes <i class="text-primary fa fa-plus-circle fa-lg"></i> pour ajouter des sorties en mer avec le support sélectionné.

> {danger.fa-warning} Attention il est possible d'avoir des quantités négatives lorsque vous forcez la création d'une sortie en mer

---
<div style="text-align: center;">
<img src="/docs/avaibility.png" style="width:50%;text-align: center;" />
</div>
