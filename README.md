# About

Minecraft Manager is a management webinterface for Minecraft servers built int Laravel and Vue.  
Minecraft servers can be running anywhere so long as the RCON and Query port are open and the application can establish
an (S)FTP connection to the host.
This project is currently a work-in-progress and is not yet intended for actual use.

# Installation

### Manager system requirements

[Docker](https://docs.docker.com/engine/install) + [Compose plugin](https://docs.docker.com/compose/install/linux/#install-using-the-repository)

### Minecraft host system requirements

> ⚠️ **Always ensure all your servers are up-to-date and use secure passwords or SSH keys where available**  
> Never use the root user to run services that are exposed to the network, always create users with the minimum required
> access. ([Principle of least privilege](https://en.wikipedia.org/wiki/Principle_of_least_privilege))

Ensure the required ports are opened to a network available to the manager.  
These ports default to:

- Minecraft / Minecraft query: `25565`
- Minecraft RCON: `25575`
- SFTP: `22` or FTP (not recommended because of security concerns): `20` and `21`
- SSH: `22`

#### Set up server.properties

The important settings to set are as follows:

```ini
broadcast-rcon-to-ops = false
enable-query = true
enable-rcon = true
enable-status = true
query.port = 25565
rcon.password = <randomly generated strong password>
rcon.port = 25575
server-port = 25565
```

<details>
<summary style="font-size: 1.2em">Ubuntu / Debian</summary>

> ℹ️ Make sure to run `apt update` and `apt upgrade` before installing requirements.  
> If apt repositories are not up-to-date, some installs might not be available

#### For running minecraft

- Java version required for the planned minecraft server
  - Versions 1.12 - 1.17 require Java 8:
  ```bash
  apt install -y openjdk-8-jre-headless
  ```
  - Versions 1.18 and above require Java 17+, newer versions provide better performance and
    security:
  ```bash
  apt install -y openjdk-23-jre
  ```

#### For FTP connectivity

- [An FTP server](https://ubuntu.com/server/docs/service-ftp)

#### For SSH connectivity

- [An SSH server](https://ubuntu.com/server/docs/service-openssh)
- [The file utility](https://www.darwinsys.com/file/)
  ```bash
  apt install -y file
  ```
- [Curl](https://curl.se/) or [GNU WGet](https://www.gnu.org/software/wget/)
  ```bash
  apt install -y curl
  ```
  ```bash
  apt install -y wget
  ```

</details>

<details>
<summary style="font-size: 1.2em">Docker</summary>

- [Docker](https://docs.docker.com/engine/install) + [Compose plugin](https://docs.docker.com/compose/install/linux/#install-using-the-repository)

</details>

<details>
<summary style="font-size: 1.2em">Oracle Linux</summary>

TODO, needs testing

</details>

<details>
<summary style="font-size: 1.2em">CentOS/RHEL</summary>

TODO, needs testing

</details>

<details>
<summary style="font-size: 1.2em">Distro-agnostic</summary>

#### For running minecraft

- Java version required for the planned minecraft server
  - Versions 1.12 - 1.17 require Java 8:
  - Versions 1.18 and above require Java 17+, newer versions provide better performance and
    security:

#### For FTP connectivity

- An FTP server

#### For SSH connectivity

- An SSH server
- [The file utility](https://www.darwinsys.com/file/)
- [Curl](https://curl.se/) or [GNU WGet](https://www.gnu.org/software/wget/)

</details>

# Development

## Install

1. Due to EULA reasons, for local development please download the minecraft server jar manually
   from https://www.minecraft.net/en-us/download/server and save it to .docker/local/minecraft/server.jar
2. Don't forget to accept the [EULA](.docker/local/minecraft/eula.txt) and restart the Minecraft server after the first
   run
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

## Manually build production Docker image

If for some reason you want to manually build the production Docker image, it can be done through the following command:

```bash
docker build -f .docker/production/Dockerfile -t mc-manager .
```

# Licensing

Minecraft Manager is licensed under the [MIT License](LICENSE), with some changes. Please find the full license in the
project root.  
The license and its changes come down to that you're completely free to do anything with the project and its code, so
long as you don't earn money from selling this product.

This means that you ARE allowed to run any Minecraft server you want without limitations on this software.  
However, you ARE NOT allowed to sell access to this software or servers hosted using this software.

If you have any questions about the licensing or any other part of the software, feel free to send me a message or raise
an issue in the GitLab.
