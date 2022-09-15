# Scholarship Field
Scholarship field is represent data that student need to provide to apply on scholarship.

Field can have conditions, called [eligibility](../../concepts/#eligibility).
If `eligibilityType` and `eligibilityValue` is set field has conditions and if conditions not path, application
wouldn't be accepted.

## Attributes
* `id`
  * Integer - Unique identifier of the scholarship field.
* `eligibilityType`
  * String|null - Eligibility type configured on the field, see available options below. Default: null.
* `eligibilityValue`
  * String|null - Eligibility value that depends on `eligibilityType` and

## Relationships
* `field` - [Field](../../concepts/#field) entity that has details about selected field.
  * Instance of [field](./field.md).

## Eligibility types
List of avaialbe eligibility checks.

| Type             | Alias                 | Description                                                                   |
|:----------------:|:---------------------:|:----------------------------------------------------------------------------- |
| eq               | Equals                | Provided data must be equal to `eligibilityValue`                             |
| neq              | Not equals            | Provided data must not be equal to `eligibilityValue`                         |
| lt               | Less then             | Provided data must less than `eligibilityValue`                               |
| lte              | Less then or equal    | Provided data must less than or equal to `eligibilityValue`                   |
| gt               | Greater then          | Provided data must greater than `eligibilityValue`                            |
| gte              | Greater then or equal | Provided data must greater than or equal to `eligibilityValue`                |
| between          | Between               | Provided data must be between 2 comma separated values in `eligibilityValue`  |
| in               | On of                 | Provided data must be on of comma separated values in `eligibilityValue`      |
| nin              | Not on of             | Provided data must be not on of comma separated values in `eligibilityValue`  |
