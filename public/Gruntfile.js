module.exports = function(grunt) {

  var targetDir = grunt.config.get('targetDir');
  var moduleDir = targetDir + "/modules/Gastro24";
  var nodeModulesPath = grunt.config.get('nodeModulesPath');

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
                }
              ]
          },
      },
  });

  grunt.registerTask('yawik:gastro24', ["less:gastro24", "concat:gastro24"]);
};

