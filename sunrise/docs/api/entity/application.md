# Application
If scholarship application was successfull new `Application` entity is created. It saves all provided information and
meta data about the application.

## Attributes
* `id`
  * GUID - Unique identifier of the application.
* `email`
  * String - Applicant email
* `name`
  * String - Applicant full name
* `source`
  * String - Application source. Default: none
* `data`
  * Object - All the data provided by applicant saved as JSON.
* `createdAt`
  * Date - Application creation date.

## Relationships
* `status`
  * Instance of [application status](./application_status.md).
* `scholarship`
  * Instance of [scholarship](./scholarship.md).
