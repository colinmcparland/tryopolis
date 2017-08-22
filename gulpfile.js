var gulp = require('gulp');  //loads the gulp node package
var sass = require('gulp-ruby-sass');

gulp.task('compilecss', function()	{
	return sass('./style.scss')
	.pipe(gulp.dest('.'));
})

gulp.task('compileadmincss', function()	{
	return sass('./student-records-style.scss')
	.pipe(gulp.dest('.'));
})

gulp.task('watch', function()	{
	gulp.watch('./style.scss', ['compilecss']); 
	gulp.watch('./student-records-style.scss', ['compileadmincss']); 
})

gulp.task('default', ['compilecss', 'watch']);
