<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Webman\Console\Make;

use Dsxwk\Framework\Webman\Console\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Webman\Console\Util;

#[AsCommand('make:route', 'Make route')]
class MakeRouteCommand extends BaseCommand
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Route name');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $output->writeln("Make route $name");

        $name = str_replace('\\', '/', $name);
        if (!($pos = strrpos($name, '/'))) {
            $route_str = Util::guessPath(base_path(), 'router') ?: 'router';
            $file = $route_str . DIRECTORY_SEPARATOR . "$name.php";
        } else {
            $name_str = substr($name, 0, $pos);
            if($real_name_str = Util::guessPath(base_path(), $name_str)) {
                $name_str = $real_name_str;
            } else if ($real_section_name = Util::guessPath(base_path(), strstr($name_str, '/', true))) {
                $upper = strtolower($real_section_name[0]) !== $real_section_name[0];
            } else if ($real_base_route = Util::guessPath(base_path(), 'router')) {
                $upper = strtolower($real_base_route[0]) !== $real_base_route[0];
            }
            $upper = $upper ?? strtolower($name_str[0]) !== $name_str[0];
            if ($upper && !$real_name_str) {
                $name_str = preg_replace_callback('/\/([a-z])/', function ($matches) {
                    return '/' . strtoupper($matches[1]);
                }, ucfirst($name_str));
            }
            $path = "$name_str/" . ($upper ? 'Route' : 'route');
            $name = substr($name, $pos + 1);
            $file = base_path() . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . "$name.php";
        }

        if (is_file($file)) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion("$file already exists. Do you want to override it? (yes/no)", false);
            if (!$helper->ask($input, $output, $question)) {
                return self::SUCCESS;
            }
        }

        $this->createRoute($file);

        return self::SUCCESS;
    }

    /**
     * @param $file
     * @return void
     */
    protected function createRoute($file): void
    {
        $path = pathinfo($file, PATHINFO_DIRNAME);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $routeContent = <<<EOF
<?php

declare(strict_types=1);

use Webman\Route;



EOF;
        file_put_contents($file, $routeContent);
    }

}
