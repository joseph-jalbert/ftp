module.exports = function (grunt) {

    var d = new Date(),
        timestamp = d.yyyymmddhhss(),
        pantheonCommit = grunt.option('pantheonCommit') || 'HEAD',
        wpEngineCommit = grunt.option('wpEngineCommit') || 'HEAD',
        sourceWpContentDirectory = grunt.option('sourceWpContentDirectory') || '/web/app',
        sourceRepo = grunt.option('sourceRepo') || 'git@github.com:Morgan-and-Morgan/forthepeople.com.git',
        sourceRepoBranch = grunt.option('sourceRepoBranch') || 'development',
        sourceDir = grunt.option('sourceDir') || './deployments/sourceRepo',
        pantheonRepo = grunt.option('pantheonRepo') || 'ssh://codeserver.dev.f866bccd-7005-4285-a294-9c15346ec607@codeserver.dev.f866bccd-7005-4285-a294-9c15346ec607.drush.in:2222/~/repository.git',
        pantheonRepoBranch = 'master',
        pantheonTempDir = './deployments/pantheonRepo',
        interimPantheonRepo = grunt.option('interimPantheonRepo') || 'git@github.com:Morgan-and-Morgan/forthepeople-interim-pantheon.git',
        interimPantheonRepoBranch = sourceRepoBranch,
        interimPantheonTempDir = './deployments/interimPantheonRepo',
        wpEngineEnvironment = 'staging',
        wpEngineRepo = grunt.option('wpEngineRepo') || 'git@git.wpengine.com:production/forthepeople.git',
        wpEngineRepoBranch = 'master',
        wpEngineTempDir = './deployments/wpEngineRepo',
        interimWpEngineRepo = grunt.option('interimWpEngineRepo') || 'git@github.com:Morgan-and-Morgan/forthepeople-interim-wpengine.git',
        interimWpEngineRepoBranch = sourceRepoBranch,
        interimWpEngineTempDir = './deployments/interimWpEngineRepo',

        finalPantheonTempDir = './deployments/final-pantheon',
        finalWpEngineTempDir = './deployments/final-wpengine';

    if (grunt.option('wpEngineTarget') === 'production' || sourceRepoBranch === 'master') {
        wpEngineEnvironment = 'production';
    }

    wpEngineRepo = wpEngineRepo.replace('production', wpEngineEnvironment);


    grunt.initConfig({
        gitclone: {
            consumeSourceRepo: {

                options: {
                    repository: sourceRepo,
                    branch: sourceRepoBranch,
                    directory: sourceDir
                }

            },
            consumePantheonRepo: {

                options: {
                    repository: pantheonRepo,
                    branch: pantheonRepoBranch,
                    directory: pantheonTempDir
                }

            },

            consumeWpEngineRepo: {

                options: {
                    repository: wpEngineRepo,
                    branch: wpEngineRepoBranch,
                    directory: wpEngineTempDir
                }

            },

            consumeInterimPantheonRepo: {
                options: {
                    repository: interimPantheonRepo,
                    branch: interimPantheonRepoBranch,
                    directory: interimPantheonTempDir
                }
            },
            consumeInterimWpEngineRepo: {
                options: {
                    repository: interimWpEngineRepo,
                    branch: interimWpEngineRepoBranch,
                    directory: interimWpEngineTempDir
                }
            }
        },
        gitadd: {
            addAllToInterimPantheon: {

                options: {
                    force: true,
                    cwd: interimPantheonTempDir
                },
                files: {
                    src: ['.']
                }

            },

            addAllToPantheon: {

                options: {
                    force: true,
                    cwd: pantheonTempDir
                },
                files: {
                    src: ['.']
                }

            },
            addAllToInterimWpEngine: {

                options: {
                    force: true,
                    cwd: interimWpEngineTempDir
                },
                files: {
                    src: ['.']
                }

            },
            addAllToWpEngine: {

                options: {
                    force: true,
                    cwd: wpEngineTempDir
                },
                files: {
                    src: ['.']
                }

            }
        },
        gitcommit: {
            commitReleaseInterimPantheon: {
                options: {
                    message: 'Update for ' + sourceRepoBranch + ' on ' + timestamp,
                    cwd: interimPantheonTempDir,
                    allowEmpty: true
                },
                files: {
                    src: ['.']
                }
            },
            commitReleasePantheon: {
                options: {
                    message: 'Update for ' + sourceRepoBranch + ' on ' + timestamp,
                    noVerify: false,
                    noStatus: false,
                    cwd: pantheonTempDir,
                    allowEmpty: true
                },
                files: {
                    src: ['.']
                }
            },
            commitReleaseInterimWpEngine: {
                options: {
                    message: 'Update for ' + sourceRepoBranch + ' on ' + timestamp,
                    cwd: interimWpEngineTempDir,
                    allowEmpty: true
                },
                files: {
                    src: ['.']
                }
            },
            commitReleaseWpEngine: {
                options: {
                    message: 'Update for ' + sourceRepoBranch + ' on ' + timestamp,
                    noVerify: false,
                    noStatus: false,
                    cwd: wpEngineTempDir,
                    allowEmpty: true
                },
                files: {
                    src: ['.']
                }
            }
        },
        gittag: {
            tagReleaseInterimPantheon: {
                options: {
                    tag: timestamp,
                    message: 'Tagging ' + timestamp,
                    cwd: interimPantheonTempDir
                }
            },
            tagReleasePantheon: {
                options: {
                    tag: timestamp,
                    message: 'Tagging ' + timestamp,
                    cwd: pantheonTempDir
                }
            },
            tagReleaseInterimWpEngine: {
                options: {
                    tag: timestamp,
                    message: 'Tagging ' + timestamp,
                    cwd: interimWpEngineTempDir
                }
            },
            tagReleaseWpEngine: {
                options: {
                    tag: timestamp,
                    message: 'Tagging ' + timestamp,
                    cwd: wpEngineTempDir
                }
            }
        },
        gitpush: {
            pushInterimPantheon: {
                options: {
                    branch: interimPantheonRepoBranch,
                    tags: true,
                    cwd: interimPantheonTempDir,
                    force: false
                }
            },
            forcePushPantheon: {
                options: {
                    branch: 'master',
                    tags: true,
                    cwd: pantheonTempDir,
                    force: true
                }
            },
            pushInterimWpEngine: {
                options: {
                    branch: interimWpEngineRepoBranch,
                    tags: true,
                    cwd: interimWpEngineTempDir,
                    force: false
                }
            },
            forcePushWpEngine: {
                options: {
                    branch: 'master',
                    tags: true,
                    cwd: wpEngineTempDir,
                    force: true
                }
            }
        },
        gitreset: {
            resetInterimPantheonRepo: {

                options: {
                    mode: 'hard',
                    commit: pantheonCommit,
                    cwd: interimPantheonTempDir
                }

            },
            resetInterimWpEngineRepo: {

                options: {
                    mode: 'hard',
                    commit: wpEngineCommit,
                    cwd: interimWpEngineTempDir
                }

            }
        },

        clean: {
            everything: ['deployments'],
            cleanPantheonContent: [pantheonTempDir + '/wp-content/plugins', pantheonTempDir + '/wp-content/themes'],
            cleanWpEngineContent: [wpEngineTempDir + '/wp-content/plugins', wpEngineTempDir + '/wp-content/themes'],
            cleanInterimPantheonKeepGitDirectory: [interimPantheonTempDir + '/*', interimPantheonTempDir + '/.gitignore', '!' + interimPantheonTempDir + '/.git/'],
            cleanInterimWpEngineKeepGitDirectory: [interimWpEngineTempDir + '/*', interimWpEngineTempDir + '/.gitignore', '!' + interimWpEngineTempDir + '/.git/'],
            cleanPantheonKeepGitDirectory: [pantheonTempDir + '/*', pantheonTempDir + '/.gitignore', '!' + pantheonTempDir + '/.git/'],
            cleanWpEngineKeepGitDirectory: [wpEngineTempDir + '/*', wpEngineTempDir + '/.gitignore', '!' + wpEngineTempDir + '/.git/'],
            pantheonWpContent: [pantheonTempDir + '/wp-content/plugins']



        },

        shell: {
            updatePantheonEnvironments: {
                command: 'cd bin && chmod +x terminus-pantheon.sh && ./terminus-pantheon.sh ' + sourceRepoBranch,
                options: {
                    failOnError: false,
                    execOptions: {
                        cwd: './'

                    }

                }
            },
            sftpKillWpEngine: {

                command: 'cd bin && chmod +x delete-wpengine-wp-content.sh && ./delete-wpengine-wp-content.sh ' + sourceRepoBranch,
                options: {
                    failOnError: false,
                    execOptions: {
                        cwd: './'

                    }

                }

            },
            cleanIndexInterimPantheon: {
                command: 'rm .git/index && git add -A',
                options: {

                    execOptions: {
                        cwd: interimPantheonTempDir
                    }

                }
            },
            cleanIndexInterimWpEngine: {
                command: 'rm .git/index && git add -A',
                options: {

                    execOptions: {
                        cwd: interimWpEngineTempDir
                    }

                }
            },
            cleanIndexPantheon: {
                command: 'rm .git/index && git add -A',
                options: {

                    execOptions: {
                        cwd: pantheonTempDir
                    }

                }
            },
            cleanIndexWpEngine: {
                command: 'rm .git/index && git add -A',
                options: {

                    execOptions: {
                        cwd: wpEngineTempDir
                    }

                }
            },
            buildBowerSource: {
                command: 'bower install',
                options: {

                    execOptions: {
                        cwd: sourceDir
                    }

                }
            },
            buildComposerSource: {
                command: 'composer install',
                options: {

                    execOptions: {
                        cwd: sourceDir
                    }

                }
            },

            processPantheonSourceRepo: {
                options: {
                    stdout: false,
                    execOptions: {
                        maxBuffer: 1024000
                    }
                },
                command: [
                    'rsync -lrv ' + pantheonTempDir + '/* ' + finalPantheonTempDir + '/',
                    'rsync -lrv ' + sourceDir + sourceWpContentDirectory + '/* ' + finalPantheonTempDir + '/wp-content/'
                ].join('&&')

            },
            processWpEngineSourceRepo: {
                options: {
                    stdout: false,
                    execOptions: {
                        maxBuffer: 1024000
                    }
                },
                command: [
                    'mkdir ' + finalWpEngineTempDir + '/',
                    'rsync -lrv ' + sourceDir + sourceWpContentDirectory + '/* ' + finalWpEngineTempDir + '/wp-content/'
                ].join('&&')

            },
            rsyncRollback: {
                options: {
                    stdout: false,
                    execOptions: {
                        maxBuffer: 1024000
                    }
                },
                command: 'rsync -lrv ' + interimPantheonTempDir + '/* ' + pantheonTempDir + '/ && rsync -lrv ' + interimWpEngineTempDir + '/* ' + wpEngineTempDir + '/'


            },
            moveFinalIntoInterimPantheon: {
                options: {
                    stdout: false,
                    execOptions: {
                        maxBuffer: 1024000
                    }
                },
                command: 'rsync -lrv ' + finalPantheonTempDir + '/* ' + interimPantheonTempDir + '/'

            },
            moveFinalIntoPantheon: {
                options: {
                    stdout: false,
                    execOptions: {
                        maxBuffer: 1024000
                    }
                },
                command: 'rsync -lrv ' + finalPantheonTempDir + '/* ' + pantheonTempDir + '/'

            },
            moveFinalIntoInterimWpEngine: {
                options: {
                    stdout: false,
                    execOptions: {
                        maxBuffer: 1024000
                    }
                },
                command: 'rsync -lrv ' + finalWpEngineTempDir + '/* ' + interimWpEngineTempDir + '/'

            },
            moveFinalIntoWpEngine: {
                options: {
                    stdout: false,
                    execOptions: {
                        maxBuffer: 1024000
                    }
                },
                command: 'rsync -lrv ' + finalWpEngineTempDir + '/* ' + wpEngineTempDir + '/'

            },

            addObjectCacheToPantheon: {

                options: {

                    stdout: false,
                    execOptions: {
                        cwd: './'

                    }
                },
                command: [
                    " > " + finalPantheonTempDir + "/wp-content/object-cache.php",
                    "touch " + finalPantheonTempDir + "/wp-content/object-cache.php",
                    "echo \"<?php if ( file_exists( WP_CONTENT_DIR . '/plugins/wp-redis/object-cache.php')) { require_once WP_CONTENT_DIR . '/plugins/wp-redis/object-cache.php';}\" >> " + finalPantheonTempDir + "/wp-content/object-cache.php",
                    " > " + interimPantheonTempDir + "/wp-content/object-cache.php",
                    "touch " + interimPantheonTempDir + "/wp-content/object-cache.php",
                    "echo \"<?php if ( file_exists( WP_CONTENT_DIR . '/plugins/wp-redis/object-cache.php')) { require_once WP_CONTENT_DIR . '/plugins/wp-redis/object-cache.php';}\" >> " + interimPantheonTempDir + "/wp-content/object-cache.php"

                ].join(' && ')


            }


        }


    });
    grunt.loadNpmTasks('grunt-git');
    grunt.loadNpmTasks('grunt-merge-copy');
    grunt.loadNpmTasks('grunt-contrib-clean');

    grunt.loadNpmTasks('grunt-shell');
    grunt.loadNpmTasks('grunt-composer');
    grunt.registerTask('cleanUp', [
        'clean:everything'
    ]);
    grunt.registerTask('consumeSources', [
        'gitclone:consumeSourceRepo',
        'gitclone:consumePantheonRepo',
        'gitclone:consumeWpEngineRepo',
        'gitclone:consumeInterimPantheonRepo',
        'gitclone:consumeInterimWpEngineRepo'


    ]);

    grunt.registerTask('processConsumed', [

        'processSourceRepos',
        'processInterimRepos'


    ]);

    grunt.registerTask('processInterimRepos', [
        'clean:cleanPantheonContent',
        'clean:cleanWpEngineContent'

    ]);




    grunt.registerTask('processInterimPantheon', [
        'clean:cleanInterimPantheonKeepGitDirectory',
        'shell:moveFinalIntoInterimPantheon',
        'shell:cleanIndexInterimPantheon',
        'shell:addObjectCacheToPantheon',
        'gitadd:addAllToInterimPantheon',
        'gitcommit:commitReleaseInterimPantheon',
        'gitreset:resetInterimPantheonRepo',
        'gittag:tagReleaseInterimPantheon',
        'gitpush:pushInterimPantheon'
    ]);

    grunt.registerTask('processInterimWpEngine', [
        'clean:cleanInterimWpEngineKeepGitDirectory',
        'shell:moveFinalIntoInterimWpEngine',
        'gitadd:addAllToInterimWpEngine',
        'gitcommit:commitReleaseInterimWpEngine',
        'gitreset:resetInterimWpEngineRepo',
        'gittag:tagReleaseInterimWpEngine',
        'gitpush:pushInterimWpEngine'
    ]);

    grunt.registerTask('processSourceRepos', [
        'clean:pantheonWpContent',
        'shell:processPantheonSourceRepo',
        'shell:processWpEngineSourceRepo',
        'processInterimPantheon',
        'processInterimWpEngine'

    ]);

    grunt.registerTask('deployCode', [
        'clean:cleanPantheonKeepGitDirectory',
        'shell:moveFinalIntoPantheon',
        'gittag:tagReleasePantheon', // this is here so we don't tag the release on a rollback
        'deployPantheon',
        'clean:cleanWpEngineKeepGitDirectory',
        'shell:moveFinalIntoWpEngine',
        'killWpEngineContent',
        'deployWpEngine'


    ]);

    grunt.registerTask('build', [
        // todo add tasks!


    ]);


    grunt.registerTask('deploy', [
        'cleanUp',
        'consumeSources',
        'build',
        'processConsumed',
        'deployCode'

    ]);

    grunt.registerTask('killWpEngineContent', [
        'shell:sftpKillWpEngine'
    ]);

    grunt.registerTask('rollback', [
        'cleanUp',
        'gitclone:consumeInterimPantheonRepo',
        'gitclone:consumePantheonRepo',
        'gitclone:consumeWpEngineRepo',
        'gitclone:consumeInterimWpEngineRepo',
        'gitreset:resetInterimPantheonRepo',
        'gitreset:resetInterimWpEngineRepo',
        'clean:cleanPantheonKeepGitDirectory',
        'clean:cleanWpEngineKeepGitDirectory',
        'shell:rsyncRollback',
        'deployPantheon',
        'killWpEngineContent',
        'deployWpEngine'

    ]);

    grunt.registerTask('deployPantheon', [
        'gitadd:addAllToPantheon',
        'gitcommit:commitReleasePantheon',
        'gitpush:forcePushPantheon',
        'updatePantheonEnvironments'

    ]);

    grunt.registerTask('deployWpEngine', [
        'gitadd:addAllToWpEngine',
        'gitcommit:commitReleaseWpEngine',
        'gitpush:forcePushWpEngine'

    ]);

    grunt.registerTask('updatePantheonEnvironments', [
        'shell:updatePantheonEnvironments'
    ]);

    grunt.registerTask('default', [
        'build'


    ]);


};


Object.defineProperty(Date.prototype, 'yyyymmddhhss', {
    value: function () {
        function pad2(n) {  // always returns a string
            return (n < 10 ? '0' : '') + n;
        }

        return this.getFullYear() +
            pad2(this.getMonth() + 1) +
            pad2(this.getDate()) +
            pad2(this.getHours()) +
            pad2(this.getMinutes()) +
            pad2(this.getSeconds());
    }
});


function log(err, stdout, stderr, cb) {
    console.log(stdout);
    cb();
}