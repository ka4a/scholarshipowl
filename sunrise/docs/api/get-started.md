---
sidebar: true
---
# Get started
## Authorization
To get access to the API you need to sign up and create API key in [Profile](/profile#api-keys).
The API Key can be used for API authorization. Theres is couple ways of authorization, we suggest to use header.

#### HTTP Header auth
You can append `SUNRISE-API-Key` to the request to get access.
```
SUNRISE-API-Key: 19ea607ec1612750ec08bc195e44e3a7ef0437e0
```
#### HTTP Param auth
You can append `api_token` param to query string of the request.
```
?api_token=19ea607ec1612750ec08bc195e44e3a7ef0437e0
```

#### Testing authorization
To make quick test you can fetch current authenticated user you can make request to fetch it.
See [user entity](./entity/user.md) to get details about more details.

`GET /api/user/me`

<<< @/docs/api/samples/user.me.json

## Relationships
Instances may have relationships that may fetch by adding `include=relation1,relation2` param to the request.
Please read JSON:API relationships [documentation](https://jsonapi.org/format/#document-resource-object-relationships).
You will see available relationships for entity in entities description.

You may request users organisations that he belongs to by adding query string `?include=organisations` it will return
additional information about user organisations.

`GET /api/user/me?include=organisations`

<<< @/docs/api/samples/user.me-include-organisations.json

## Pagination
We respect JSON:API standard for pagination please read JSON:API [documentation](https://jsonapi.org/format/#fetching-pagination).
So you can add pagination for collections by adding `?page[number]=1&page[size]=100` to the request.
