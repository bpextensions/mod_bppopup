'use strict';

var gulp = require('gulp');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var watch = require('gulp-watch');
var sass = require('gulp-sass');
var clean = require('gulp-clean');
var merge = require('merge-stream');
var parallel = gulp.parallel;
var series = gulp.series;

/**
 * App assets building.
 * 
 * @type Array
 */
var app = {
    
    /**
     * Clean theme output (css and js).
     * 
     * @return {unresolved}
     */
    clean: function(){
        return gulp.src([
            'modules/mod_bppopup/assets/**/*'
        ], {read: false})
        .pipe(clean());
    },
    
    /**
     * Build app JS.
     * 
     * @return {unresolved}
     */
    js: function(){

        return gulp.src([
            'modules/mod_bppopup/.dev/js/**/*.js'
        ])
        .pipe(concat('app.js'))
        .pipe(gulp.dest('modules/mod_bppopup/assets/'))
        .pipe(uglify({
            output: {
                comments: /^!/
            }
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('modules/mod_bppopup/assets/'));

    },
    
    /**
     * Build theme CSS from SASS files.
     * 
     * @return {unresolved}
     */
    sass: function () {
        return gulp.src([
            'modules/mod_bppopup/.dev/sass/**/*.scss',
            'modules/mod_bppopup/.dev/sass/**/*.sass'
        ])
        .pipe(sass({
                outputStyle: 'expanded',
                loadPaths: ['./node_modules']
            }).on('error', sass.logError))
        .pipe(gulp.dest('modules/mod_bppopup/assets/'))
        .pipe(sass({
                outputStyle: 'compressed',
                loadPaths: ['./node_modules']
            }).on('error', sass.logError))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('modules/mod_bppopup/assets/'));
    },
    
    /**
     * Watch for SASS changes and build CSS when chagnes are made.
     * 
     * @return {undefined}
     */
    watchSass: function(){
        return gulp.watch([
            'modules/mod_bppopup/.dev/sass/**/*.sass',
            'modules/mod_bppopup/.dev/sass/**/*.scss'
        ], series('app:sass'));
    },
    
    /**
     * Watch fo JS chagnes and build JS when changes are made.
     * 
     * @return {undefined}
     */
    watchJs: function(){
        return gulp.watch([
            'modules/mod_bppopup/.dev/js/**/*.js'
        ], series('app:js'));
    }
};

/**
 * Vendor assets building.
 * 
 * @type Array
 */
var vendor = {
    
    /**
     * Clean vendor assets directories.
     * 
     * @return {unresolved}
     */
    clean: function(){
        return gulp.src([
            'modules/mod_bppopup/assets/**/*'
        ], {read: false})
        .pipe(clean());
    },
    
    /**
     * Copy vendor modules into assets.
     * 
     * @return {unresolved}
     */
    modules: function(){

        // Bundle Magnific Popup
        var magnificPopup = gulp.src([
            'node_modules/magnific-popup/dist/**'
        ])
        .pipe(gulp.dest('modules/mod_bppopup/assets/'));

        // Magnific Popup Compression
        var magnificPopupCompression = gulp.src([
            'node_modules/magnific-popup/dist/magnific-popup.css'
        ])
        .pipe(uglifycss())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('modules/mod_bppopup/assets/'));

        return merge(
            magnificPopup,
            magnificPopupCompression,
        );
    },
    
    /**
     * Build vendor JS/CSS into a single bundle file.
     * 
     * @return {unresolved}
     */
    bundle: function(){

        // Bundled vendor JavaScript
        var vendorPackageJavaScript = gulp.src([
            'modules/mod_bppopup/.dev/vendor/**/*'
        ])
        .pipe(concat('vendor.js'))
        .pipe(gulp.dest('modules/mod_bppopup/assets/'))
        .pipe(uglify({
            output: {comments: /^!/}
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('modules/mod_bppopup/assets/'));

        // Bundled vendor StyleSheet
        var vendorPackageStylesheet = gulp.src([
            'modules/mod_bppopup/.dev/vendor/**/*'
        ])
        .pipe(concat('vendor.css'))
        .pipe(gulp.dest('modules/mod_bppopup/assets/'))
        .pipe(uglifycss({
            output: {comments: /^!/}
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('modules/mod_bppopup/assets/'));

        return merge(
            vendorPackageJavaScript,
            vendorPackageStylesheet
        );
    }
};

// App javascript
gulp.task('app:js', app.js);

// Vendor libraries
gulp.task('vendor:clean', vendor.clean);
gulp.task('vendor:bundle', vendor.bundle);
gulp.task('vendor:modules', vendor.modules);
gulp.task('vendor', series('vendor:clean', 'vendor:modules', 'vendor:bundle'));

// Clean app
gulp.task('app:clean', app.clean);

// App SASS
gulp.task('app:sass', app.sass);
 
// Watch SASS files
gulp.task('sass:watch', app.watchSass);

// Watch JavaScript files
gulp.task('js:watch', app.watchJs);

// Watch everything
gulp.task('watch', parallel('sass:watch','js:watch'));

// Run default tasks
gulp.task('default', parallel('app:js','app:sass','vendor'));