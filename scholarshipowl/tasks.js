const fs = require('fs');
const path = require('path');
const concat = require('concat');

const UglifyJS = require("uglify-js");
const CleanCSS = require("clean-css");

const sourceMapFile = 'assets-source-map.json';

const clearDirectory = directory => {
  return new Promise((resolve, reject) => {
    fs.mkdir(directory, err => {
      if(err && err.code === 'EEXIST') {

        fs.readdir(directory, (err, files) => {
          if (err) reject(err);

          for (const file of files) {
            if(file === '.gitignore') continue;

            fs.unlink(path.join(directory, file), err => {
              if (err) reject(err);
            });
          }
        });
      }

      resolve();
    })
  })
};

const generateHash = () => {
  let text = "";
  let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

  for( var i=0; i < 15; i++ )
      text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
};

const writeFile = (filename, data) => {
  return new Promise((resolve, reject) => {
    fs.writeFile(filename, data, err => {
      if(err) reject(err);

      resolve(data);
    })
  })
};

const minifyJs = (files, path, fileName) => {
  console.log('JS - Starting processing for: ' + fileName);
  return concat(files)
    .then(result => UglifyJS.minify(result, {fromString: true}))
    .then(result => writeFile(path + fileName, result.code))
    .then(result => { console.log('JS - Finished processing for: ' + fileName); })
    .catch(err =>  { throw err })
};


const minifyCss = (files, buildPath, fileName) => {
  console.log('CSS - Starting processing for: ' + fileName);

  let resolveRelativePaths = (file) => {
    console.log('CSS - Processing file: ' + file);

    let dir = path.dirname(file);
    let relativeDir = dir.substring(dir.indexOf('public/') + 'public/'.length);

    return concat([file])
      .then(result => {
        let minifyCSS = new CleanCSS({
          target: __dirname + '/public/build/css/',
          relativeTo: relativeDir,
          keepBreaks: true,
          advanced: false,
          aggressiveMerging: false,
          mediaMerging: false,
          processImport: false,
          shorthandCompacting: false
        });

        return new Promise(resolve => resolve(minifyCSS.minify(result)));
      });
  };

  let actions = files.map(resolveRelativePaths);

  Promise.all(actions).then(result => {
     let outputString = '';
     result.map((result) => {
        outputString += result.styles;
     });

     return new CleanCSS().minify(outputString)
  })
    .then(result => writeFile(buildPath + fileName, result.styles))
    .then(result => { console.log('CSS - Finished processing for: ' + fileName) })
    .catch(err =>  { throw err })
};

const assetsJs = () => {
  let sourceMap = JSON.parse(fs.readFileSync(__dirname + '/' + sourceMapFile, 'utf8'));
  let buildPath = __dirname + '/public/' + sourceMap.buildPath + 'js/';
  clearDirectory(buildPath).then(() => {
    Object.keys(sourceMap.js).forEach(function(key) {
      let val = sourceMap.js[key];
      let fileNameHash = generateHash();
      let files = val.map(name => path.join(__dirname + '/public/', name));
      let fileName = `${key}-${fileNameHash}.min.js`;
      minifyJs(files, buildPath, fileName);
    })
  }).catch(err => { throw err });
};

const assetsCss = () => {
  let sourceMap = JSON.parse(fs.readFileSync(__dirname + '/' + sourceMapFile, 'utf8'));
  let buildPath = __dirname + '/public/' + sourceMap.buildPath + 'css/';
  clearDirectory(buildPath).then(() => {
    Object.keys(sourceMap.css).forEach(function(key) {
      let val = sourceMap.css[key];
      let fileNameHash = generateHash();
      let files = val.map(name => path.join(__dirname + '/public/', name));
      let fileName = `${key}-${fileNameHash}.min.css`;
      minifyCss(files, buildPath, fileName);
    })
  }).catch(err => { throw err });
};

const assetsAll = () => {
  assetsCss();
  assetsJs();
};

module.exports = {
  assetsJs,
  assetsCss,
  assetsAll
};