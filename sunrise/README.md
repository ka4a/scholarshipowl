# Sunrise Scholarships Management

## Installation

Project is using Laravel Framework so installation same as for other Laravel projects.

## Shell access to kubernetes

First of all you must have `gcloud` CLI installed on your local machine.
See: [https://cloud.google.com/sdk/install](https://cloud.google.com/sdk/install)
As well you will need `kubectl` installed.
See: [https://kubernetes.io/docs/tasks/tools/install-kubectl/](https://kubernetes.io/docs/tasks/tools/install-kubectl/)

Then authorize to get access to Google Cloud Console.

```bash
gcloud auth login
```

Get access to kubernetes cluster.
```bash
gcloud container clusters get-credentials sowl-tech
```

Get list of pods for Sunrise project
```bash
kubectl -n sunrise-dev get pods
```

You will see list of all Kube pods, and you need to find pod matching next mask: `sunrise-dev-develop-app-XXXX..`
Use this pod name to get access to it.
```bash
kubectl -n sunrise-dev exec -it `sunrise-dev-develop-app-747d758b55-fh2h9` bash
```

### Migrations

We using [Laravel Doctrine Migrations](https://www.laraveldoctrine.org/docs/1.3/migrations) migrations for run migrations. Run migrations:

```bash
php artisan doctrine:migrations:migrate
```

### Passport install
After first install of application we should create default Passport (OAuth 2.0) clients
```
php artisan passport:install
```

### Barn Client
Generate client or get current oauth client.
```
php artisan barn:client
```

### SOWL Client
Generate client or get current oauth client.
```
php artisan sowl:client
```

### Scheduler
After application installed put it into `/etc/crontab`

```
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```


### Organisation
Organisation can be created from console.

```
php artisan organisation:create "Test organisation"
```

In response you should get something like this:
```
Congratulations "Test organisation" organisation created!
Organisation API token: 9YFrvIX14qgrE6bk4h1A9pNW4aTLoeVGrlS77A3kMxgdFtsGQBi5ok6d1wyX
```

Use API Token for Bearer authorization with API.

### Scholarship seed and manipulation

Please generate new scholarship template

```
php artisan scholarship:template:create {organisationId}
```

Then you can publish scholarship and make it active

```
php artisan scholarship:template:publish {templateId}
```

You will have new scholarship ID that should be used for apply.

```
php artisan scholarship:apply {scholarshipId}
```

In order to force expire scholarship and run winner award mechanism run:

```
php artisan scholarship:expire {scholarshipId}
```

In order to add new winners use next command `websiteId` - take from scholarship data.

```
curl -X POST https://sunrise.dev.scholarshipowl.com/api/scholarship_website/{websiteId}/winners \
  -H 'Authorization: Bearer {access_token}' \
  -F 'data[0][attributes][name]=Test T.' \
  -F 'data[0][attributes][testimonial]=testimonial' \
  -F 'data[0][attributes][image]=@/home/r3volut1oner/Documents/profile.jpg'
```


### Pub\Sub Setup
Setup topics and subscription for pub\sub.

`php artisan pubsub:setup`
