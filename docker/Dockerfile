FROM mysql/mysql-server:5.7
ENV MYSQL_ROOT_HOST=%
ENV LANG=C.UTF-8
ENV MYSQL_ROOT_PASSWORD=root

COPY ./CreateUser.sql /docker-entrypoint-initdb.d/1.sql
COPY ./CreateDb.sql /docker-entrypoint-initdb.d/2.sql
