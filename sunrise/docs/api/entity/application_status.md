# Application Status
Application status represents what happens with application in current moment, if it was accepted or rejected to be
included in winner draw or waiting for review.

<mermaid>
graph TD
  Received -.-> |Seen by reviewer| Review
  Review -.-> |Reviewer accepted| Accepted
  Review -.-> |Reviewer rejected| Rejected
  Accepted ==> Yes(Included in winner draw)
  Rejected ==> No(Not included in winner draw)
</mermaid>

::: warning
Application without requirements should not be reviewed so they automaticaly get `Accepted` status.
:::

## Attributes
* `id`
  * String - Status id. Example: accepted
* `name`
  * String - Status label. Example: Accepted

## List
List of scholarship statues.

| Status ID    | Description                                                  |
|:------------ |:------------------------------------------------------------ |
| **received** | Application received and waiting for review                  |
| **review**   | Application under review                                     |
| **accepted** | Application accepted and will be included in winner picking. |
| **rejected** | Application rejected.                                        |
