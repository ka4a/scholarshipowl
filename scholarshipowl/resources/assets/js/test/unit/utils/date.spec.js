import { spliteDateToSemanticItems, stringDateToObject,
  monthNumberRepresentation } from 'lib/utils/date'

describe("Splite date string format to semantic items", function() {
  it("spliteDateToSemanticItems is defined", function() {
    expect(spliteDateToSemanticItems).toBeDefined();
  })

  it("should return expected result for write string parameter", function() {
    let result = spliteDateToSemanticItems("27 Aug, 2018");

    expect(result).toEqual(["27", "Aug", "2018"])
  })

  it("should return result for not trimmed write format string parameter", function() {
    let result = spliteDateToSemanticItems("28 Sep, 2017");

    expect(result).toEqual(["28", "Sep", "2017"])
  })

  it("should return result for not right format data with long date month name", function() {
    expect(spliteDateToSemanticItems("13 September, 2017"))
      .toEqual(["13", "September", "2017"])
  })
})

describe('Convert month name to number representation', function() {
  it('monthNumberRepresentation should be defined', function() {
    expect(monthNumberRepresentation).toBeDefined();
  })

  it('should return month number representation when pass month name', function() {
    expect(monthNumberRepresentation("January")).toBe(1);
  })

  it('should return month number representation when pass month name abbreviation', function() {
    expect(monthNumberRepresentation("Jan")).toBe(1);
  })

  it('should return month number representation when pass last month', function() {
    expect(monthNumberRepresentation("December")).toBe(12);
  })

  it('should emmit exeption when string is not month name', function(done) {
    try {
      monthNumberRepresentation("Somting")
    } catch(err) {
      done();
    }
  })
})

describe('Convert date string to objec representation', function() {
  it("stringDateToObject should be defined", function() {
    expect(stringDateToObject).toBeDefined()
  })

  it("should return object numbers date when pass 'day_number month_name, year'", function() {
    expect(stringDateToObject("28 Sep, 2017")).toEqual({day: 28, month: 9, year: 2017})
  })

  it("should return object numbers date when pass 'month_name, day_number year", function() {
    expect(stringDateToObject("August, 12 2018")).toEqual({day: 12, month: 8, year: 2018})
  })

  it("should return object numbers date when pass 'year month_name, day_number", function() {
    expect(stringDateToObject("2015 August, 12")).toEqual({day: 12, month: 8, year: 2015})
  })

  it("should return null when pass wrong year length", function() {
    expect(stringDateToObject("20153 August, 12")).toEqual(null)
  })

  it("should return null when day equal 0", function() {
    expect(stringDateToObject("00 Sep, 2017")).toEqual(null)
  })

  it("should return null when day more then 31", function() {
    expect(stringDateToObject("32 Sep, 2017")).toEqual(null)
  })
})