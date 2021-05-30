#Signing App 



##Developing with TDD

I'm not developing with TDD as adam W, i prefer isolate my eloquent model in the repository layer and let my domain
pure of all database schema concerns.

#### Outside in development

I test every part of my domain model outside in (from my use case or query into the code). 
I always try to have the loop "green red refactor" the faster as possible, 
by implementing small changes on my test I let the design emerged step by step.

#### Classical vs Mockist
I'm in a classical approach of TDD, I'm used to developing double instead of mock, we get the same feedback and tests breaks 
often when we use mocks. You can find my double (InMemory implementation in tests folders)
[https://martinfowler.com/articles/mocksArentStubs.html][Classical vs mock TDD]

##DDD

Asking if this feature is a core domain model is one of my favorite question ! 
If yes i will spend time on it to develop something robust with a good test coverage.
For the core domain model I will think about developing small aggregates in order to product maintainable code.
For the secondaries contexts I may use libraries to develop faster (billings and subscriptions 
with sparks, for team management teamWork...) 
When I use a third party package I consider it "safe", and I will not write unit test, but some integration test. 

In this project i differentiated "contexts" as follows :

##core domain context => signing context 
- handle fleets
- handle boat trips (creation, add time to a boat trip, cancel...) 

##Secondary context : 
- team management : invite collaborator on the team, etc...
- billing 
- notifications and emails  
- reporting



[Classical vs mock TDD]: https://martinfowler.com/articles/mocksArentStubs.html
