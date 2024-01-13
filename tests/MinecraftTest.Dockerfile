FROM openjdk:22-bookworm

RUN useradd -u 1000 mcm-test

USER mcm-test
