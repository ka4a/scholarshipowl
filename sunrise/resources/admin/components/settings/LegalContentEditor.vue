<template>
  <b-field>
    <quill-editor
      ref="editor"
      :class="{ 'is-pdf': isPdf }"
      :value="value"
      @input="$emit('input', $event)"
      :options="editorOptions">
    </quill-editor>
  </b-field>
</template>
<script>
import { Quill, quillEditor as QuillEditor } from 'vue-quill-editor';

const SizeStyle = Quill.import('attributors/style/size');

/**
 * List of available tags.
 * After adding new tag you MUST add also CSS style for set tag name.
 */
const tags = [
  'scholarship_id',
  'scholarship_url',
  'scholarship_pp_url',
  'scholarship_amount',
  'scholarship_awards',
  'scholarship_start',
  'scholarship_deadline',
  'scholarship_timezone',

  'organisation_name',
  'organisation_business_name',
  'organisation_website',
  'organisation_email',
  'organisation_phone',
  'organisation_address'
];

export default {
  name: 'ContentEditor',
  components: {
    QuillEditor,
  },
  props: {
    value: String,
    isPdf: Boolean,
  },
  mounted() {
    // Add bulma "content" class to quill root container
    this.$refs.editor.quill.root.classList.add('content');
  },
  computed: {
    editorOptions: () => ({
      modules: {
        toolbar: {
          container: [
            [{ 'tags': tags }],
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'font': [] }],
            // [{ 'header': 1 }, { 'header': 2 }],
            [{ 'size': [false].concat(SizeStyle.whitelist) }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'script': 'sub'}, { 'script': 'super' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            // [{ 'direction': 'rtl' }],
            [{ 'align': [] }],
            ['link'],
            ['clean'],
          ],
          handlers: {
            "tags": function (value) {
              if (value) {
                const cursorPosition = this.quill.getSelection().index;
                this.quill.insertText(cursorPosition, `[${value}]`, 'dynamic-tag', 'user');
                this.quill.setSelection(cursorPosition + value.length + 2);
              }
            }
          },
        },
      }
    }),
  }
}
</script>
<style lang="scss" scoped>
/deep/.quill-editor {
  .content {
    margin-bottom: 0;
  }
  &.is-pdf {
    .ql-editor {
      font-family: sans-serif;
      h1, h2, h3, h4, h5, h6 {
        font-weight: bold;
      }
    }
  }
  .ql-dynamic-tag {
    background-color: #FFF200;
  }
  .ql-tags {
    width: 110px;
    .ql-picker-label {
      &::before {
        content: 'Dynamic Tag';
      }
    }
    .ql-picker-item {
      &[data-value=scholarship_id]::before {
        content: 'Scholarship ID';
      }
      &[data-value=scholarship_url]::before {
        content: 'Scholarship website URL';
      }
      &[data-value=scholarship_pp_url]::before {
        content: 'Scholarship Privacy Policy URL';
      }
      &[data-value=scholarship_amount]::before {
        content: 'Scholarship Award Amount';
      }
      &[data-value=scholarship_awards]::before {
        content: 'Scholarship Awards Number';
      }
      &[data-value=scholarship_start]::before {
        content: 'Scholarship Start Date';
      }
      &[data-value=scholarship_deadline]::before {
        content: 'Scholarship Deadline Date';
      }
      &[data-value=scholarship_timezone]::before {
        content: 'Scholarship Timezone';
      }
      &[data-value=organisation_name]::before {
        content: 'Organisation Name';
      }
      &[data-value=organisation_business_name]::before {
        content: 'Organisation Business Name';
      }
      &[data-value=organisation_address]::before {
        content: 'Organisation Address';
      }
      &[data-value=organisation_website]::before {
        content: 'Organisation Website';
      }
      &[data-value=organisation_phone]::before {
        content: 'Organisation Phone';
      }
      &[data-value=organisation_email]::before {
        content: 'Organisation Email';
      }
    }
  }
}
</style>
