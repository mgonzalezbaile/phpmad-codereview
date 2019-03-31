# Application couple to the Framework #



## Setup ## 



## Problems ## 


We have found the following problems in the audit of the application:

* All the logic of the application in the Controller.

* Difficult testing (WebTestCase).

* Database autogenerate Identity of Entity:
    * You need to persist the entity to be able to work with related entities.
    * If the user does not exist, he throws an exception and the transaction is left halfway. 

* Foreign keys:
    * Model navigation hides the handling of queries to persistence.
    * Problems with the blocking of transitions if the processes work with the related entities. 
    
* Doctrine ORM Events:
    * Hide business logic and other people who work with the entity are not aware of this.
    
* Doctrine ArrayCollection coupled to Library ORM.

* ParamConverter:
    * Hides the handling of queries to persistence and access to repository.

