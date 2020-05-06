/**
 * Automating Development Tasks
 * ----------------------------
 * @package awsm-team
 */

"use strict";

/*============================= Dependencies =============================*/

const gulp = require("gulp"),
	config = require("./config"),
	concat = require("gulp-concat"),
	rename = require("gulp-rename"),
	clone = require("gulp-clone"),
	merge = require("merge-stream"),
	lineEC = require("gulp-line-ending-corrector"),
	bs = require("browser-sync").create();

/* --- Dependencies: css --- */
const cleanCSS = require("gulp-clean-css"), // Minify CSS
	autoprefixer = require("gulp-autoprefixer");

/* --- Dependencies: js --- */
const uglify = require("gulp-uglify"), // Minify JavaScript
	stripDebug = require("gulp-strip-debug"); // Remove debugging stuffs

/* --- Dependencies: i18n --- */
const wpPot = require("gulp-wp-pot"),
	sort = require("gulp-sort");

/*================================= Tasks =================================*/

let init = cb => {
	console.log("-------------------------------------------");
	console.log("<<<<<----------- AWSM Team ----------->>>>>");
	console.log("-------------------------------------------");
	cb();
};

/* --- Tasks: Browsersync --- */

let browserSync = cb => {
	bs.init({
		ghostMode: false,
		proxy: config.previewURL,
		notify: false
	});
	cb();
};
let bsReload = cb => {
	bs.reload();
	cb();
};
browserSync.description = `Initialize Browsersync and proxy ${
	config.previewURL
}`;
gulp.task("browser-sync", browserSync);

/* --- Tasks: CSS and JS --- */

const srcOptions = {
	sourcemaps: config.debug ? true : false
};

const destOptions = {
	sourcemaps: config.debug ? "." : false
};

/* --- Tasks: CSS --- */

let styleTask = () => {
	let src = [
		config.style.dir + "icomoon.css",
		config.style["public"].src + "style.css"
	];
	let outputName = config.style["public"].outputName;
	let dest = config.style["public"].dest;

	let stream = gulp
		.src(src, srcOptions)
		.pipe(concat(outputName))
		.pipe(autoprefixer());
	let compressedStream = stream.pipe(clone());
	let unCompressedStream = stream.pipe(clone());
	compressedStream = compressedStream
		.pipe(cleanCSS({compatibility: "ie9"}))
		.pipe(rename({suffix: ".min"}))
		.pipe(lineEC())
		.pipe(gulp.dest(dest, destOptions));
	unCompressedStream = unCompressedStream
		.pipe(lineEC())
		.pipe(gulp.dest(dest, destOptions));
	return merge(compressedStream, unCompressedStream);
};
let loadStyleTask = () => {
	let src = config.style["public"].dest;
	return gulp.src(src + "*.css").pipe(bs.stream());
};
styleTask.description = "Concatenate team styles and minify it";
gulp.task("team-style", styleTask);
gulp.task("load-team-styles", gulp.series("team-style", loadStyleTask));

/* --- Tasks: JS --- */

let scriptTask = () => {
	let src = [
		config.scripts["public"].src + "main.js",
	];
	let outputName = config.scripts["public"].outputName;
	let dest = config.scripts["public"].dest;

	let stream = gulp.src(src, srcOptions);
	if (!config.debug) {
		stream = stream.pipe(stripDebug());
	}
	stream = stream.pipe(concat(outputName));
	let compressedStream = stream.pipe(clone());
	let unCompressedStream = stream.pipe(clone());
	compressedStream = compressedStream
		.pipe(concat(outputName))
		.pipe(uglify())
		.pipe(rename({suffix: ".min"}))
		.pipe(lineEC())
		.pipe(gulp.dest(dest, destOptions));
	unCompressedStream = unCompressedStream
		.pipe(lineEC())
		.pipe(gulp.dest(dest, destOptions));
	return merge(compressedStream, unCompressedStream);
};
scriptTask.description = "Concatenate team js files and minify it";
gulp.task("team-scripts", scriptTask);
gulp.task("load-team-scripts", gulp.series("team-scripts", bsReload));

/* --- Tasks: i18n --- */

let i18n = () => {
	return gulp
		.src(["./**/*.php", "!./build/**/*.php"])
		.pipe(sort())
		.pipe(
			wpPot({
				domain: config.translation.domain,
				package: config.translation.package,
				team: config.translation.team
			})
		)
		.pipe(gulp.dest(config.translation.dest));
};
i18n.description = "Generates pot file for plugin localization";
gulp.task("translate", i18n);

/* --- Tasks: Watch files for any change --- */

let watchFiles = () => {
	gulp.watch("./**/*.php", bsReload);
	gulp.watch(
		config.style["public"].src + "**/*.css",
		gulp.series(`load-team-styles`)
	);
	gulp.watch(
		config.scripts["public"].src + "**/*.js",
		gulp.series(`load-team-scripts`)
	);
};
watchFiles.description = "Watch PHP, JS and CSS files for any change";
gulp.task("watch", gulp.series(browserSync, watchFiles));

/* --- Tasks: Default tasks --- */
gulp.task("default", gulp.series(init, browserSync));

/* --- Tasks: Build tasks --- */
gulp.task(
	"build",
	gulp.series(init, gulp.parallel("team-style", "team-scripts"))
);
