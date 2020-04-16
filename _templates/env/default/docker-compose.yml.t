---
to: docker/default/docker-compose.yml
---
## generated
version: '3.5'
services:
  <%= docker.name %>:
    restart: always
    container_name: <%= docker.name %>
    image: webdevops/php-apache:ubuntu-16.04
    ports:
      - <%= port %>:80
    volumes:
      - ../../src:/app