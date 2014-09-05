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


// main app.css
gulp.task('app.css', function () {
	var arrCssFiles = [
		vendorPath + 'foundation/scss/normalize.scss',
		vendorPath + 'foundation/scss/foundation.scss',
		vendorPath + 'foundation5fonts/css/foundation-icons.css'
	];

	gulp.src(arrCssFiles)
		.pipe(sass({style: 'compressed'}))
		.pipe(minifycss())
		.pipe(concat('app.css'))
		.pipe(rename({suffix: '.min'}))
		.pipe(eol("\r\n"))
		.pipe(gulp.dest(destPath + 'css'));

	// fonts
	console.log('Moving font files.');
	gulp.src(vendorPath + "foundation5fonts/css/fonts/**.*")
		.pipe(eol("\r\n"))
		.pipe(gulp.dest(destPath + 'css/fonts'));
});


// main app.js (jquery, modernizr)
gulp.task('app.js', function () {
	var arrAppFiles = [
		vendorPath + 'foundation/js/vendor/modernizr.js',
		vendorPath + 'foundation/js/vendor/jquery.js'
	];

	gulp.src(arrAppFiles)
		.pipe(uglify())
		.pipe(concat('app.js'))
		.pipe(rename({suffix: '.min'}))
		.pipe(eol("\r\n"))
		.pipe(gulp.dest(destPath + 'js'));
});


// foundation5 css/js framework
gulp.task('cf.js', function () {

	var zfJsPath = vendorPath + 'foundation/js/';

	var arrJsFiles = [
		zfJsPath + 'vendor/fastclick.js',
		zfJsPath + 'foundation.js',
		zfJsPath + 'foundation/foundation.reveal.js',
		zfJsPath + 'foundation/foundation.tooltips.js'
	];

	gulp.src(arrJsFiles)
		.pipe(uglify())
		.pipe(concat('cf.js'))
		.pipe(rename({suffix: '.min'}))
		.pipe(eol("\r\n"))
		.pipe(gulp.dest(destPath + 'js'));
});


// select2
gulp.task('select2', function () {

	// files
	var aSelect2Files = [
		vendorPath + 'select2/select2.png',
		vendorPath + 'select2/select2-spinner.gif',
		vendorPath + 'select2/select2x2.png'
	];

	gulp.src(aSelect2Files)
		.pipe(gulp.dest(destPath + 'select2/css'));

	// css
	gulp.src([vendorPath + 'select2/select2.css'])
		.pipe(rename({suffix: '.min'}))
		.pipe(minifycss())
		.pipe(eol("\r\n"))
		.pipe(gulp.dest(destPath + 'select2/css'));

	// js
	gulp.src([vendorPath + 'select2/select2.min.js'])
		.pipe(eol("\r\n"))
		.pipe(gulp.dest(destPath + 'select2/js'));
});


// datatables
gulp.task('datatables', function () {

	// js
	gulp.src([vendorPath + 'datatables/datatables/media/js/jquery.dataTables.js'])
		.pipe(uglify())
		.pipe(eol("\r\n"))
		.pipe(rename({
			basename: 'datatables',
			suffix: '.min',
			extname: '.js'
		}))
		.pipe(gulp.dest(destPath + 'datatables/js'));

	// plugins
	// foundation plugin
	// js
	gulp.src([vendorPath + 'datatables-plugins/integration/foundation/dataTables.foundation.js'])
		.pipe(uglify())
		.pipe(eol("\r\n"))
		.pipe(rename({
			prefix: 'foundation.',
			basename: 'datatables',
			suffix: '.min',
			extname: '.js'
		}))
		.pipe(gulp.dest(destPath + 'datatables/plugins/foundation'));

	// css
	gulp.src([vendorPath + 'datatables-plugins/integration/foundation/dataTables.foundation.css'])
		.pipe(minifycss())
		.pipe(eol("\r\n"))
		.pipe(rename({
			prefix: 'foundation.',
			basename: 'datatables',
			suffix: '.min',
			extname: '.css'
		}))
		.pipe(gulp.dest(destPath + 'datatables/plugins/foundation'));

	// images
	gulp.src([vendorPath + 'datatables-plugins/integration/foundation/images/**.*'])
		.pipe(gulp.dest(destPath + 'datatables/plugins/foundation/images'));
});


// toastr
gulp.task('toastr', function () {

	// js
	gulp.src([vendorPath + 'toastr/toastr.min.js'])
		.pipe(eol("\r\n"))
		.pipe(gulp.dest(destPath + 'toastr'));

	// css
	gulp.src([vendorPath + 'toastr/toastr.min.css'])
		.pipe(eol("\r\n"))
		.pipe(gulp.dest(destPath + 'toastr'));
});


// buid all
gulp.task('build-all', [
	'app.js',
	'app.css',
	'cf.js',
	'select2',
	'datatables',
	'toastr'
]);