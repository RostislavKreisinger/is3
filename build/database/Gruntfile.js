module.exports = function (grunt) {

    var path = require('path');

    var timestamp = new Date().getTime();

    var appJavaScripts = {
        '../../public/assets/database/js/build/app/app.min.js': [
            '../../public/assets/database/js/scripts/*.js'
        ]
    };
    var libsJavaScript = {
        '../../public/assets/database/js/build/libs/libs.min.js': [
            '../../public/assets/database/libs/jquery/js/jquery.min.js',
            '../../public/assets/database/libs/bootstrap/js/bootstrap.min.js',
            '../../public/assets/database/libs/**/*.js'
        ]
    };

    grunt.initConfig({
        bower: {
            install: {
                options: {
                    targetDir: '../../public/assets/database/libs/',
                    install: true,
                    verbose: true,
                    cleanTargetDir: true,
                    cleanBowerDir: true,
                    layout: function (type, component, source) {
                        return path.join(component, type);
                    }
                }
            }
        },
        less: {
            frontend: {
                options: {
                    compress: true
                },
                files: {
                    "../../public/assets/database/css/app.min.css": "../../public/assets/database/less/app/build.less",
                    "../../public/assets/database/css/bootstrap.min.css": "../../public/assets/database/less/bootstrap/bootstrap.less"
                }
            }
        },
        clean: {
            options: {force: true},
            app: ['../../public/assets/database/js/build/app'],
            app_css: ['../../public/assets/database/css/build/app'],
            libs: ['../../public/assets/database/js/build/libs']
        },
        uglify: {
            libs: {
                files: libsJavaScript
            },
            app: {
                files: appJavaScripts
            }
        },
        htmlbuild: {
            app: {
                src: '../../resources/views/database/build/scripts/app/scripts.html',
                dest: '../../resources/views/database/build/scripts/app/scripts.latte',
                options: {data: {timestamp: timestamp}}
            },
            libs: {
                src: '../../resources/views/database/build/scripts/libs/scripts.html',
                dest: '../../resources/views/database/build/scripts/libs/scripts.latte',
                options: {data: {timestamp: timestamp}}
            },
            bootstrap_css: {
                src: '../../resources/views/database/build/css/app/bootstrap.html',
                dest: '../../resources/views/database/build/css/app/bootstrap.latte',
                options: {data: {timestamp: timestamp}}
            },
            app_css: {
                src: '../../resources/views/database/build/css/app/css.html',
                dest: '../../resources/views/database/build/css/app/css.latte',
                options: {data: {timestamp: timestamp}}
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    '../../public/assets/database/css/libs.min.css': ['../../public/assets/database/libs/**/*.css']
                }
            }
        },
        watch: {
            scripts: {
                files: [
                    '../../public/assets/database/js/scripts/*.js',
                    "../../public/assets/database/less/**/*.less"
                ],
                tasks: ['build:app'],
                options: {
                    interrupt: true,
                    livereload: {
                        host: 'localhost',
                        port: 35729
                    }
                }
            }
        }
    });

    grunt.registerTask('watch', ['watch']);
    grunt.registerTask('build', ['bower', 'clean', 'uglify', 'cssmin', 'htmlbuild', 'less']);
    grunt.registerTask('build:app', ['clean:app', 'uglify:app', 'htmlbuild:app', 'htmlbuild:app_css', 'htmlbuild:bootstrap_css', 'less']);
    grunt.registerTask('build:libs', ['clean:libs', 'uglify:libs', 'cssmin', 'htmlbuild:libs']);

    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-bower-task');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-html-build');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-less');
};