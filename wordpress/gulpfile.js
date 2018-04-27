const	gulp			=	require('gulp'),
		sass			=	require('gulp-sass'),
		babel			=	require('gulp-babel'),
		autoprefixer	=	require('gulp-autoprefixer'),
		uglify			=	require('gulp-uglify'),
		cleanCSS		=	require('gulp-clean-css'),
		concat			=	require('gulp-concat'),
		watch			=	require('gulp-watch'),
		browserSync		=	require('browser-sync').create(),
		sourcemaps		=	require('gulp-sourcemaps'),
		image = require('gulp-image'),
		changed = require('gulp-changed');

gulp.task('sass', () => {
	gulp.src('./src/sass/style.sass')
		.pipe(sourcemaps.init())
		.pipe(sass({
			sourcemaps: true
		}).on('error', sass.logError))
		.pipe(autoprefixer({
			cascade: false
		}))
		.pipe(cleanCSS())
		.pipe(sourcemaps.write('../sourcemaps/'))
		.pipe(gulp.dest('./css/'));
});

gulp.task('vendor', () => {
	gulp.src('./node_modules/jquery/dist/jquery.min.js')
		.pipe(gulp.dest('./js/'));

	// gulp.src([
	// 		'./node_modules/bootstrap/js/dist/util.js',
	// 		'./node_modules/bootstrap/js/dist/modal.js',
	// 	])
	// 	.pipe(concat('bootstrap.min.js'))
	// 	.pipe(babel())
	// 	.pipe(uglify())
	// 	.pipe(gulp.dest('./public/wp-content/themes/zanoma/js/'));

});

gulp.task('js', () => {
	gulp.src('./src/js/*.js')
		.pipe(sourcemaps.init())
		.pipe(babel())
		// .pipe(uglify())
		.pipe(sourcemaps.write('../sourcemaps/'))
		.pipe(gulp.dest('./js/'));
});

gulp.task('image', function () {
	var dest = './img';
  gulp.src('./src/img/**/*')
		.pipe(changed(dest))
    .pipe(image())
    .pipe(gulp.dest(dest));
});

gulp.task('watch', () => {
    browserSync.init({
        proxy:		"http://localhost:8005",
        ghostMode:	false,
        notify:		false,
    });
	watch(['./src/sass/**/*.sass'], function(){
		gulp.start('sass');
	});
	watch(['./src/js/**/*.js'], function(){
		gulp.start('js');
	});
	watch(['./src/img/**/*'], function(){
		gulp.start('image');
	});

	watch('./**/*.php').on('change', browserSync.reload);
	watch('./js/**/*.js').on('change', browserSync.reload);
	watch('./css/**/*.css').on('change', browserSync.reload);
});
