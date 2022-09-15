# Doctrine usage

## Generating entity classes


### Generate entities from database

```
php artisan doctrine:convert:mapping --from-database annotation ./doctrine
```

1) Command will generate entities with annotations and properties in ./doctrine folder

2) Copy required needed entity to app\Entity

3) Change namespace for entity to \App\Entity and change `private` to `protected`

4) **Validate annotations**

### Generating entity getters/setters

```
php artisan doctrine:generate:entities ./doctrine
```

1) Command will generate entities with setters/getters in ./doctrine/App folder

2) Copy generated setters and getters to correct class

3) **Validate new setters and getters working**

### Clean

Remove ./doctrine folder
