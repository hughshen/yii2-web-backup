## Docker 安装

```bash
curl -sSL https://get.docker.com/ | sh
sudo groupadd docker
sudo usermod -aG docker $USER
groups $USER
# 重新登录或重启

# docker-compose
sudo curl -L https://github.com/docker/compose/releases/download/1.15.0/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
docker-compose --version
```

## 使用

```bash
# Init and symlink
docker exec web_php php init --env=DockerDevelopment --overwrite=n
# Migration
docker exec web_php php yii migrate --interactive=0
```

## MySQL [See](https://gist.github.com/spalladino/6d981f7b33f6e0afe6bb)

```bash
# Backup
docker exec CONTAINER /usr/bin/mysqldump -u root --password=root DATABASE > backup.sql
# Restore
cat backup.sql | docker exec -i CONTAINER /usr/bin/mysql -u root --password=root DATABASE
```
