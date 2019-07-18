module.exports = function(grunt) {

  var targetDir = grunt.config.get('targetDir');
  var moduleDir = targetDir + "/modules/Gastro24";
  var nodeModulesPath = grunt.config.get('nodeModulesPath');
  var mainDir = grunt.config.get('mainDir');

  grunt.config.merge({
    less: {
      gastro24: {
        options: {
          compress: true,
          optimization: 2
        },
        files: [
            {
              dest: targetDir + "/modules/Gastro24/Gastro24.css",
              src: moduleDir + "/less/Gastro24.less"
            },
            { dest: moduleDir + "/templates/default/job.css", src: moduleDir + "/templates/default/less/job.less"}, // destination file and source file
            { dest: moduleDir + "/templates/modern/job.css", src: moduleDir + "/templates/modern/less/job.less"}, // destination file and source file
            { dest: moduleDir + "/templates/classic/job.css", src: moduleDir + "/templates/classic/less/job.less"} // destination file and source file
          ]
      }
    },
    concat: {
        gastro24: {
            files: [
                {
                    src: [
                        nodeModulesPath + "/blueimp-file-upload/js/vendor/jquery.ui.widget.js",
                        nodeModulesPath + "/blueimp-file-upload/js/jquery.iframe-transport.js",
                        nodeModulesPath + "/blueimp-file-upload/js/jquery.fileupload.js",
                      ],
                    dest: targetDir+"/dist/js/blueimp-file-upload.js"
                },
                {
                    src: nodeModulesPath + "/iframe-resizer/js/iframeResizer.js",
                    dest: targetDir+"/dist/js/iframeResizer.js"
                },
                {
                    src: nodeModulesPath + "/iframe-resizer/js/iframeResizer.contentWindow.min.js",
                    dest: targetDir+"/dist/js/iframeResizer.contentWindow.min.js"
                },
                {
                    src: [
                        moduleDir + "/js/jquery.matchHeight.js",
                        moduleDir + "/js/index.js",
                    ],
                    dest: targetDir+"/dist/js/index-main.js"
                },
              ]
          },
      },
      uglify: {
          gastro24: {
              options: {
                  compress: true,
              },
              files: [
                  {
                      src: targetDir+'/dist/js/blueimp-file-upload.js',
                      dest: targetDir+'/dist/js/blueimp-file-upload.min.js',
                  },
                  {
                      src: moduleDir+'/jquery-ui-1-12/jquery-ui-1-12.js',
                      dest: targetDir+'/dist/js/jquery-ui-1-12.min.js',
                  },
                  {
                      src: targetDir+'/dist/js/index-main.js',
                      dest: targetDir+'/dist/js/index-main.min.js',
                  }
              ]
          },
      },
      cssmin: {
          gastro24: {
              files: [
                  {
                      src: moduleDir+'/jquery-ui-1-12/jquery-ui-1-12.css',
                      dest: targetDir+'/dist/css/jquery-ui-1-12.min.css',
                  }
              ]
          }
      },
      cacheBust: {
          gastro24: {
              options: {
                  baseDir: targetDir,
                  jsonOutput: true,
                  outputDir: "modules/Gastro24/hashed",
                  jsonOutputFilename: mainDir + '/gastro24-grunt-cache-bust.json',
                  clearOutputDir: true,
                  assets: ["modules/Gastro24/Gastro24.css", "modules/Gastro24/js/*"]
              },
              src: [mainDir + '/views/**/*.phtml'] // files where css is used, not really important
          }
      }
  });

  grunt.registerTask('yawik:gastro24', ["less:gastro24", "concat:gastro24", "uglify:gastro24", "cssmin:gastro24", "cacheBust:gastro24"]);
};

