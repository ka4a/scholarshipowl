# Scholarships
With scholarships API you add new application or apply students to scholarship.
As well as pull details of sent applications.

See [scholarship instance](./entity/scholarship.md) for more details.

## List
You need to fetch list of published scholarships to begin work with scholarships API.

`GET /api/scholarship`

<<< @/docs/api/samples/scholarship.index.json

## Fields
You need to get scholarships fields before making new application request.

`GET /api/scholarship/@id/fields`

<<< @/docs/api/samples/scholarship.fields.json

## Apply
We can apply to the scholarship with this API.

Each scholarship may require different students profile data. So each scholarship have own required fields configurations.
Scholarship fields can be fetched via fields relation at API Scholarship Show action.

After we verified that we have all student profile data we can send next request to apply to the scholarship.

### Attributes
List of attributes required for scholarship application is dynamic.
Depends on [Scholarship Fields](/concepts/#field) and [Scholarship Requirements](/concepts/#requirement) configured on scholarship.

* {fieldId} - Dynamic field ID got from `relationships.fields[].field.id` field.
  * In case of field type text, email, phone data must be string
  * In case of field type date data must have date format ( Example: 1989-02-17 )
  * In case of option type option value should be sent.
  * In case of multiple options, comma separated values must be sent.

* requirements - List of requirement data got from scholarship details
  * {scholarshipRequirementId} - Scholarship requirement ID got from scholarship detail `relationships.requirements[].id` field.

    Requirement data depends on `relationships.requirements[].requirement.type`.
    * In case requirement type is text, input or link data should be sent as string
    * In case requirement type is file or image must be sent as file data

* source - application source ( website, mobile application, web application, etc )
  string
  max 255
  Default: none

### Request example
`POST /api/scholarship/@id/apply`

<<< @/docs/api/samples/scholarship.apply.request.json

### Java Script form example
If scholarship has file or image requirements we can't send them as JSON. We must send it as "multipart/form-data".

<<< @/docs/api/samples/scholarship.apply.form.js

### Response
Success response will be in Application show format.

<<< @/docs/api/samples/scholarship.apply.response.json
