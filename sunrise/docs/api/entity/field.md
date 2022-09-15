# Field
We can setup required profile data for applying to scholarship.
Each profile data chunk ( name, email, phone, etc. ) called field. Scholarship fields configurated on scholarship setup and in most cases shouldn't be changed later. Each field must be included in the application's data when students apply to the scholarship.

## Attributes
* `id`
  * String - Unique identifier of the field.
* `name`
  * String - Field name.
* `type`
  * String - Field type.
* `options`
  * null|JSON Object - If type is **option** so `options` will be JSON Object, with list of available options.
    Key is the "id" that should be sent as selected value. Item in the list can be 2 types: String or JSON Object. If an option value type is `string` we can use it as option label, another way if it is `object` we MUST have `name` property that can be used as option label.

#### String example
```json
{
  "1": "High school freshman",
  "2": "High school sophomore",
  "3": "High school junior",
  "4": "High school senior",
  "5": "College 1st year",
  "6": "College 2nd year",
  "7": "College 3rd year",
  "8": "College 4th year",
  "9": "Graduate student",
  "10": "Adult/Non-traditional Student"
}
```

#### JSON Object example
```json
{
  "1": {
      "name": "Alabama",
      "abbreviation": "AL"
  },
  "2": {
      "name": "Alaska",
      "abbreviation": "AK"
  },
  "3": {
      "name": "Arizona",
      "abbreviation": "AZ"
  },
  ...,
  "52": {
      "name": "Wyoming",
      "abbreviation": "WY"
  }
}
```

## Actions
* Get all fields details `/api/field`
* Get field details by id `/api/field/@id`

## Default Fields
Each application requires name, email and phone number to apply other fields are optional.

| Id    | Name      | Type  | Description                                                                                                   | Available eligibilities   |
|:----- |:--------- |:----- |:------------------------------------------------------------------------------------------------------------- |:------------------------- |
| name  | Name      | text  | To apply to the scholarship each student need to provide his **full name**.                                   | None                      |
| email | E-mail    | email | Student's private e-mail. We contact the student by sending emails ( winner notification ) to his email.   | None                      |
| phone | Phone     | phone | Student's phone can be also used for contacting the student in case of winning.                                | None                      |

## Fields list
List of available fields it is types and available options.

| Id                            | Type      | Name                          | Description                                                   | [Available eligibilities](./scholarship_field.md#eligibility-types)   |
|:----------------------------- |:--------- |:----------------------------- |:------------------------------------------------------------- |:--------------------------------------------------------------------- |
| dateOfBirth                   | date      | Date Of Birth                 | Student's date of birth                                       | eq, neq, gt, gte, lt, lte ( Applied to student's age)                 |
| city                          | text      | City                          | Student's city of living                                      |                                                                       |
| address                       | text      | Address                       | Student's address                                             |                                                                       |
| zip                           | text      | Zip                           | Student's zip code                                            |                                                                       |
| [state](#state)               | option    | State                         | Student's USA state where he lives.                           | eq, neq, in, nin                                                      |
| [schoolLevel](#school-level)   | option    | School level                  | Student's school level allowed to apply to the scholarship.   | eq, neq, in, nin                                                      |
| [fieldOfStudy](#field-of-study) | option    | Field Of Study                | Student's field of study ( degree ).                          | eq, neq, in, nin                                                      |
| [degreeType](#degree-type)     | option    | Degree type                   | Student's degree type.                                        | eq, neq, in, nin                                                      |
| [GPA](#gpa)                   | option    | GPA                           | Student's GPA.                                                | eq, neq, in, nin                                                      |
| [gender](#gender)             | option    | Gender                        | Student's gender.                                             | eq, neq, in, nin                                                      |
| [ethnicity](#ethnicity)       | option    | Ethnicity                     | Student's Ethnicity.                                          | eq, neq, in, nin                                                      |
| [careerGoal](#career-goal)     | option    | Career goal                   | Career goal.                                                  | eq, neq, in, nin                                                      |
| enrollmentDate                | date      | Enrollment date               | Date of enrollment to college.                                | eq, neq, gt, gte, lt, lte                                                      |
| highSchoolName                | text      | High school name              | Student's high school name.                                   |                                                                       |
| highSchoolGraduationDate      | date      | High school graduation date   | Student's high school graduation date.                        | eq, neq, gt, gte, lt, lte                                                                      |
| collegeName                   | text      | College name                  | Student's college name.                                       |                                                                       |
| collegeGraduationDate         | date      | College graduation date       | Student's college graduation date.                            | eq, neq, gt, gte, lt, lte                                                                      |

### State

#### Get list field details.
`GET /api/field/state`

<<< @/docs/api/samples/field/field.state.json

### School level

#### Get list field details.
`GET /api/field/schoolLevel`

<<< @/docs/api/samples/field/field.schoolLevel.json

### Field of study

#### Get list field details.
`GET /api/field/fieldOfStudy`

<<< @/docs/api/samples/field/field.fieldOfStudy.json

### Degree type

#### Get list field details.
`GET /api/field/degreeType`

<<< @/docs/api/samples/field/field.degreeType.json


### GPA

#### Get list field details.
`GET /api/field/GPA`

<<< @/docs/api/samples/field/field.GPA.json


### Ethnicity

#### Get list field details.
`GET /api/field/ethnicity`

<<< @/docs/api/samples/field/field.ethnicity.json

### Career goal

### Get list field details.
`GET /api/field/careerGoal`

<<< @/docs/api/samples/field/field.careerGoal.json

### Gender

#### Get list field details.
`GET /api/field/gender`

<<< @/docs/api/samples/field/field.gender.json
