# GENEO TEST

 This is a simple social networking app, where users can register and follow each other, make posts and comment on posts of users they follow.
 
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
  - copy .env.example to .env and edit your database information
  - run ```docker-compose up -d``` to build the docker image.
  - bash into the docker container ```docker exec -it geneo.app bash```
  - run ``` composer install```
  - run ```php bin/console doctrine:migrations:migrate``` to run migration and add ```--env=test``` flag for test migration
  - if you have issues with migrating the env test, run ```php bin/console doctrine:database:create --env=test```
  - ```php bin/console doctrine:fixtures:load``` to load data fixtures add the test env flag to load for test db
  - run ``` bin/console lexik:jwt:generate-keypair``` to generate jwt key
  - an admin account is setup already with detail
  - email: admin@geneo.com
  - password: password
  - to run tests ```php bin/phpunit```

## Routes

  - POST ```/api/login_check``` to login
       
            ```
              curl --location --request POST '0.0.0.0:43219/api/login_check' \
                --header 'Content-Type: application/json' \
                --data-raw '{
                    "email": "john@abc.com",
                    "password": "testing@12"
                }'
              ```
  - POST ```/api/register``` to create a new user 
      
            ```
            curl --location --request POST '0.0.0.0:43219/api/register' \
            --header 'Content-Type: application/json' \
            --data-raw '{
                "name": "John David",
                "email": "john@abc.com",
                "password": "testing@12"
            }'
            ```
  - POST ```/api/refresh/token``` to refresh login token
       
            ```
                 curl --location --request POST '0.0.0.0:43219/api/token/refresh' \
                    --header 'Content-Type: application/json' \
                    --data-raw '{
                    "refresh_token":                    "d05e93f712f57831c897b3ac1a1c2b31067bd7e8d818decc050a8dc4edfd7745021d83e690ecf3b0ed0741e6e99441149dd1d3d5ea086fb3f85a42e9c0c97981"
                      }'
                      
              ```
              
  - POST ```/api/post``` to create post
      
          ```
            curl --location --request POST '0.0.0.0:43219/api/post' \
              --header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MTU5MzM0OTQsImV4cCI6MTYxNTkzNzA5NCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiam9obkBhYmMuY29tIn0.CpTFWbqr4ECJFxpWLoz4GIbon_MjEIkFQq0RP9ic1Vyvx5qo-ZC9r-8ijcSVEjiTzgpTZ6HSpiaO0Wbhmvb89mK2wHEe5aNlBabAdBnwj0WcyoaYDRx5rdQFV4_T1hjKcYdwnwAzSxnFN3K4X6bjiNwqLWEtv3qcdQM-zTf9ApVS9Kh6amNWbwEQ31GBPngI_PmLQ4CWTBj_UvIVi2nLlkwIi13bKmS-k4zLNm60ujyd-sTed5wAxNgTlissJm6IJ9JG8n8cplQA75bAZpqcgS003VfnfbGgkycWJlqwTAqV3LKwfzO2N03gcbgVkYMYedL14lxcffRoYGPU1UvD1Q' \
              --header 'Content-Type: application/json' \
              --data-raw '{
                  "title": "Testing",
                  "content": "Nam efficitur mauris non mauris imperdiet, at faucibus erat rhoncus. Maecenas iaculis vehicula odio, id dapibus libero pellentesque at. Nunc quis mauris nec urna pretium placerat at ut nulla. Etiam eget interdum sem. Pellentesque volutpat iaculis ipsum eget tempor. Etiam tincidunt libero nec bibendum efficitur. Nulla nec urna nunc. In consequat dictum aliquam. Curabitur elit mi, sollicitudin eget condimentum at, elementum sed dui. Maecenas mollis ipsum eu sapien facilisis auctor. Maecenas nec viverra lorem. Cras dictum, enim ut bibendum rhoncus, ipsum odio efficitur dolor, rhoncus euismod nulla lacus eu enim. Etiam facilisis viverra ultricies. Aliquam erat volutpat. Etiam maximus dignissim metus, vitae porta lacus rhoncus at.",
                  "tags": "lagos, nigeria"
              }'
              
              ```
  - GET ```/api/admin/pending-posts``` to fetch pending posts
  - POST ```/api/admin/post/{id}/approval``` to approve 
      
      ```
        curl --location --request POST '0.0.0.0:43219/api/post/19/approval' \
          --header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MTU4MjkyOTcsImV4cCI6MTYxNTgzMjg5Nywicm9sZXMiOlsiUk9MRV9VU0VSIiwiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6Imljbndha2FubWErMTNAZ21haWwuY29tIn0.nl0HcaVH0a-JlApvdqLuxjgZe_Llxp9dnJV-Y1_DpRBsOQpnBZNkN76uZiWnh_PHWS_2NzKL9LqMu2J_XwXKa1mdlG0kbG6ikk8CDwU0NN_KSIA34Md7ZPL7wlTiDIKUyos6bWSvjj1heiSNlYcGwY28EBXSzX1PjUzO9e85W5tpSkk-lcbZ8D5fHF2GsPnHxiATPYd3zSG0u9wV6LPnUsq5ethin2QFgIQmoD17g_E5oXDwqKCbXRiI7d4XmNDGQy9PaHXJuzJS3I6665RRiCETE-aGNtWpMRfL-teU4N3vq51xtsy9yRUFN4GdV6Ot61-2BaMVBGPW6frRMvJMAw' \
          --header 'Content-Type: application/json' \
          --data-raw '{
              "approved": "omo"
          }'
          ```

  - DELETE ```/api/post/{id}``` to delete post
    
        ```
          curl --location --request DELETE '0.0.0.0:43219/api/post/16' \
              --header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MTU4NDk0ODUsImV4cCI6MTYxNTg1MzA4NSwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiaWNud2FrYW5tYSsxMkBnbWFpbC5jb20ifQ.d2Y13qEr_sQ3ID3xBTYnM4-xhIjO9LoCWrahiStf5cMPma0jnzUna-IvboLeIt0DHgHC70J1Pz6QT1cGWrnDQm9h_FiIP3ep47VIsA1UIa4md0TxyfkyPA6zIl01oEuJ_oz0E_-mr1jrKr54ycsxyQlZid0AmAdDmJT2APuua2xYRkQWkr9ugQnURsJp2vimDsd5NJXQxqeOzalPCE-8HzDva9jlRBQz0SVmJ0JtPS3OZJOpJOWW-8Z7pLGkm1SVfAxXTnzQgVjcK1bF454HZ5lvfHStZaDhtDc54ukZzykooL3aL34RduAAcpWZfi0XtAcGkCmVtM45yO_wHPPOYg'
    
        ```

  - POST ```api/post/{id}/edit``` to edit post
  
      ``` 
          curl --location --request POST '0.0.0.0:43219/api/post/19/edit' \
            --header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MTU4MTg4ODAsImV4cCI6MTYxNTgyMjQ4MCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiaWNud2FrYW5tYSsxM0BnbWFpbC5jb20ifQ.gyBDmCyxU3s6fIWjEyYD0l04FQoM6bmN_ukyd1T7x9p4pDNfblqOlx1P5FiUcE_kav2CEaHjgXnfC8UDLeJD43xawfgdm2i_WxdaT9am3tpmSZHjebk-FmO7un0DkIX7xLcIY7NhOeDoVp5k3NExCoFmJ2tZhz9IIHFvRVGRSToWpeM5PIeTjmvPRrlqDCyLJK1EMAB4O6lM63PIDMXrhT_DhgQ6LinpCqBWIpgrG6EBjEYZSVMD_vX1NyjgaTlpg82wegx4wa5o9ib1RKuVaLX9YM0Dkejzj5mQRxmo1GTj4OwVLYEwC9sIh2SavWwXywh3s__gUIXVkCknTKQRcg' \
            --header 'Content-Type: application/json' \
            --data-raw '{
                "title": "yes we can",
                "content": "Well this is a new test"
            }'
            
       ```
       
   - GET ```/api/post/{slug}``` show single post
   - POST ```/api/post/{id}/comment ``` post comment 
         
         ```
            curl --location --request POST '0.0.0.0:43219/api/post/18/comment' \
              --header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MTU4MjkyOTcsImV4cCI6MTYxNTgzMjg5Nywicm9sZXMiOlsiUk9MRV9VU0VSIiwiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6Imljbndha2FubWErMTNAZ21haWwuY29tIn0.nl0HcaVH0a-JlApvdqLuxjgZe_Llxp9dnJV-Y1_DpRBsOQpnBZNkN76uZiWnh_PHWS_2NzKL9LqMu2J_XwXKa1mdlG0kbG6ikk8CDwU0NN_KSIA34Md7ZPL7wlTiDIKUyos6bWSvjj1heiSNlYcGwY28EBXSzX1PjUzO9e85W5tpSkk-lcbZ8D5fHF2GsPnHxiATPYd3zSG0u9wV6LPnUsq5ethin2QFgIQmoD17g_E5oXDwqKCbXRiI7d4XmNDGQy9PaHXJuzJS3I6665RRiCETE-aGNtWpMRfL-teU4N3vq51xtsy9yRUFN4GdV6Ot61-2BaMVBGPW6frRMvJMAw' \
              --header 'Content-Type: application/json' \
              --data-raw '{
                  "comment": "omo mad oo"
              }'
              
           ```
   - POST ```/api/follow/{id} ``` endpoint to follow user
   - POST ```/api/unfollow/{id} ``` endpoint to unfollow user
   - GET ```/api/user/posts ``` show users posts
   - DELETE ```/api/admin/user/{id}``` delete user
   - POST ```/api/admin/post/{id}/approval ``` approve post
    
        ```
          curl --location --request POST '0.0.0.0:43219/api/admin/post/1/approval' \
            --header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MTU5ODMwMzIsImV4cCI6MTYxNTk4NjYzMiwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiam9obkBhYmMuY29tIn0.bTS5MEMSnz0Xjo64wgL7KTWB9lOFKGL0Xh6tmKEQj2-gjw0w2byZTNWAlqcfXC3RliSOhqg3dM5C4NATzopm6imHLgm-OvNVVmpW5dg4PB-4zUzmelvtSpD1M_GnR_mCa_JBkeA92ng1pEOO_lGfmrKia1qQELtcszpyypiXxz45ZDxwPHbbS8xW8buahvqL296RY91QAb2sWa-Wf7O3dqBLwotF4KglabyS6vBVChvriTGk13MLyBg5c5IVp-MKhHBdMwWD0mu1EO53I_msafORbcqX_SbMxBKFzkq4C_yXK-qy8JwXmmln88xSyPVpkm8euXF3d0fZPdpbypw_FQ' \
            --header 'Content-Type: application/json' \
            --data-raw '{
                "approved": 1
            }'
            
         
          
  - GET ``` /api/posts``` fetches all approved posts
  
  ## Note
  
   - Test Coverage is 70% 
 
