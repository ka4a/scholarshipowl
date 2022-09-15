# Requirement

## Attributes

## Relationships

* `text` - Scholarship provider may ask student to write an essay for apply to scholarship.	Text area with simple format options.
May have simple validation as minimal/maximal characters or words.

  * minWords - Minimal words that essay may have
  * maxWords - Maximum amount of words of the essay
  * minChars - Minimum amount of characters in essay
  * maxChars - Maximum amount of characters in essay

* `input` - Can be used for providing some specific information like IDs, numbers.	Text input requirement.
May have validation for characters number.

  * minChars - Minimum amount of characters in input
  * maxChars - Maximum amount of characters in input

* `link` - Can be used for getting link to social network profile or other websites.	Text input requirement.
Must be link format and may have min/max characters validation.

  * minChars - Minimum amount of characters in link
  * maxChars - Maximum amount of characters in link

* `file` - Can be used for getting proof of acceptence/enrollment or video.	File upload.
Can have file size and file extension prohibitions.

  * maxFileSize - Maximum filesize in Kb
  * fileExtensions - File extensions that can be uploaded

* `image` - Can be used for getting proof of acceptence/enrollment as picture.	File upload. File must be an image.
Can have file size and file extension prohibitions.
Can have image sizes prohibitions like min/max width or min/max height.

  * maxFileSize - Maximum filesize in Kb
  * fileExtensions - Image extensions that can be uploaded
  * minWidth - Image min width in pixel
  * maxWidth - Image max width in pixel
  * minHeight - Image min height in pixel
  * maxHeight - Image max height in pixel
