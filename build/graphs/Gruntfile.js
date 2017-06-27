module.exports = function (grunt) {

    var path = require('path');

    var timestamp = new Date().getTime();

    var appJavaScripts = [
        // '../../public/assets/frontend/js/helpers/environment.js',
        '../../public/assets/graphs/js/helpers.js',
        // '../../public/assets/js/tooltip.js',
        '../../public/assets/graphs/js/dashboard/StoreLab.js',
        '../../public/assets/graphs/js/dashboard/MonkeyDataSelect.js',

        '../../public/assets/graphs/js/graph/Graphs.js',
        '../../public/assets/graphs/js/graph/Graph.js',
        '../../public/assets/graphs/js/graph/GraphFormat.js',

    ];

    var libsJavaScript = {
        '../../public/assets/graphs/js/build/libs.min.js': [
            // /home/tomw/htdocs/app.monkeydata.com/public/assets/graphs/libs/bootstrap/js/bootstrap.min.js
            // '../../public/assets/graphs/libs/**/*.js',
            '../../public/assets/graphs/static-libs/helpers.js',
            '../../public/assets/graphs/libs/bootstrap/js/bootstrap.min.js',



            // '../../public/assets/graphs/static-libs/**/*.js',
            '../../public/assets/graphs/static-libs/dx_16_2/dx.all.js',
            '../../public/assets/graphs/static-libs/dx_16_2/cldr.js',
            '../../public/assets/graphs/static-libs/dx_16_2/globalize.js',

            '../../public/assets/graphs/static-libs/dx_16_2/jszip.js',
            '../../public/assets/graphs/static-libs/dx_16_2/knockout-3.4.0.js',

            '../../public/assets/graphs/static-libs/dx_16_2/vectormap-data/*.js',

            '../../public/assets/graphs/static-libs/dx_16_2/underscore-1.5.1.min.js',
            '../../public/assets/graphs/static-libs/sumoselect/jquery.sumoselect.js',

            '../../public/assets/graphs/static-libs/mdajax/MDAjax.js',
            '../../public/assets/graphs/static-libs/monkeydata/mdDate.js',
            '../../public/assets/graphs/static-libs/monkeydata/mdFormat.js',
            '../../public/assets/graphs/static-libs/monkeydata/MonkeyDataSelectSearch.js',
            '../../public/assets/graphs/static-libs/tmpl/tmpl.js',

            '../../public/assets/graphs/static-libs/bootstrap/bootstrap-tabdrop.js',
            '../../public/assets/graphs/static-libs/autowidth_input/autowidth_input.js',
        ]
    };




    grunt.initConfig({
        availabletasks: {           // task
            tasks: {
                options: {
                    groups: {
                        'Custom': ['watch', 'build', 'report-watch', 'mdjs:libs', 'mdjs:app']
                    },
                    filter: 'exclude',
                    tasks: ['availabletasks', 'default']
                }
            }         // target
        },
        bower: {
            install: {
                options: {
                    targetDir: '../../public/assets/graphs/libs/',
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
            graphs: {
                options: {
                    compress: true,
                    sourceMap: true,
                    sourceMapFilename: '../../public/assets/graphs/css/graphs.min.css.map',
                    sourceMapURL: '/assets/graphs/css/graphs.min.css.map',
                },
                files: {
                    "../../public/assets/graphs/css/graphs.min.css": "../../public/assets/graphs/less/build-graphs.less"
                }
            },
            graphsReport: {
                options: {
                    compress: true,
                    sourceMap: true,
                    sourceMapFilename: '../../public/assets/graphs/css/graphs-report.min.css.map',
                    sourceMapURL: '/assets/graphs/css/graphs-report.min.css.map',
                },
                files: {
                    "../../public/assets/graphs/css/graphs-report.min.css": "../../public/assets/graphs/less/build-graphs-report.less"
                }
            },
            report: {
                options: {
                    compress: true,
                    sourceMap: true,
                    sourceMapFilename: '../../public/assets/graphs/css/report.min.css.map',
                    sourceMapURL: '/assets/graphs/css/report.min.css.map',
                },
                files: {
                    "../../public/assets/graphs/css/report.min.css": "../../public/assets/graphs/less/report/build.less"
                }
            },
        },
        clean: {
            options: {force: true},
            libs: ['../../public/assets/graphs/js/libs']
        },
        ngmin: {
            app: {
                src: appJavaScripts,
                dest: '../../public/assets/graphs/js/build/graphs.min.js'
            }
        },

        concat: {
            libs: {
                files: libsJavaScript
            },
            dxMaps: {
                files: {
                    '../../public/assets/graphs/js/build/dx-maps.min.js': [
                        '../../public/assets/graphs/static-libs/dx_16_2/vectormap-data/*.js',
                    ]
                }
            },
            jquery: {
                files: {
                    '../../public/assets/graphs/js/build/jquery.min.js': [
                        '../../public/assets/graphs/libs/jquery/js/jquery.min.js',
                        '../../public/assets/graphs/libs/jquery-modern/js/jquery.min.js',
                    ]
                }
            }
        },

        // uglify: {
        //     libs: {
        //         files: libsJavaScript
        //     },
        //     dxMaps: {
        //         files: {
        //             '../../public/assets/graphs/js/build/dx-maps.min.js': [
        //                  '../../public/assets/graphs/static-libs/dx_16_2/vectormap-data/*.js',
        //             ]
        //         }
        //     }
        // },
        htmlbuild: {
            graphs: {
                src: '../../app/views/frontend/build/scripts/graphs/scripts.html',
                dest: '../../app/views/frontend/build/scripts/graphs/scripts.blade.php',
                options: {data: {timestamp: timestamp}}
            },
            graphsCss: {
                src: '../../app/views/frontend/build/css/graphs/css.html',
                dest: '../../app/views/frontend/build/css/graphs/css.blade.php',
                options: {data: {timestamp: timestamp}}
            }
        },
        fontless: {
            mdicomoon: {
                src: '../../public/assets/graphs/libs/md-icomoon/fonts/md-icomoon.ttf',
                desc: '../../public/assets/graphs/less/fonts/md-icomoon.less',
                font: 'md-icomoon'
            },
            opensantRegular: {
                ///home/tomw/htdocs/app.monkeydata.com/public/assets/graphs/fonts/OpenSans-Regular.ttf
                src: '../../public/assets/graphs/fonts/OpenSans-Regular.ttf',
                desc: '../../public/assets/graphs/less/fonts/opensans-regular.less',
                font: 'Open Sans',
                fontWeight: '500',
            },
            opensantSemibold: {
                ///home/tomw/htdocs/app.monkeydata.com/public/assets/graphs/fonts/OpenSans-Regular.ttf
                src: '../../public/assets/graphs/fonts/OpenSans-Semibold.ttf',
                desc: '../../public/assets/graphs/less/fonts/opensans-semibold.less',
                font: 'Open Sans',
                fontWeight: '600',
            },
            opensantBold: {
                ///home/tomw/htdocs/app.monkeydata.com/public/assets/graphs/fonts/OpenSans-Regular.ttf
                src: '../../public/assets/graphs/fonts/OpenSans-Bold.ttf',
                desc: '../../public/assets/graphs/less/fonts/opensans-bold.less',
                font: 'Open Sans',
                fontWeight: '700',
            },
            DXIcons: {
                ///home/tomw/htdocs/app.monkeydata.com/public/assets/graphs/fonts/icons/dxicons.ttf
                src: '../../public/assets/graphs/fonts/icons/dxicons.ttf',
                desc: '../../public/assets/graphs/less/fonts/dxicons.less',
                font: 'DXIcons',
                fontWeight: 'normal',
            }
        },
        watch: {
            lessGraphs: {
                files: [
                    '../../public/assets/graphs/less/**/*.less'
                ],
                tasks: [ 'less:graphs'],
                options: {
                    interrupt: true
                }
            },
            lessGraphsReport: {
                files: [
                    '../../public/assets/graphs/less/**/*.less'
                ],
                tasks: [ 'less:graphsReport', 'less:report'],
                options: {
                    interrupt: true
                }
            },
            js: {
                files: appJavaScripts,
                tasks: [ 'mdjs:app' ],
                options: {
                    interrupt: true
                }
            },
            libs: {
                files: libsJavaScript,
                tasks: [ 'mdjs:libs' ],
                options: {
                    interrupt: true
                }
            }
        }
    });



    grunt.registerTask('default', ['availabletasks']);
    grunt.registerTask('mdjs:libs', ['concat']);
    grunt.registerTask('mdjs:app', ['ngmin']);


// WATCH
    grunt.registerTask('report-watch', ['watch:lessGraphsReport']);

// BUILD
    grunt.registerTask('build', ['bower', 'mdjs:app', 'mdjs:libs', 'htmlbuild', 'fontless', 'icomoonCopyCss', 'less']);


    grunt.registerTask('icomoonCopyCss', '', function(){
        var fs = require('fs');
        var src = '../../public/assets/graphs/libs/md-icomoon/style.css';
        var desc = '../../public/assets/graphs/less/fonts/md-icomoon-style.less';

        var bitmap = fs.readFileSync(src);
        // convert binary data to base64 encoded string
        var content = new Buffer(bitmap).toString();

        const regex = /@font-face.*{[.\s\S]*?}/;
        content = content.replace(regex, '');

        const regex1 = /(\.([a-zA-Z0-9\-]*):before {\n\W+content(: ["\\a-zA-Z0-9]+;)\n})/g;
        content = content.replace(regex1, '$1\n@$2$3\n\n');

        fs.writeFileSync(desc, content);
        grunt.log.writeln("File created: " + desc);




    });

    grunt.registerMultiTask('fontless', 'Log stuff.', function() {
        var data = this.data;
        var options = Object.assign({
            'src' : '',
            'desc' : '',
            'font' : '',
            'fontStyle' : 'normal',
            'fontWeight' : '500',
        }, data);

        grunt.log.writeln(this.target + ': ' + options.src);

        var fs = require('fs');

        function base64_encode(file) {
            // read binary data
            var bitmap = fs.readFileSync(file);
            // convert binary data to base64 encoded string
            return new Buffer(bitmap).toString('base64');
        }

        function saveToFile(content){
            fs.writeFileSync(options.desc, content);
        }

        var base64str = base64_encode(options.src);
        var content = "@font-face { \n";
        content += "   font-family: '" + options.font + "';\n";
        content += "   src: url('data:application/x-font-ttf;base64," + base64str + "') format('truetype');\n";
        content += "   font-style: " + options.fontStyle + ";\n";
        content += "   font-weight: " + options.fontWeight + ";\n";
        content += "}\n";
        saveToFile(content);
    });

// NPM
    grunt.loadNpmTasks('grunt-bower-task');
    grunt.loadNpmTasks('grunt-ngmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-html-build');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-exec');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-available-tasks');
};