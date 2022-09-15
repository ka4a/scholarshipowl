import capitalize from "lodash/capitalize";

const selectMessage = field => `Please select ${field}`
const enterMessage = filed => `Please enter ${filed}`

export default {
  messages: {
    required: enterMessage,
    min: (field, [length]) => capitalize(
      `${field} is too short, enter minimum ${length} characters`
    ),
  },
  attributes: {
    address2: "address",
    firstName: "first name",
    lastName: "last name",
    zip: "zip",
    state_id: "state",
    stateName: "state/province/region",
    state_name: "state/province/region",
    confirmPassword: "confirm password",
    universities: "colleges",
    university: "college name",
    gpa: "GPA",
    schoolLevel: "school level",
    dateOfBirth: "birthdate",
    highschool: "high school",
    enrollmentDate: "enrollment date",
    graduationDate: "graduation date",
    degree: "field of study",
    degreeType: "degree type",
    careerGoal: "career goal",
    studyOnline: "if you intend to study online",
    militaryAffiliation: "military affiliation",
    parentFirstName: "parent's first name",
    parentLastName: "parent's last name",
    highschoolAddress1: "highschool address 1",
    universityAddress1: "college address 1",
    highschoolGraduationYear: "high school graduation year",
    highschoolGraduationDate: "high school graduation date",
    collegeGraduationDate: "college graduation date",
    sport: "sport",
    question: "a question"

  },
  custom: {
    profileType: {
      required: 'Please select account type',
    },
    dateOfBirth: {
      required: selectMessage,
    },
    schoolLevel: {
      required: selectMessage,
    },
    gender: {
      required: selectMessage,
    },
    ethnicity: {
      required: selectMessage,
    },
    citizenship: {
      required: selectMessage,
    },
    highschool: {
      required: selectMessage,
    },
    enrolled: {
      required: () => "Please complete this field",
    },
    enrollmentDate: {
      required: selectMessage,
    },
    universities: {
      required: "Please enter college name",
      array_min: (field,[size]) => `Please enter at least ${size} college names`,
      true_values_array_min: (field,[size]) => `Please enter at least ${size} college names`,
    },
    gpa: {
      required: selectMessage,
    },
    graduationDate: {
      required: selectMessage,
    },
    highschoolGraduationDate: {
      required: selectMessage
    },
    collegeGraduationDate: {
      required: selectMessage
    },
    degree: {
      required: selectMessage,
    },
    degreeType: {
      required: selectMessage,
    },
    careerGoal: {
      required: selectMessage,
    },
    studyOnline: {
      required: "Please complete this field",
    },
    agreeTerms: {
      checked: "You must agree with the terms and conditions before you continue!"
    },
    studyCountries: {
      required: "Please enter at least one country",
      array_max: "You have entered the maximum number of countries",
    },
    email: {
      required: "Please enter email address",
      email: "Email address is invalid"
    },
    phone: {
      required: "Please enter phone number",
      numeric: "Please enter phone number",
      min: "Phone number is too short"
    },
    state: {
      required: selectMessage
    },
    stateName: {
      required: filed => `Please select ${filed}`
    },
    zip: {
      min: (field, [length]) => `Please enter ${length} digit zip code`,
      required: field => `Please enter ${field} code`
    },
    password: {
      confirmed: "Passwords do not match",
      min: (field, [length]) => `Password is too short, enter min ${length} characters`
    },
    repassword: {
      required: "Please enter password",
      confirmed: "Passwords do not match",
      min: (field, [length]) => `Password is too short, enter min ${length} characters`
    },
    state_id: {
      required: "Please select a state"
    },
    parentFirstName: {
      required: enterMessage
    },
    parentLastName: {
      required: enterMessage
    },
    highschoolGraduationYear: {
      required: selectMessage
    },
    sport: {
      required: selectMessage
    }
  }
};
