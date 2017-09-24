var gulp = require('gulp');
var sass = require('gulp-sass');
var browserify = require('gulp-browserify');

gulp.task('default', ['sass', 'fonts', 'js'], function () {
});

gulp.task('sass', function () {
    return gulp.src('assets/scss/app.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('web/build/css'));
});

gulp.task('sass:watch', function () {
    gulp.watch('assets/**/*.scss', ['sass']);
});

gulp.task('fonts', function () {
   gulp.src('node_modules/font-awesome/fonts/**')
       .pipe(gulp.dest('web/build/fonts'))
});

gulp.task('js', function () {
    gulp.src('assets/js/app.js')
        .pipe(browserify())
        .pipe(gulp.dest('web/build/js'))
});
