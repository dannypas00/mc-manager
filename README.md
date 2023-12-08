# About
Minecraft Manager is a management webinterface for Minecraft servers built int Laravel and Vue.  
Minecraft servers can be running anywhere so long as the RCON and Query port are open and the application can establish an (S)FTP connection to the host.
This project is currently a work-in-progress and is not yet intended for actual use.

# Development
## Install
1. Due to EULA reasons, for local development please download the minecraft server jar manually from https://www.minecraft.net/en-us/download/server and save it to .docker/local/minecraft/server.jar
2. Don't forget to accept the [EULA](.docker/local/minecraft/eula.txt) and restart the Minecraft server after the first run
3. Ensure you run `php artisan storage:link` from inside the php docker container
4. Run the seeders to seed a default user (test@test.com : test1234) and the local development Minecraft server

## Infrastructure  
A couple services are run from the Docker Compose configuration:
- Nginx & PHP fpm for serving the website
- Horizon for running non-sync queue jobs
- Scheduler for running scheduled commands / queueing scheduled jobs
- Laravel Websockets as a free open source pusher replacement
- MySql as the relational database
- Redis as the job queue and cache database
- Minecraft as a test Minecraft server. Defaults to port 25565 and RCON port 25575 with password test1234
- FTP and SFTP for FTP connections to the local Minecraft server

# Licensing
Minecraft Manager is licensed under the [MIT License](LICENSE), with some changes. Please find the full license in the project root.  
The license and its changes come down to that you're completely free to do anything with the project and its code, so long as you don't earn money from selling this product.

This means that you ARE allowed to run any Minecraft server you want without limitations on this software.  
However, you ARE NOT allowed to sell access to this software or servers hosted using this software.

If you have any questions about the licensing or any other part of the software, feel free to send me a message or raise an issue in the GitLab.
