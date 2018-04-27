const	gulp			=	require('gulp'),
		sass			=	require('gulp-sass'),
		babel			=	require('gulp-babel'),
		autoprefixer	=	require('gulp-autoprefixer'),
		uglify			=	require('gulp-uglify'),
		cleanCSS		=	require('gulp-clean-css'),
		concat			=	require('gulp-concat'),
		watch			=	require('gulp-watch'),
		sourcemaps		=	require('gulp-sourcemaps'),
		browserify		= require('browserify'),
		babelify		= require('babelify'),
		source			= require('vinyl-source-stream'),
		gutil			= require('gulp-util'),
		browserSync		=	require('browser-sync').create();

gulp.task('sass', () => {
	gulp.src('./assets/sass/style.sass')
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

	gulp.src('./node_modules/slick-carousel/slick/slick.min.js')
		.pipe(gulp.dest('./js/'));

	gulp.src('./node_modules/slick-carousel/slick/slick.css')
		.pipe(cleanCSS())
		.pipe(gulp.dest('./css/'));

	gulp.src([
			'./node_modules/bootstrap/js/dist/util.js',
			'./node_modules/bootstrap/js/dist/modal.js',
		])
		.pipe(concat('bootstrap.min.js'))
		.pipe(babel())
		.pipe(uglify())
		.pipe(gulp.dest('./js/'));

});

gulp.task('js', function() {
	browserify({
		entries: './assets/js/scripts.js',
		debug: true
	})
	.transform(babelify)
	.on('error',gutil.log)
	.bundle()
	.on('error',gutil.log)
	.pipe(source('scripts.js'))
	.pipe(gulp.dest('./js'));
});

gulp.task('watch', () => {
	browserSync.init({
		proxy:		"http://localhost:8008",
		ghostMode:	false,
		notify:		false,
	});

	watch(['./assets/sass/**/*.sass'], function(){
		gulp.start('sass');
	});
	watch(['./assets/js/**/*.js'], function(){
		gulp.start('js');
	});

	watch('./**/*.php').on('change', browserSync.reload);
	watch('./js/**/*.js').on('change', browserSync.reload);
	watch("./css/**/*.css").on('change', browserSync.reload);
});