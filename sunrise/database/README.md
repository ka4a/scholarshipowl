# Database

## Migrations

We using [Laravel Doctrine Migrations](https://www.laraveldoctrine.org/docs/1.3/migrations) migrations for run migrations.

Run migrations:

```bash
php artisan doctrine:migrations:migrate
```

After changing entity metadata run `diff` command to generate new migrations depends on changes.
```bash
php artisan doctrine:migrations:diff
```

Generate new migration custom migration:
```bash
php artisan doctrine:migrations:generate
```

Generate new migration file, that will be located at `/database/migrations`

