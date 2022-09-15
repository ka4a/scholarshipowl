'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var iconfont = require('gulp-iconfont');
var iconfontCss = require('gulp-iconfont-css');
var clearFolder = require('del');
var path = require('path');

var browserSync = require('browser-sync').create();

gulp.task('browser-sync', function() {
    browserSync.init({
        proxy: "localhost:8080"
    });
});

/**
 * @name sass
 * @description C port sass file compilation to css
 * @url https://www.npmjs.com/package/gulp-sass
 */

gulp.task('sass', function () {
    return gulp.src('./public/assets/scss/*.scss')
        .pipe(sass({
            includePaths: [path.resolve(__dirname, 'resources/assets/sass')]
        }))
        .pipe(gulp.dest('./public/assets/css'));
});

gulp.task('sass:watch', ['sass'], function () {
  gulp.watch(['./public/assets/scss/**/*.scss', './public/assets/scss/*.scss'], ['sass']);
});

function hash() {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 15; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

var fontHash = '-' + hash();
var fontName = 'icon';
var runTimestamp = Math.round(Date.now()/1000);
var fileHash = require('gulp-hash-filename');

gulp.task('icon-font', function(){
    // clear forlder before generation
    clearFolder([path.resolve('public/assets/fonts/icon-font/*')]);

    gulp.src(['public/assets/svg/*.svg'])
        .pipe(iconfontCss({
          fontName: fontName + fontHash,
          path: 'public/assets/scss/templates/icon-font.scss',
          targetPath: '../../scss/icon-font.scss',
          fontPath: '../fonts/icon-font/'
        }))
        .pipe(iconfont({
            fontName: fontName + fontHash,
            prependUnicode: true, // recommended option
            formats: ['ttf', 'eot', 'woff', 'woff2', 'svg'], // default, 'woff2' and 'svg' are available
            timestamp: runTimestamp, // recommended to get consistent builds when watching files
            fontHeight: 1001,
            normalize: true
        }))
        .pipe(gulp.dest('public/assets/fonts/icon-font'));
});
/*
* Image optimization
*/

var imagemin = require('gulp-imagemin');

gulp.task('imagemin', () =>
    gulp.src('./public/assets/img/winners/*')
        .pipe(imagemin([
          imagemin.optipng({optimizationLevel: 2}),
        ]))
        .pipe(gulp.dest('./public/assets/img/winners/'))
);

// crear folder public/js/
gulp.task('clear:build-dir', function() {
  clearFolder([path.resolve('public/js/*')]);
})