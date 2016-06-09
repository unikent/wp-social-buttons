'use strict';
module.exports = function(grunt) {
	// Load all tasks
	require('load-grunt-tasks')(grunt);
	// Show elapsed time
	require('time-grunt')(grunt);

	grunt.initConfig({
		sass: {
			dist: {
				files: {
					"kent-social-buttons.css": "kent-social-buttons.scss"
				}
			}
		}
	});

	grunt.loadNpmTasks("grunt-sass");

	// Register tasks
	grunt.registerTask('default', [
		'build'
	]);
	grunt.registerTask('build', [
		'sass'
	]);
};