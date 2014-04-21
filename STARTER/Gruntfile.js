/**
 * @file
 *
 * configuration for grunt, we are using grunt to precompile our css and this file
 * is mandatory.
 * Gruntfile.js and package.json are copy pasted from foundation folders.
 */

module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: {
      options: {
        includePaths: ['foundation/bower_components/foundation/scss']
      },
      dist: {
        options: {
          outputStyle: 'uncompressed'
        },
        files: {
          'css/app.css': 'scss/app.scss'
        }        
      }
    },

    watch: {
      grunt: { files: ['Gruntfile.js'] },

      sass: {
        files: 'scss/**/*.scss',
        tasks: ['sass']
      }
    }
  });

  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('build', ['sass']);
  grunt.registerTask('default', ['build','watch']);
}