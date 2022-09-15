module.exports = {
  important: true,
  theme: {
    screens: {
      sm: "640px",
      md: "768px",
      lg: "1024px",
      xl: "1440px",
    },
    fontFamily: {
      heading: ["Open Sans", "Helvetica", "Arial", "sans-serif"],
      text: ["Open Sans", "Helvetica", "Arial", "sans-serif"],
    },
    colors: {
      prim: {
        "1": "hsla(17, 93%, 63%, 1)", //coral
        "2": "hsla(225, 69%, 62%, 1)", //havelock blue
        "3": "hsla(216, 100%, 90%, 1)", //onahau
        "4": "hsla(225, 100%, 70%, 1)", //cornflower_blue
      },
      sec: {
        "1": "hsla(167, 69%, 52%, 1)", //turquoise
        "2": "hsla(355, 88%, 62%, 1)", //carnation
        "3": "hsla(216, 26%, 31%, 1)", //blue_bayoux
        "4": "hsla(208, 100%, 97%, 1)", //alice_blue
        "5": "hsla(204, 17%, 89%, 1)", //geyser
      },
      gray: {
        "1": "hsla(0, 0%, 18%, 1)", //mine_shaft
        "2": "hsla(0, 0%, 38%, 1)", //dove_gray
        "3": "hsla(0, 0%, 77%, 1)", //silver
        "4": "#ffffff",
      },
      oth: {
        "1": "#F7F7F7", //alabaster
        "2": "hsla(0, 0%, 38%, 1)", //loblolly
        "3": "#F3F3F3", //concrete
        "4": "hsla(0, 100%, 100%, 1)", //silver_chalice
        "5": "hsla(0, 0%, 77%, 1)", //nobel
        "6": "hsla(216, 16%, 63%, 1)", //bali_hai
      },
    },
    fontSize: {
      "40": "40px",
      "32": "32px",
      "28": "28px",
      "25": "25px",
      "24": "24px",
      "22": "22px",
      "20": "20px",
      "19": "19px",
      "18": "18px",
      "17": "17px",
      "16": "16px",
      "15": "15px",
      "14": "14px",
      "13": "13px",
      "12": "12px",
    },
    lineHeight: {
      "14": "1.4",
    },
    borderRadius: {
      xs: "2px",
      sm: "5px",
      md: "10px",
      circle: "50%",
      "50": "50px",
    },
    boxShadow: {
      s: "0 0 0 1px rgba(92,106,196,.1)",
      m: "0px 4px 10px #E3E9EE",
      l: "6px 20px 18px rgba(0, 0, 0, 0.06)",
      xl: "0px 20px 50px rgba(212, 219, 225, 0.72)",
    },
    margin: {
      "0": "0",
      "5": "5px",
      "8": "8px",
      "10": "10px",
      "15": "15px",
      "20": "20px",
      auto: "auto",
    },
    padding: {
      "2": "2px",
      "5": "5px",
      "8": "8px",
      "10": "10px",
      "15": "15px",
      "20": "20px",
      "30": "30px",
      "40": "40px",
    },
    width: {
      "11per": "11%",
      "14per": "14%",
      "50per": "50%",
      "100per": "100%",
    },
    maxWidth: {
      "240": "240px",
    },
    height: {
      "20": "20px",
      "45": "45px",
      "55": "55px",
      "100": "100%",
    },
    minHeight: {
      "80": "80px",
      "96": "96px",
      "45": "45px",
    },
    maxHeight: {
      "20": "20px",
    },
    opacity: {
      "70": "0.7",
    },
    borderColor: theme => theme("colors"),
  },
  variants: {
    opacity: ["responsive", "hover"],
  },
}
