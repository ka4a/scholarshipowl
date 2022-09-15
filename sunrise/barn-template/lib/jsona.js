import Jsona, { ModelPropertiesMapper, JsonPropertiesMapper } from 'jsona';

class DefaultModelPropertiesMapper extends ModelPropertiesMapper {
  getType(model) {
    return typeof model.getType === 'function' ? model.getType() : null;
  }
  getId(model) {
    return model.id;
  }
  getAttributes(model) {
    const exclude = model.readOnlyAttributes();
    let attributes = model.getAttributes();

    console.log('model', model, 'exclude', exclude);
    return Object.keys(attributes)
      .filter(key => !(exclude.indexOf(key) > -1))
      .reduce((obj, key) => {
        obj[key] = attributes[key];
        return obj;
      }, {});
  }
  getRelationships(model) {
    const exclude = model.readOnlyRelationships();
    const relationships = model.getRelationships();
    const result = {};

    Object.keys(relationships).forEach(name => {
      if (exclude.indexOf(name) > -1) {
        return;
      }

      let relation = relationships[name];

      if (Array.isArray(relation)) {
        relation = relation.map(item => {
          if (typeof item === 'object' && item.id && item.type) {
            return new JsonaModel(item.type, item.id);
          }

          return item;
        })
      }

      result[name] = relation;
    })

    return result;
  }
}

class DefaultJsonPropertiesMapper extends JsonPropertiesMapper {
  createModel(type) {
    return new JsonaModel(type);
  }
  setId(model, id) {
    model.setId(id);
  }
  setAttributes(model, attributes) {
    model.setAttributes(attributes);
  }
  setRelationships(model, relationships) {
    model.setRelationships(relationships);
  }
  setRelationshipLinks(parentModel, relationName, links) {
    // inherit your IJsonPropertiesMapper and overload this method, if you want to handle links
  }
}

const jsona = new Jsona({
    modelPropertiesMapper: new DefaultModelPropertiesMapper(),
    jsonPropertiesMapper: new DefaultJsonPropertiesMapper(),
});

export default jsona;

export class JsonaModel {
  static new(type, attributes, relationships) {
    return (new JsonaModel(type, null, attributes, relationships));
  }
  static cloneId(model) {
    return (new JsonaModel(model.getType(), model.id))
  }
  static clone(model) {
    return this.cloneId(model)
      .setAttributes(model.getAttributes())
      .setRelationships(model.getRelationships());
  }
  static instance(id, type, attributes, relationships) {
    return (new JsonaModel(type, id, attributes, relationships))
  }
  readOnlyAttributes() {
    return this._readOnlyAttributes;
  }
  readOnlyRelationships() {
    return this._readOnlyRelationships;
  }
  setReadOnlyAttributes(readOnlyAttributes) {
    if (!Array.isArray(readOnlyAttributes)) {
      throw new Error('Read only attributes should be an array');
    }
    this._readOnlyAttributes = readOnlyAttributes;
    return this;
  }
  setReadOnlyRelationships(readOnlyRelationships) {
    if (!Array.isArray(readOnlyRelationships)) {
      throw new Error('Read only relationships should be an array');
    }
    this._readOnlyRelationships = readOnlyRelationships
    return this;
  }
  serialize(options = {}) {
    return jsona.serialize({ stuff: this });
  }
  constructor(type, id, attributes, relationships) {
    this.setType(type);

    if (id) {
      this.setId(id);
    }

    if (attributes) {
      this.setAttributes(attributes);
    }

    if (relationships) {
      this.setRelationships(relationships);
    }

    Object.defineProperty(this, '_readOnlyAttributes', {
      enumerable: false,
      writable: true,
      value: [],
    });

    Object.defineProperty(this, '_readOnlyRelationships', {
      enumerable: false,
      writable: true,
      value: [],
    });
  }
  setType(type) {
    Object.defineProperty(this, '_type', {
      enumerable: false,
      writable: false,
      value: type
    });
  }
  setId(id) {
    // this.id = id;
    Object.defineProperty(this, 'id', {
      writable: false,
      enumerable: true,
      value: id,
    });

    return this;
  }
  setAttribute(name, value) {
    this[name] = value;
    return this;
  }
  setRelation(name, value) {
    this[name] = value;

    Object.defineProperty(this, '_relationshipNames', {
      enumerable: false,
      writable: false,
      configurable: true,
      value: this._relationshipNames ?
        this._relationshipNames.push(name) : [name]
    })

    return this;
  }
  setAttributes(attributes) {
    if (typeof attributes === 'object') {
      Object.keys(attributes)
        .forEach(name => { this.setAttribute(name, attributes[name]) });
    }

    return this;
  }
  setRelationships(relationships) {
    if (typeof relationships === 'object') {
      Object.keys(relationships)
        .forEach(name => this.setRelation(name, relationships[name]));
    }

    return this;
  }
  getType() {
    return this._type;
  }
  getAttributes() {
    const exceptProps = ['id'].concat(this._relationshipNames);

    let attributes = {};
    Object.keys(this).forEach(name => {
      if (exceptProps.indexOf(name) === -1) {
        attributes[name] = this[name];
      }
    })

    return attributes;
  }
  getRelationships() {
    let relationships = {};

    if (Array.isArray(this._relationshipNames)) {
      this._relationshipNames.forEach((name) => {
        relationships[name] = this[name];
      })
    }

    return relationships;
  }
}
