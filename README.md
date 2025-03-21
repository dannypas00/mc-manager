# Minecraft manager

[![Test integration on push](https://github.com/dannypas00/mc-manager/actions/workflows/test_integration_on_push.yaml/badge.svg)](https://github.com/dannypas00/mc-manager/actions/workflows/test_integration_on_push.yaml)

Minecraft Manager is a management webinterface for Minecraft servers built int Laravel and Vue.  
Minecraft servers can be running anywhere so long as the RCON and Query port are open and the application can establish
an (S)FTP connection to the host.
This project is currently a work-in-progress and is not yet intended for actual use.

## Installation

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
<summary style="font-size: 1.2em">Distro-agnostic</summary>

#### For running minecraft

- Java version required for the planned minecraft server
    - Versions 1.12 - 1.17 require Java 8:
    - Versions 1.18 and above require Java 17+, newer versions provide better performance and security

#### For FTP connectivity

- An FTP server

#### For SSH connectivity

- An SSH server
- [The file utility](https://www.darwinsys.com/file/)
- [Curl](https://curl.se/) or [GNU WGet](https://www.gnu.org/software/wget/)

</details>

## Development

To run the project locally, after installation simply run `docker compose up -d` to start the docker environment.

To change ports of services, change the [.env](.env) file, ports are imported from there.

### Infrastructure

A couple services are run from the Docker Compose configuration:

- Nginx & PHP fpm for serving the website
- Horizon for running non-sync queue jobs
- Scheduler for running scheduled commands / queueing scheduled jobs
- Laravel Websockets as a free open source pusher replacement
- MySql as the relational database
- Redis as the job queue and cache database
- Minecraft as a test Minecraft server. Defaults to port 25565 and RCON port 25575 with password test1234
- FTP and SFTP for FTP connections to the local Minecraft server

### Local environment default urls

- Application: http://localhost:80
- Mailpit: http://localhost:8025

## Deploying

### Reverse proxy

To set up a reverse proxy on your host (e.g. for hosting multiple projects on one host):

- Copy the [reverse-proxy.docker-compose.yaml](.docker/production/reverse-proxy.docker-compose.yaml)
  and [reverse-proxy.Caddyfile](.docker/production/reverse-proxy.Caddyfile) to a location on your host.
- Configure the [reverse-proxy.Caddyfile](.docker/production/reverse-proxy.Caddyfile) to accept the correct host
- Run the service with `docker compose up -d`
- Adding more hosts is as simple as copy/pasting the contents of the Caddyfile and changing the external host and
  internal port

## Licensing

Minecraft Manager is licensed under the [MIT License](LICENSE), with some changes. Please find the full license in the
project root.  
The license and its changes come down to that you're completely free to do anything with the project and its code, so
long as you don't earn money from selling this product.

This means that you ARE allowed to run any Minecraft server you want without limitations on this software.  
However, you ARE NOT allowed to sell access to this software or servers hosted using this software.

If you have any questions about the licensing or any other part of the software, feel free to send me a message or raise
an issue in the GitLab.
