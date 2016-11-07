var themeBase = 'web/app/themes/forthepeople',
    projectJsFileList = [
        themeBase + '/js/aria.js',
        themeBase + '/js/mobile.js',
        themeBase + '/js/navigation.js',
        themeBase + '/js/skip-link-focus-fix.js',
        themeBase + '/bootstrap/js/bootstrap.min.js',
        themeBase + '/assets/js/libs/modernizr.2.6.2.min.js',
        themeBase + '/assets/js/jquery.slides.js',
        themeBase + '/assets/js/plugins/jquery.sortAllTheThings.min.js',
        themeBase + '/assets/js/plugins/scotchPanels.min.js',
        themeBase + '/assets/js/scripts/global.js',
        themeBase + '/assets/js/scripts/social-sharing.js',
        themeBase + '/assets/js/libs/jquery.fitvids.js',
        themeBase + '/inc/videos-page/js/video-page.js',
    ],
    projectCssFileList = [
        themeBase + '/style.css',
        themeBase + '/bootstrap/css/bootstrap.min.css',
        themeBase + '/assets/css/borrowed.css',
        themeBase + '/assets/css/custom.css',
        themeBase + '/assets/css/social-sharing.css',
        themeBase + '/inc/videos-page/css/video-page.css',
    ];



module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        
        uglify: {
            theme: {
                options: {
                    preserveComments: "some"
                },
                files: {
                    "web/app/themes/forthepeople/js/main.min.js": projectJsFileList
                }
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    'web/app/themes/forthepeople/style.min.css': projectCssFileList
                }
            }
        },
        watch: {
            scripts: {
                files: projectCssFileList,
                tasks: ["default"],
                options: {
                    livereload: true
                }
            }
        }
    });
    grunt.loadNpmTasks("grunt-contrib-watch");
    grunt.loadNpmTasks("grunt-contrib-uglify");
    grunt.loadNpmTasks("grunt-contrib-cssmin");
    grunt.registerTask('default', ['uglify','cssmin']);
};
