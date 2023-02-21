# NNEWS-PARSER

This project introduces will parsing news using cron job

## Deployment
####git clone the repository
```
git clone git@github.com:alovega/news-parser.git
or
git clone https://github.com/alovega/news-parser.git
```

#### Deployment Notes
 Navigate to the working directory and Compose down all the existing container below is the command
  ``` 
  docker compose down
  ```
 Prune all the dangling images that is news-parser and parser-nginx with below command
  ``` 
  docker image prune -a 
  ```
  
 navigate to working directory and rebuild the containers with command
  ``` 
  docker compose build
  docker compose up 
  ```

 navigate to news-parser-app-1  container and check if cron and service worker(messenger-worker@.service) is active if not activate
  ```
    service cron status
    service cron start

  ```

check if the messenger-worker@.service is active if not use the command to activate
```
   systemctl list-unit-files --type=service
   systemctl start messenger-worker@{1..20}.service
```

 the nginx proxy parser will be listening to port 8000 expose the port through firewall if blocked. If you wish to map to a different port update it on the docker compose file


<br>
#### Endpoints available:
| http methods |    Endpoint route                          |   Endpoint functionality                                     |
| ------------ | ----------------------------------         | ------------------------------------------------------------ |
| POST         | http://localhost:8000/signup                        |   Creates a user account                             |
| POST         | http://localhost:8000/login                         |   Logs in a user                                     |
| GET          | http://localhost:8000/uploaded                      |   Get all news parsed on platform                 |
| POST         | http://localhost:8000/news/create                   |   add news                          |
## Prerequisites



#### Author
Kelvin Alwavega

