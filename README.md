# laravel-template-project project
[![Test integration on push](https://github.com/dannypas00/laravel-template/actions/workflows/test_integration_on_push.yaml/badge.svg)](https://github.com/dannypas00/laravel-template/actions/workflows/test_integration_on_push.yaml)  
Insert your own readme information here

## Installation
Run the command `make PROJECT_NAME=<your project name here>`, substituting for you project name.  
This make target will install all required dependencies and replace all templating with your chosen project name.  

## Running locally
To run the project locally, after installation simply run `docker compose up -d` to start the docker environment.

To change ports of services, change the [.env](.env) file, ports are imported from there.

## Local environment default urls
- Application: http://localhost:80
- Mailpit: http://localhost:8025

## Default settings
- User credentials:
  - email: test@example.com
  - password: test1234
