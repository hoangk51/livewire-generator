<?php

namespace Hoangk51\Generator\Generators\Scaffold;

use Hoangk51\Generator\Common\CommandData;
use Hoangk51\Generator\Generators\BaseGenerator;
use Hoangk51\Generator\Generators\ModelGenerator;
use Hoangk51\Generator\Utils\FileUtil;

class RequestGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $createFileName;

    /** @var string */
    private $updateFileName;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathRequest;
        $this->createFileName = 'Create'.$this->commandData->modelName.'Request.php';
        $this->updateFileName = 'Update'.$this->commandData->modelName.'Request.php';
    }

    public function generate()
    {
        $this->generateCreateRequest();
        $this->generateUpdateRequest();
    }

    private function generateCreateRequest()
    {
        $templateData = get_template('scaffold.request.create_request', 'livewire-generator');

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        FileUtil::createFile($this->path, $this->createFileName, $templateData);

        $this->commandData->commandComment("\nCreate Request created: ");
        $this->commandData->commandInfo($this->createFileName);
    }

    private function generateUpdateRequest()
    {
        $modelGenerator = new ModelGenerator($this->commandData);
        $rules = $modelGenerator->generateUniqueRules();
        $this->commandData->addDynamicVariable('$UNIQUE_RULES$', $rules);

        $templateData = get_template('scaffold.request.update_request', 'livewire-generator');

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        FileUtil::createFile($this->path, $this->updateFileName, $templateData);

        $this->commandData->commandComment("\nUpdate Request created: ");
        $this->commandData->commandInfo($this->updateFileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->createFileName)) {
            $this->commandData->commandComment('Create API Request file deleted: '.$this->createFileName);
        }

        if ($this->rollbackFile($this->path, $this->updateFileName)) {
            $this->commandData->commandComment('Update API Request file deleted: '.$this->updateFileName);
        }
    }
}