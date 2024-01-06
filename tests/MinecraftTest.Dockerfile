FROM openjdk:22-bookworm

RUN useradd -u 1000 mcm-test
RUN apt update && apt install screen -y

USER mcm-test
