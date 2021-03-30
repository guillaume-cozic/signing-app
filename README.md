#Developing with TDD

I'm not developping with TDD as adam W, i prefer isolate my eloquent model in the repository layer and let my domain free of all database schema concern.  

Use Cases : 

I test every part of my domain model outside in (from my use case or query into the code). I always try to have the loop "green red refactor" the more faster as possible, by implementing small changes on my test i let the design emerged step by step.


#DDD

Asking if this feature is a core domain model is one of my favorite question ! 
If yes i will spend time on it to develop something robust with a good test coverage. In my core domain model I will think about developping small aggregates in order to product maintenable code. I a sencondary context i may use librairies to develop faster, for billings and subscriptions i will use sparks, for team managment i use a package NAME. 
When i use a third party package 

In this project i differentiated differents "contexts". 

##core domain context => signing context 
- handle fleets
- handle boat trips (creation, add time to a boat trip, cancel...) 

##Secondary context : 
- team managment : invite collaborator on the team, etc...
- billing 
- notifications and emails  
- reporting


