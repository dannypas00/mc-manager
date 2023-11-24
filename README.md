# Development
## Install
1. Due to EULA reasons, for local development please download the minecraft server jar manually from https://www.minecraft.net/en-us/download/server and save it to .docker/local/minecraft/server.jar
2. Don't forget to accept the [EULA](.docker/local/minecraft/eula.txt) and restart the Minecraft server after the first run
3. Ensure you run `php artisan storage:link` from inside the php docker container
4. Run the seeders to seed a default user (test@test.com : test1234) and the local development Minecraft server

## Infrastructure  
A couple services are run from the docker compose configuration:
- Nginx & PHP fpm for serving the website
- Horizon for running non-sync queue jobs
- Scheduler for running scheduled commands / queueing scheduled jobs
- Laravel Websockets as a free open source pusher replacement
- MySql as the relational database
- Redis as the job queue and cache database
- Minecraft as a test Minecraft server. Defaults to port 25565 and RCON port 25575 with password test1234
- FTP and SFTP for FTP connections to the local Minecraft server
