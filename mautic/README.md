# Sunrise Mautic

Build docker image

```bash
docker build -t sunrise-mautic ./docker/mautic
```

Push into GCP

```bash
docker tag sunrise-mautic gcr.io/sowl-tech/sunrise-mautic/mautic:2.14.2
docker push gcr.io/sowl-tech/sunrise-mautic/mautic:2.14.2
```

Run with docker compose

```bash
docker-compose up
```

# Running Basic Container

Setting up MySQL Server:

```bash
	$ docker volume create mysql_data

	$ docker run --name percona -d \
        -p 3306:3306 \
        -e MYSQL_ROOT_PASSWORD=mypassword \
        -v mysql_data:/var/lib/mysql \
        percona/percona-server:5.7 \
         --character-set-server=utf8mb4 --collation-server=utf8mb4_general_ci
```

Running Mautic:

```bash
	$ docker volume create mautic_data

	$ docker run --name mautic -d \
        --restart=always \
        -e MAUTIC_DB_HOST=127.0.0.1 \
        -e MAUTIC_DB_USER=root \
        -e MAUTIC_DB_PASSWORD=mypassword \
        -e MAUTIC_DB_NAME=mautic \
        -e MAUTIC_RUN_CRON_JOBS=true \
        -e MAUTIC_TRUSTED_PROXIES=0.0.0.0/0 \
        -p 8080:80 \
        -v mautic_data:/var/www/html \
        mautic/mautic:latest
```

This will run a basic mysql service within Mautic on http://localhost:8080.

## Customizing Mautic Container

The following environment variables are also honored for configuring your Mautic instance:

#### Database Options
-	`-e MAUTIC_DB_HOST=...` (defaults to the IP and port of the linked `mysql` container)
-	`-e MAUTIC_DB_USER=...` (defaults to "root")
-	`-e MAUTIC_DB_PASSWORD=...` (defaults to the value of the `MYSQL_ROOT_PASSWORD` environment variable from the linked `mysql` container)
-	`-e MAUTIC_DB_NAME=...` (defaults to "mautic")
-	`-e MAUTIC_DB_TABLE_PREFIX=...` (defaults to empty) Add prefix do Mautic Tables. Very useful when migrate existing databases from another server to docker.

If you'd like to use an external database instead of a linked `mysql` container, specify the hostname and port with `MAUTIC_DB_HOST` along with the password in `MAUTIC_DB_PASSWORD` and the username in `MAUTIC_DB_USER` (if it is something other than `root`).


If the `MAUTIC_DB_NAME` specified does not already exist on the given MySQL server, it will be created automatically upon startup of the `mautic` container, provided that the `MAUTIC_DB_USER` specified has the necessary permissions to create it.

### Mautic Options
-	`-e MAUTIC_RUN_CRON_JOBS=...` (defaults to true - enabled) If set to true runs mautic cron jobs using included cron daemon
-	`-e MAUTIC_TRUSTED_PROXIES=...` (defaults to empty) If it's Mautic behing a reverse proxy you can set a list of comma separated CIDR network addresses it sets those addreses as trusted proxies. You can use `0.0.0.0/0` or See [documentation](http://symfony.com/doc/current/request/load_balancer_reverse_proxy.html)
-	`-e MAUTIC_CRON_HUBSPOT=...` (defaults to empty) Enables mautic crons for Hubspot CRM integration
-	`-e MAUTIC_CRON_SALESFORCE=...` (defaults to empty) Enables mautic crons for Salesforce integration
-	`-e MAUTIC_CRON_PIPEDRIVE=...` (defaults to empty) Enables mautic crons for Pipedrive CRM integration
-	`-e MAUTIC_CRON_ZOHO=...` (defaults to empty) Enables mautic crons for Zoho CRM integration
-	`-e MAUTIC_CRON_SUGARCRM=...` (defaults to empty) Enables mautic crons for SugarCRM integration
-	`-e MAUTIC_CRON_DYNAMICS=...` (defaults to empty) Enables mautic crons for Dynamics CRM integration

### Enable / Disable Features
-	`-e MAUTIC_TESTER=...` (defaults to empty) Enables Mautic Github Pull Tester  [documentation](https://github.com/mautic/mautic-tester)


### Persistent Data Volumes

On first run Mautic is unpacked at `/var/www/html`. You need to attach a volume on this path to persist data.
