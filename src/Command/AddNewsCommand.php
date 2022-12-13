<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Nette\Utils\Finder;


class AddNewsCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:add-news';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $title = $input->getArgument('title');
        $command = $this->getApplication();
        $description = $input->getArgument('description');
        $image_dir = $input->getArgument('image');
        $image_name = basename($image_dir);
        $extension = explode('.', $image_name);
        $image_data = file_get_contents($image_dir, true);
        $destination = __DIR__.'/public/uploads/image';
        var_dump($destination);
        //add image to folder
        file_put_contents(__DIR__.'/../../public/uploads/image'.'/'.$title.'.'.end($extension), $image_data);
        // var_dump($image);
        // $content = $image->getContents();
        $date_added = new \DateTime('@'.strtotime('now'));
        $returnCode = $command->run('php', __DIR__.'/../../../scheduled_task.php');

        // $output->writeln(json_encode(['image'=>$title.'.'.end($extension),'title'=>$title, 'description'=>$description, 'date_added'=>$date_added]));
        $output->writeln($returnCode);
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this

            // configure an argument
            ->addArgument('title', InputArgument::REQUIRED, 'The title of the news')
            ->addArgument('description', InputArgument::REQUIRED, 'A short description  of the news')
            ->addArgument('image', InputArgument::REQUIRED, 'The full path of the image of the news')
            // the command help shown when running the command with the "--help" option
            ->setHelp("This command allows you to add news expected arguments 'title' 'description', 'image_full_path'");
        ;
    }
}





?>