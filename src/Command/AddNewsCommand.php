<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use GuzzleHttp\Client;


class AddNewsCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:add-news';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = new Client();
        //simulate getting data
        $news_data = $client->get('http://localhost:8000/news')->getBody()->getContents();
        //create data to be posted
        $data = json_decode($news_data);
        //queue data to message bus
        $data = $client->post('http://localhost:8000/schedule', [
            'title'=>$data->title,
            'description'=>$data->description,
            'image'=>$data->image
        ]);

        print_r($data->getBody()->getContents());

        $output->writeln(strval($news_data));
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        // the command help shown when running the command with the "--help" option
        $this->setHelp("This command allows you to add news after simulation scrapping");
        
    }
}





?>