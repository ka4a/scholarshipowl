import { objectToArray } from "lib/utils/utils";

describe("Convert object to array", function() {
  it("objectToArray should be defined", function() {
    expect(objectToArray).toBeDefined();
  })

  it("should return array when pass object", function() {
    expect(Array.isArray(objectToArray({}))).toBe(true);
  })

  it("should return array with primitives when pass object with primitives in values", function() {
    let data = {name: 'Vasiliy', age: 18, height: 184};

    expect(objectToArray(data)).toEqual(['Vasiliy', 18, 184]);
  })

  it("should throw exaption when pass not object data type", function() {
    expect(() => objectToArray(38)).toThrow();
  })

  it("should return exaption when pass null", function() {
    expect(() => objectToArray(null)).toThrow();
  })

  it("should return an array of objects when pass object of objects", function() {
    let data = {
      "23" : { name: 'Vasiliy', age: 18, height: 184 },
      "34" : { name: 'Super Man', age: Infinity, height: 218 },
      "76" : { name: 'Bat Man', age: 38, height: 204 },
    }

    expect(objectToArray([
      { name: 'Vasiliy', age: 18, height: 184 },
      { name: 'Super Man', age: Infinity, height: 218 },
      { name: 'Bat Man', age: 38, height: 204 },
    ]))
  })
})