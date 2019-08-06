# PHPMad Codereview App
Application example being developed as part of a series of workshops given at [PHPMad](https://www.meetup.com/es-ES/phpmad/).

# The Idea
CodeReview is an imaginary tool for developers to push code to some CVS (Control Version System) so that other members (reviewers) can be assigned to review that code. The writer of the code has to pay some money to get the code reviewed and reviewers get paid for reviewing.

# The Workshops
[Mob programming: Refactorización de código legacy](https://www.slideshare.net/MaikelGonzlezBaile/mob-programming-refactorizacin-de-cdigo-legacy): First workshop where it is shown some testing and coding techniques to refactor an application whose code is totally coupled and tangled with the framework to be moved to a separated layer easier to be tested and maintained.

[II - Vitaminando nuestros caso de uso](https://es.slideshare.net/MaikelGonzlezBaile/ii-vitaminando-nuestros-casos-de-uso): Use cases, Commands, Domain Events and functional testing get explained in this second workshop of the series.

# Running the code
## Build docker containers
```
docker-compose -f docker/services-docker-compose.yml build
docker-compose -f docker/infra-docker-compose.yml build
```
## Install dependencies
```
docker-compose -f docker/services-docker-compose.yml run phpmad_pull_request_server composer install
```


## Run migrations to generate MySQL tables
```
docker-compose -f docker/infra-docker-compose.yml up
docker-compose -f docker/services-docker-compose.yml run phpmad_pull_request_server bin/console doctrine:schema:create
```

## Run tests
```
docker-compose -f docker/services-docker-compose.yml run phpmad_pull_request_server vendor/bin/phpunit -c phpunit.xml.dist
```

## Start server
```
docker-compose -f docker/services-docker-compose.yml up
```
