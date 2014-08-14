/**
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

var gulp = require('gulp');
var sass = require('gulp-ruby-sass');
var rename = require('gulp-rename');
var minifycss = require('gulp-minify-css');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var livereload = require('gulp-livereload');
var eol = require('gulp-eol');


// paths
var vendorPath = './vendor/';
var destPath = './public/';

gulp.task('default', function () {
});

gulp.task('app-css', function () {
	var arrCssFiles = [
		vendorPath + 'foundation/scss/normalize.scss',
		vendorPath + 'foundation/scss/foundation.scss',
		vendorPath + 'foundation/css/foundation-icons.css',
	];

	return gulp.src(arrCssFiles)
		.pipe(sass({style: 'compressed'}))
		.pipe(minifycss())
		.pipe(concat('app.css'))
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(destPath + 'css'));
//        .pipe(notify({message: 'Foundation5 scss->css complete.'}));
});

gulp.task('app.js', function () {
	var arrAppFiles = [
		vendorPath + 'foundation/js/vendor/modernizr.js',
		vendorPath + 'foundation/js/vendor/jquery.js',
	];

	return gulp.src(arrAppFiles)
		.pipe(eol("\r\n"))
		.pipe(rename({suffix: '.min'}))
		.pipe(uglify())
		.pipe(concat('app.js'))
		.pipe(eol("\r\n"))
		.pipe(gulp.dest(destPath + 'js'));
});

gulp.task('cf-js', function () {

	var zfJsPath = vendorPath + 'foundation/js/';

	var arrJsFiles = [
		zfJsPath + 'vendor/fastclick.js',
		zfJsPath + 'foundation.js',
		zfJsPath + 'foundation/foundation.topbar.js',
		zfJsPath + 'foundation/foundation.dropdown.js',
		zfJsPath + 'foundation/foundation.offcanvas.js',
		zfJsPath + 'foundation/foundation.reveal.js',
		zfJsPath + 'foundation/foundation.accordion.js',
	];

	return gulp.src(arrJsFiles)
		.pipe(rename({suffix: '.min'}))
		.pipe(uglify())
		.pipe(concat('cf.js'))
		.pipe(gulp.dest(destPath + 'js'));
});

gulp.task('watch', function () {

	gulp.watch(vendorPath + 'foundation/scss/**/*.scss', ['unicss']);

	var server = livereload();
	livereload.listen();

	gulp.watch([destPath + '**', destPathShop + '**']).on('change', function (file) {
		server.changed(file.path);
	});
});
