const parseErrors = (data, validator) => {
  data.errors.forEach((err) => {
    if (!err || !err.source || !err.source.pointer) {
      return;
    }

    const name = err.source.pointer
      .replace(/data\.(attributes|relationships)\./gi, '')
      .replace(/\.(.*)/gi, '')

    const field = validator.attach({ name });

    if (!field) {
      validator.fields.add({ name })
    }

    /**
     * Replace pointer from the message and place alias.
     */
    const msg = err.detail.map((e) => e.replace(err.source.pointer, field.alias || field.name));

    validator.errors.add({ field: name, msg  });
  });
};

/**
 * Convert Base64 to Blob
 */
function b64toBlob(b64Data, contentType = '', sliceSize = 512) {
  const byteCharacters = atob(b64Data);
  const byteArrays = [];

  for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
    const slice = byteCharacters.slice(offset, offset + sliceSize);

    const byteNumbers = new Array(slice.length);
    for (var i = 0; i < slice.length; i++) {
      byteNumbers[i] = slice.charCodeAt(i);
    }

    const byteArray = new Uint8Array(byteNumbers);

    byteArrays.push(byteArray);
  }

  return new Blob(byteArrays, {type: contentType});
};

/**
 * Convert Base64 Data into File
 */
function b64toFile(b64Data, mime, name) {
  return new File([b64toBlob(b64Data, mime)], name);
}

export {
  parseErrors,
  b64toBlob,
  b64toFile,
};
