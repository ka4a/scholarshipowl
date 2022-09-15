---
sidebar: auto
---

# Concepts
To get into Surnise advanced usage, you will need to know some base knowledge about basic Sunrise concept.

## Scholarship
Scholarship instance created right after scholarship publish.
New created `scholarship` instance an `id` (GUID) that can be used later in API calls or webhooks.

Scholarship id example: **4424406d-36a5-11e9-aff3-7824afb64838**

### Field
As different scholarship may require different information from student we want our scholarship be agile and and be configurable in different ways to accept different kind of data from student's profile.

Each profile data chunk ( name, email, phone, etc. ) called [field](../api/entity/field.md). Scholarship may have many configured fields. Each field must be included in application's data when students applies to the scholarship.

Fields must be configurable in scholarship provider interface when he creates new scholarship.

### Eligibility
Fields could also be configured with additional "conditions" that can be used for creating automatic scholarship eligibility check.

**For example**: If scholarship eligible only for students 18 years old.
So we should ask student for his date of birth and do not accept application if age below 18 years old.

### Requirement
Scholarship may require some additional information from the user, not only the profile data.
Such information is called [requirement](../api/entity/requirement.md). Requirements can be of the different types.

Requirements displayed on application form with different input depend on the requirement type.
Scholarship requirements must be verified against their basic field validations and other validations depending on configuration,

### Requirement Type
Depends on information we want get from students we may use different input type on application form.
Each requirement type have different input type and may have different view on review options in review application process.

### Recurrence
If scholarship repeatable and it is configured so in Deadline configurations.
New [scholarship](../api/entity/scholarship.md) will be created right after previous scholarship instance deadline.

If you have repeatable scholarship you need to subscribe using one of integration to `scholarship published` and `scholarship deadline` events if you want to be subscribed to scholarship `application` events.

## Application
When scholarship became active (published) it accepts new applications.
On scholarship apply action if we have all applicant data and it passes validation new `application` created.
So after deadline scholarship manager needs to finish review applications and run winner draw.

### Application Status
If scholarship have `requirements` application will have `received` status and must be reviewed
before it is `accepted` into winner draw. As well application can be `rejected`.

Other case scholarship don't have `requirements` so application status will automatically receive `accepted` status
and on scholarship deadline winner will be chosen automatically.

* `received` - Application received and waiting for review
* `review` - Application under review
* `accepted` - Application accepted and will be included in winner picking.
* `rejected` - Application rejected.

## Winner
After scholarship deadline we need to pick a scholarship winner from `accepted` applications.

::: warning
If scholarship don't have requirements winner picked automatically from accepted applications list or after deadline
after all applications reviewed winner picking mechanism need to be run manually.
:::
