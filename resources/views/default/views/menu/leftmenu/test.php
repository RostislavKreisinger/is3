<?php\n
    // source: C:\cygwin64\home\Tomas\htdocs\import-support-v3.monkeydata.com\resources/views/default/index.latte\n
    \n
    class Template7051c55d9196326ea35a968f04fcab25 extends Latte\Template {\n
    function render() {\n
    foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);\n
    // prolog Latte\Macros\CoreMacros\n
    list($_b, $_g, $_l) = $template->initialize('8b9ac328b5', 'html')\n
    ;\n
    //\n
    // main template\n
    //\n
    ?>\n
    <div class="row">\n
        <div class="col-lg-12">\n
            <h1 class="page-header">Dashboard</h1>\n
        </div>\n
        <!-- /.col-lg-12 -->\n
    </div>\n
    <!-- /.row -->\n
    <div class="row">\n
        <div class="col-lg-4 col-md-6">\n
            <div class="panel panel-primary panel-error-project-list">\n
                <div class="panel-heading">\n
                    <i class="fa fa-bell fa-fw"></i> Daily import\n
                </div>\n
                <div class="panel-body">\n
                    <div class="dataTable_wrapper">\n
                        <table width="100%" class="table table-striped table-bordered table-hover table-projects">\n
                            <thead hidden="">\n
                                <tr>\n
                                    <th>id</th>\n
                                    <th>name</th>\n
                                </tr>\n
                            </thead>\n
                            <tbody> \n
    <?php $iterations = 0; foreach ($invalidProjects as $project) { ?>                            <tr>\n
                                    <td><a href="<?php $method = 'Project\DetailController'; if(!strpos($method, '@')) { $method .= '@getIndex'; } echo action('App\\Http\\Controllers\\'.$method,  ['project_id' => $project->getId()]) ?>\n
    "><?php echo Latte\Runtime\Filters::escapeHtml($project->getId(), ENT_NOQUOTES) ?></a></td>\n
                                    <td><?php echo Latte\Runtime\Filters::escapeHtml($project->getName(), ENT_NOQUOTES) ?>\n
    \n
                                        [\n
    <?php $iterations = 0; foreach ($project->getResourceModels() as $resource) { ?>\n
                                            <a href="<?php $method = 'Project\Resource\DetailController'; if(!strpos($method, '@')) { $method .= '@getIndex'; } echo action('App\\Http\\Controllers\\'.$method,  ['project_id' => $project->getId(), 'resource_id' => 1]) ?>">\n
                                                <?php echo Latte\Runtime\Filters::escapeHtml($resource->name, ENT_NOQUOTES) ?>\n
    \n
                                            </a>\n
    <?php $iterations++; } ?>\n
                                        ]\n
                                        [\n
    <?php $iterations = 0; foreach ($project->getResourceModels() as $resource) { $params = array('project_id' => $project->getId(), 'resource_id' => 1 ) ?>\n
                                            <a href="<?php $method = 'Project\Resource\DetailController'; if(!strpos($method, '@')) { $method .= '@getIndex'; } echo action('App\\Http\\Controllers\\'.$method,  $params) ?>">\n
                                                <?php echo Latte\Runtime\Filters::escapeHtml($resource->name, ENT_NOQUOTES) ?>\n
    \n
                                            </a>\n
    <?php $iterations++; } ?>\n
                                        ]\n
                                    </td>\n
                                </tr>\n
    <?php $iterations++; } ?>\n
                            </tbody>\n
                        </table>\n
                    </div>\n
                </div>\n
            </div>\n
        </div>\n
        <div class="col-lg-4 col-md-6">\n
            <div class="panel panel-green panel-error-project-list">\n
                <div class="panel-heading">\n
                    <i class="fa fa-bell fa-fw"></i> History import\n
                </div>\n
                <div class="panel-body">\n
                    <div class="dataTable_wrapper">\n
                        <table width="100%" class="table table-striped table-bordered table-hover table-projects">\n
                            <thead hidden="">\n
                                <tr>\n
                                    <th>id</th>\n
                                    <th>name</th>\n
                                </tr>\n
                            </thead>\n
                            <tbody>\n
    <?php $iterations = 0; foreach ($historyProjects as $project) { ?>                            <tr>\n
                                    <td><a href="<?php $method = 'Project\DetailController'; if(!strpos($method, '@')) { $method .= '@getIndex'; } echo action('App\\Http\\Controllers\\'.$method,  ['project_id' => $project->getId()]) ?>\n
    "><?php echo Latte\Runtime\Filters::escapeHtml($project->getId(), ENT_NOQUOTES) ?></a></td>\n
                                    <td><?php echo Latte\Runtime\Filters::escapeHtml($project->getName(), ENT_NOQUOTES) ?></td>\n
                                </tr>\n
    <?php $iterations++; } ?>\n
                            </tbody>\n
                        </table>\n
                    </div>\n
                </div>\n
            </div>\n
        </div>\n
        <div class="col-lg-4 col-md-6">\n
            <div class="panel panel-yellow panel-error-project-list">\n
                <div class="panel-heading">\n
                    <i class="fa fa-bell fa-fw"></i> Automattest import\n
                </div>\n
                <div class="panel-body">\n
                    <div class="dataTable_wrapper">\n
                        <table width="100%" class="table table-striped table-bordered table-hover table-projects">\n
                            <thead hidden="">\n
                                <tr>\n
                                    <th>id</th>\n
                                    <th>name</th>\n
                                </tr>\n
                            </thead>\n
                            <tbody>\n
    <?php $iterations = 0; foreach ($automattestProjects as $project) { ?>                            <tr>\n
                                    <td><a href="<?php $method = 'Project\DetailController'; if(!strpos($method, '@')) { $method .= '@getIndex'; } echo action('App\\Http\\Controllers\\'.$method,  ['project_id' => $project->getId()]) ?>\n
    "><?php echo Latte\Runtime\Filters::escapeHtml($project->getId(), ENT_NOQUOTES) ?></a></td>\n
                                    <td><?php echo Latte\Runtime\Filters::escapeHtml($project->getName(), ENT_NOQUOTES) ?></td>\n
                                </tr>\n
    <?php $iterations++; } ?>\n
                            </tbody>\n
                        </table>\n
                    </div>\n
                </div>\n
            </div>\n
        </div>\n
    </div>\n
    \n
    <?php\n
    }}