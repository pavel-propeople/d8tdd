<?php

namespace MPNDEV\D8TDD\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateKernelTestCommand extends Command {

  protected static $defaultName = 'make:kerneltest';
  public $PascalName;
  public $snake_name;
  public $template_file;
  public $module_dir;
  public $module_tests_kernel_dir;
  public $new_file;
  public $file_content;
  public $input;
  public $output;

  protected function configure()
  {
    $this->addArgument('name', InputArgument::REQUIRED, 'The name of the folder of the module in PascalCase.')
      ->setDescription('Create [your module name]KernelTestBase that need to be extended by your kernel tests in drupal 8.')
      ->setHelp('This command allows you to create [your module name]KernelBaseTest...');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->input = $input;
    $this->output = $output;
    $this->PascalName = $this->input->getArgument('name');
    $this->snake_name  = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $this->PascalName));

    $this->template_file = realpath(dirname(__DIR__).'/Commands/kernel_template.php');
    $this->module_dir = dirname(__DIR__, 5) . '/web/modules/custom/' . $this->snake_name;
    $this->module_tests_kernel_dir = $this->module_dir . '/tests/src/Kernel/';
    $this->new_file = $this->module_tests_kernel_dir . $this->PascalName . 'KernelTestBase.php';

    $this->file_content = file_get_contents($this->template_file);
    $this->file_content = str_replace('PascalName', $this->PascalName, $this->file_content);
    $this->file_content = str_replace('snake_name', $this->snake_name, $this->file_content);

    $this->startPreparingMessage();
    if (is_dir($this->module_dir)) {
      $this->startCreatingMessage();
      if (!is_dir($this->module_dir . '/config/install')) {
        $this->warningForConfig();
      }
      if (file_exists($this->new_file)) {
        $this->fileAlreadyExist();
      } else {
        file_put_contents($this->new_file, $this->file_content);
        $this->createdMessage();
      }
    } else {
      $this->noModuleMessage();
    }
  }

  private function startPreparingMessage() {
    $this->output->writeln([
      'Prepare to create: [<info>YourModuleNameInPascalCase</info>]KernelTestBase',
    ]);
  }

  private function  startCreatingMessage() {
    $this->output->writeln([
      'Start creating: <info>' . $this->PascalName . 'KernelTestBase.php</info>',
    ]);
  }

  private function warningForConfig() {
    $this->output->writeln([
      '',
      '<comment>if you will use DB in kernel tests(99% you will...),</comment>',
      '',
      '<comment>the ' . $this->snake_name . ' module must have config/install directory with config yml files for all needed nodes, fields, etc...!</comment>',
      '',
    ]);
  }

  private function fileAlreadyExist() {
    $this->output->writeln([
      '',
      '<error>' . $this->PascalName . 'KernelTestBase.php already exist!</error>',
      '',
      '<error>Found in: ' . $this->new_file . '</error>',
      '',
    ]);
  }

  private function createdMessage() {
    $this->output->writeln([
      'Created: <info>' . $this->PascalName . 'KernelTestBase class</info>',
      '',
      'Now you can extend <info>' . $this->PascalName . 'KernelTestBase</info> and use the power of',
      '<info>$this->factory(...)</info> and <info>$this->jsonRequest(...)</info> methods<info>.</info>',
      '',
    ]);
  }

  private function noModuleMessage() {
    $this->output->writeln([
      '',
      '<error>There is no module directory: ' . $this->module_dir . '</error>',
      '',
    ]);
  }

}
