docker stop ad_mysql
docker rm ad_mysql

docker buildx build -t "ad" .
docker run --name ad_mysql -p 3306:3306 ad
