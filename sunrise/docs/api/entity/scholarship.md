# Scholarship
Scholarship instance it is published scholarship that can receive new applications.
Other words applicant can apply to it.

## Attributes
* `id`
  * GUID - Unique identifier of the scholarship.
* `title`
  * String - Scholarship name or basic description.
* `description`
  * String - Full scholarship description.
* `amount`
  * Number - How many money in dollars, receive 1 winner (award)
* `awards`
  * Number - Number of scholarship awards

* `start`
  * Date - Date in `timezone` when scholarship starts.
* `deadline`
  * Date - Date in `timezone` when scholarship deadline is.
* `timezone`
  * String - Scholarship timezone

* `recurringType`
  * String|null - Period type
    * day
    * week
    * month
    * year
* `recurringValue`
  * String|null - Period value

* `expiredAt`
  * Date|null - If set scholarship is expired (after deadline) and new applications can't be sent.

::: warning
If `recurringType` and `recurringValue` is not null scholarship is repeatable. Recurring period can be build by
concatinating `recurringValue` and `recurringType` examples: 1 day, 1 week, 1 month, 1 year.
:::

## Relationships
* `fields` - List of required for application fields. It is also has [eligibility](../../concepts/#eligibility)
  configurations that must be checked before application.
  * Array of [scholarship field](./scholarship_field.md)s.

<!--
* `requirements` - List of [requirement](../../concepts/#requirement)s that must be sent as application data.
  * Array of [scholarship requirement](./scholarship_requirement.md)s.
  * [/api/scholarship/@id/requirements](../scholarships.md#requirements) - Get requirements by scholarship id
-->

## Meta
* `next`
  * Date|null - If scholarship is repeatable next scholarship start date will be returned.

## Actions
* [/api/scholarship/@id/fields](../scholarships.md#fields) - Get fields by scholarship id
<!-- * [/api/scholarship/@id/requirements](../scholarships.md#requirements) - Get requirements by scholarship id -->

<!--
### template
Scholarship settings.
#### Type
Instance of [scholarship template](./scholarship_template.md)s.
#### Actions
Get fields by scholarship id [/api/scholarship/@id/template](../scholarships.md#template)

### content
Scholarship legal and other content.
#### Type
Instance of [scholarship content](./scholarship_content.md)s.
#### Actions
Get fields by scholarship id [/api/scholarship/@id/content](../scholarships.md#content)

### winners
 -->
