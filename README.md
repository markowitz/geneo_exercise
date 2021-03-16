# GENEO TEST

## Tasks
  
  - Endpoint to register users, based on email and password. Email validation is mandatory. Password requirements should be implemented.
  - Endpoint to authenticate users, which should return a JWT token and a refresh token. (Bonus points if JWT token is set as a HTTP cookie and returned in the response object).
  - Users should have two available roles (ROLE_USER and ROLE_ADMIN).
  - A user should be able to create a post which should contain at least a title and content. A user can have many posts, a post can only be owned by one user.
  - Posts can have the ability to contain images.
  - A user can follow many other users, and be able to see those users’ most recent posts.
  - A user with ROLE_ADMIN should be able to see a list of pending posts and have endpoints to approve them. Each post should be publishable. That is, an admin needs to approve a post before it is visible to all other users. Until it is published, the owner should be able to modify the post.
  - Each published post should support comments, where each user who follows the owner of the post can comment.
  - Each post should support tags. Multiple tags can be added against a single post.
  - If a user is removed by an admin, their posts should remain visible.
  - If a post is removed (either by the owner or by an admin), comments and media objects should be removed as well.


## How to Setup
  
  - Clone repo
  - The app is dockerized. To start, ensue you have docker on your system, you can check how to install docker <a href="https://docs.docker.com/get-docker/">here</a> 
  - SET up your copy .env.example to .env and edit your database information 
  - run ```docker-compose up -d``` to build the docker image.
  - bash into the docker container ```docker exec -it geneo.app bash```
  - run ```php bin/console doctrine:migrations:migrate``` to run migration and add ```--env=test``` flag for test migration
  - ```php bin/console doctrine:fixtures:load``` to load data fixtures add the test env flag to load for test db
  - an admin account is setup already with detail
  - email: admin@geneo.com
  - password: password
  - to run tests ```php bin/phpunit```
