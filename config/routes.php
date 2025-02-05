<?php

return [
    // Template routes
    'GET|/dashboard/templates' => ['TemplateController', 'index'],
    'GET|/dashboard/templates/create' => ['TemplateController', 'create'],
    'POST|/dashboard/templates/store' => ['TemplateController', 'store'],
    'GET|/dashboard/templates/edit/{id}' => ['TemplateController', 'edit'],
    'POST|/dashboard/templates/update/{id}' => ['TemplateController', 'update'],
    'POST|/dashboard/templates/delete/{id}' => ['TemplateController', 'delete'],
    'GET|/dashboard/templates/preview/{id}' => ['TemplateController', 'preview'],
    'GET|/dashboard/templates/duplicate/{id}' => ['TemplateController', 'duplicate'],
    'POST|/dashboard/templates/process-variables' => ['TemplateController', 'processVariables'],
    'GET|/dashboard/templates/variables' => ['TemplateController', 'getVariables'],
    'POST|/dashboard/templates/validate' => ['TemplateController', 'validateTemplate'],
    'GET|/dashboard/templates/export/{id}' => ['TemplateController', 'exportTemplate'],
    'POST|/dashboard/templates/import' => ['TemplateController', 'importTemplate'],
    'GET|/dashboard/templates/categories' => ['TemplateController', 'getCategories'],
    'POST|/dashboard/templates/test-send/{id}' => ['TemplateController', 'sendTestEmail'],
]; 